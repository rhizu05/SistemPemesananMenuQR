@extends('layouts.app')

@section('content')
<div class="container py-4">
    <style>
        .form-control:focus {
            border-color: #4A7F5A;
            box-shadow: 0 0 0 0.2rem rgba(74, 127, 90, 0.25);
        }
        .form-check-input:checked {
            background-color: #2A5C3F;
            border-color: #2A5C3F;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #2A5C3F;">
                    <h4 class="mb-0">Tambah Kategori Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required
                                placeholder="Contoh: Makanan Utama, Minuman, Dessert"
                                autofocus
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Nama kategori harus unik</small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    id="is_active" 
                                    name="is_active"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="is_active">
                                    <strong>Aktifkan Kategori</strong>
                                    <br>
                                    <small class="text-muted">
                                        Kategori yang aktif akan muncul di menu customer
                                    </small>
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn text-white" 
                                    style="background-color: #2A5C3F; transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                                    onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
                                <i class="bi bi-save"></i> Simpan Kategori
                            </button>
                            <a href="{{ route('admin.categories') }}" class="btn" 
                               style="background-color: #ffffff; color: #4A7F5A; border: 1px solid #4A7F5A; transition: all 0.3s;"
                               onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.transform='translateY(-2px)';" 
                               onmouseout="this.style.backgroundColor='#ffffff'; this.style.transform='translateY(0)';">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="card-title" style="color: #1E3B2C;">
                        <i class="bi bi-lightbulb-fill me-2" style="color: #1976D2;"></i> Tips:
                    </h6>
                    <ul class="mb-0 small" style="color: #1E3B2C;">
                        <li>Buat kategori yang jelas dan mudah dipahami customer</li>
                        <li>Gunakan nama kategori yang umum seperti "Makanan Utama", "Minuman", "Dessert", dll</li>
                        <li>Kategori yang nonaktif tidak akan ditampilkan di menu customer</li>
                        <li>Anda dapat mengubah status kategori kapan saja</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection
