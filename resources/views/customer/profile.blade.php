@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="bi bi-person-circle"></i> Profil Saya</h2>
        <p>Kelola informasi profil Anda</p>
    </div>

    <div class="row g-4">
        <!-- Profile Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white" style="background-color: #2A5C3F;">
                    <i class="bi bi-pencil" style="color: #8FC69A;"></i> Edit Profil
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="phone" 
                                   value="{{ $user->phone }}" 
                                   disabled
                                   style="background-color: #F3F7F4; border-color: #ced4da;">
                            <small class="text-muted">Nomor telepon tidak dapat diubah</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn text-white"
                                    style="background-color: #2A5C3F; transition: all 0.3s;"
                                    onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                                    onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('customer.menu') }}" class="btn"
                               style="background-color: #ffffff; color: #4A7F5A; border: 1px solid #4A7F5A; transition: all 0.3s;"
                               onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.transform='translateY(-2px)';" 
                               onmouseout="this.style.backgroundColor='#ffffff'; this.style.transform='translateY(0)';">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-white" style="background-color: #1976D2;">
                    <i class="bi bi-info-circle"></i> Informasi Akun
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px; background-color: #E3F2FD;">
                            <i class="bi bi-person-fill" style="font-size: 3rem; color: #1976D2;"></i>
                        </div>
                        <h5 class="mt-3 mb-0">{{ $user->name }}</h5>
                        <p class="text-muted small">{{ $user->phone }}</p>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <small class="text-muted">Role:</small>
                        <br>
                        <span class="badge text-white" style="background-color: #4A7F5A;">Customer</span>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">Bergabung sejak:</small>
                        <br>
                        <strong>{{ $user->created_at->format('d M Y') }}</strong>
                    </div>

                    <div>
                        <small class="text-muted">Terakhir diupdate:</small>
                        <br>
                        <strong>{{ $user->updated_at->format('d M Y H:i') }}</strong>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color: #1976D2;"><i class="bi bi-shield-check"></i> Keamanan</h6>
                    <p class="small text-muted mb-2">Pastikan informasi profil Anda selalu up-to-date untuk pengalaman pemesanan yang lebih baik.</p>
                    <a href="{{ route('customer.my-orders') }}" class="btn btn-sm w-100"
                       style="background-color: #ffffff; color: #4A7F5A; border: 1px solid #4A7F5A; transition: all 0.3s;"
                       onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.transform='translateY(-2px)';" 
                       onmouseout="this.style.backgroundColor='#ffffff'; this.style.transform='translateY(0)';">
                        <i class="bi bi-clock-history"></i> Lihat Riwayat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
