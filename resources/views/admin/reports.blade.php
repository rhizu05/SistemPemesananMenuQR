@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="bi bi-graph-up"></i> Laporan Penjualan</h2>
        <p>Ringkasan dan analisis penjualan restoran</p>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">TOTAL PESANAN</p>
                        <h2 class="mb-0 fw-bold">{{ \App\Models\Order::count() }}</h2>
                    </div>
                    <div class="text-primary" style="font-size: 3rem; opacity: 0.2;">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">SELESAI</p>
                        <h2 class="mb-0 fw-bold text-success">{{ \App\Models\Order::where('status', 'delivered')->count() }}</h2>
                    </div>
                    <div class="text-success" style="font-size: 3rem; opacity: 0.2;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">AKTIF</p>
                        <h2 class="mb-0 fw-bold text-warning">{{ \App\Models\Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])->count() }}</h2>
                    </div>
                    <div class="text-warning" style="font-size: 3rem; opacity: 0.2;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">PENDAPATAN</p>
                        <h3 class="mb-0 fw-bold text-info" style="font-size: 1.5rem;">Rp {{ number_format(\App\Models\Order::where('payment_status', 'paid')->sum('total_amount'), 0, ',', '.') }}</h3>
                    </div>
                    <div class="text-info" style="font-size: 3rem; opacity: 0.2;">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Terlaris -->
    <div class="card mb-4">
        <div class="card-header bg-success">
            <i class="bi bi-trophy"></i> Menu Terlaris
        </div>
        <div class="card-body">
            @php
                $topMenus = \App\Models\OrderItem::select('menu_id', \DB::raw('SUM(quantity) as total_sold'))
                    ->groupBy('menu_id')
                    ->orderBy('total_sold', 'desc')
                    ->limit(5)
                    ->with('menu')
                    ->get();
            @endphp

            @if($topMenus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="10%">Peringkat</th>
                                <th width="40%">Nama Menu</th>
                                <th width="20%" class="text-center">Terjual</th>
                                <th width="15%" class="text-end">Harga</th>
                                <th width="15%" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topMenus as $index => $item)
                                <tr>
                                    <td>
                                        @if($index == 0)
                                            <span class="badge bg-warning">🥇 #1</span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary">🥈 #2</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-danger">🥉 #3</span>
                                        @else
                                            <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $item->menu->name ?? 'Menu Dihapus' }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $item->total_sold }} porsi</span>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item->menu->price ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format(($item->menu->price ?? 0) * $item->total_sold, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Belum ada data penjualan</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Status Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info">
                    <i class="bi bi-credit-card"></i> Status Pembayaran
                </div>
                <div class="card-body">
                    @php
                        $paidOrders = \App\Models\Order::where('payment_status', 'paid')->count();
                        $pendingPayment = \App\Models\Order::where('payment_status', 'pending')->count();
                        $failedPayment = \App\Models\Order::where('payment_status', 'failed')->count();
                        $totalOrders = \App\Models\Order::count();
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Lunas</span>
                            <strong class="text-success">{{ $paidOrders }} pesanan</strong>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $totalOrders > 0 ? ($paidOrders / $totalOrders * 100) : 0 }}%">
                                {{ $totalOrders > 0 ? round($paidOrders / $totalOrders * 100) : 0 }}%
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Pending</span>
                            <strong class="text-warning">{{ $pendingPayment }} pesanan</strong>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $totalOrders > 0 ? ($pendingPayment / $totalOrders * 100) : 0 }}%">
                                {{ $totalOrders > 0 ? round($pendingPayment / $totalOrders * 100) : 0 }}%
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Gagal</span>
                            <strong class="text-danger">{{ $failedPayment }} pesanan</strong>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-danger" role="progressbar" 
                                 style="width: {{ $totalOrders > 0 ? ($failedPayment / $totalOrders * 100) : 0 }}%">
                                {{ $totalOrders > 0 ? round($failedPayment / $totalOrders * 100) : 0 }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <i class="bi bi-box-seam"></i> Status Pesanan
                </div>
                <div class="card-body">
                    @php
                        $statusCounts = [
                            'pending' => \App\Models\Order::where('status', 'pending')->count(),
                            'confirmed' => \App\Models\Order::where('status', 'confirmed')->count(),
                            'preparing' => \App\Models\Order::where('status', 'preparing')->count(),
                            'ready' => \App\Models\Order::where('status', 'ready')->count(),
                            'delivered' => \App\Models\Order::where('status', 'delivered')->count(),
                            'cancelled' => \App\Models\Order::where('status', 'cancelled')->count(),
                        ];
                    @endphp

                    <table class="table table-sm mb-0">
                        <tr>
                            <td><span class="badge bg-secondary">Pending</span></td>
                            <td class="text-end"><strong>{{ $statusCounts['pending'] }}</strong></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-info">Confirmed</span></td>
                            <td class="text-end"><strong>{{ $statusCounts['confirmed'] }}</strong></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-primary">Preparing</span></td>
                            <td class="text-end"><strong>{{ $statusCounts['preparing'] }}</strong></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-warning">Ready</span></td>
                            <td class="text-end"><strong>{{ $statusCounts['ready'] }}</strong></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-success">Delivered</span></td>
                            <td class="text-end"><strong>{{ $statusCounts['delivered'] }}</strong></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-danger">Cancelled</span></td>
                            <td class="text-end"><strong>{{ $statusCounts['cancelled'] }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-success" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
    </div>
</div>

@section('styles')
<style>
    @media print {
        .btn, .card-header, .page-header { 
            display: none !important; 
        }
        .card {
            border: 1px solid #000 !important;
            page-break-inside: avoid;
        }
    }
</style>
@endsection
@endsection
