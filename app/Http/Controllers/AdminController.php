<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil data untuk dashboard - hitung semua pesanan
        $totalOrders = Order::count(); // Total semua pesanan
        $pendingOrders = Order::where('status', 'pending')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();
        $readyOrders = Order::where('status', 'ready')->count();

        return view('admin.dashboard', compact('totalOrders', 'pendingOrders', 'preparingOrders', 'readyOrders'));
    }

    // ========== CATEGORY MANAGEMENT ==========
    
    // Menampilkan daftar kategori
    public function categories(Request $request)
    {
        $query = Category::withCount('menus');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        $categories = $query->get();
        return view('admin.categories', compact('categories'));
    }

    // Menampilkan form untuk membuat kategori baru
    public function createCategory()
    {
        return view('admin.category-create');
    }

    // Menyimpan kategori baru
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        Category::create($data);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit kategori
    public function editCategory($id)
    {
        $category = Category::withCount('menus')->findOrFail($id);
        return view('admin.category-edit', compact('category'));
    }

    // Memperbarui kategori
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Menghapus kategori
    public function deleteCategory($id)
    {
        $category = Category::withCount('menus')->findOrFail($id);

        // Cek apakah kategori masih memiliki menu
        if ($category->menus_count > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus kategori yang masih memiliki menu.');
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil dihapus.');
    }

    // ========== MENU MANAGEMENT ==========
    public function menu(Request $request)
    {
        $query = Menu::with('category');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }
        
        $menus = $query->get();
        return view('admin.menu', compact('menus'));
    }

    // Menampilkan form untuk membuat menu baru
    public function createMenu()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.menu-create', compact('categories'));
    }

    // Menyimpan menu baru
    public function storeMenu(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'stock' => 'required|integer|min:0'
        ]);

        $data = $request->all();
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu-images', 'public');
            $data['image'] = $imagePath;
        }

        Menu::create($data);

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit menu
    public function editMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.menu-edit', compact('menu', 'categories'));
    }

    // Memperbarui menu
    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'stock' => 'required|integer|min:0'
        ]);

        $data = $request->all();
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($menu->image) {
                \Storage::delete('public/' . $menu->image);
            }

            $imagePath = $request->file('image')->store('menu-images', 'public');
            $data['image'] = $imagePath;
        } else {
            unset($data['image']); // Jangan update kolom image jika tidak ada file baru
        }

        $menu->update($data);

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil diperbarui.');
    }

    // Menghapus menu
    public function deleteMenu($id)
    {
        $menu = Menu::findOrFail($id);

        if ($menu->image) {
            \Storage::delete('public/' . $menu->image);
        }

        $menu->delete();

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil dihapus.');
    }

    // Method untuk manajemen pesanan
    public function orders()
    {
        // Check expiration for all pending QRIS orders
        $ordersToCheck = Order::where('payment_method', 'qris')
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

        $orders = Order::with('user', 'orderItems.menu')->orderBy('created_at', 'desc')->paginate(10);
        $totalOrders = Order::count(); // Hitung semua pesanan, bukan hanya yang di-paginate
        return view('admin.orders', compact('orders', 'totalOrders'));
    }

    // Method untuk melihat detail pesanan
    public function orderDetail($id)
    {
        $order = Order::with('user', 'orderItems.menu')->findOrFail($id);
        return view('admin.order-detail', compact('order'));
    }

    // Method untuk memperbarui status pesanan
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        // Broadcast event untuk real-time update
        \App\Events\OrderStatusUpdated::dispatch($order);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // Method untuk pelaporan
    public function reports()
    {
        return view('admin.reports');
    }

    // Method untuk verifikasi pembayaran
    public function verifyPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Log request data untuk debugging
        \Log::info('Verify Payment Request:', [
            'order_id' => $id,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->amount_paid,
            'total_amount' => $order->total_amount
        ]);

        $request->validate([
            'payment_status' => 'required|in:paid,failed',
            'payment_method' => 'nullable|string',
            'amount_paid' => 'nullable|numeric|min:0'
        ]);

        $updateData = [
            'payment_status' => $request->payment_status
        ];

        if ($request->payment_status === 'paid') {
            // Gunakan payment_method dari request, atau gunakan yang sudah ada, atau default 'cash'
            $updateData['payment_method'] = $request->payment_method ?? $order->payment_method ?? 'cash';
            $updateData['paid_at'] = now();

            // Simpan amount_paid dan hitung kembalian
            if ($request->has('amount_paid')) {
                $amountPaid = $request->amount_paid;
                $updateData['amount_paid'] = $amountPaid;
                $updateData['change_amount'] = max(0, $amountPaid - $order->total_amount);
                
                \Log::info('Calculated change:', [
                    'amount_paid' => $amountPaid,
                    'total_amount' => $order->total_amount,
                    'change_amount' => $updateData['change_amount']
                ]);
            }

            // Jika pembayaran berhasil, ubah status pesanan menjadi preparing (langsung diproses koki)
            if (in_array($order->status, ['pending', 'confirmed'])) {
                $updateData['status'] = 'preparing';
            }
        }

        $order->update($updateData);

        \Log::info('Order updated:', ['order_id' => $id, 'update_data' => $updateData]);


        // Broadcast event untuk real-time update (jika ada)
        // \App\Events\OrderStatusUpdated::dispatch($order);

        // Return JSON untuk AJAX request
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil diverifikasi.']);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    // Method untuk mencetak struk
    public function printReceipt($id)
    {
        // Query langsung dari database tanpa cache
        $order = Order::where('id', $id)
            ->with(['orderItems.menu', 'user'])
            ->first();
        
        if (!$order) {
            abort(404, 'Order tidak ditemukan');
        }
        
        // Log data untuk debugging
        \Log::info('Print Receipt - Order Data:', [
            'order_id' => $order->id,
            'total_amount' => $order->total_amount,
            'amount_paid' => $order->amount_paid,
            'change_amount' => $order->change_amount,
            'payment_status' => $order->payment_status
        ]);
        
        return view('admin.receipt', compact('order'));
    }

    // ========== POS (POINT OF SALE) ==========
    
    // Menampilkan halaman POS untuk admin
    public function showPOS()
    {
        $categories = Category::where('is_active', true)->with(['menus' => function($query) {
            $query->where('is_available', true)->where('stock', '>', 0);
        }])->get();
        
        $menus = Menu::where('is_available', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->get();
        
        return view('admin.pos', compact('categories', 'menus'));
    }
    
    // Konfigurasi Midtrans
    private function configureMidtrans()
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
    }

    // Membuat pesanan dari POS
    public function createPOSOrder(Request $request)
    {
        try {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'table_number' => 'nullable|string|max:10',
                'order_type' => 'required|in:dine_in,takeaway,delivery',
                'special_requests' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.menu_id' => 'required|exists:menus,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.notes' => 'nullable|string|max:255',
                'payment_method' => 'required|in:cash,card,qris',
                'amount_paid' => 'nullable|numeric|min:0'
            ]);

            // Buat nomor pesanan unik
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(\Str::random(6));

            // Hitung total dan siapkan item details untuk Midtrans
            $totalAmount = 0;
            $midtransItems = [];
            
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);
                $totalAmount += $menu->price * $item['quantity'];
                
                $midtransItems[] = [
                    'id' => $menu->id,
                    'price' => $menu->price,
                    'quantity' => $item['quantity'],
                    'name' => substr($menu->name, 0, 50)
                ];
            }

            // Siapkan data pembayaran
            $amountPaid = $request->amount_paid;
            $changeAmount = 0;
            $paymentStatus = 'pending';
            $paidAt = null;
            $qrisUrl = null;

            // Logika Pembayaran
            if ($request->payment_method === 'qris') {
                // Integrasi Midtrans QRIS
                try {
                    $this->configureMidtrans();
                    
                    // DEBUG: Log server key (partial)
                    $serverKey = config('services.midtrans.server_key');
                    \Log::info('Midtrans Config Check', [
                        'server_key_exists' => !empty($serverKey),
                        'server_key_prefix' => substr($serverKey, 0, 5) . '...',
                        'is_production' => config('services.midtrans.is_production')
                    ]);

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
                    // Jika gagal generate QR, kembalikan error
                    return response()->json([
                        'success' => false, 
                        'message' => 'Gagal memproses QRIS: ' . $e->getMessage()
                    ], 500);
                }
            } else {
                // Pembayaran Tunai
                if ($amountPaid !== null) {
                    if ($amountPaid >= $totalAmount) {
                        $paymentStatus = 'paid';
                        $changeAmount = $amountPaid - $totalAmount;
                        $paidAt = now();
                    }
                } else if ($request->payment_method === 'cash') {
                    // Jika metode pembayaran tunai dan amount_paid tidak dispesifikasikan,
                    // asumsikan pembayaran pas atau akan dihitung nanti
                    $paymentStatus = 'paid';
                }
            }

            // Buat pesanan
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => null, // POS order tidak terhubung ke user
                'status' => 'confirmed', // Langsung confirmed karena dibuat admin
                'subtotal' => $totalAmount,
                'total_amount' => $totalAmount,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone ?? '-',
                'table_number' => $request->table_number,
                'order_type' => $request->order_type,
                'special_requests' => $request->special_requests,
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'paid_at' => $paidAt
            ]);

            // Buat item pesanan
            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['menu_id']);

                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'special_instructions' => $item['notes'] ?? null
                ]);

                // Update stok menu
                $menu->decrement('stock', $item['quantity']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'order_number' => $orderNumber,
                'order_id' => $order->id,
                'total_amount' => $totalAmount,
                'qris_url' => $qrisUrl,
                'payment_method' => $request->payment_method
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('POS Order Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // ========== QR CODE MANAGEMENT ==========
    
    // Menampilkan halaman QR Code Generator
    public function qrCodeManager()
    {
        return view('admin.qr-codes');
    }
    
    // Generate QR Code untuk meja
    public function generateTableQR($tableNumber)
    {
        $url = url('/menu?table=' . $tableNumber);
        
        return view('admin.qr-code-view', [
            'tableNumber' => $tableNumber,
            'url' => $url
        ]);
    }
    
    // ========== CUSTOMER MANAGEMENT ==========
    
    // Menampilkan daftar pelanggan
    public function customers()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.customers', compact('customers'));
    }
    
    // Detail pelanggan
    public function customerDetail($id)
    {
        $customer = User::where('role', 'customer')
            ->with(['orders' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            }])
            ->withCount('orders')
            ->findOrFail($id);
            
        return view('admin.customer-detail', compact('customer'));
    }
    
    // Toggle status aktif/nonaktif pelanggan
    public function toggleCustomerStatus($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        
        // Toggle is_active (jika ada field ini)
        // Atau bisa gunakan soft delete
        $customer->update([
            'is_active' => !($customer->is_active ?? true)
        ]);
        
        $status = $customer->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()->with('success', "Pelanggan berhasil {$status}");
    }
    
    // Hapus pelanggan
    public function deleteCustomer($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        
        // Nullify orders - convert to guest orders (preserve order history)
        if ($customer->orders()->count() > 0) {
            $customer->orders()->update(['user_id' => null]);
            \Log::info("Customer deleted with orders converted to guest", [
                'customer_id' => $id,
                'customer_name' => $customer->name,
                'orders_count' => $customer->orders()->count()
            ]);
        }
        
        $customer->delete();
        
        return redirect()->route('admin.customers')->with('success', 'Pelanggan berhasil dihapus. Riwayat pesanan disimpan sebagai pesanan guest.');
    }

    // Cek status pesanan (untuk polling QRIS)
    public function checkStatus($id)
    {
        $order = Order::findOrFail($id);
        
        return response()->json([
            'status' => $order->payment_status,
            'order_number' => $order->order_number,
            'total_amount' => $order->total_amount,
            'amount_paid' => $order->amount_paid
        ]);
    }

    // Simulasi Bayar (Hanya untuk Development/Sandbox)
    public function simulatePay($id)
    {
        $order = Order::findOrFail($id);
        
        // Update status jadi paid
        $order->update([
            'payment_status' => 'paid',
            'amount_paid' => $order->total_amount,
            'change_amount' => 0,
            'paid_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
}