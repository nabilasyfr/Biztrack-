@extends('layouts.app')
@section('title','Laporan Inventori')
@section('page-title','Laporan Inventori')

@push('styles')
<style>
@media print {
    .no-print { display: none !important; }
    #page-content { padding: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>
@endpush

@section('content')
<div class="page-header no-print">
    <div>
        <h1>Laporan Inventori</h1>
        <p>Status stok semua produk</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline-primary">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>

{{-- Filter --}}
<div class="card mb-3 no-print">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Filter Stok</label>
                <select name="filter" class="form-select">
                    <option value="all" {{ $filter==='all'?'selected':'' }}>Semua Produk</option>
                    <option value="low" {{ $filter==='low'?'selected':'' }}>Stok Rendah</option>
                    <option value="out" {{ $filter==='out'?'selected':'' }}>Habis / 0</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-filter me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('reports.inventory') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Print header --}}
<div class="text-center mb-3 d-none d-print-block">
    <h3 class="fw-bold">BizTrack UMKM — Laporan Inventori</h3>
    <p class="text-muted">Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>

{{-- Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;color:#1a56db;"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="stat-label">Total Produk</div>
                <div class="stat-value">{{ $products->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-label">Stok Rendah</div>
                <div class="stat-value" style="color:#dc2626">{{ $lowStockCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="stat-label">Nilai Stok (HPP)</div>
                <div class="stat-value" style="font-size:15px">Rp{{ number_format($totalValue,0,',','.') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Low stock alert --}}
@if($lowStockCount > 0 && $filter === 'all')
<div class="alert alert-warning d-flex align-items-center gap-2 mb-3" style="border-radius:10px; font-size:13.5px;">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div><strong>Perhatian!</strong> Ada {{ $lowStockCount }} produk dengan stok di bawah minimum.
    <a href="?filter=low" class="alert-link">Lihat produk stok rendah →</a></div>
</div>
@endif

{{-- Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard-data me-2"></i>
            @if($filter==='low') Produk Stok Rendah
            @elseif($filter==='out') Produk Habis
            @else Semua Produk ({{ $products->count() }})
            @endif
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th class="text-end">H. Beli</th>
                        <th class="text-end">H. Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min.</th>
                        <th class="text-end pe-4">Nilai Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @php $currentCat = null; @endphp
                    @forelse($products as $p)
                    @if($currentCat !== $p->category)
                    @php $currentCat = $p->category; @endphp
                    <tr class="table-light">
                        <td colspan="9" class="ps-4 fw-bold text-muted" style="font-size:11px; text-transform:uppercase; letter-spacing:.05em">
                            {{ $p->category }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="ps-4"><code style="font-size:11px">{{ $p->code }}</code></td>
                        <td class="fw-semibold">{{ $p->name }}</td>
                        <td>{{ $p->category }}</td>
                        <td class="text-muted" style="font-size:12px">{{ $p->supplier ?: '-' }}</td>
                        <td class="text-end">Rp{{ number_format($p->cost_price,0,',','.') }}</td>
                        <td class="text-end">Rp{{ number_format($p->selling_price,0,',','.') }}</td>
                        <td class="text-center">
                            @if($p->stock === 0)
                                <span class="badge bg-danger">HABIS</span>
                            @elseif($p->isLowStock())
                                <span class="badge bg-warning text-dark">{{ $p->stock }} ⚠️</span>
                            @else
                                <span class="fw-semibold text-success">{{ $p->stock }}</span>
                            @endif
                        </td>
                        <td class="text-center text-muted">{{ $p->min_stock }}</td>
                        <td class="text-end pe-4 fw-bold">Rp{{ number_format($p->stock * $p->cost_price,0,',','.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-5">Tidak ada produk ditemukan</td></tr>
                    @endforelse
                </tbody>
                @if($products->count() > 0)
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="8" class="ps-4 fw-bold">TOTAL NILAI STOK</td>
                        <td class="text-end pe-4 fw-bold">Rp{{ number_format($totalValue,0,',','.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
