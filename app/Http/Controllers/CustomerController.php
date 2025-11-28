<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Cegah admin mengakses halaman menu customer
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.pos')->with('warning', 'Admin silakan gunakan fitur POS untuk membuat pesanan.');
        }

        $categories = Category::where('is_active', true)->with('menus')->get();
        // Get all available menus without pagination
        $menus = Menu::where('is_available', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();
        
        // Get table number from QR Code scan and save to session
        $tableNumber = $request->query('table');
        
        if ($tableNumber) {
            session(['table_number' => $tableNumber]);
        } else {
            // Cek apakah ada di session
            $tableNumber = session('table_number');
        }

        // Jika tidak ada nomor meja, redirect ke halaman scan QR
        if (empty($tableNumber)) {
            return redirect()->route('customer.scan-qr');
        }

        return view('customer.menu', compact('categories', 'menus', 'tableNumber'));
    }

    // Halaman Scan QR
    public function scanQR()
    {
        // Hapus session nomor meja saat masuk halaman scan
        session()->forget('table_number');
        return view('customer.scan-qr');
    }

    // Method untuk menambahkan item ke keranjang
    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        
        // Cek stok
        if ($menu->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi'
            ], 400);
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->menu_id])) {
            $cart[$request->menu_id]['quantity'] += $request->quantity;
        } else {
            $cart[$request->menu_id] = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $request->quantity,
                'image' => $menu->image
            ];
        }
        
        session()->put('cart', $cart);
        
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan ke keranjang',
            'total_items' => $totalItems
        ]);
    }

    // Method untuk update quantity di keranjang
    public function updateCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = session()->get('cart', []);
        
        if ($request->quantity == 0) {
            unset($cart[$request->menu_id]);
        } else {
            if (isset($cart[$request->menu_id])) {
                $cart[$request->menu_id]['quantity'] = $request->quantity;
            }
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diupdate'
        ]);
    }

    // Method untuk menghapus item dari keranjang
    public function removeFromCart($menuId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            session()->put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang'
        ]);
    }

    // Method untuk clear keranjang
    public function clearCart()
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan'
        ]);
    }

    // Method untuk menampilkan halaman keranjang
    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('customer.cart', compact('cart'));
    }

    // Method untuk membuat pesanan
    public function createOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'table_number' => 'nullable|string|max:10',
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'special_requests' => 'nullable|string',
            'item_notes' => 'nullable|array',
            'item_notes.*' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,qris'
        ]);

        // Ambil cart dari session
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong'
            ], 400);
        }

        // Buat nomor pesanan unik
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        // Hitung total jumlah dan siapkan item details untuk Midtrans
        $totalAmount = 0;
        $midtransItems = [];

        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
            
            $midtransItems[] = [
                'id' => $item['menu_id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => substr($item['name'], 0, 50)
            ];
        }

        $qrisUrl = null;
        $paymentStatus = 'pending';

        // Logika Pembayaran QRIS
        if ($request->payment_method === 'qris') {
            try {
                $this->configureMidtrans();
                
                $params = [
                    'payment_type' => 'qris',
                    'transaction_details' => [
                        'order_id' => $orderNumber,
                        'gross_amount' => $totalAmount,
                    ],
                    'item_details' => $midtransItems,
                    'customer_details' => [
                        'first_name' => $request->customer_name,
                        'phone' => $request->customer_phone ?? '',
                    ]
                ];
                
                $response = \Midtrans\CoreApi::charge($params);
                
                // Ambil URL QR Code
                if (isset($response->actions)) {
                    foreach ($response->actions as $action) {
                        if ($action->name === 'generate-qr-code') {
                            $qrisUrl = $action->url;
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Midtrans Error: ' . $e->getMessage());
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal memproses QRIS: ' . $e->getMessage()
                ], 500);
            }
        }

        // Buat pesanan
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->check() ? auth()->id() : null,
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'table_number' => $request->table_number,
            'order_type' => $request->order_type,
            'special_requests' => $request->special_requests,
            'payment_status' => $paymentStatus,
            'payment_method' => $request->payment_method,
            'snap_token' => $qrisUrl // Simpan URL QRIS di kolom snap_token sementara (atau buat kolom baru jika perlu)
        ]);

        // Buat item pesanan
        foreach ($cart as $menuId => $item) {
            $menu = Menu::findOrFail($item['menu_id']);

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'special_instructions' => $request->item_notes[$menuId] ?? null
            ]);

            // Update stok menu
            $menu->decrement('stock', $item['quantity']);
        }

        // Simpan order number ke session untuk guest
        if (!auth()->check()) {
            $guestOrders = session()->get('guest_orders', []);
            $guestOrders[] = $orderNumber;
            session()->put('guest_orders', $guestOrders);
        }

        // Clear cart
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'order_number' => $orderNumber,
            'payment_method' => $request->payment_method,
            'qris_url' => $qrisUrl
        ]);
    }

    // Method untuk melihat status pesanan
    public function orderStatus($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('orderItems.menu')->firstOrFail();
        
        return view('customer.order-status', compact('order'));
    }

    // Method untuk menampilkan halaman order success
    public function orderSuccess($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        return view('customer.order-success', compact('order'));
    }


    // Method untuk menampilkan daftar pesanan pelanggan
    public function myOrders()
    {
        if (auth()->check()) {
            // Customer login: tampilkan semua riwayat dari database
            $orders = Order::where('user_id', auth()->id())
                ->with('orderItems.menu')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Guest: tampilkan hanya riwayat dari session saat ini
            $sessionOrders = session()->get('guest_orders', []);
            $orders = Order::whereIn('order_number', $sessionOrders)
                ->with('orderItems.menu')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('customer.my-orders', compact('orders'));
    }

    // Method untuk menampilkan profil
    public function profile()
    {
        $user = auth()->user();
        return view('customer.profile', compact('user'));
    }

    // Method untuk update profil
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500'
        ]);
        
        \Log::info('Update Profile Request', [
            'user_id' => $user->id,
            'request_all' => $request->all()
        ]);

        $updated = $user->update([
            'name' => $request->name,
            'address' => $request->address
        ]);

        \Log::info('Profile Updated Status', ['success' => $updated]);

        return redirect()->route('customer.profile')->with('success', 'Profil berhasil diperbarui');
    }

    private function configureMidtrans()
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
    }
}