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
        $cart = session()->get('cart', []);
        $currentQtyInCart = isset($cart[$request->menu_id]) ? $cart[$request->menu_id]['quantity'] : 0;
        $requestedTotal = $currentQtyInCart + $request->quantity;

        if ($menu->stock < $requestedTotal) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi (Tersedia: ' . $menu->stock . ', di Keranjang: ' . $currentQtyInCart . ')'
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
            $menu = Menu::find($request->menu_id);
            if (!$menu) {
                return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan'], 404);
            }

            if ($menu->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi (Tersedia: ' . $menu->stock . ')',
                    'current_qty' => isset($cart[$request->menu_id]) ? $cart[$request->menu_id]['quantity'] : $menu->stock,
                    'max_stock' => $menu->stock
                ], 400);
            }

            if (isset($cart[$request->menu_id])) {
                $cart[$request->menu_id]['quantity'] = $request->quantity;
            }
        }
        
        session()->put('cart', $cart);
        
        // Calculate totals
        $totalItems = 0;
        $cartSubtotal = 0;
        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
            $cartSubtotal += $item['price'] * $item['quantity'];
        }

        // Calculate item subtotal
        $itemSubtotal = 0;
        if (isset($cart[$request->menu_id])) {
            $itemSubtotal = $cart[$request->menu_id]['price'] * $cart[$request->menu_id]['quantity'];
        }

        // Calculate discount if voucher exists
        $discount = 0;
        $appliedVoucher = session('applied_voucher');
        if ($appliedVoucher) {
            // Re-validate voucher min transaction
            $voucher = \App\Models\Voucher::find($appliedVoucher['id']);
            if ($voucher && $cartSubtotal >= $voucher->min_transaction) {
                $discount = $voucher->calculateDiscount($cartSubtotal);
                // Update session discount
                $appliedVoucher['discount'] = $discount;
                session(['applied_voucher' => $appliedVoucher]);
            } else {
                // Remove voucher if min transaction not met
                session()->forget(['applied_voucher', 'applied_voucher_code']);
                $appliedVoucher = null;
            }
        }

        $cartTotal = $cartSubtotal - $discount;
        
        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diupdate',
            'item_subtotal' => $itemSubtotal,
            'cart_subtotal' => $cartSubtotal,
            'cart_total' => $cartTotal,
            'discount_amount' => $discount,
            'total_items' => $totalItems,
            'voucher_removed' => ($appliedVoucher === null && session()->has('applied_voucher_code')) // Flag if voucher was removed
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
    
    // Method untuk update notes item di keranjang
    public function updateItemNotes(Request $request)
    {
        $request->validate([
            'menu_id' => 'required',
            'notes' => 'nullable|string|max:255'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->menu_id])) {
            $cart[$request->menu_id]['notes'] = $request->notes;
            session()->put('cart', $cart);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil disimpan'
        ]);
    }

    // Method untuk menampilkan halaman keranjang
    public function cart(Request $request)
    {
        $cart = session()->get('cart', []);
        
        // Calculate subtotal
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // Handle voucher application
        if ($request->has('apply_voucher')) {
            // Check if user is logged in
            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('error', 'Anda harus login terlebih dahulu untuk menggunakan voucher.')
                    ->with('intended', route('customer.cart'));
            }
            
            $code = strtoupper($request->get('apply_voucher'));
            $voucher = \App\Models\Voucher::where('code', $code)
                ->active()
                ->valid()
                ->available()
                ->first();
            
            if ($voucher) {
                $userId = auth()->id();
                
                // Check if user can use this voucher
                if ($voucher->canBeUsedBy($userId)) {
                    // Check minimum transaction
                    if ($total >= $voucher->min_transaction) {
                        $discount = $voucher->calculateDiscount($total);
                        
                        session([
                            'applied_voucher' => [
                                'id' => $voucher->id,
                                'code' => $voucher->code,
                                'name' => $voucher->name,
                                'discount' => $discount,
                            ],
                            'applied_voucher_code' => $voucher->code,
                        ]);
                        
                        return redirect()->route('customer.cart')->with('success', 'Voucher berhasil diterapkan!');
                    } else {
                        return redirect()->route('customer.cart')->with('error', 'Minimal belanja Rp ' . number_format($voucher->min_transaction, 0, ',', '.') . ' untuk menggunakan voucher ini.');
                    }
                } else {
                    return redirect()->route('customer.cart')->with('error', 'Anda sudah mencapai limit penggunaan voucher ini.');
                }
            } else {
                return redirect()->route('customer.cart')->with('error', 'Kode voucher tidak valid atau sudah tidak berlaku.');
            }
        }
        
        // Handle voucher removal  
        if ($request->has('remove_voucher')) {
            session()->forget(['applied_voucher', 'applied_voucher_code']);
            return redirect()->route('customer.cart')->with('success', 'Voucher dihapus.');
        }
        
        return view('customer.cart', compact('cart', 'total'));
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

        // Apply Voucher if exists
        $voucher = null;
        $discount = 0;
        $subtotal = $totalAmount; // Store original amount
        
        $appliedVoucher = session('applied_voucher');
        if ($appliedVoucher) {
            $voucher = \App\Models\Voucher::find($appliedVoucher['id']);
            
            if ($voucher && $voucher->isValid() && $voucher->isAvailable()) {
                $userId = auth()->id();
                
                if ($voucher->canBeUsedBy($userId)) {
                    $discount = $appliedVoucher['discount'];
                    $totalAmount = $subtotal - $discount; // Apply discount to final amount
                    
                    \Log::info('Voucher applied to order', [
                        'voucher_code' => $voucher->code,
                        'subtotal' => $subtotal,
                        'discount' => $discount,
                        'total' => $totalAmount
                    ]);
                }
            }
        }

        // Add voucher discount as negative item for Midtrans
        if ($voucher && $discount > 0) {
            $midtransItems[] = [
                'id' => 'VOUCHER-' . $voucher->code,
                'price' => -$discount,
                'quantity' => 1,
                'name' => 'Diskon Voucher ' . $voucher->code
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
                    ],
                    'custom_expiry' => [
                        'expiry_duration' => 15,
                        'unit' => 'minute',
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

        // Check stock availability BEFORE creating anything
        foreach ($cart as $menuId => $item) {
            $menu = Menu::find($item['menu_id']);
            if (!$menu || $menu->stock < $item['quantity']) {
                $available = $menu ? $menu->stock : 0;
                return response()->json([
                    'success' => false,
                    'message' => 'Stok untuk menu "' . $item['name'] . '" tidak mencukupi. Tersedia: ' . $available
                ], 400);
            }
        }

        // Buat pesanan
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->check() ? auth()->id() : null,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $totalAmount,
            'voucher_id' => $voucher ? $voucher->id : null,
            'voucher_code' => $voucher ? $voucher->code : null,
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

            // Re-check stock atomically (optimistic locking pattern or strict check)
            if ($menu->stock < $item['quantity']) {
                // If race condition happens here, we should rollback order
                $order->delete(); 
                // Restore previous items if needed (not implemented here as loop breaks)
                return response()->json([
                    'success' => false,
                    'message' => 'Stok menu "' . $menu->name . '" baru saja habis diambil pelanggan lain.'
                ], 400);
            }

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'special_instructions' => $item['notes'] ?? null // Ambil notes dari cart session
            ]);

            // Update stok menu
            $menu->decrement('stock', $item['quantity']);
        }

        // Record Voucher Usage
        if ($voucher) {
            \App\Models\VoucherUsage::create([
                'voucher_id' => $voucher->id,
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'discount_amount' => $discount,
            ]);
            
            // Increment voucher used count
            $voucher->increment('used_count');
            
            \Log::info('Voucher usage recorded', [
                'voucher_code' => $voucher->code,
                'order_number' => $orderNumber,
                'user_id' => auth()->id(),
            ]);
        }

        // Simpan order number ke session untuk guest
        if (!auth()->check()) {
            $guestOrders = session()->get('guest_orders', []);
            $guestOrders[] = $orderNumber;
            session()->put('guest_orders', $guestOrders);
        }

        // Clear cart and voucher
        session()->forget(['cart', 'applied_voucher', 'applied_voucher_code']);

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
        
        // Check for QRIS expiration manually if still pending
        if ($order->payment_method === 'qris' && $order->payment_status === 'pending') {
            // 15 minutes expiration
            $expiredTime = $order->created_at->addMinutes(15);
            
            if (now()->greaterThan($expiredTime)) {
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'failed'
                ]);
                
                // Restore stock
                foreach ($order->orderItems as $item) {
                    $item->menu->increment('stock', $item->quantity);
                }
            }
        }
        
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
            
            // Check expiration for all pending QRIS orders in the list
            $ordersToCheck = Order::whereIn('order_number', $sessionOrders)
                ->where('payment_method', 'qris')
                ->where('payment_status', 'pending')
                ->get();
                
            foreach ($ordersToCheck as $order) {
                if (now()->greaterThan($order->created_at->addMinutes(15))) {
                    $order->update([
                        'status' => 'cancelled',
                        'payment_status' => 'failed'
                    ]);
                    // Restore stock
                    foreach ($order->orderItems as $item) {
                        $item->menu->increment('stock', $item->quantity);
                    }
                }
            }

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