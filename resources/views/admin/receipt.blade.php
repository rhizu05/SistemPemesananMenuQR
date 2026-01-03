<!DOCTYPE html>
<html>
<head>
    <title>Struk Pesanan #{{ $order->order_number }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }
        
        body {
            font-family: 'Courier New', monospace;
            margin: 20px;
            background: #f5f5f5;
        }
        
        .receipt {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border: 2px dashed #1a4d2e;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a4d2e;
            padding-bottom: 15px;
        }
        
        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        
        .header h2 {
            color: #1a4d2e;
            margin: 10px 0 5px 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 3px 0;
            font-size: 12px;
            color: #4f772d;
        }
        
        .order-info {
            margin-bottom: 15px;
            font-size: 13px;
        }
        
        .order-info p {
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
        }
        
        .order-info strong {
            color: #1a4d2e;
        }
        
        .items {
            margin-bottom: 15px;
        }
        
        .items table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        
        .items th {
            background-color: #1a4d2e;
            color: #f5efe6;
            padding: 8px 5px;
            text-align: left;
            border-bottom: 2px solid #1a4d2e;
        }
        
        .items td {
            padding: 8px 5px;
            border-bottom: 1px dashed #ddd;
        }
        
        .items tr:last-child td {
            border-bottom: none;
        }
        
        .subtotal {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 13px;
        }
        
        .subtotal p {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        
        .total {
            background: #1a4d2e;
            color: #f5efe6;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }
        
        .payment-info {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #1a4d2e;
            font-size: 12px;
        }
        
        .payment-info p {
            margin: 5px 0;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #1a4d2e;
            text-align: center;
            font-size: 11px;
            color: #4f772d;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .status-paid {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        
        .btn-print {
            background: #1a4d2e;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px auto;
            display: block;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Dapoer Katendjo">
            <h2>DAPOER KATENDJO</h2>
            <p>Jl. Mayor Syamsu No.7, Jayaraga</p>
            <p>Kec. Tarogong Kidul, Kabupaten Garut</p>
            <p>Jawa Barat 44151</p>
            <p>Telp: 0813-2196-9737</p>
        </div>
        
        <div class="order-info">
            <p><strong>No. Pesanan:</strong> <span>{{ $order->order_number }}</span></p>
            <p><strong>Tanggal:</strong> <span>{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
            <p><strong>Pelanggan:</strong> <span>{{ $order->customer_name }}</span></p>
            @if($order->table_number)
                <p><strong>No. Meja:</strong> <span>{{ $order->table_number }}</span></p>
            @endif
            <p><strong>Tipe:</strong> 
                <span>
                    @if($order->order_type === 'dine_in')
                        Dine In
                    @elseif($order->order_type === 'takeaway')
                        Takeaway
                    @else
                        Delivery
                    @endif
                </span>
            </p>
        </div>
        
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Harga</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->menu->name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td style="text-align: right;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @if($item->special_instructions)
                    <tr>
                        <td colspan="4" style="font-size: 11px; color: #666; padding-left: 15px;">
                            <em>Catatan: {{ $item->special_instructions }}</em>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="subtotal">
            <p><span>Subtotal:</span> <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
        </div>
        
        <div class="total">
            TOTAL: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
        </div>
        
        
        
        <div class="payment-info">
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method ?? 'Cash') }}</p>
            
            @if($order->amount_paid || ($order->payment_status === 'paid'))
            <div style="margin-top: 10px; padding: 10px; background: #f0f0f0; border-radius: 5px;">
                <p style="display: flex; justify-content: space-between; margin: 5px 0;">
                    <strong>Uang Dibayar:</strong> 
                    <span style="font-weight: bold;">Rp {{ number_format($order->amount_paid ?? $order->total_amount, 0, ',', '.') }}</span>
                </p>
                @if($order->change_amount && $order->change_amount > 0)
                <p style="display: flex; justify-content: space-between; margin: 5px 0;">
                    <strong>Kembalian:</strong> 
                    <span style="font-weight: bold; color: #28a745;">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                </p>
                @elseif(!$order->amount_paid && $order->payment_status === 'paid')
                <p style="display: flex; justify-content: space-between; margin: 5px 0;">
                    <strong>Kembalian:</strong> 
                    <span style="font-weight: bold; color: #28a745;">Rp 0</span>
                </p>
                @endif
            </div>
            @endif
            
            <p style="margin-top: 10px;"><strong>Status:</strong> 
                @if($order->payment_status === 'paid')
                    <span class="status-paid">LUNAS</span>
                @else
                    <span class="status-pending">BELUM LUNAS</span>
                @endif
            </p>
        </div>
        
        <div class="footer">
            <p><strong>Terima kasih atas kunjungan Anda!</strong></p>
            <p>Selamat menikmati hidangan kami</p>
            <p style="margin-top: 10px; font-size: 10px;">Struk ini merupakan bukti pembayaran yang sah</p>
        </div>
    </div>
    
    <button class="btn-print no-print" onclick="window.print()">
        Cetak Struk
    </button>
    
    <script>
        // Auto print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>