@extends('layouts.app')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-card-list"></i> Manajemen Menu</h2>
                <p>Kelola menu restoran</p>
            </div>
            <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Menu
            </a>
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

    <div class="card">
        <div class="card-header">
            <i class="bi bi-list-ul"></i> Daftar Menu
        </div>
        <div class="card-body p-0">
            @if($menus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Gambar</th>
                                <th width="20%">Nama Menu</th>
                                <th width="15%">Kategori</th>
                                <th width="12%">Harga</th>
                                <th width="8%" class="text-center">Stok</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $index => $menu)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}" 
                                                 alt="{{ $menu->name }}" 
                                                 class="img-thumbnail" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $menu->name }}</strong>
                                        @if($menu->description)
                                            <br><small class="text-muted">{{ Str::limit($menu->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $menu->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td><strong>Rp {{ number_format($menu->price, 0, ',', '.') }}</strong></td>
                                    <td class="text-center">
                                        @if($menu->stock > 10)
                                            <span class="badge bg-success">{{ $menu->stock }}</span>
                                        @elseif($menu->stock > 0)
                                            <span class="badge bg-warning">{{ $menu->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $menu->stock }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($menu->is_available)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.menu.edit', $menu->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.menu.delete', $menu->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
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
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">Belum Ada Menu</h4>
                    <p class="text-muted">Belum ada menu yang ditambahkan</p>
                    <a href="{{ route('admin.menu.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Tambah Menu Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection