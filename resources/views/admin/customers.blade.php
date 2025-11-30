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
            <div class="card text-white" style="background-color: #1976D2;">
                <div class="card-body">
                    <h3 class="mb-0">{{ $customers->total() }}</h3>
                    <small>Total Pelanggan</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white" style="background-color: #2A5C3F;">
                <div class="card-body">
                    <h3 class="mb-0">{{ $customers->where('phone_verified_at', '!=', null)->count() }}</h3>
                    <small>Terverifikasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white" style="background-color: #8FC69A;">
                <div class="card-body">
                    <h3 class="mb-0">{{ $customers->sum('orders_count') }}</h3>
                    <small>Total Pesanan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-white" style="background-color: #2A5C3F;">
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
                                            <br><small style="color: #4A7F5A;"><i class="bi bi-check-circle-fill"></i> Terverifikasi</small>
                                        @endif
                                    </td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>
                                        <small class="text-muted">{{ $customer->email ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($customer->orders_count > 0)
                                            <span class="badge text-white" style="background-color: #8FC69A;">{{ $customer->orders_count }} pesanan</span>
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
                                            <span class="badge text-white" style="background-color: #2A5C3F;">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.customer.detail', $customer->id) }}" 
                                               class="btn btn-sm text-white d-inline-flex align-items-center justify-content-center" 
                                               style="background-color: #4A7F5A; min-width: 36px; height: 31px; border-radius: 6px; margin-right: 4px;"
                                               title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.customer.delete', $customer->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus pelanggan {{ $customer->name }}?\n\n{{ $customer->orders_count > 0 ? 'Customer memiliki ' . $customer->orders_count . ' pesanan. Pesanan akan diubah menjadi pesanan guest (tanpa customer).' : 'Customer tidak memiliki pesanan.' }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm text-white d-inline-flex align-items-center justify-content-center" 
                                                        style="background-color: #D32F2F; min-width: 36px; height: 31px; border-radius: 6px;"
                                                        title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
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
        <a href="{{ route('admin.dashboard') }}" class="btn text-white" 
           style="background-color: #4A7F5A; transition: all 0.3s;"
           onmouseover="this.style.backgroundColor='#3d6b4a'; this.style.transform='translateY(-2px)';" 
           onmouseout="this.style.backgroundColor='#4A7F5A'; this.style.transform='translateY(0)';">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
