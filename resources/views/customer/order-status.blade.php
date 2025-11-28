@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Pesanan #{{ $order->order_number }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                            <p><strong>Tipe Pesanan:</strong> 
                                @if($order->order_type === 'dine_in')
                                    Dine In
                                @elseif($order->order_type === 'takeaway')
                                    Takeaway
                                @else
                                    Delivery
                                @endif
                            </p>
                            @if($order->table_number)
                                <p><strong>No. Meja:</strong> {{ $order->table_number }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                <span class="badge 
                                    @if($order->status === 'pending') bg-warning
                                    @elseif($order->status === 'confirmed') bg-info
                                    @elseif($order->status === 'preparing') bg-primary
                                    @elseif($order->status === 'ready') bg-success
                                    @elseif($order->status === 'delivered') bg-success
                                    @else bg-danger
                                    @endif
                                ">
                                    @if($order->status === 'pending') Pending
                                    @elseif($order->status === 'confirmed') Confirmed
                                    @elseif($order->status === 'preparing') Sedang Diproses
                                    @elseif($order->status === 'ready') Siap Diantar
                                    @elseif($order->status === 'delivered') Selesai
                                    @else Dibatalkan
                                    @endif
                                </span>
                            </p>
                            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                            <p><strong>Total:</strong> Rp {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                            <p><strong>Status Pembayaran:</strong> 
                                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    @if($order->special_requests)
                        <div class="alert alert-info">
                            <strong>Permintaan Khusus:</strong> {{ $order->special_requests }}
                        </div>
                    @endif
                    
                    <h5>Item Pesanan:</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->menu->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price, 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>Rp {{ number_format($order->total_amount, 2, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection