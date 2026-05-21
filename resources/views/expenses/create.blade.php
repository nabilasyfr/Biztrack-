@extends('layouts.app')
@section('title','Tambah Pengeluaran')
@section('page-title','Tambah Pengeluaran')

@section('content')
<div class="page-header">
    <div>
        <h1>Tambah Pengeluaran</h1>
        <p>Catat pengeluaran operasional baru</p>
    </div>
    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Form Pengeluaran</div>
            <div class="card-body p-4">
                <div class="alert alert-info" style="font-size:13px; border-radius:10px;">
                    <i class="bi bi-info-circle me-2"></i>
                    Pengeluaran akan otomatis membuat jurnal akuntansi: <strong>Debit Beban Operasional</strong> dan <strong>Kredit Kas</strong>.
                </div>
                <form method="POST" action="{{ route('expenses.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Pengeluaran <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="cth: Bayar Listrik Bulan Ini" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                                   value="{{ old('amount') }}" min="1" step="1" placeholder="Contoh: 20000000" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror"
                               value="{{ old('expense_date', now()->format('Y-m-d')) }}" required>
                        @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Keterangan</label>
                        <textarea name="notes" class="form-control" rows="3"
                                  placeholder="Keterangan tambahan (opsional)">{{ old('notes') }}</textarea>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
