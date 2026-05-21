@extends('layouts.app')
@section('title','Produk')
@section('page-title','Produk & Inventori')

@section('content')
<div class="page-header">
    <div>
        <h1>Produk</h1>
        <p>Kelola semua produk dan stok barang</p>
    </div>
    @if(session('biztrack_role')==='owner')
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </a>
    @endif
</div>

{{-- Search & filter --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label mb-1">Cari Produk</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, kode, atau kategori..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Cari</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-seam me-2"></i>Daftar Produk ({{ $products->total() }})</span>
        <a href="{{ route('inventory.log') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left-right me-1"></i>Log Inventori
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">H. Beli</th>
                        <th class="text-end">H. Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min.</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td class="ps-4"><code style="font-size:12px">{{ $p->code }}</code></td>
                        <td>
                            <a href="{{ route('products.show',$p) }}" class="text-decoration-none fw-semibold">{{ $p->name }}</a>
                            @if($p->supplier)<div class="text-muted" style="font-size:11px">{{ $p->supplier }}</div>@endif
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $p->category }}</span></td>
                        <td class="text-end">Rp{{ number_format($p->cost_price,0,',','.') }}</td>
                        <td class="text-end fw-semibold">Rp{{ number_format($p->selling_price,0,',','.') }}</td>
                        <td class="text-center">
                            @if($p->isLowStock())
                                <span class="badge bg-danger">{{ $p->stock }}</span>
                            @else
                                <span class="fw-semibold">{{ $p->stock }}</span>
                            @endif
                        </td>
                        <td class="text-center text-muted">{{ $p->min_stock }}</td>
                        <td class="text-center pe-4">
                            @if(session('biztrack_role')==='owner')
                            <a href="{{ route('products.edit',$p) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('products.destroy',$p) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus produk {{ $p->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @else
                            <a href="{{ route('products.show',$p) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">Tidak ada produk ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="d-flex justify-content-center py-3">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
