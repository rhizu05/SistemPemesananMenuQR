@extends('layouts.app')

@section('title', 'Voucher Tersedia')

@section('content')
<div class="container py-4">
    <div class="page-header mb-4">
        <h2><i class="bi bi-gift"></i> Voucher Tersedia</h2>
        <p class="text-muted">Gunakan voucher untuk mendapatkan diskon menarik!</p>
    </div>

    @if($availableVouchers->count() > 0)
        <div class="row g-3">
            @foreach($availableVouchers as $voucher)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-ticket-perforated"></i> {{ $voucher->code }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $voucher->name }}</h6>
                            
                            @if($voucher->description)
                                <p class="card-text text-muted small">{{ $voucher->description }}</p>
                            @endif
                            
                            <div class="mb-2">
                                <strong class="text-success" style="font-size: 1.5rem;">
                                    @if($voucher->type === 'percentage')
                                        {{ $voucher->value }}%
                                    @else
                                        Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                    @endif
                                </strong>
                                @if($voucher->max_discount)
                                    <br><small class="text-muted">Maks. diskon: Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</small>
                                @endif
                            </div>
                            
                            <hr>
                            
                            <ul class="list-unstyled small">
                                <li><i class="bi bi-check-circle text-success"></i> Min. belanja: <strong>Rp {{ number_format($voucher->min_transaction, 0, ',', '.') }}</strong></li>
                                
                                @if($voucher->quota)
                                    <li><i class="bi bi-hourglass-split text-warning"></i> Sisa: <strong>{{ $voucher->quota - $voucher->used_count }}</strong> dari {{ $voucher->quota }}</li>
                                @endif
                                
                                <li><i class="bi bi-person-check text-info"></i> Limit: <strong>{{ $voucher->user_limit }}x</strong> per user</li>
                                
                                @if($voucher->valid_until)
                                    <li><i class="bi bi-calendar-event text-danger"></i> Berlaku sampai: <strong>{{ $voucher->valid_until->format('d M Y') }}</strong></li>
                                @endif
                            </ul>
                        </div>
                        <div class="card-footer bg-light">
                            <button class="btn btn-success btn-sm w-100 copy-code" data-code="{{ $voucher->code }}">
                                <i class="bi bi-clipboard"></i> Salin Kode
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-ticket-perforated" style="font-size: 4rem; color: #ccc;"></i>
                <h4 class="mt-3">Belum Ada Voucher Tersedia</h4>
                <p class="text-muted">Nantikan voucher menarik dari kami!</p>
                <a href="{{ route('customer.menu') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-card-list"></i> Lihat Menu
                </a>
            </div>
        </div>  
    @endif

    <div class="mt-4">
        <a href="{{ route('customer.menu') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Menu
        </a>
        <a href="{{ route('customer.cart') }}" class="btn btn-primary">
            <i class="bi bi-cart"></i> Lihat Keranjang
        </a>
    </div>
</div>

<script>
// Copy voucher code to clipboard
document.querySelectorAll('.copy-code').forEach(btn => {
    btn.addEventListener('click', function() {
        const code = this.dataset.code;
        
        // Copy to clipboard
        navigator.clipboard.writeText(code).then(() => {
            // Show feedback
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bi bi-check-circle"></i> Kode Disalin!';
            this.classList.remove('btn-success');
            this.classList.add('btn-info');
            
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('btn-info');
                this.classList.add('btn-success');
            }, 2000);
        });
    });
});
</script>
@endsection
