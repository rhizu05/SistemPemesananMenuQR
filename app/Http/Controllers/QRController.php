<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;

class QRController extends Controller
{
    // Menampilkan menu untuk pelanggan yang scan QR
    public function showMenu($tableNumber = null)
    {
        $categories = Category::where('is_active', true)->with('menus')->get();
        $menus = Menu::where('is_available', true)->with('category')->get();
        
        return view('qr.menu', compact('categories', 'menus', 'tableNumber'));
    }
    
    // Menyimpan pesanan dari QR ordering
    public function createOrder(Request $request, $tableNumber = null)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string'
        ]);
        
        // Buat nomor pesanan unik
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
        
        // Hitung total jumlah
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            $totalAmount += $menu->price * $item['quantity'];
        }
        
        // Buat pesanan
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => null, // Pesanan dari QR tidak perlu login
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'table_number' => $tableNumber, // Meja dari QR code
            'order_type' => 'dine_in', // Pesanan melalui QR pasti dine-in
            'special_requests' => $request->special_requests,
            'payment_status' => 'pending' // Menunggu pembayaran di kasir
        ]);
        
        // Buat item pesanan
        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price' => $menu->price,
                'special_instructions' => $item['special_instructions'] ?? null
            ]);
            
            // Update stok menu
            $menu->decrement('stock', $item['quantity']);
        }
        
        // Broadcast event untuk real-time update
        \App\Events\OrderStatusUpdated::dispatch($order);
        
        // Redirect ke halaman status pesanan
        return redirect()->route('customer.order.status', ['orderNumber' => $orderNumber])
                         ->with('success', 'Pesanan berhasil dibuat! Nomor pesanan: ' . $orderNumber);
    }
    
    // Generate QR code untuk meja tertentu (ini hanya akan menghasilkan URL)
    public function generateQR($tableNumber)
    {
        $qrUrl = route('qr.menu', ['table' => $tableNumber]);
        
        // Kita bisa menggunakan library QR code di sini, tapi untuk sekarang kita hanya kirim URL
        return view('qr.generate', compact('qrUrl', 'tableNumber'));
    }
}