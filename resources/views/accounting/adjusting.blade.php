@extends('layouts.app')
@section('title','Jurnal Penyesuaian')
@section('page-title','Jurnal Penyesuaian (Adjusting Entries)')

@section('content')
<div class="page-header">
    <div>
        <h1>Jurnal Penyesuaian</h1>
        <p>Koreksi akun akhir periode — beban akrual, prabayar, depresiasi, dll</p>
    </div>
</div>

<div class="row g-3">
    {{-- FORM --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Tambah Jurnal Penyesuaian</div>
            <div class="card-body p-4">

                <div class="alert alert-info" style="font-size:13px;border-radius:10px">
                    <i class="bi bi-info-circle me-2"></i>
                    Jurnal penyesuaian dicatat dengan prefix <strong>ADJ-</strong> dan digunakan
                    dalam <strong>Neraca Saldo Disesuaikan</strong> serta <strong>Kertas Kerja</strong>.
                </div>

                <form method="POST" action="{{ route('accounting.adjusting.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tipe Penyesuaian <span class="text-danger">*</span></label>
                        <select name="adj_type" class="form-select @error('adj_type') is-invalid @enderror"
                                onchange="setAdjTemplate(this.value)" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="prepaid"     {{ old('adj_type')=='prepaid'     ?'selected':'' }}>Beban Dibayar di Muka (Prepaid)</option>
                            <option value="accrued"     {{ old('adj_type')=='accrued'     ?'selected':'' }}>Beban Akrual / Terutang (Accrued)</option>
                            <option value="depreciation"{{ old('adj_type')=='depreciation'?'selected':'' }}>Penyusutan Aset (Depreciation)</option>
                            <option value="inventory"   {{ old('adj_type')=='inventory'   ?'selected':'' }}>Penyesuaian Persediaan (Inventory)</option>
                            <option value="other"       {{ old('adj_type')=='other'       ?'selected':'' }}>Lainnya</option>
                        </select>
                        @error('adj_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="adjHint" class="form-text"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" name="description"
                               class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description') }}"
                               placeholder="Contoh: Penyesuaian beban listrik akrual bulan Mei"
                               required>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Penyesuaian <span class="text-danger">*</span></label>
                        <input type="date" name="entry_date"
                               class="form-control @error('entry_date') is-invalid @enderror"
                               value="{{ old('entry_date', now()->format('Y-m-d')) }}" required>
                        @error('entry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{ old('amount') }}" min="1" step="1"
                                   placeholder="0" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label"><span class="badge bg-primary">DEBIT</span> Akun</label>
                            <select name="debit_account" id="debitSel"
                                    class="form-select @error('debit_account') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ old('debit_account')==$acc->id?'selected':'' }}>
                                    {{ $acc->code }} - {{ $acc->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('debit_account')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label"><span class="badge bg-success">KREDIT</span> Akun</label>
                            <select name="credit_account" id="creditSel"
                                    class="form-select @error('credit_account') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ old('credit_account')==$acc->id?'selected':'' }}>
                                    {{ $acc->code }} - {{ $acc->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('credit_account')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    @error('error')
                        <div class="alert alert-danger py-2" style="font-size:13px">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Simpan Jurnal Penyesuaian
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- DAFTAR JURNAL PENYESUAIAN --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-journal-check me-2"></i>Daftar Jurnal Penyesuaian ({{ $entries->total() }})</div>
            <div class="card-body p-0">
                @forelse($entries as $entry)
                <div class="border-bottom p-3 px-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold text-warning"><i class="bi bi-wrench me-1"></i>{{ $entry->reference }}</span>
                        <span class="text-muted" style="font-size:12px">{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}</span>
                    </div>
                    <p class="text-muted mb-2" style="font-size:12px">{{ $entry->description }}</p>
                    <table class="table table-sm mb-0" style="font-size:12px">
                        <tbody>
                            @foreach($entry->lines as $line)
                            <tr>
                                <td style="width:30px" class="ps-2">
                                    @if($line->debit > 0)<span class="badge bg-primary">Dr</span>
                                    @else<span class="badge bg-success">Cr</span>@endif
                                </td>
                                <td>{{ $line->account->code ?? '-' }} - {{ $line->account->name ?? '-' }}</td>
                                <td class="text-end {{ $line->debit>0?'text-primary':'text-success' }} fw-bold">
                                    Rp{{ number_format($line->debit > 0 ? $line->debit : $line->credit, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-journal-x" style="font-size:32px;display:block;margin-bottom:8px"></i>
                    Belum ada jurnal penyesuaian
                </div>
                @endforelse
                @if($entries->hasPages())
                <div class="d-flex justify-content-center py-3">{{ $entries->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var adjHints = {
    prepaid:      'Debit: Beban Operasional | Kredit: Aset (biaya dibayar dimuka diakui sebagai beban)',
    accrued:      'Debit: Beban Operasional | Kredit: Utang Usaha (beban sudah terjadi tapi belum dibayar)',
    depreciation: 'Debit: Beban Operasional | Kredit: Aset (penyusutan nilai aset tetap)',
    inventory:    'Debit: Beban Operasional | Kredit: Persediaan Barang (selisih stok fisik vs buku)',
    other:        'Pilih akun Debit & Kredit sesuai kebutuhan penyesuaian',
};
function setAdjTemplate(type) {
    document.getElementById('adjHint').textContent = adjHints[type] || '';
}
</script>
@endpush
