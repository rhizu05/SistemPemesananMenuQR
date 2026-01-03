@extends('layouts.app')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container">
    <!-- Debug Info (Remove in production) -->
    <div class="alert alert-info mb-3">
        <strong>Debug Info:</strong><br>
        Logged in: {{ Auth::check() ? 'Yes' : 'No' }}<br>
        @if(Auth::check())
            User: {{ Auth::user()->name }}<br>
            Email: {{ Auth::user()->email }}<br>
            Role: {{ Auth::user()->role }}<br>
            Is Admin: {{ Auth::user()->isAdmin() ? 'Yes' : 'No' }}
        @endif
    </div>

    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-card-list"></i> Manajemen Menu</h2>
                <p>Kelola menu restoran</p>
            </div>
            <a href="{{ route('admin.menu.create') }}" class="btn text-white" 
               style="background-color: #2A5C3F; transition: all 0.3s;"
               onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
               onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
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
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #2A5C3F;">
            <span><i class="bi bi-list-ul"></i> Daftar Menu</span>
            <form action="{{ route('admin.menu') }}" method="GET" class="d-flex" style="max-width: 300px;">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Cari menu..." value="{{ request('search') }}">
                    <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('admin.menu') }}" class="btn btn-danger"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            @if($menus->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="10%" class="text-center">Gambar</th>
                                <th width="20%" class="text-center">Nama Menu</th>
                                <th width="15%" class="text-center">Kategori</th>
                                <th width="12%" class="text-center">Harga</th>
                                <th width="8%" class="text-center">Stok</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $index => $menu)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">
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
                                    <td class="text-center">
                                        <strong>{{ $menu->name }}</strong>
                                        @if($menu->description)
                                            <br><small class="text-muted">{{ Str::limit($menu->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge text-white" style="background-color: #8FC69A;">
                                            {{ $menu->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center"><strong>Rp {{ number_format($menu->price, 0, ',', '.') }}</strong></td>
                                    <td class="text-center">
                                        @if($menu->stock > 10)
                                            <span class="badge text-white" style="background-color: #4A7F5A;">{{ $menu->stock }}</span>
                                        @elseif($menu->stock > 0)
                                            <span class="badge text-dark" style="background-color: #FBC02D;">{{ $menu->stock }}</span>
                                        @else
                                            <span class="badge text-white" style="background-color: #D32F2F;">Habis</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($menu->is_available)
                                            <span class="badge text-white" style="background-color: #2A5C3F;">Tersedia</span>
                                        @else
                                            <span class="badge text-white" style="background-color: #D32F2F;">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.menu.edit', $menu->id) }}" 
                                               class="btn btn-sm text-white" 
                                               style="background-color: #4A7F5A;"
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
                                                        class="btn btn-sm text-white" 
                                                        style="background-color: #D32F2F;"
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
        <a href="{{ route('admin.dashboard') }}" class="btn text-white" 
           style="background-color: #4A7F5A; transition: all 0.3s;"
           onmouseover="this.style.backgroundColor='#3d6b4a'; this.style.transform='translateY(-2px)';" 
           onmouseout="this.style.backgroundColor='#4A7F5A'; this.style.transform='translateY(0)';">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Ensure Bootstrap dropdowns work properly
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded - Initializing dropdowns');
        
        // Re-initialize all Bootstrap dropdowns
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        console.log('Found ' + dropdownElementList.length + ' dropdown toggles');
        
        dropdownElementList.forEach(function (dropdownToggleEl) {
            new bootstrap.Dropdown(dropdownToggleEl, {
                autoClose: true
            });
        });
        
        console.log('Dropdowns initialized');
    });
</script>
@endsection
