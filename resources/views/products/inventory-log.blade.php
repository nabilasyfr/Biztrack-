@extends('layouts.app')
@section('title','Log Inventori')
@section('page-title','Log Pergerakan Inventori')

@section('content')
<div class="page-header">
    <div>
        <h1>Log Inventori</h1>
        <p>Semua pergerakan stok barang</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Filter Tipe</label>
                <select name="type" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="sale" {{ request('type')==='sale'?'selected':'' }}>Penjualan</option>
                    <option value="restock" {{ request('type')==='restock'?'selected':'' }}>Restock</option>
                    <option value="adjustment" {{ request('type')==='adjustment'?'selected':'' }}>Adjustment</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-filter me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-arrow-left-right me-2"></i>Log Inventori</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>Produk</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Sebelum</th>
                        <th class="text-center">Sesudah</th>
                        <th>Referensi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4 text-muted" style="font-size:12px">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-semibold">{{ $log->product->name ?? '-' }}</td>
                        <td class="text-center">
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
                        <td class="text-center fw-bold">{{ $log->stock_after }}</td>
                        <td><code style="font-size:11px">{{ $log->reference }}</code></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">Tidak ada log inventori</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="d-flex justify-content-center py-3">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection
