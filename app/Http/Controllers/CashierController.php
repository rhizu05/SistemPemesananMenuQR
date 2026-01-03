<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashierController extends Controller
{
    /**
     * Display cashier dashboard
     */
    public function dashboard()
    {
        $today = Carbon::today();
        
        // Get today's orders
        $todayOrders = Order::whereDate('created_at', $today)->get();
        
        // Only count revenue from paid orders
        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
            
        $pendingPayments = Order::where('payment_status', 'pending')
            ->where('payment_method', 'cash') // Only count cash payments
            ->count();
        
        return view('cashier.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'pendingPayments'
        ));
    }
    
    /**
     * Display today's orders
     */
    public function orders()
    {
        $orders = Order::whereDate('created_at', Carbon::today())
            ->with(['orderItems.menu', 'user'])
            ->latest()
            ->get();
            
        return view('cashier.orders', compact('orders'));
    }
    
    /**
     * Display pending payments
     */
    public function pendingPayments()
    {
        $orders = Order::where('payment_status', 'pending')
            ->where('payment_method', 'cash') // Only show cash payments
            ->with(['orderItems.menu', 'user'])
            ->latest()
            ->get();
            
        return view('cashier.payments', compact('orders'));
    }
    
    /**
     * Verify payment
     */
    public function verifyPayment(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Validate amount paid
        $request->validate([
            'amount_paid' => 'required|numeric|min:' . $order->total_amount
        ]);
        
        $amountPaid = $request->amount_paid;
        $changeAmount = $amountPaid - $order->total_amount;
        
        $order->update([
            'payment_status' => 'paid',
            'status' => 'preparing', // Langsung ke preparing, skip confirmed
            'paid_at' => now(),
            'amount_paid' => $amountPaid,
            'change_amount' => $changeAmount
        ]);
        
        return back()->with('success', 'Payment verified successfully!');
    }
    
    /**
     * Get pending payment count (for AJAX polling)
     */
    public function getPendingCount()
    {
        $count = Order::where('payment_status', 'pending')
            ->where('payment_method', 'cash') // Only count cash payments
            ->count();
        return response()->json(['count' => $count]);
    }
}
