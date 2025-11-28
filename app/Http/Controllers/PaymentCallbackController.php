<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Log semua request yang masuk untuk debugging
        \Log::info('Midtrans Webhook Received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'raw_body' => $request->getContent()
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        try {
            // Buat instance notifikasi Midtrans
            // Midtrans akan membaca php://input secara otomatis
            $notification = new Notification();

            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;

            \Log::info('Midtrans Notification Parsed', [
                'order_id' => $orderId,
                'transaction_status' => $transaction,
                'payment_type' => $type,
                'fraud_status' => $fraud
            ]);

            // Cari order berdasarkan order_number
            $order = Order::where('order_number', $orderId)->first();

            if (!$order) {
                \Log::error('Order not found', ['order_number' => $orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            \Log::info('Order found', [
                'order_id' => $order->id,
                'current_status' => $order->payment_status
            ]);

            // Handle status transaksi
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->update(['payment_status' => 'pending']);
                    } else {
                        $order->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                            'amount_paid' => $notification->gross_amount,
                            'change_amount' => 0
                        ]);
                    }
                }
            } else if ($transaction == 'settlement') {
                // Pembayaran berhasil (QRIS, VA, dll masuk sini)
                \Log::info('Processing settlement', ['order_id' => $orderId]);
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'amount_paid' => $notification->gross_amount,
                    'change_amount' => 0,
                    'status' => 'preparing' // Auto-update ke preparing setelah pembayaran
                ]);
                \Log::info('Order updated to paid and preparing', ['order_id' => $order->id]);
            } else if ($transaction == 'pending') {
                $order->update(['payment_status' => 'pending']);
            } else if ($transaction == 'deny') {
                $order->update(['payment_status' => 'failed']);
            } else if ($transaction == 'expire') {
                $order->update(['payment_status' => 'failed']);
            } else if ($transaction == 'cancel') {
                $order->update(['payment_status' => 'failed']);
            }

            return response()->json(['message' => 'Payment status updated']);

        } catch (\Exception $e) {
            \Log::error('Midtrans Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error processing notification: ' . $e->getMessage()], 500);
        }
    }
}
