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
                <div class="card-header">
                    <i class="bi bi-pencil"></i> Edit Profil
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
                                   style="background-color: #e9ecef;">
                            <small class="text-muted">Nomor telepon tidak dapat diubah</small>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Alamat lengkap Anda">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('customer.menu') }}" class="btn btn-outline-danger">
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
                <div class="card-header bg-primary">
                    <i class="bi bi-info-circle"></i> Informasi Akun
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="mt-3 mb-0">{{ $user->name }}</h5>
                        <p class="text-muted small">{{ $user->phone }}</p>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <small class="text-muted">Role:</small>
                        <br>
                        <span class="badge bg-success">Customer</span>
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
                    <h6 class="fw-bold mb-3"><i class="bi bi-shield-check"></i> Keamanan</h6>
                    <p class="small text-muted mb-2">Pastikan informasi profil Anda selalu up-to-date untuk pengalaman pemesanan yang lebih baik.</p>
                    <a href="{{ route('customer.my-orders') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-clock-history"></i> Lihat Riwayat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
