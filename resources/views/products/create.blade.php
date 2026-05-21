@extends('layouts.app')
@section('title','Tambah Produk')
@section('page-title','Tambah Produk Baru')

@section('content')
<div class="page-header">
    <div>
        <h1>Tambah Produk</h1>
        <p>Isi semua informasi produk baru</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i>Informasi Produk</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('products.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Produk <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}" placeholder="cth: MNM001" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Nama lengkap produk" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
                                   value="{{ old('category') }}" list="cat-list" placeholder="cth: Minuman" required>
                            <datalist id="cat-list">
                                @foreach($categories as $c)<option value="{{ $c }}">@endforeach
                            </datalist>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Supplier</label>
                            <input type="text" name="supplier" class="form-control"
                                   value="{{ old('supplier') }}" placeholder="Nama supplier (opsional)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Beli (HPP) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror"
                                       value="{{ old('cost_price', 0) }}" min="0" step="100" required>
                                @error('cost_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror"
                                       value="{{ old('selling_price', 0) }}" min="0" step="100" required>
                                @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror"
                                   value="{{ old('min_stock', 5) }}" min="0" required>
                            <div class="form-text">Notifikasi tampil jika stok ≤ nilai ini</div>
                            @error('min_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
