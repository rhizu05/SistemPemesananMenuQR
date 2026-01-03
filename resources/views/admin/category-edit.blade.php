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
                <div class="card-header text-dark" style="background-color: #FBC02D;">
                    <h4 class="mb-0">✏️ Edit Kategori</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Kategori <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $category->name) }}" 
                                required
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
                                    {{ old('is_active', $category->is_active) ? 'checked' : '' }}
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

                        @if($category->menus_count > 0)
                            <div class="alert" style="background-color: #E3F2FD; border-color: #1976D2; color: #0D47A1;">
                                <i class="bi bi-info-circle" style="color: #1976D2;"></i>
                                Kategori ini memiliki <strong>{{ $category->menus_count }} menu</strong>.
                                Jika dinonaktifkan, menu dalam kategori ini tidak akan ditampilkan.
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn text-dark" 
                                    style="background-color: #FBC02D; transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='#f9a825'; this.style.transform='translateY(-2px)';" 
                                    onmouseout="this.style.backgroundColor='#FBC02D'; this.style.transform='translateY(0)';">
                                <i class="bi bi-save"></i> Update Kategori
                            </button>
                            <a href="{{ route('admin.categories') }}" class="btn" 
                               style="background-color: #ffffff; color: #2A5C3F; border: 1px solid #2A5C3F; transition: all 0.3s;"
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
                    <h6 class="card-title" style="color: #1976D2;">
                        <i class="bi bi-info-circle-fill me-2"></i>Informasi Kategori:
                    </h6>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td width="40%"><strong>Dibuat pada:</strong></td>
                            <td>{{ $category->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir diupdate:</strong></td>
                            <td>{{ $category->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah menu:</strong></td>
                            <td>
                                <span class="badge text-white" style="background-color: #8FC69A;">{{ $category->menus_count }} menu</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection
