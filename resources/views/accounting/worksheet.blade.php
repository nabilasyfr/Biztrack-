@extends('layouts.app')
@section('title','Kertas Kerja')
@section('page-title','Kertas Kerja (Worksheet)')

@push('styles')
<style>
/* ─── Worksheet table ──────────────────────────────────────── */
.ws-table { border-collapse: collapse; width: 100%; }
.ws-table th,
.ws-table td { font-size: 11.5px; white-space: nowrap; padding: 5px 8px; }

/* Header row 1: group labels */
.ws-table thead tr:first-child th {
    text-align: center;
    background: #0f172a;
    color: #fff;
    font-weight: 700;
    border: 1px solid #1e293b;
}
/* Header row 1: account name cell */
.ws-table thead tr:first-child th.col-akun {
    text-align: left;
    padding-left: 12px;
    vertical-align: middle;
    min-width: 200px;
}
/* Header row 2: Debit / Kredit sub-labels */
.ws-table thead tr:last-child th {
    background: #1e293b;
    color: #94a3b8;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .05em;
    border: 1px solid #263347;
    text-align: right;
}

/* Body cells */
.ws-table tbody tr { border-bottom: 1px solid #e2e8f0; }
.ws-table tbody tr:hover { background: #f8fafc; }
.ws-table tbody td { border: 1px solid #e2e8f0; vertical-align: middle; }

/* Number cells */
.num-cell { text-align: right; font-variant-numeric: tabular-nums; }
.text-dr  { color: #1d4ed8; font-weight: 600; }
.text-cr  { color: #15803d; font-weight: 600; }

/* Net income row */
.row-net-income { background: #f0fdf4 !important; }
.row-net-loss   { background: #fff1f2 !important; }

/* Footer */
.ws-table tfoot tr td {
    background: #0f172a;
    color: #fff;
    font-weight: 800;
    font-size: 11.5px;
    border: 1px solid #1e293b;
}

/* Balance indicator badges */
.balance-ok   { background: #16a34a; color: #fff; }
.balance-fail { background: #dc2626; color: #fff; }

/* ─── Print ────────────────────────────────────────────────── */
@media print {
    .no-print { display: none !important; }
    #page-content { padding: 0 !important; }
    .card { box-shadow: none !important; }
    .ws-table th, .ws-table td { font-size: 8.5px !important; padding: 3px 5px !important; }
}
</style>
@endpush

@section('content')
<div class="page-header no-print">
    <div>
        <h1>Kertas Kerja (Worksheet)</h1>
        <p class="text-muted mb-0">Neraca lajur 10 kolom — standar AIS</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-3 no-print">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold">Periode</label>
                <input type="month" name="month" class="form-control" value="{{ $month }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Print header (only visible on print) --}}
<div class="text-center mb-3 d-none d-print-block">
    <h4 class="fw-bold mb-0">BizTrack UMKM — Kertas Kerja (Neraca Lajur)</h4>
    <p class="mb-0">Periode: {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</p>
</div>

@php
/*
 * ─── Aggregate totals untuk footer & net income ──────────────────────────
 */
$totNsDr  = $rows->sum('nsDr');
$totNsCr  = $rows->sum('nsCr');
$totAdjDr = $rows->sum('adjDr');
$totAdjCr = $rows->sum('adjCr');
$totNsdDr = $rows->sum('nsdDr');
$totNsdCr = $rows->sum('nsdCr');

// REVISI LOGIKA FILTER TOTAL: Supaya kalkulasi total bawah sinkron dengan baris tabel yang di-filter
$totLrDr  = $rows->filter(fn($r) => !in_array(substr($r->acc->code, 0, 1), ['1', '2', '3']))->sum('lrDr');
$totLrCr  = $rows->filter(fn($r) => !in_array(substr($r->acc->code, 0, 1), ['1', '2', '3']))->sum('lrCr');
$totNerDr = $rows->filter(fn($r) => in_array(substr($r->acc->code, 0, 1), ['1', '2', '3']))->sum('nerDr');
$totNerCr = $rows->filter(fn($r) => in_array(substr($r->acc->code, 0, 1), ['1', '2', '3']))->sum('nerCr');

// Net income (positif = laba, negatif = rugi)
$netIncome = $totLrCr - $totLrDr;

// Setelah penambahan baris net income, total keempat kolom seharusnya seimbang
$grandLrDr  = $totLrDr  + ($netIncome > 0 ? $netIncome : 0);
$grandLrCr  = $totLrCr  + ($netIncome < 0 ? abs($netIncome) : 0);
$grandNerDr = $totNerDr + ($netIncome < 0 ? abs($netIncome) : 0);
$grandNerCr = $totNerCr + ($netIncome > 0 ? $netIncome : 0);

// Helper format angka: tampilkan '-' jika nol
$fmt = fn($n) => $n > 0 ? number_format($n, 0, ',', '.') : '-';
$fmtAbs = fn($n) => number_format(abs($n), 0, ',', '.');

// Balance checks
$nsBalanced  = abs($totNsDr  - $totNsCr)  < 0.01;
$nsdBalanced = abs($totNsdDr - $totNsdCr) < 0.01;
$lrBalanced  = abs($grandLrDr - $grandLrCr)  < 0.01;
$nerBalanced = abs($grandNerDr - $grandNerCr) < 0.01;
@endphp

<div class="card">
    <div class="card-header d-flex align-items-center gap-2 flex-wrap">
        <i class="bi bi-grid-3x3-gap me-1"></i>
        <strong>Kertas Kerja — {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</strong>

        {{-- Balance status badges --}}
        <span class="badge {{ $nsBalanced ? 'balance-ok' : 'balance-fail' }} ms-1">
            NS {{ $nsBalanced ? '✓ Seimbang' : '✗ Tidak Seimbang' }}
        </span>
        <span class="badge {{ $nsdBalanced ? 'balance-ok' : 'balance-fail' }}">
            NSD {{ $nsdBalanced ? '✓ Seimbang' : '✗ Tidak Seimbang' }}
        </span>
        <span class="badge {{ $lrBalanced ? 'balance-ok' : 'balance-fail' }}">
            L/R {{ $lrBalanced ? '✓ Seimbang' : '✗ Tidak Seimbang' }}
        </span>
        <span class="badge {{ $nerBalanced ? 'balance-ok' : 'balance-fail' }}">
            Neraca {{ $nerBalanced ? '✓ Seimbang' : '✗ Tidak Seimbang' }}
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 ws-table">
                {{-- ── HEADER ────────────────────────────────────────────────── --}}
                <thead>
                    {{-- Row 1: Group labels --}}
                    <tr>
                        <th class="col-akun" rowspan="2">Nama Akun</th>
                        <th colspan="2">Neraca Saldo</th>
                        <th colspan="2">Penyesuaian</th>
                        <th colspan="2">NS Disesuaikan</th>
                        <th colspan="2">Laba / Rugi</th>
                        <th colspan="2">Neraca</th>
                    </tr>
                    {{-- Row 2: D/K sub-headers --}}
                    <tr>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                    </tr>
                </thead>

                {{-- ── BODY ─────────────────────────────────────────────────── --}}
                <tbody>
                    @forelse($rows as $r)
                    @php
                        // Ambil digit pertama dari kode akun untuk membedakan kategori (1=Aset, 2=Utang, 3=Modal, 4=Pendapatan, 5=Beban)
                        $accountFirstDigit = substr($r->acc->code, 0, 1);
                        $isRealAccount = in_array($accountFirstDigit, ['1', '2', '3']);
                    @endphp
                    <tr>
                        {{-- Nama akun --}}
                        <td class="ps-3">
                            <code style="font-size:10px;color:#64748b">{{ $r->acc->code }}</code>
                            <span class="ms-1">{{ $r->acc->name }}</span>
                            <span class="badge bg-light text-secondary ms-1" style="font-size:9px">{{ $r->acc->type }}</span>
                        </td>

                        {{-- Neraca Saldo (saldo bersih) --}}
                        <td class="num-cell {{ $r->nsDr > 0 ? 'text-dr' : '' }}">{{ $fmt($r->nsDr) }}</td>
                        <td class="num-cell {{ $r->nsCr > 0 ? 'text-cr' : '' }}">{{ $fmt($r->nsCr) }}</td>

                        {{-- Penyesuaian (mentah) --}}
                        <td class="num-cell {{ $r->adjDr > 0 ? 'text-dr' : '' }}">{{ $fmt($r->adjDr) }}</td>
                        <td class="num-cell {{ $r->adjCr > 0 ? 'text-cr' : '' }}">{{ $fmt($r->adjCr) }}</td>

                        {{-- NS Disesuaikan (saldo bersih) --}}
                        <td class="num-cell {{ $r->nsdDr > 0 ? 'text-dr' : '' }}">{{ $fmt($r->nsdDr) }}</td>
                        <td class="num-cell {{ $r->nsdCr > 0 ? 'text-cr' : '' }}">{{ $fmt($r->nsdCr) }}</td>

                        {{-- REVISI: Laba / Rugi (Hanya muncul jika BUKAN akun Riil kepala 1,2,3) --}}
                        <td class="num-cell {{ (!$isRealAccount && $r->lrDr > 0) ? 'text-dr' : '' }}">
                            {{ $isRealAccount ? '-' : $fmt($r->lrDr) }}
                        </td>
                        <td class="num-cell {{ (!$isRealAccount && $r->lrCr > 0) ? 'text-cr' : '' }}">
                            {{ $isRealAccount ? '-' : $fmt($r->lrCr) }}
                        </td>

                        {{-- REVISI: Neraca (Hanya muncul jika merupakan akun Riil kepala 1,2,3) --}}
                        <td class="num-cell {{ ($isRealAccount && $r->nerDr > 0) ? 'text-dr' : '' }}">
                            {{ $isRealAccount ? $fmt($r->nerDr) : '-' }}
                        </td>
                        <td class="num-cell {{ ($isRealAccount && $r->nerCr > 0) ? 'text-cr' : '' }}">
                            {{ $isRealAccount ? $fmt($r->nerCr) : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">Tidak ada data untuk periode ini</td>
                    </tr>
                    @endforelse

                    {{-- ── Net Income / Rugi Baris Penyeimbang ──────────────── --}}
                    @if($netIncome != 0)
                    <tr class="{{ $netIncome > 0 ? 'row-net-income' : 'row-net-loss' }}">
                        <td class="ps-3 fw-bold {{ $netIncome > 0 ? 'text-success' : 'text-danger' }}">
                            @if($netIncome > 0)
                                <i class="bi bi-arrow-up-circle me-1"></i>Laba Bersih
                            @else
                                <i class="bi bi-arrow-down-circle me-1"></i>Rugi Bersih
                            @endif
                        </td>
                        {{-- NS, Penyesuaian, NS Disesuaikan: kosong --}}
                        <td colspan="6" style="background: transparent;"></td>
                        {{-- L/R: letakkan pada kolom penyeimbang --}}
                        @if($netIncome > 0)
                            {{-- Laba: masuk Debit L/R (menyeimbangkan kredit yang lebih besar) --}}
                            <td class="num-cell fw-bold text-dr">{{ $fmtAbs($netIncome) }}</td>
                            <td class="num-cell">-</td>
                        @else
                            {{-- Rugi: masuk Kredit L/R (menyeimbangkan debit yang lebih besar) --}}
                            <td class="num-cell">-</td>
                            <td class="num-cell fw-bold text-cr">{{ $fmtAbs($netIncome) }}</td>
                        @endif
                        {{-- Neraca: kebalikannya --}}
                        @if($netIncome > 0)
                            {{-- Laba masuk Kredit Neraca (menambah ekuitas) --}}
                            <td class="num-cell">-</td>
                            <td class="num-cell fw-bold text-cr">{{ $fmtAbs($netIncome) }}</td>
                        @endif
                    </tr>
                    @endif
                </tbody>

                {{-- ── FOOTER: Grand Total ───────────────────────────────────── --}}
                <tfoot>
                    <tr>
                        <td class="ps-3">TOTAL</td>
                        {{-- NS --}}
                        <td class="num-cell">{{ number_format($totNsDr,  0, ',', '.') }}</td>
                        <td class="num-cell">{{ number_format($totNsCr,  0, ',', '.') }}</td>
                        {{-- Penyesuaian --}}
                        <td class="num-cell">{{ number_format($totAdjDr, 0, ',', '.') }}</td>
                        <td class="num-cell">{{ number_format($totAdjCr, 0, ',', '.') }}</td>
                        {{-- NS Disesuaikan --}}
                        <td class="num-cell">{{ number_format($totNsdDr, 0, ',', '.') }}</td>
                        <td class="num-cell">{{ number_format($totNsdCr, 0, ',', '.') }}</td>
                        {{-- L/R (sudah termasuk baris net income) --}}
                        <td class="num-cell">{{ number_format($grandLrDr,  0, ',', '.') }}</td>
                        <td class="num-cell">{{ number_format($grandLrCr,  0, ',', '.') }}</td>
                        {{-- Neraca (sudah termasuk baris net income) --}}
                        <td class="num-cell">{{ number_format($grandNerDr, 0, ',', '.') }}</td>
                        <td class="num-cell">{{ number_format($grandNerCr, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>{{-- /table-responsive --}}
    </div>{{-- /card-body --}}


        </div>
    </div>
</div>
@endsection