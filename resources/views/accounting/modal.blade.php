@extends('layouts.app')
@section('title','Input Modal & Jurnal Manual')
@section('page-title','Input Modal & Jurnal Manual')

@section('content')
<div class="page-header">
    <div>
        <h1>Input Modal & Jurnal Manual</h1>
        <p>Catat modal awal, penyesuaian, atau transaksi akuntansi yang perlu diinput manual</p>
    </div>
    <a href="{{ route('accounting.journal') }}" class="btn btn-outline-secondary">
        <i class="bi bi-journal-text me-1"></i> Lihat Jurnal
    </a>
</div>

{{-- Saldo Modal Saat Ini --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-bank"></i></div>
            <div>
                <div class="stat-label">Total Modal Pemilik (3100)</div>
                <div class="stat-value" style="font-size:18px; color:#16a34a">
                    Rp{{ number_format($totalModal, 0, ',', '.') }}
                </div>
                <div class="stat-sub">Saldo akun Modal Pemilik</div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="alert alert-info mb-0" style="border-radius:12px; font-size:13.5px; height:100%; display:flex; align-items:center; gap:12px;">
            <i class="bi bi-lightbulb-fill fs-4"></i>
            <div>
                <strong>Cara Input Modal Awal:</strong><br>
                Pilih <strong>Debit → Kas (1100)</strong> dan <strong>Kredit → Modal Pemilik (3100)</strong>.<br>
                Ini mencerminkan bahwa uang masuk ke kas berasal dari modal pemilik toko.
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- FORM JURNAL MANUAL --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Form Jurnal Manual</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('accounting.modal.store') }}" id="journalForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Keterangan / Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" name="description"
                               class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description') }}"
                               placeholder="Contoh: Modal Awal Toko, Penyesuaian Kas, Setoran Dana..."
                               required>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="entry_date"
                               class="form-control @error('entry_date') is-invalid @enderror"
                               value="{{ old('entry_date', now()->format('Y-m-d')) }}" required>
                        @error('entry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" id="amountInput"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   value="{{ old('amount') }}"
                                   min="1" step="1"
                                   placeholder="Contoh: 20000000"
                                   oninput="updatePreview()" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div id="amountFormatted" class="form-text text-primary fw-semibold"></div>
                    </div>

                    {{-- Debit & Kredit --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="badge bg-primary me-1">DEBIT</span>
                                Akun yang Bertambah <span class="text-danger">*</span>
                            </label>
                            <select name="debit_account"
                                    class="form-select @error('debit_account') is-invalid @enderror"
                                    required onchange="updatePreview()">
                                <option value="">-- Pilih Akun Debit --</option>
                                @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}"
                                    {{ old('debit_account') == $acc->id ? 'selected' : '' }}>
                                    {{ $acc->code }} - {{ $acc->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('debit_account')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <span class="badge bg-success me-1">KREDIT</span>
                                Akun Sumber Dana <span class="text-danger">*</span>
                            </label>
                            <select name="credit_account"
                                    class="form-select @error('credit_account') is-invalid @enderror"
                                    required onchange="updatePreview()">
                                <option value="">-- Pilih Akun Kredit --</option>
                                @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}"
                                    {{ old('credit_account') == $acc->id ? 'selected' : '' }}>
                                    {{ $acc->code }} - {{ $acc->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('credit_account')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Preview Jurnal --}}
                    <div id="journalPreview" class="p-3 mb-3" style="background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0; display:none;">
                        <div class="fw-bold mb-2" style="font-size:13px; color:#374151">
                            <i class="bi bi-eye me-1"></i> Preview Jurnal:
                        </div>
                        <table class="table table-sm mb-0" style="font-size:12px;">
                            <thead>
                                <tr>
                                    <th>Akun</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="prev_debit_name" class="text-primary fw-semibold">-</td>
                                    <td class="text-end text-primary fw-bold" id="prev_debit_amt">-</td>
                                    <td class="text-end text-muted">-</td>
                                </tr>
                                <tr>
                                    <td id="prev_credit_name" class="text-success fw-semibold ps-3">-</td>
                                    <td class="text-end text-muted">-</td>
                                    <td class="text-end text-success fw-bold" id="prev_credit_amt">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @error('error')
                    <div class="alert alert-danger" style="border-radius:8px; font-size:13px">{{ $message }}</div>
                    @enderror

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('accounting.coa') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Jurnal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- PANDUAN CEPAT --}}
    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-book me-2"></i>Panduan: Kombinasi Umum</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    {{-- Modal Awal --}}
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Modal Awal Toko', 1100, 3100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            💰 Modal Awal / Setoran Modal
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            <span class="badge bg-primary">Debit</span> Kas (1100)
                            &nbsp;←&nbsp;
                            <span class="badge bg-success">Kredit</span> Modal Pemilik (3100)
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">
                            Klik untuk isi otomatis →
                        </div>
                    </div>

                    {{-- Setoran ke Dana --}}
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Setoran Modal ke Dana Wallet', 1110, 3100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            💙 Modal Masuk ke Dana Wallet
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            <span class="badge bg-primary">Debit</span> Dana Wallet (1110)
                            &nbsp;←&nbsp;
                            <span class="badge bg-success">Kredit</span> Modal Pemilik (3100)
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">Klik untuk isi otomatis →</div>
                    </div>

                    {{-- Setoran ke Bank --}}
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Setoran Modal ke Rekening Bank', 1120, 3100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            🏦 Modal Masuk ke Rekening Bank
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            <span class="badge bg-primary">Debit</span> Rekening Bank (1120)
                            &nbsp;←&nbsp;
                            <span class="badge bg-success">Kredit</span> Modal Pemilik (3100)
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">Klik untuk isi otomatis →</div>
                    </div>

                    {{-- Beli stok awal --}}
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Pembelian Stok Barang Awal', 1200, 1100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            📦 Pembelian Stok Barang (Tunai)
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            <span class="badge bg-primary">Debit</span> Persediaan (1200)
                            &nbsp;←&nbsp;
                            <span class="badge bg-success">Kredit</span> Kas (1100)
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">Klik untuk isi otomatis →</div>
                    </div>

                    {{-- Penyesuaian kas --}}
                    <div class="list-group-item px-3 py-3" style="cursor:pointer"
                         onclick="fillTemplate('Penyesuaian Saldo Kas', 1100, 3100)">
                        <div class="fw-bold mb-1" style="font-size:13px">
                            🔧 Koreksi / Penyesuaian Saldo
                        </div>
                        <div style="font-size:12px; color:#64748b">
                            Bebas pilih akun debit & kredit sesuai kebutuhan
                        </div>
                        <div class="text-muted mt-1" style="font-size:11px">Klik untuk isi otomatis →</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning" style="border-radius:12px; font-size:13px">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Perhatian:</strong> Jurnal manual langsung mempengaruhi saldo Buku Besar.
            Pastikan akun Debit dan Kredit sudah benar sebelum menyimpan.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data akun untuk preview
var accountNames = {
    @foreach($accounts as $acc)
    {{ $acc->id }}: '{{ $acc->code }} - {{ addslashes($acc->name) }}',
    @endforeach
};

function fmtRp(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}

// Update preview jurnal secara real-time
function updatePreview() {
    var amount  = parseFloat(document.getElementById('amountInput').value) || 0;
    var debitId = document.querySelector('[name=debit_account]').value;
    var credId  = document.querySelector('[name=credit_account]').value;
    var preview = document.getElementById('journalPreview');

    // Format jumlah terbilang
    var fmtDiv = document.getElementById('amountFormatted');
    if (amount > 0) {
        fmtDiv.textContent = '= ' + fmtRp(amount);
    } else {
        fmtDiv.textContent = '';
    }

    if (amount > 0 && debitId && credId) {
        preview.style.display = 'block';
        document.getElementById('prev_debit_name').textContent  = accountNames[debitId] || '-';
        document.getElementById('prev_credit_name').textContent = '    ' + (accountNames[credId] || '-');
        document.getElementById('prev_debit_amt').textContent   = fmtRp(amount);
        document.getElementById('prev_credit_amt').textContent  = fmtRp(amount);
    } else {
        preview.style.display = 'none';
    }
}

// Isi form otomatis dari template panduan
function fillTemplate(desc, debitCode, creditCode) {
    // Set deskripsi
    document.querySelector('[name=description]').value = desc;

    // Cari ID akun berdasarkan kode
    var debitSel  = document.querySelector('[name=debit_account]');
    var creditSel = document.querySelector('[name=credit_account]');

    // Map kode akun ke account_id
    var codeToId = {
        @foreach($accounts as $acc)
        '{{ $acc->code }}': {{ $acc->id }},
        @endforeach
    };

    var debitId  = codeToId[String(debitCode).padStart(4,'0')] || codeToId[debitCode];
    var creditId = codeToId[String(creditCode).padStart(4,'0')] || codeToId[creditCode];

    if (debitId)  debitSel.value  = debitId;
    if (creditId) creditSel.value = creditId;

    // Focus ke field jumlah
    document.getElementById('amountInput').focus();
    updatePreview();

    // Scroll ke form
    document.getElementById('journalForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Jalankan preview saat load jika ada old value
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endpush
