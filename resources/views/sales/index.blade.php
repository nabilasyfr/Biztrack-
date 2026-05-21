@extends('layouts.app')
@section('title','Riwayat Penjualan')
@section('page-title','Riwayat Penjualan')

@section('content')
<div class="page-header">
    <div>
        <h1>Riwayat Penjualan</h1>
        <p>Semua transaksi penjualan</p>
    </div>
    <a href="{{ route('pos.index') }}" class="btn btn-primary">
        <i class="bi bi-upc-scan me-1"></i> Buka Kasir
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Cari Invoice</label>
                <input type="text" name="search" class="form-control" placeholder="Nomor invoice..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">Pembayaran</label>
                <select name="payment" class="form-select">
                    <option value="">Semua</option>
                    <option value="cash" {{ request('payment')==='cash'?'selected':'' }}>Tunai</option>
                    <option value="dana" {{ request('payment')==='dana'?'selected':'' }}>Dana</option>
                    <option value="qris" {{ request('payment')==='qris'?'selected':'' }}>QRIS</option>
                    <option value="transfer" {{ request('payment')==='transfer'?'selected':'' }}>Transfer</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt me-2"></i>Transaksi ({{ $sales->total() }})</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Invoice</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th class="text-center">Item</th>
                        <th class="text-center">Pembayaran</th>
                        <th class="text-end pe-4">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('sales.show',$sale) }}" class="fw-semibold text-primary text-decoration-none">
                                {{ $sale->invoice_number }}
                            </a>
                        </td>
                        <td class="text-muted">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $sale->user->name ?? '-' }}</td>
                        <td class="text-center">{{ $sale->items->sum('quantity') }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $sale->payment_method }}">{{ strtoupper($sale->payment_method) }}</span>
                        </td>
                        <td class="text-end pe-4 fw-bold">Rp{{ number_format($sale->total_amount,0,',','.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('sales.receipt',$sale) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Struk">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">Tidak ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sales->hasPages())
        <div class="d-flex justify-content-center py-3">{{ $sales->links() }}</div>
        @endif
    </div>
</div>
@endsection
