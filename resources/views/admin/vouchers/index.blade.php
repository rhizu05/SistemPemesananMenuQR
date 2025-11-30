@extends('layouts.app')

@section('title', 'Manajemen Voucher')

@section('content')
<div class="container">
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-ticket-perforated"></i> Manajemen Voucher</h2>
                <p>Kelola voucher diskon untuk pelanggan</p>
            </div>
            <a href="{{ route('admin.vouchers.create') }}" class="btn text-white" 
               style="background-color: #2A5C3F; transition: all 0.3s;"
               onmouseover="this.style.backgroundColor='#1E3B2C'; this.style.transform='translateY(-2px)';" 
               onmouseout="this.style.backgroundColor='#2A5C3F'; this.style.transform='translateY(0)';">
                <i class="bi bi-plus-circle"></i> Buat Voucher Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header text-white" style="background-color: #2A5C3F;">
            <i class="bi bi-list-ul"></i> Daftar Voucher
        </div>
        <div class="card-body p-0">
            @if($vouchers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Nilai</th>
                                <th>Min. Belanja</th>
                                <th>Quota</th>
                                <th>Berlaku</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $voucher)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $voucher->code }}</strong>
                                    </td>
                                    <td>{{ $voucher->name }}</td>
                                    <td>
                                        @if($voucher->type === 'percentage')
                                            <span class="badge text-white" style="background-color: #8FC69A;">Persentase</span>
                                        @else
                                            <span class="badge text-white" style="background-color: #1976D2;">Nominal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $voucher->formatted_value }}</strong>
                                        @if($voucher->max_discount)
                                            <br><small class="text-muted">Max: Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</small>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($voucher->min_transaction, 0, ',', '.') }}</td>
                                    <td>
                                        @if($voucher->quota)
                                            {{ $voucher->used_count }}/{{ $voucher->quota }}
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar" style="width: {{ ($voucher->used_count / $voucher->quota) * 100 }}%"></div>
                                            </div>
                                        @else
                                            <span class="badge text-white" style="background-color: #B0BEC5;">Unlimited</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            @if($voucher->valid_from)
                                                {{ $voucher->valid_from->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                            <br>s/d<br>
                                            @if($voucher->valid_until)
                                                {{ $voucher->valid_until->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </small>
                                    </td>
                                    <td>{!! $voucher->status_badge !!}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="{{ route('admin.vouchers.usage', $voucher->id) }}" 
                                               class="btn btn-sm text-white"
                                               style="background-color: #1976D2;"
                                               title="Laporan Penggunaan">
                                                <i class="bi bi-bar-chart"></i>
                                            </a>
                                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" 
                                               class="btn btn-sm text-white"
                                               style="background-color: #4A7F5A;"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.vouchers.toggle', $voucher->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm text-dark"
                                                        style="background-color: #FBC02D;"
                                                        title="{{ $voucher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="bi bi-{{ $voucher->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            @if($voucher->used_count == 0)
                                                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus voucher ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm text-white"
                                                            style="background-color: #D32F2F;"
                                                            title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm text-white" 
                                                        style="background-color: #D32F2F; opacity: 0.5;"
                                                        disabled 
                                                        title="Voucher sudah digunakan">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-ticket-perforated" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">Belum Ada Voucher</h4>
                    <p class="text-muted">Buat voucher pertama Anda untuk menarik pelanggan!</p>
                    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Buat Voucher
                    </a>
                </div>
            @endif
        </div>
        @if($vouchers->hasPages())
            <div class="card-footer">
                {{ $vouchers->links() }}
            </div>
        @endif
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.dashboard') }}" class="btn text-white" 
           style="background-color: #4A7F5A; transition: all 0.3s;"
           onmouseover="this.style.backgroundColor='#3d6b4a'; this.style.transform='translateY(-2px)';" 
           onmouseout="this.style.backgroundColor='#4A7F5A'; this.style.transform='translateY(0)';">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
