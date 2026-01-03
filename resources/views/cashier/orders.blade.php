@extends('layouts.app')

@section('title', 'Orders Hari Ini')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-receipt me-2"></i>Orders Hari Ini</h2>
        <a href="{{ route('cashier.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header" style="background: #2A5C3F; color: white;">
            <i class="bi bi-list-ul me-2"></i>Daftar Pesanan ({{ $orders->count() }} pesanan)
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->customer_name ?? ($order->user->name ?? 'Guest') }}</td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->status == 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($order->status == 'preparing')
                                        <span class="badge bg-info">Diproses</span>
                                    @elseif($order->status == 'ready')
                                        <span class="badge bg-primary">Siap</span>
                                    @elseif($order->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('H:i') }}</td>
                                <td>
                                    @if($order->payment_status == 'paid')
                                        <a href="{{ route('cashier.order.receipt', $order->id) }}" class="btn btn-sm btn-info" target="_blank">
                                            <i class="bi bi-printer"></i> Print
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled title="Pembayaran belum lunas">
                                            <i class="bi bi-printer"></i> Print
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Belum ada pesanan hari ini</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
