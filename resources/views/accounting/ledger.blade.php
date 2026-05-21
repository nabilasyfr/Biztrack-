@extends('layouts.app')
@section('title','Buku Besar')
@section('page-title','Buku Besar (General Ledger)')

@section('content')
<div class="page-header">
    <div>
        <h1>Buku Besar</h1>
        <p>Rekap transaksi per akun</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Pilih Akun <span class="text-danger">*</span></label>
                <select name="account_id" class="form-select" required>
                    <option value="">-- Pilih Akun --</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('account_id')==$acc->id?'selected':'' }}>
                        {{ $acc->code }} - {{ $acc->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">Dari</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">Sampai</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>

@if($selectedAccount)
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5 class="fw-bold mb-1">{{ $selectedAccount->code }} — {{ $selectedAccount->name }}</h5>
                <span class="badge bg-secondary text-capitalize">{{ $selectedAccount->type }}</span>
            </div>
            <div class="col-md-6 text-md-end">
                @php
                    $totalDebit  = $lines->sum('debit');
                    $totalCredit = $lines->sum('credit');
                    $balance = in_array($selectedAccount->type, ['asset','expense']) 
                               ? $totalDebit - $totalCredit 
                               : $totalCredit - $totalDebit;
                @endphp
                <div class="d-flex gap-4 justify-content-md-end">
                    <div>
                        <div class="text-muted" style="font-size:11px">TOTAL DEBIT</div>
                        <div class="fw-bold text-primary">Rp{{ number_format($totalDebit,0,',','.') }}</div>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:11px">TOTAL KREDIT</div>
                        <div class="fw-bold text-success">Rp{{ number_format($totalCredit,0,',','.') }}</div>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:11px">SALDO</div>
                        <div class="fw-bold" style="font-size:18px">Rp{{ number_format($balance,0,',','.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-book me-2"></i>Transaksi Akun</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Tanggal</th>
                    <th>Referensi</th>
                    <th>Keterangan</th>
                    <th class="text-end">Debit</th>
                    <th class="text-end pe-4">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @php $runBalance = 0; @endphp
                @forelse($lines as $line)
                @php
                    if (in_array($selectedAccount->type, ['asset','expense'])) {
                        $runBalance += $line->debit - $line->credit;
                    } else {
                        $runBalance += $line->credit - $line->debit;
                    }
                @endphp
                <tr>
                    <td class="ps-4 text-muted">{{ \Carbon\Carbon::parse($line->journalEntry->entry_date)->format('d/m/Y') }}</td>
                    <td><code style="font-size:11px">{{ $line->journalEntry->reference }}</code></td>
                    <td style="font-size:12px">{{ $line->description ?: $line->journalEntry->description }}</td>
                    <td class="text-end {{ $line->debit > 0 ? 'fw-bold text-primary' : 'text-muted' }}">
                        {{ $line->debit > 0 ? 'Rp'.number_format($line->debit,0,',','.') : '-' }}
                    </td>
                    <td class="text-end pe-4 {{ $line->credit > 0 ? 'fw-bold text-success' : 'text-muted' }}">
                        {{ $line->credit > 0 ? 'Rp'.number_format($line->credit,0,',','.') : '-' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card">
    <div class="card-body text-center text-muted py-5">
        <i class="bi bi-book" style="font-size:40px; display:block; margin-bottom:10px;"></i>
        Pilih akun untuk melihat buku besar
    </div>
</div>
@endif
@endsection
