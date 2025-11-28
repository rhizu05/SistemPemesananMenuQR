@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container">
    <div class="page-header">
        <h2><i class="bi bi-clock-history"></i> Riwayat Pesanan</h2>
        <p>Lihat semua pesanan Anda</p>
    </div>

    @if($orders->count() > 0)
        <div class="row g-3">
            @foreach($orders as $order)
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $order->order_number }}</strong>
                                <br>
                                <small class="text-dark">{{ $order->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            <div>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($order->status == 'confirmed')
                                    <span class="badge bg-info">Dikonfirmasi</span>
                                @elseif($order->status == 'preparing')
                                    <span class="badge bg-primary">Diproses</span>
                                @elseif($order->status == 'ready')
                                    <span class="badge bg-success">Siap</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-2">Item Pesanan:</h6>
                                    <ul class="list-unstyled">
                                        @foreach($order->orderItems as $item)
                                            <li class="mb-1">
                                                <strong>{{ $item->quantity }}x</strong> {{ $item->menu->name }}
                                                @if($item->special_instructions)
                                                    <br><small class="text-muted">Catatan: {{ $item->special_instructions }}</small>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-4 text-end">
                                    <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    <p class="mb-1"><small>Tipe: {{ ucfirst($order->order_type) }}</small></p>
                                    @if($order->table_number)
                                        <p class="mb-1"><small>Meja: {{ $order->table_number }}</small></p>
                                    @endif
                                    <p class="mb-1">
                                        <small>Metode: 
                                            @if($order->payment_method == 'cash')
                                                Tunai
                                            @elseif($order->payment_method == 'qris')
                                                QRIS
                                            @elseif($order->payment_method == 'card')
                                                Kartu
                                            @else
                                                {{ strtoupper($order->payment_method ?? '-') }}
                                            @endif
                                        </small>
                                    </p>
                                    <p class="mb-0">
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">Dibayar</span>
                                        @else
                                            <span class="badge bg-warning">Belum Bayar</span>
                                        @endif
                                    </p>
                                    <div class="mt-3">
                                        <a href="{{ route('customer.order.status', $order->order_number) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Detail Pembayaran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <h4 class="mt-3">Belum Ada Pesanan</h4>
                <p class="text-muted">Anda belum pernah melakukan pesanan</p>
                <a href="{{ route('customer.menu') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-card-list"></i> Lihat Menu
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
