@extends('layouts.app')

@section('title', 'Manajemen Kategori Menu')

@section('content')
<div class="container">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-folder"></i> Manajemen Kategori Menu</h2>
                <p>Kelola kategori menu restoran</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
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
            <i class="bi bi-list-ul"></i> Daftar Kategori
        </div>
        <div class="card-body p-0">
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Nama Kategori</th>
                                <th width="35%">Deskripsi</th>
                                <th width="10%" class="text-center">Jumlah Menu</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $category->name }}</strong></td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $category->description ?? '-' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            {{ $category->menus_count }} menu
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($category->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.delete', $category->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="Hapus"
                                                        {{ $category->menus_count > 0 ? 'disabled' : '' }}>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @if($category->menus_count > 0)
                                            <small class="d-block text-muted mt-1">
                                                Tidak dapat dihapus
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-folder-x" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">Belum Ada Kategori</h4>
                    <p class="text-muted">Belum ada kategori menu yang ditambahkan</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Tambah Kategori Pertama
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
