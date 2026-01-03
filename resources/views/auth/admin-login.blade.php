@extends('layouts.app')

@section('title', 'Login Admin/Kasir/Koki')

@section('content')
<div class="container">
    <style>
        .form-control:focus {
            border-color: #4A7F5A;
            box-shadow: 0 0 0 0.2rem rgba(74, 127, 90, 0.25);
        }
        .form-check-input:checked {
            background-color: #4A7F5A;
            border-color: #4A7F5A;
        }
    </style>
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header text-white text-center py-4" style="background-color: #2A5C3F;">
                    <h3 class="mb-0"><i class="bi bi-shield-lock"></i> Login Admin/Kasir/Koki</h3>
                    <p class="mb-0 small mt-2">Khusus untuk Admin dan Staf Dapur</p>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="admin@example.com"
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="••••••••"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn text-white w-100 btn-lg"
                                style="background-color: #2A5C3F; transition: all 0.3s;"
                                onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                                onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-circle"></i> Pelanggan? 
                            <a href="{{ route('login') }}" class="text-decoration-none" style="color: #4A7F5A;">Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
