@extends('layouts.app')

@section('title', 'Buat Voucher Baru')

@section('content')
<style>
    .form-control:focus, .form-select:focus {
        border-color: #4A7F5A;
        box-shadow: 0 0 0 0.2rem rgba(74, 127, 90, 0.25);
    }
    .form-check-input:checked {
        background-color: #2A5C3F;
        border-color: #2A5C3F;
    }
</style>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #2A5C3F;">
                    <h4 class="mb-0">➕ Buat Voucher Baru</h4>
                </div>
                <div class="card-body">
            <form method="POST" action="{{ route('admin.vouchers.store') }}">
                @csrf

                <!-- Hidden field for user_type since it's not in the form -->
                <input type="hidden" name="user_type" value="registered">

                <div class="row">
                    <!-- Kode Voucher -->
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">Kode Voucher <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('code') is-invalid @enderror" 
                               id="code" 
                               name="code" 
                               value="{{ old('code') }}"
                               placeholder="Contoh: WELCOME10"
                               required 
                               style="text-transform: uppercase;">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kode unik untuk voucher (akan otomatis uppercase)</small>
                    </div>

                    <!-- Nama Voucher -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Voucher <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               placeholder="Contoh: Voucher Welcome 10%"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="2"
                              placeholder="Deskripsi voucher (opsional)">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Tipe Diskon -->
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Tipe Diskon <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" 
                                name="type" 
                                required>
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed_amount" {{ old('type') == 'fixed_amount' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nilai Diskon -->
                    <div class="col-md-4 mb-3">
                        <label for="value" class="form-label">Nilai Diskon <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('value') is-invalid @enderror" 
                               id="value" 
                               name="value" 
                               value="{{ old('value') }}"
                               min="0"
                               step="0.01"
                               placeholder="10 atau 50000"
                               required>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="valueHelper">Contoh: 10 = 10% atau Rp 10.000</small>
                    </div>

                    <!-- Max Diskon (untuk percentage) -->
                    <div class="col-md-4 mb-3" id="maxDiscountContainer">
                        <label for="max_discount" class="form-label">Maks. Diskon (Opsional)</label>
                        <input type="number" 
                               class="form-control @error('max_discount') is-invalid @enderror" 
                               id="max_discount" 
                               name="max_discount" 
                               value="{{ old('max_discount') }}"
                               min="0"
                               step="0.01"
                               placeholder="50000">
                        @error('max_discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maksimal diskon untuk persentase</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Min Transaction -->
                    <div class="col-md-4 mb-3">
                        <label for="min_transaction" class="form-label">Min. Belanja <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('min_transaction') is-invalid @enderror" 
                               id="min_transaction" 
                               name="min_transaction" 
                               value="{{ old('min_transaction', 0) }}"
                               min="0"
                               step="0.01"
                               required>
                        @error('min_transaction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal belanja (0 = tidak ada min.)</small>
                    </div>

                    <!-- Quota -->
                    <div class="col-md-4 mb-3">
                        <label for="quota" class="form-label">Total Quota</label>
                        <input type="number" 
                               class="form-control @error('quota') is-invalid @enderror" 
                               id="quota" 
                               name="quota" 
                               value="{{ old('quota') }}"
                               min="1"
                               placeholder="Kosongkan untuk unlimited">
                        @error('quota')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Berapa kali total (kosong = unlimited)</small>
                    </div>

                    <!-- User Limit -->
                    <div class="col-md-4 mb-3">
                        <label for="user_limit" class="form-label">Limit per User <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('user_limit') is-invalid @enderror" 
                               id="user_limit" 
                               name="user_limit" 
                               value="{{ old('user_limit', 1) }}"
                               min="1"
                               required>
                        @error('user_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Berapa kali per user</small>
                    </div>
                </div>



                <div class="row">
                    <!-- Valid From -->
                    <div class="col-md-6 mb-3">
                        <label for="valid_from" class="form-label">Berlaku Dari</label>
                        <input type="datetime-local" 
                               class="form-control @error('valid_from') is-invalid @enderror" 
                               id="valid_from" 
                               name="valid_from" 
                               value="{{ old('valid_from') }}">
                        @error('valid_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan untuk mulai sekarang</small>
                    </div>

                    <!-- Valid Until -->
                    <div class="col-md-6 mb-3">
                        <label for="valid_until" class="form-label">Berlaku Sampai</label>
                        <input type="datetime-local" 
                               class="form-control @error('valid_until') is-invalid @enderror" 
                               id="valid_until" 
                               name="valid_until" 
                               value="{{ old('valid_until') }}">
                        @error('valid_until')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kosongkan untuk tidak ada batas</small>
                    </div>
                </div>

                <!-- Active Status -->
                <div class="mb-3 form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="is_active" 
                           name="is_active" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Aktifkan voucher sekarang
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn text-white" 
                            style="background-color: #2A5C3F; border: none; transition: all 0.3s;"
                            onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
                            onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
                        <i class="bi bi-save"></i> Simpan Voucher
                    </button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn" 
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const maxDiscountContainer = document.getElementById('maxDiscountContainer');
    const valueHelper = document.getElementById('valueHelper');

    function updateFields() {
        if (typeSelect.value === 'percentage') {
            maxDiscountContainer.style.display = 'block';
            valueHelper.textContent = 'Contoh: 10 = diskon 10%';
        } else {
            maxDiscountContainer.style.display = 'none';
            valueHelper.textContent = 'Contoh: 50000 = diskon Rp 50.000';
        }
    }

    typeSelect.addEventListener('change', updateFields);
    updateFields(); // Initial call
});
</script>
@endsection
