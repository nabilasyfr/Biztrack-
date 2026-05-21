@extends('layouts.app')
@section('title','Detail Produk')
@section('page-title','Detail Produk')

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $product->name }}</h1>
        <p>Detail dan riwayat stok produk</p>
    </div>
    <div class="d-flex gap-2">
        @if(session('biztrack_role')==='owner')
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        @endif
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">KODE</div>
            <code style="font-size:16px; font-weight:700">{{ $product->code }}</code>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">STOK</div>
            <div style="font-size:24px; font-weight:800; color:{{ $product->isLowStock() ? '#dc2626' : '#16a34a' }}">
                {{ $product->stock }}
            </div>
            @if($product->isLowStock())<small class="text-danger">Stok Rendah!</small>@endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">HARGA JUAL</div>
            <div style="font-size:18px; font-weight:800">Rp{{ number_format($product->selling_price,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">MARGIN</div>
            <div style="font-size:18px; font-weight:800; color:#0891b2">
                {{ number_format($product->profit_margin, 1) }}%
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-arrow-left-right me-2"></i>Riwayat Stok (20 terakhir)</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Waktu</th>
                    <th>Tipe</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Sebelum</th>
                    <th class="text-center">Sesudah</th>
                    <th>Referensi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="ps-4 text-muted">{{ $log->created_at->format('d/m/y H:i') }}</td>
                    <td>
                        @if($log->type==='sale')<span class="badge bg-primary">Penjualan</span>
                        @elseif($log->type==='restock')<span class="badge bg-success">Restock</span>
                        @else<span class="badge bg-secondary">Adjustment</span>@endif
                    </td>
                    <td class="text-center">
                        <span class="{{ $log->type==='sale' ? 'text-danger' : 'text-success' }} fw-bold">
                            {{ $log->type==='sale' ? '-' : '+' }}{{ $log->quantity }}
                        </span>
                    </td>
                    <td class="text-center">{{ $log->stock_before }}</td>
                    <td class="text-center fw-semibold">{{ $log->stock_after }}</td>
                    <td><code style="font-size:11px">{{ $log->reference }}</code></td>
                    <td class="text-muted" style="font-size:12px">{{ $log->notes }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
