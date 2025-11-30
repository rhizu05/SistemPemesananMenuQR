@extends('layouts.app')

@section('content')
<div class="container py-4">
    <style>
        .form-control:focus, .form-select:focus {
            border-color: #4A7F5A;
            box-shadow: 0 0 0 0.2rem rgba(74, 127, 90, 0.25);
        }
        .form-check-input:checked {
            background-color: #2A5C3F;
            border-color: #2A5C3F;
        }
        input[type="file"]::file-selector-button {
            background-color: #4A7F5A;
            color: white;
            border: none;
        }
        input[type="file"]::file-selector-button:hover {
            background-color: #3d6b4a;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-dark" style="background-color: #FBC02D;">
                    <h4 class="mb-0">✏️ Edit Menu</h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Menu</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $menu->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $menu->stock) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Format: jpeg, png, jpg, gif. Maksimal: 2MB</small>
                            @if($menu->image)
                                <div class="mt-2">
                                    <p class="mb-1">Gambar saat ini:</p>
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" width="100" class="img-thumbnail" style="border-color: #8FC69A;">
                                </div>
                            @endif
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available"><strong>Tersedia</strong></label>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn text-dark" 
                                    style="background-color: #FBC02D; transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='#f9a825'; this.style.transform='translateY(-2px)';" 
                                    onmouseout="this.style.backgroundColor='#FBC02D'; this.style.transform='translateY(0)';">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.menu') }}" class="btn" 
                               style="background-color: #ffffff; color: #2A5C3F; border: 1px solid #2A5C3F; transition: all 0.3s;"
                               onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.transform='translateY(-2px)';" 
                               onmouseout="this.style.backgroundColor='#ffffff'; this.style.transform='translateY(0)';">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection