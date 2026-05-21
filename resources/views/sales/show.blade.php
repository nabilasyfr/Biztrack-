@extends('layouts.app')
@section('title','Detail Transaksi')
@section('page-title','Detail Transaksi')

@section('content')
<div class="page-header">
    <div>
        <h1>{{ $sale->invoice_number }}</h1>
        <p>Detail transaksi penjualan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.receipt',$sale) }}" target="_blank" class="btn btn-outline-primary no-print">
            <i class="bi bi-printer me-1"></i> Cetak Struk
        </a>
        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary no-print">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Informasi Transaksi</div>
            <div class="card-body">
                <table class="table table-sm mb-0" style="font-size:13.5px">
                    <tr><td class="text-muted fw-semibold">Invoice</td><td class="fw-bold">{{ $sale->invoice_number }}</td></tr>
                    <tr><td class="text-muted fw-semibold">Tanggal</td><td>{{ $sale->created_at->format('d M Y H:i') }}</td></tr>
                    <tr><td class="text-muted fw-semibold">Kasir</td><td>{{ $sale->user->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted fw-semibold">Pembayaran</td>
                        <td><span class="badge badge-{{ $sale->payment_method }}">{{ strtoupper($sale->payment_method) }}</span></td></tr>
                    <tr><td class="text-muted fw-semibold">Total</td><td class="fw-bold text-primary">Rp{{ number_format($sale->total_amount,0,',','.') }}</td></tr>
                    @if($sale->payment_method === 'cash')
                    <tr><td class="text-muted fw-semibold">Diterima</td><td>Rp{{ number_format($sale->cash_received,0,',','.') }}</td></tr>
                    <tr><td class="text-muted fw-semibold">Kembalian</td><td class="text-success fw-bold">Rp{{ number_format($sale->change_amount,0,',','.') }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Item Pembelian</div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:13.5px">
                    <thead>
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">H. Satuan</th>
                            <th class="text-end pe-4">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $i => $item)
                        <tr>
                            <td class="ps-4 text-muted">{{ $i+1 }}</td>
                            <td class="fw-semibold">{{ $item->product->name ?? 'Produk dihapus' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp{{ number_format($item->unit_price,0,',','.') }}</td>
                            <td class="text-end pe-4 fw-bold">Rp{{ number_format($item->subtotal,0,',','.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light">
                            <td colspan="4" class="text-end pe-4 fw-bold">TOTAL</td>
                            <td class="text-end pe-4 fw-bold text-primary" style="font-size:16px">Rp{{ number_format($sale->total_amount,0,',','.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
