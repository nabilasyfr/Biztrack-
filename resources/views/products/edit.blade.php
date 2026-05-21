@extends('layouts.app')
@section('title','Edit Produk')
@section('page-title','Edit Produk')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Produk</h1>
        <p>Perbarui informasi produk: <strong>{{ $product->name }}</strong></p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Produk</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Produk <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $product->code) }}" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $product->category) }}"
                                   list="cat-list" required>
                            <datalist id="cat-list">
                                @foreach($categories as $c)<option value="{{ $c }}">@endforeach
                            </datalist>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Supplier</label>
                            <input type="text" name="supplier" class="form-control" value="{{ old('supplier', $product->supplier) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Beli (HPP)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="cost_price" class="form-control"
                                       value="{{ old('cost_price', $product->cost_price) }}" min="0" step="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="selling_price" class="form-control"
                                       value="{{ old('selling_price', $product->selling_price) }}" min="0" step="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok Saat Ini</label>
                            <input type="number" name="stock" class="form-control"
                                   value="{{ old('stock', $product->stock) }}" min="0">
                            <div class="form-text">Mengubah stok akan membuat log penyesuaian otomatis</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok Minimum</label>
                            <input type="number" name="min_stock" class="form-control"
                                   value="{{ old('min_stock', $product->min_stock) }}" min="0">
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Perbarui Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
