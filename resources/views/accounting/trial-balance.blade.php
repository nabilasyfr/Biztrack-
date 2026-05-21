@extends('layouts.app')
@section('title','Neraca Saldo')
@section('page-title','Neraca Saldo (Trial Balance)')

@push('styles')
<style>
@media print {
    .no-print{display:none!important}
    #page-content{padding:0!important}
    .card{box-shadow:none!important;border:1px solid #ddd!important}
}
</style>
@endpush

@section('content')
<div class="page-header no-print">
    <div>
        <h1>Neraca Saldo</h1>
        <p>Ringkasan saldo semua akun sebelum & setelah penyesuaian</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-primary no-print">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-3 no-print">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Periode</label>
                <input type="month" name="month" class="form-control" value="{{ $month }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>

{{-- Print header --}}
<div class="text-center mb-3 d-none d-print-block">
    <h4 class="fw-bold">BizTrack UMKM — Neraca Saldo</h4>
    <p class="text-muted">Periode: {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</p>
</div>

@php
$totalDr     = $balances->sum('debit');
$totalCr     = $balances->sum('credit');
$totalAdjDr  = $adjustedBalances->sum('debit');
$totalAdjCr  = $adjustedBalances->sum('credit');
@endphp

{{-- Summary --}}
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;color:#1a56db"><i class="bi bi-table"></i></div>
            <div>
                <div class="stat-label">Total Debit (Sblm Penyesuaian)</div>
                <div class="stat-value" style="font-size:16px">Rp{{ number_format($totalDr,0,',','.') }}</div>
                <div class="stat-sub {{ $totalDr == $totalCr ? 'text-success' : 'text-danger' }}">
                    {{ $totalDr == $totalCr ? '✅ Seimbang' : '❌ Tidak Seimbang' }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-label">Total Debit (Stlh Penyesuaian)</div>
                <div class="stat-value" style="font-size:16px">Rp{{ number_format($totalAdjDr,0,',','.') }}</div>
                <div class="stat-sub {{ $totalAdjDr == $totalAdjCr ? 'text-success' : 'text-danger' }}">
                    {{ $totalAdjDr == $totalAdjCr ? '✅ Seimbang' : '❌ Tidak Seimbang' }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Adjustment entries for this period --}}
@if($adjEntries->count() > 0)
<div class="alert alert-warning mb-3" style="font-size:13px;border-radius:10px">
    <i class="bi bi-wrench me-2"></i>
    <strong>{{ $adjEntries->count() }} jurnal penyesuaian</strong> ditemukan pada periode ini.
    Kolom "Disesuaikan" sudah termasuk nilai penyesuaian tersebut.
</div>
@endif

{{-- NERACA SALDO SEBELUM PENYESUAIAN --}}
<div class="card mb-3">
    <div class="card-header fw-bold">
        <i class="bi bi-table me-2"></i>
        Neraca Saldo Sebelum Penyesuaian — {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:80px">Kode</th>
                        <th>Nama Akun</th>
                        <th class="text-center" style="width:100px">Tipe</th>
                        <th class="text-end" style="width:160px">Debit</th>
                        <th class="text-end pe-4" style="width:160px">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $b)
                    <tr>
                        <td class="ps-4"><code style="font-size:11px">{{ $b->code }}</code></td>
                        <td>{{ $b->name }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark" style="font-size:10px">{{ $b->type }}</span>
                        </td>
                        <td class="text-end {{ $b->debit > 0 ? 'text-primary fw-semibold' : 'text-muted' }}">
                            {{ $b->debit > 0 ? 'Rp'.number_format($b->debit,0,',','.') : '-' }}
                        </td>
                        <td class="text-end pe-4 {{ $b->credit > 0 ? 'text-success fw-semibold' : 'text-muted' }}">
                            {{ $b->credit > 0 ? 'Rp'.number_format($b->credit,0,',','.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi periode ini</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3" class="ps-4 fw-bold">TOTAL</td>
                        <td class="text-end fw-bold">Rp{{ number_format($totalDr,0,',','.') }}</td>
                        <td class="text-end pe-4 fw-bold">Rp{{ number_format($totalCr,0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- NERACA SALDO SETELAH PENYESUAIAN --}}
<div class="card">
    <div class="card-header fw-bold text-success">
        <i class="bi bi-check2-square me-2"></i>
        Neraca Saldo Setelah Penyesuaian — {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:80px">Kode</th>
                        <th>Nama Akun</th>
                        <th class="text-center" style="width:100px">Tipe</th>
                        <th class="text-end" style="width:160px">Debit</th>
                        <th class="text-end pe-4" style="width:160px">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustedBalances as $b)
                    <tr>
                        <td class="ps-4"><code style="font-size:11px">{{ $b->code }}</code></td>
                        <td>{{ $b->name }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark" style="font-size:10px">{{ $b->type }}</span>
                        </td>
                        <td class="text-end {{ $b->debit > 0 ? 'text-primary fw-semibold' : 'text-muted' }}">
                            {{ $b->debit > 0 ? 'Rp'.number_format($b->debit,0,',','.') : '-' }}
                        </td>
                        <td class="text-end pe-4 {{ $b->credit > 0 ? 'text-success fw-semibold' : 'text-muted' }}">
                            {{ $b->credit > 0 ? 'Rp'.number_format($b->credit,0,',','.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi periode ini</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3" class="ps-4 fw-bold">TOTAL</td>
                        <td class="text-end fw-bold">Rp{{ number_format($totalAdjDr,0,',','.') }}</td>
                        <td class="text-end pe-4 fw-bold">Rp{{ number_format($totalAdjCr,0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
