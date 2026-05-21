@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
.stat-icon.blue   { background: #eff6ff; color: #1a56db; }
.stat-icon.green  { background: #f0fdf4; color: #16a34a; }
.stat-icon.orange { background: #fff7ed; color: #ea580c; }
.stat-icon.red    { background: #fef2f2; color: #dc2626; }
.stat-icon.purple { background: #f5f3ff; color: #7c3aed; }
.stat-icon.cyan   { background: #ecfeff; color: #0891b2; }
.top-product-item { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
.top-product-item:last-child { border-bottom:none; }
.top-rank { width:24px; height:24px; border-radius:6px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#64748b; flex-shrink:0; }
.top-rank.gold { background:#fef3c7; color:#d97706; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ session('biztrack_name') }}! Ini ringkasan bisnis Anda.</p>
    </div>
    <a href="{{ route('pos.index') }}" class="btn btn-primary">
        <i class="bi bi-upc-scan me-1"></i> Buka Kasir
    </a>
</div>

{{-- Stat cards row 1 --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-label">Penjualan Hari Ini</div>
                <div class="stat-value">Rp{{ number_format($salesToday,0,',','.') }}</div>
                <div class="stat-sub">{{ $transactionsToday }} transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-graph-up"></i></div>
            <div>
                <div class="stat-label">Omset Bulan Ini</div>
                <div class="stat-value">Rp{{ number_format($monthlyRevenue,0,',','.') }}</div>
                <div class="stat-sub">{{ now()->format('F Y') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon {{ $monthlyProfit >= 0 ? 'cyan' : 'red' }}">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <div class="stat-label">Laba Bersih Bulan Ini</div>
                <div class="stat-value" style="color:{{ $monthlyProfit >= 0 ? '#0891b2' : '#dc2626' }}">
                    Rp{{ number_format(abs($monthlyProfit),0,',','.') }}
                </div>
                <div class="stat-sub">Setelah HPP + pengeluaran</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon {{ $lowStockProducts->count() > 0 ? 'red' : 'green' }}">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <div class="stat-label">Stok Rendah</div>
                <div class="stat-value" style="color:{{ $lowStockProducts->count() > 0 ? '#dc2626' : '#16a34a' }}">
                    {{ $lowStockProducts->count() }}
                </div>
                <div class="stat-sub">dari {{ $totalProducts }} produk</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Transactions --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Transaksi Terbaru</span>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No. Invoice</th>
                                <th>Waktu</th>
                                <th>Item</th>
                                <th>Pembayaran</th>
                                <th class="text-end pe-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('sales.show', $sale) }}" class="text-decoration-none fw-semibold text-primary">
                                        {{ $sale->invoice_number }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $sale->created_at->format('d/m H:i') }}</td>
                                <td>{{ $sale->items->sum('quantity') }} item</td>
                                <td>
                                    @php $pm = $sale->payment_method; @endphp
                                    <span class="badge badge-{{ $pm }}">{{ strtoupper($pm) }}</span>
                                </td>
                                <td class="text-end pe-4 fw-bold">Rp{{ number_format($sale->total_amount,0,',','.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div class="col-lg-4">
        {{-- Top Products --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-trophy me-2"></i>Produk Terlaris</div>
            <div class="card-body py-2">
                @forelse($topProducts as $i => $tp)
                <div class="top-product-item">
                    <div class="top-rank {{ $i === 0 ? 'gold' : '' }}">{{ $i+1 }}</div>
                    <div class="flex-fill overflow-hidden">
                        <div class="fw-semibold text-truncate" style="font-size:13px">{{ $tp->product->name ?? '-' }}</div>
                        <div class="text-muted" style="font-size:11px">{{ $tp->total_qty }} terjual</div>
                    </div>
                    <div class="fw-bold text-primary" style="font-size:12px">
                        Rp{{ number_format($tp->total_revenue,0,',','.') }}
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3 mb-0" style="font-size:13px">Belum ada data</p>
                @endforelse
            </div>
        </div>

        {{-- Low Stock Alert --}}
        @if($lowStockProducts->count() > 0)
        <div class="card border-danger" style="border: 1.5px solid #fca5a5 !important;">
            <div class="card-header text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Peringatan Stok Rendah</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($lowStockProducts->take(5) as $p)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3" style="font-size:13px">
                        <span class="text-truncate me-2">{{ $p->name }}</span>
                        <span class="badge bg-danger">{{ $p->stock }} tersisa</span>
                    </li>
                    @endforeach
                </ul>
                @if($lowStockProducts->count() > 5)
                <div class="text-center py-2">
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-danger">
                        +{{ $lowStockProducts->count() - 5 }} lainnya
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
