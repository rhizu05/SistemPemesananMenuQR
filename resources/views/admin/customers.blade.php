@extends('layouts.app')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="container">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-people"></i> Manajemen Pelanggan</h2>
                <p>Kelola data pelanggan terdaftar</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $customers->total() }}</h3>
                    <small>Total Pelanggan</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $customers->where('phone_verified_at', '!=', null)->count() }}</h3>
                    <small>Terverifikasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $customers->sum('orders_count') }}</h3>
                    <small>Total Pesanan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-list-ul"></i> Daftar Pelanggan
        </div>
        <div class="card-body p-0">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Nama</th>
                                <th width="15%">No. HP</th>
                                <th width="15%">Email</th>
                                <th width="10%" class="text-center">Total Pesanan</th>
                                <th width="12%">Terdaftar</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="13%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $index => $customer)
                                <tr>
                                    <td>{{ $customers->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $customer->name }}</strong>
                                        @if($customer->phone_verified_at)
                                            <br><small class="text-success"><i class="bi bi-check-circle-fill"></i> Terverifikasi</small>
                                        @endif
                                    </td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>
                                        <small class="text-muted">{{ $customer->email ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($customer->orders_count > 0)
                                            <span class="badge bg-info">{{ $customer->orders_count }} pesanan</span>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $customer->created_at->format('d/m/Y') }}</small>
                                        <br><small class="text-muted">{{ $customer->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($customer->is_active ?? true)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.customer.detail', $customer->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($customer->orders_count == 0)
                                                <form action="{{ route('admin.customer.delete', $customer->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-danger" 
                                                        disabled 
                                                        title="Tidak dapat dihapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">Belum Ada Pelanggan</h4>
                    <p class="text-muted">Belum ada pelanggan yang terdaftar</p>
                </div>
            @endif
        </div>
        @if($customers->hasPages())
            <div class="card-footer">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
