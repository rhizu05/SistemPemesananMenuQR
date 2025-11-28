@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h3 class="mb-3">Pesanan Berhasil!</h3>
                    <p class="text-muted mb-4">Terima kasih telah memesan. Silakan tunjukkan nomor pesanan ini ke kasir untuk pembayaran.</p>
                    
                    <!-- Nomor Pesanan -->
                    <div class="bg-light p-4 rounded mb-4">
                        <h2 class="text-primary fw-bold mb-2">{{ $order->order_number }}</h2>
                        <p class="text-muted small mb-0">Nomor Pesanan Anda</p>
                    </div>
                    
                    <!-- Instruksi -->
                    @if($order->payment_method === 'qris' && $order->payment_status === 'pending')
                        <div class="alert alert-warning">
                            <i class="bi bi-qr-code-scan me-2"></i>
                            <strong>Pembayaran QRIS</strong>
                            <p class="mb-3">Silakan scan QR Code di bawah ini untuk membayar:</p>
                            
                            @if($order->snap_token)
                                <div class="text-center bg-white p-3 rounded mb-3">
                                    <img src="{{ $order->snap_token }}" alt="QRIS Code" class="img-fluid" style="max-width: 200px;">
                                </div>
                                <p class="small text-muted">Otomatis dicek setiap 5 detik...</p>
                            @else
                                <p class="text-danger">Gagal memuat QR Code. Silakan hubungi kasir.</p>
                            @endif
                        </div>

                        <script>
                            // Auto check payment status
                            setInterval(function() {
                                fetch('{{ route("customer.order.status", $order->order_number) }}')
                                    .then(response => response.text())
                                    .then(html => {
                                        if (html.includes('Lunas') || html.includes('paid')) {
                                            location.reload();
                                        }
                                    });
                            }, 5000);
                        </script>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Langkah Selanjutnya:</strong>
                            <ol class="text-start mt-2 mb-0">
                                <li>Tunjukkan nomor pesanan ini ke kasir/admin</li>
                                <li>Lakukan pembayaran</li>
                                <li>Tunggu pesanan Anda diproses</li>
                            </ol>
                        </div>
                    @endif
                    
                    <!-- Tombol Kembali -->
                    <a href="{{ route('customer.menu') }}" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Menu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
