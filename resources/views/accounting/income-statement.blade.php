@extends('layouts.app')
@section('title','Laporan Laba Rugi')
@section('page-title','Laporan Laba Rugi (Income Statement)')

@push('styles')
<style>
@media print {
    .no-print{display:none!important}
    #page-content{padding:0!important}
    .card{box-shadow:none!important;border:1px solid #ddd!important}
}
.is-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f1f5f9; font-size:14px; }
.is-row:last-child { border-bottom:none; }
.is-total { display:flex; justify-content:space-between; padding:12px 0; font-size:15px; font-weight:800; }
</style>
@endpush

@section('content')
<div class="page-header no-print">
    <div>
        <h1>Laporan Laba Rugi</h1>
        <p>Ringkasan pendapatan & beban dari akun akuntansi</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline-primary no-print">
        <i class="bi bi-printer me-1"></i> Cetak
    </button>
</div>

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
<div class="text-center mb-4 d-none d-print-block">
    <h4 class="fw-bold">BizTrack UMKM</h4>
    <h5>Laporan Laba Rugi</h5>
    <p>Periode: {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}</p>
    <p style="font-size:12px">Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>

<div class="row g-3">
    {{-- Summary Cards --}}
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;color:#1a56db"><i class="bi bi-graph-up"></i></div>
            <div>
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value" style="font-size:17px;color:#1a56db">Rp{{ number_format($totalRevenue,0,',','.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;color:#dc2626"><i class="bi bi-graph-down"></i></div>
            <div>
                <div class="stat-label">Total Beban</div>
                <div class="stat-value" style="font-size:17px;color:#dc2626">Rp{{ number_format($totalExpense,0,',','.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $netIncome>=0?'#f0fdf4':'#fef2f2' }};color:{{ $netIncome>=0?'#16a34a':'#dc2626' }}">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <div class="stat-label">Laba / Rugi Bersih</div>
                <div class="stat-value" style="font-size:17px;color:{{ $netIncome>=0?'#16a34a':'#dc2626' }}">
                    {{ $netIncome < 0 ? '(' : '' }}Rp{{ number_format(abs($netIncome),0,',','.') }}{{ $netIncome < 0 ? ')' : '' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Laporan Formal --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header fw-bold">
                <i class="bi bi-file-earmark-bar-graph me-2"></i>
                Laporan Laba Rugi — {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}
            </div>
            <div class="card-body" style="padding:24px 28px">

                {{-- Header --}}
                <div class="text-center mb-4">
                    <div class="fw-bold" style="font-size:15px">BizTrack UMKM</div>
                    <div style="font-size:13px;color:#64748b">Laporan Laba Rugi</div>
                    <div style="font-size:12px;color:#94a3b8">
                        Untuk Periode {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
                    </div>
                </div>

                {{-- Pendapatan --}}
                <div class="fw-bold mb-2" style="font-size:13px;text-transform:uppercase;letter-spacing:.05em;color:#374151">
                    PENDAPATAN
                </div>
                @forelse($revenues as $rev)
                <div class="is-row">
                    <span class="text-muted ps-3">{{ $rev->code }} - {{ $rev->name }}</span>
                    <span class="fw-semibold">Rp{{ number_format($rev->amount,0,',','.') }}</span>
                </div>
                @empty
                <div class="is-row"><span class="text-muted ps-3 fst-italic">Tidak ada pendapatan</span><span>-</span></div>
                @endforelse
                <div class="is-total" style="border-top:2px solid #0f172a;margin-top:4px">
                    <span>Total Pendapatan</span>
                    <span class="text-primary">Rp{{ number_format($totalRevenue,0,',','.') }}</span>
                </div>

                <div style="margin:20px 0 12px;border-top:1px dashed #e2e8f0"></div>

                {{-- Beban --}}
                <div class="fw-bold mb-2" style="font-size:13px;text-transform:uppercase;letter-spacing:.05em;color:#374151">
                    BEBAN OPERASIONAL
                </div>
                @forelse($expenses as $exp)
                <div class="is-row">
                    <span class="text-muted ps-3">{{ $exp->code }} - {{ $exp->name }}</span>
                    <span class="fw-semibold text-danger">(Rp{{ number_format($exp->amount,0,',','.') }})</span>
                </div>
                @empty
                <div class="is-row"><span class="text-muted ps-3 fst-italic">Tidak ada beban</span><span>-</span></div>
                @endforelse
                <div class="is-total" style="border-top:2px solid #0f172a;margin-top:4px">
                    <span>Total Beban</span>
                    <span class="text-danger">(Rp{{ number_format($totalExpense,0,',','.') }})</span>
                </div>

                <div style="margin:20px 0 12px;border-top:2px solid #0f172a"></div>

                {{-- Net Income --}}
                <div class="is-total" style="background:{{ $netIncome>=0?'#f0fdf4':'#fef2f2' }};padding:14px 16px;border-radius:10px;margin-top:4px">
                    <span style="font-size:16px">{{ $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</span>
                    <span style="font-size:20px;color:{{ $netIncome>=0?'#16a34a':'#dc2626' }}">
                        {{ $netIncome < 0 ? '(' : '' }}Rp{{ number_format(abs($netIncome),0,',','.') }}{{ $netIncome < 0 ? ')' : '' }}
                    </span>
                </div>

                @if($totalRevenue > 0)
                <div class="mt-3 d-flex gap-3 flex-wrap" style="font-size:12px;color:#64748b">
                    <span>Margin Bersih: <strong>{{ number_format(($netIncome/$totalRevenue)*100,1) }}%</strong></span>
                    <span>Rasio Beban: <strong>{{ number_format(($totalExpense/$totalRevenue)*100,1) }}%</strong></span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Side info --}}
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-bar-chart me-2"></i>Analisis Akun</div>
            <div class="card-body p-0">
                <div class="p-3 border-bottom">
                    <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:8px">Sumber Pendapatan</div>
                    @foreach($revenues as $rev)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1" style="font-size:12px">
                            <span>{{ $rev->name }}</span>
                            <span class="fw-bold">{{ $totalRevenue>0 ? number_format(($rev->amount/$totalRevenue)*100,1) : 0 }}%</span>
                        </div>
                        <div class="progress" style="height:5px;border-radius:3px">
                            <div class="progress-bar bg-primary" style="width:{{ $totalRevenue>0?($rev->amount/$totalRevenue)*100:0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="p-3">
                    <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:8px">Beban Terbesar</div>
                    @foreach($expenses->sortByDesc('amount')->take(5) as $exp)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1" style="font-size:12px">
                            <span>{{ $exp->name }}</span>
                            <span class="fw-bold text-danger">Rp{{ number_format($exp->amount,0,',','.') }}</span>
                        </div>
                        <div class="progress" style="height:5px;border-radius:3px">
                            <div class="progress-bar bg-danger" style="width:{{ $totalExpense>0?($exp->amount/$totalExpense)*100:0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
