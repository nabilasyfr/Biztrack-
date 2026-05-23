@extends('layouts.app')
@section('title','Bagan Akun')
@section('page-title','Bagan Akun (Chart of Accounts)')

@section('content')
<div class="page-header">
    <div>
        <h1>Bagan Akun (CoA)</h1>
        <p>Daftar semua akun dalam sistem akuntansi</p>
    </div>
</div>

@php
$typeLabels = [
    'asset'     => ['Aset', 'primary', 'bi-bank'],
    'liability' => ['Kewajiban', 'warning', 'bi-exclamation-circle'],
    'equity'    => ['Modal/Ekuitas', 'success', 'bi-person-circle'],
    'revenue'   => ['Pendapatan', 'info', 'bi-graph-up'],
    'expense'   => ['Beban', 'danger', 'bi-wallet2'],
];
@endphp

<div class="row g-3">
    @foreach($accounts as $type => $group)
    @php [$label, $color, $icon] = $typeLabels[$type] ?? [$type, 'secondary', 'bi-circle']; @endphp
    <div class="col-12">
        <div class="card">
            <div class="card-header text-{{ $color }}">
                <i class="bi {{ $icon }} me-2"></i>{{ $label }}
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width:120px">Kode</th>
                            <th>Nama Akun</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group as $acc)
                        <tr>
                            <td class="ps-4"><code class="fw-bold">{{ $acc->code }}</code></td>
                            <td class="fw-semibold">{{ $acc->name }}</td>
                            <td class="text-muted" style="font-size:12px">{{ $acc->description }}</td>
                            <
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
