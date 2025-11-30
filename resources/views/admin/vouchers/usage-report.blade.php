@extends('layouts.app')

@section('title', 'Laporan Penggunaan Voucher')

@section('content')
<div class="container">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-bar-chart"></i> Laporan Penggunaan Voucher</h2>
                <p>Detail penggunaan untuk voucher: <strong>{{ $voucher->name }}</strong> ({{ $voucher->code }})</p>
            </div>
            <a href="{{ route('admin.vouchers.index') }}" class="btn text-white" 
               style="background-color: #4A7F5A; transition: all 0.3s;"
               onmouseover="this.style.backgroundColor='#3d6b4a'; this.style.transform='translateY(-2px)';" 
               onmouseout="this.style.backgroundColor='#4A7F5A'; this.style.transform='translateY(0)';">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white h-100" style="background-color: #1976D2;">
                <div class="card-body">
                    <h5 class="card-title">Total Penggunaan</h5>
                    <h2 class="display-4">{{ $voucher->usages->count() }}</h2>
                    <p class="card-text">Kali digunakan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white h-100" style="background-color: #FFB74D;">
                <div class="card-body">
                    <h5 class="card-title">Total Diskon Diberikan</h5>
                    <h2 class="display-4">Rp {{ number_format($voucher->usages->sum('discount_amount'), 0, ',', '.') }}</h2>
                    <p class="card-text">Total penghematan pelanggan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white h-100" style="background-color: #8FC69A;">
                <div class="card-body">
                    <h5 class="card-title">Sisa Kuota</h5>
                    <h2 class="display-4">
                        @if($voucher->quota)
                            {{ $voucher->quota - $voucher->used_count }}
                        @else
                            ∞
                        @endif
                    </h2>
                    <p class="card-text">Voucher yang masih bisa diklaim</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-white" style="background-color: #2A5C3F;">
            <i class="bi bi-list-ul"></i> Riwayat Penggunaan
        </div>
        <div class="card-body p-0">
            @if($voucher->usages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>No. Pesanan</th>
                                <th>Total Belanja</th>
                                <th>Diskon</th>
                                <th>Status Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($voucher->usages as $usage)
                                <tr>
                                    <td>{{ $usage->created_at ? $usage->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td>
                                        @if($usage->user)
                                            {{ $usage->user->name }}<br>
                                            <small class="text-muted">{{ $usage->user->email ?? $usage->user->phone }}</small>
                                        @else
                                            <span class="text-muted">Guest / Kasir</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($usage->order)
                                            <a href="{{ route('admin.order.detail', $usage->order->id) }}" class="text-decoration-none" style="color: #1976D2;">
                                                {{ $usage->order->order_number }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="color: #1E3B2C; font-weight: bold;">
                                        @if($usage->order)
                                            Rp {{ number_format($usage->order->subtotal, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="fw-bold" style="color: #4A7F5A;">
                                        -Rp {{ number_format($usage->discount_amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if($usage->order)
                                            @if($usage->order->status == 'completed' || $usage->order->status == 'delivered')
                                                <span class="badge text-white" style="background-color: #4A7F5A;">Selesai</span>
                                            @elseif($usage->order->status == 'cancelled')
                                                <span class="badge text-white" style="background-color: #D32F2F;">Dibatalkan</span>
                                            @elseif($usage->order->status == 'ready')
                                                <span class="badge text-dark" style="background-color: #FBC02D;">Siap</span>
                                            @else
                                                <span class="badge text-white" style="background-color: #1976D2;">{{ ucfirst($usage->order->status) }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada data penggunaan untuk voucher ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
