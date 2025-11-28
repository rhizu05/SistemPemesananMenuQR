@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>QR Code untuk Meja {{ $tableNumber }}</h4>
                </div>
                <div class="card-body text-center">
                    <p>Gunakan aplikasi scan QR untuk mengakses menu di meja {{ $tableNumber }}</p>
                    
                    <!-- Di sini seharusnya menampilkan QR code, tapi kita gunakan link sebagai gantinya -->
                    <div class="mb-3">
                        <p>Link Menu:</p>
                        <a href="{{ $qrUrl }}" target="_blank">{{ $qrUrl }}</a>
                    </div>
                    
                    <!-- Placeholder untuk QR code -->
                    <div class="border p-3 mb-3" style="display: inline-block;">
                        <div style="width: 200px; height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                            <span>QR Code</span>
                        </div>
                    </div>
                    
                    <p>QR Code ini akan membawa pelanggan langsung ke menu untuk meja {{ $tableNumber }}</p>
                    
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection