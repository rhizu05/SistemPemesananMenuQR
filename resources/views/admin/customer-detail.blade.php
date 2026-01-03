@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="container">
    <div class="page-header mb-4">
        <h2><i class="bi bi-person-circle"></i> Detail Pelanggan</h2>
        <p>Informasi lengkap pelanggan dan riwayat pesanan</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Customer Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-person"></i> Informasi Pelanggan
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px; font-size: 3rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    </div>
                    
                    <h4 class="text-center mb-3">{{ $customer->name }}</h4>
                    
                    <table class="table table-sm">
                        <tr>
                            <td><strong>No. HP:</strong></td>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($customer->is_active ?? true)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Verifikasi:</strong></td>
                            <td>
                                @if($customer->phone_verified_at)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning">Belum</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Terdaftar:</strong></td>
                            <td>
                                {{ $customer->created_at->format('d M Y') }}
                                <br><small class="text-muted">{{ $customer->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <i class="bi bi-graph-up"></i> Statistik
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Pesanan:</span>
                        <strong class="text-primary">{{ $customer->orders_count }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Belanja:</span>
                        <strong class="text-success">
                            Rp {{ number_format($customer->orders->sum('total_amount'), 0, ',', '.') }}
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Pesanan Terakhir:</span>
                        <small class="text-muted">
                            {{ $customer->orders->first() ? $customer->orders->first()->created_at->diffForHumans() : '-' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-clock-history"></i> Riwayat Pesanan ({{ $customer->orders_count }})
                </div>
                <div class="card-body p-0">
                    @if($customer->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->orders as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>
                                                <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($order->order_type === 'dine_in')
                                                    <span class="badge bg-info">Dine In</span>
                                                @elseif($order->order_type === 'takeaway')
                                                    <span class="badge bg-warning">Takeaway</span>
                                                @else
                                                    <span class="badge bg-primary">Delivery</span>
                                                @endif
                                            </td>
                                            <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                            <td>
                                                @if($order->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($order->status === 'confirmed')
                                                    <span class="badge bg-info">Dikonfirmasi</span>
                                                @elseif($order->status === 'preparing')
                                                    <span class="badge bg-primary">Diproses</span>
                                                @elseif($order->status === 'ready')
                                                    <span class="badge bg-success">Siap</span>
                                                @elseif($order->status === 'completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                @else
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.order.receipt', $order->id) }}" 
                                                   class="btn btn-sm btn-info"
                                                   target="_blank">
                                                    <i class="bi bi-receipt"></i> Receipt
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Belum ada riwayat pesanan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.customers') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pelanggan
        </a>
    </div>
</div>
@endsection
