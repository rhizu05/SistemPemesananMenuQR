<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function dashboard()
    {
        // Get orders by status
        // Confirmed: pesanan yang dikonfirmasi dan sudah dibayar tapi belum diproses
        $confirmedOrders = Order::where('status', 'confirmed')
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Preparing: pesanan yang sedang diproses dapur
        // Include juga pesanan QRIS yang sudah paid tapi masih status pending
        $preparingOrders = Order::where(function($query) {
                $query->where('status', 'preparing')
                      ->orWhere(function($q) {
                          $q->where('status', 'pending')
                            ->where('payment_status', 'paid')
                            ->where('payment_method', 'qris');
                      });
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Ready: pesanan yang sudah siap disajikan
        $readyOrders = Order::where('status', 'ready')
            ->orderBy('created_at', 'asc')
            ->get();

        // Log untuk debugging
        \Log::info('Kitchen Dashboard', [
            'confirmed_count' => $confirmedOrders->count(),
            'preparing_count' => $preparingOrders->count(),
            'ready_count' => $readyOrders->count(),
            'preparing_orders' => $preparingOrders->pluck('order_number', 'status', 'payment_method', 'payment_status')
        ]);

        return view('kitchen.dashboard', compact('confirmedOrders', 'preparingOrders', 'readyOrders'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            \Log::info('Kitchen updateStatus called', [
                'order_id' => $id,
                'status' => $request->status,
                'user' => auth()->user()->email ?? 'unknown'
            ]);

            $request->validate([
                'status' => 'required|in:preparing,ready,delivered'
            ]);

            $order = Order::findOrFail($id);
            
            \Log::info('Order found', [
                'order_number' => $order->order_number,
                'current_status' => $order->status,
                'new_status' => $request->status
            ]);
            
            // Update status
            $order->update([
                'status' => $request->status
            ]);

            \Log::info('Order status updated successfully', ['order_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            \Log::error('Kitchen updateStatus error', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}