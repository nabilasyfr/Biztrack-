<?php $__env->startSection('title','Neraca Saldo'); ?>
<?php $__env->startSection('page-title','Neraca Saldo (Trial Balance)'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Report Variables ─────────────────────────────────────── */
:root {
    --rpt-primary: #2563EB;
    --rpt-success: #16A34A;
    --rpt-warning: #F59E0B;
    --rpt-danger:  #DC2626;
    --rpt-bg:      #F8FAFC;
    --rpt-border:  #E2E8F0;
}

/* ── KPI Cards ─────────────────────────────────────────────── */
.kpi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
@media(max-width:768px){ .kpi-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width:480px){ .kpi-grid { grid-template-columns: 1fr; } }

.kpi-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px 20px 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
    border: 1px solid var(--rpt-border);
    position: relative;
    overflow: hidden;
    transition: box-shadow .2s;
}
.kpi-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.1); }
.kpi-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: var(--kpi-accent, var(--rpt-primary));
    border-radius: 14px 14px 0 0;
}
.kpi-label  { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94A3B8; margin-bottom: 6px; }
.kpi-value  { font-size: 20px; font-weight: 800; color: #0F172A; letter-spacing: -.4px; font-variant-numeric: tabular-nums; }
.kpi-badge  { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 20px; margin-top: 8px; }
.kpi-badge.ok   { background: #DCFCE7; color: #15803D; }
.kpi-badge.fail { background: #FEE2E2; color: #DC2626; }
.kpi-badge.info { background: #EFF6FF; color: #1D4ED8; }
.kpi-icon {
    position: absolute; right: 16px; top: 16px;
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    background: var(--kpi-icon-bg, #EFF6FF);
    color: var(--kpi-accent, var(--rpt-primary));
    font-size: 16px;
}

/* ── Section divider ───────────────────────────────────────── */
.section-heading {
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; font-weight: 700; color: #475569;
    text-transform: uppercase; letter-spacing: .07em;
    padding: 0 0 10px;
    margin: 0 0 4px;
    border-bottom: 2px solid var(--rpt-border);
}
.section-heading .dot {
    width: 10px; height: 10px; border-radius: 50%;
    background: var(--kpi-accent, var(--rpt-primary));
    flex-shrink: 0;
}

/* ── Premium table ─────────────────────────────────────────── */
.rpt-table-wrap {
    background: #fff;
    border-radius: 14px;
    border: 1px solid var(--rpt-border);
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.rpt-table-wrap .table-responsive { max-height: 520px; overflow-y: auto; }

.rpt-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.rpt-table thead { position: sticky; top: 0; z-index: 2; }

.rpt-table thead tr.group-head th {
    background: #0F172A;
    color: #CBD5E1;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    padding: 10px 16px;
}
.rpt-table thead tr.col-head th {
    background: #1E293B;
    color: #94A3B8;
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    padding: 8px 16px;
    border-bottom: 1px solid #334155;
}

.rpt-table tbody tr { border-bottom: 1px solid #F1F5F9; transition: background .15s; }
.rpt-table tbody tr:hover { background: #F8FAFC; }
.rpt-table tbody tr:nth-child(even) { background: #FAFBFC; }
.rpt-table tbody tr:nth-child(even):hover { background: #F1F5F9; }

.rpt-table td { padding: 9px 16px; vertical-align: middle; color: #334155; }
.rpt-table td.td-code { font-family: 'SF Mono', 'Consolas', monospace; font-size: 11px; color: #64748B; font-weight: 500; }
.rpt-table td.td-name { font-weight: 500; color: #1E293B; }
.rpt-table td.num { text-align: right; font-variant-numeric: tabular-nums; }
.rpt-table td.num.dr { color: #1D4ED8; font-weight: 600; }
.rpt-table td.num.cr { color: #15803D; font-weight: 600; }
.rpt-table td.num.zero { color: #CBD5E1; }

/* Group separator row */
.rpt-table tr.group-sep td {
    background: #F8FAFC;
    padding: 5px 16px 4px;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #94A3B8;
    border-bottom: 1px solid #E2E8F0;
    border-top: 1px solid #E2E8F0;
}
.rpt-table tr.group-sep .type-pill {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--pill-bg, #EFF6FF);
    color: var(--pill-color, #1D4ED8);
    padding: 2px 10px; border-radius: 20px;
}

/* Footer */
.rpt-table tfoot td {
    background: #0F172A; color: #F1F5F9;
    font-weight: 800; font-size: 13px;
    padding: 12px 16px; border-top: 2px solid #334155;
}
.rpt-table tfoot td.num { color: #A5F3FC; }
.rpt-table tfoot td.num.balanced { color: #86EFAC; }
.rpt-table tfoot td.num.unbalanced { color: #FCA5A5; }

/* ── Type badge ────────────────────────────────────────────── */
.type-badge {
    display: inline-block; font-size: 10px; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; text-transform: uppercase; letter-spacing: .04em;
}
.type-asset     { background: #EFF6FF; color: #1D4ED8; }
.type-liability { background: #FFF7ED; color: #C2410C; }
.type-equity    { background: #F5F3FF; color: #6D28D9; }
.type-revenue   { background: #F0FDF4; color: #15803D; }
.type-expense   { background: #FEF2F2; color: #B91C1C; }

/* ── Toolbar ───────────────────────────────────────────────── */
.report-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px;
    background: #fff; border: 1px solid var(--rpt-border);
    border-radius: 12px; padding: 14px 18px;
    margin-bottom: 16px;
}
.toolbar-left  { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.toolbar-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

.search-box {
    position: relative;
}
.search-box input {
    padding: 8px 12px 8px 36px;
    border: 1px solid var(--rpt-border);
    border-radius: 8px;
    font-size: 13px;
    outline: none;
    width: 220px;
    transition: border-color .2s, box-shadow .2s;
}
.search-box input:focus { border-color: var(--rpt-primary); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.search-box .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 14px; pointer-events: none; }

.btn-rpt {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12.5px; font-weight: 600; padding: 8px 14px;
    border-radius: 8px; cursor: pointer; transition: all .15s;
    border: 1px solid transparent; text-decoration: none;
}
.btn-rpt-outline { background: #fff; border-color: var(--rpt-border); color: #475569; }
.btn-rpt-outline:hover { background: #F8FAFC; border-color: #CBD5E1; }
.btn-rpt-primary { background: var(--rpt-primary); color: #fff; }
.btn-rpt-primary:hover { background: #1D4ED8; }
.btn-rpt-success { background: var(--rpt-success); color: #fff; }
.btn-rpt-success:hover { background: #15803D; }

/* ── Alert balance ─────────────────────────────────────────── */
.balance-alert {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: 12px; margin-bottom: 16px;
    font-size: 13.5px; font-weight: 500;
}
.balance-alert.ok   { background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; }
.balance-alert.fail { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }
.balance-alert .alert-icon { font-size: 20px; }

/* ── Print styles ──────────────────────────────────────────── */
@media print {
    .no-print { display: none !important; }
    #page-content { padding: 0 !important; }
    .rpt-table-wrap { box-shadow: none !important; border: 1px solid #ddd !important; }
    .rpt-table thead { position: static; }
    .rpt-table-wrap .table-responsive { max-height: none; overflow: visible; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header no-print">
    <div>
        <h1>Neraca Saldo</h1>
        <p>Ringkasan saldo semua akun sebelum &amp; setelah penyesuaian</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button onclick="exportExcel()" class="btn-rpt btn-rpt-success no-print">
            <i class="bi bi-file-earmark-excel"></i> Excel
        </button>
        <button onclick="window.print()" class="btn-rpt btn-rpt-outline no-print">
            <i class="bi bi-printer"></i> Cetak
        </button>
    </div>
</div>


<div class="text-center mb-4 d-none d-print-block">
    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:#94A3B8;margin-bottom:4px">BizTrack ERP — Accounting Report</div>
    <div style="font-size:22px;font-weight:800;color:#0F172A">NERACA SALDO (TRIAL BALANCE)</div>
    <div style="font-size:13px;color:#475569;margin-top:4px">Periode: <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?></div>
    <div style="font-size:11px;color:#94A3B8;margin-top:2px">Dicetak: <?php echo e(now()->format('d M Y H:i')); ?></div>
    <hr style="border-color:#E2E8F0;margin:12px 0 0">
</div>


<div class="card mb-3 no-print" style="border-radius:12px;border:1px solid var(--rpt-border)">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label mb-1 fw-semibold" style="font-size:12px">Periode</label>
                <input type="month" name="month" class="form-control form-control-sm" value="<?php echo e($month); ?>" style="border-radius:8px">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-sm px-4" style="border-radius:8px">
                    <i class="bi bi-search me-1"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$totalDr    = $balances->sum('debit');
$totalCr    = $balances->sum('credit');
$totalAdjDr = $adjustedBalances->sum('debit');
$totalAdjCr = $adjustedBalances->sum('credit');
$diff       = abs($totalDr - $totalCr);
$adjDiff    = abs($totalAdjDr - $totalAdjCr);
$isBalanced    = $diff < 1;
$isAdjBalanced = $adjDiff < 1;
?>


<div class="balance-alert <?php echo e($isAdjBalanced ? 'ok' : 'fail'); ?> no-print">
    <span class="alert-icon"><?php echo e($isAdjBalanced ? '✅' : '⚠️'); ?></span>
    <div>
        <strong><?php echo e($isAdjBalanced ? 'Neraca Seimbang' : 'Neraca Tidak Seimbang'); ?></strong>
        &mdash; Periode <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?>

        <?php if(!$isAdjBalanced): ?>
            &nbsp;· Selisih: <strong>Rp<?php echo e(number_format($adjDiff, 0, ',', '.')); ?></strong>
        <?php endif; ?>
    </div>
</div>


<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-accent:#2563EB;--kpi-icon-bg:#EFF6FF">
        <div class="kpi-icon"><i class="bi bi-table"></i></div>
        <div class="kpi-label">Total Debit (Sblm. Penyesuaian)</div>
        <div class="kpi-value">Rp<?php echo e(number_format($totalDr,0,',','.')); ?></div>
        <span class="kpi-badge info"><i class="bi bi-arrow-right-short"></i>Before Adj.</span>
    </div>
    <div class="kpi-card" style="--kpi-accent:#16A34A;--kpi-icon-bg:#F0FDF4">
        <div class="kpi-icon"><i class="bi bi-check2-circle"></i></div>
        <div class="kpi-label">Total Kredit (Sblm. Penyesuaian)</div>
        <div class="kpi-value">Rp<?php echo e(number_format($totalCr,0,',','.')); ?></div>
        <span class="kpi-badge <?php echo e($isBalanced ? 'ok' : 'fail'); ?>">
            <i class="bi bi-<?php echo e($isBalanced ? 'check' : 'x'); ?>-circle-fill"></i>
            <?php echo e($isBalanced ? 'Seimbang' : 'Selisih: Rp'.number_format($diff,0,',','.')); ?>

        </span>
    </div>
    <div class="kpi-card" style="--kpi-accent:#7C3AED;--kpi-icon-bg:#F5F3FF">
        <div class="kpi-icon"><i class="bi bi-sliders"></i></div>
        <div class="kpi-label">Total Debit (Stlh. Penyesuaian)</div>
        <div class="kpi-value">Rp<?php echo e(number_format($totalAdjDr,0,',','.')); ?></div>
        <span class="kpi-badge info"><i class="bi bi-arrow-right-short"></i>After Adj.</span>
    </div>
    <div class="kpi-card" style="--kpi-accent:#0891B2;--kpi-icon-bg:#ECFEFF">
        <div class="kpi-icon"><i class="bi bi-clipboard-check"></i></div>
        <div class="kpi-label">Total Kredit (Stlh. Penyesuaian)</div>
        <div class="kpi-value">Rp<?php echo e(number_format($totalAdjCr,0,',','.')); ?></div>
        <span class="kpi-badge <?php echo e($isAdjBalanced ? 'ok' : 'fail'); ?>">
            <i class="bi bi-<?php echo e($isAdjBalanced ? 'check' : 'x'); ?>-circle-fill"></i>
            <?php echo e($isAdjBalanced ? 'Seimbang' : 'Tidak Seimbang'); ?>

        </span>
    </div>
    <div class="kpi-card" style="--kpi-accent:<?php echo e($isBalanced ? '#16A34A' : '#DC2626'); ?>;--kpi-icon-bg:<?php echo e($isBalanced ? '#F0FDF4' : '#FEF2F2'); ?>">
        <div class="kpi-icon"><i class="bi bi-<?php echo e($isBalanced ? 'shield-check' : 'exclamation-diamond'); ?>"></i></div>
        <div class="kpi-label">Selisih (Sblm. Penyesuaian)</div>
        <div class="kpi-value" style="color:<?php echo e($isBalanced ? '#16A34A' : '#DC2626'); ?>">
            <?php echo e($isBalanced ? 'Rp0' : 'Rp'.number_format($diff,0,',','.')); ?>

        </div>
        <span class="kpi-badge <?php echo e($isBalanced ? 'ok' : 'fail'); ?>">
            <?php echo e($isBalanced ? 'Balance ✓' : 'Tidak Balance'); ?>

        </span>
    </div>
    <div class="kpi-card" style="--kpi-accent:<?php echo e($adjEntries->count() > 0 ? '#F59E0B' : '#94A3B8'); ?>;--kpi-icon-bg:<?php echo e($adjEntries->count() > 0 ? '#FFFBEB' : '#F8FAFC'); ?>">
        <div class="kpi-icon"><i class="bi bi-wrench-adjustable"></i></div>
        <div class="kpi-label">Jurnal Penyesuaian</div>
        <div class="kpi-value"><?php echo e($adjEntries->count()); ?></div>
        <span class="kpi-badge <?php echo e($adjEntries->count() > 0 ? 'info' : 'ok'); ?>">
            <?php echo e($adjEntries->count() > 0 ? $adjEntries->count().' Entri' : 'Tidak Ada'); ?>

        </span>
    </div>
</div>

<?php if($adjEntries->count() > 0): ?>
<div class="balance-alert" style="background:#FFFBEB;border-color:#FDE68A;color:#92400E;margin-bottom:16px">
    <span class="alert-icon">🔧</span>
    <div>
        <strong><?php echo e($adjEntries->count()); ?> jurnal penyesuaian</strong> ditemukan pada periode ini.
        Kolom "Disesuaikan" sudah mencakup nilai penyesuaian tersebut.
    </div>
</div>
<?php endif; ?>


<div class="section-heading no-print">
    <div class="dot" style="background:#2563EB"></div>
    Neraca Saldo Sebelum Penyesuaian — <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?>

</div>

<div class="report-toolbar no-print">
    <div class="toolbar-left">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchBefore" placeholder="Cari akun..." oninput="filterTable('tblBefore',this.value)">
        </div>
        <select id="filterTypeBefore" class="form-select form-select-sm" style="width:140px;border-radius:8px;font-size:12.5px" onchange="filterType('tblBefore',this.value)">
            <option value="">Semua Tipe</option>
            <option value="asset">Asset</option>
            <option value="liability">Liability</option>
            <option value="equity">Equity</option>
            <option value="revenue">Revenue</option>
            <option value="expense">Expense</option>
        </select>
    </div>
    <div class="toolbar-right">
        <span style="font-size:12px;color:#94A3B8"><?php echo e($balances->count()); ?> akun</span>
    </div>
</div>

<div class="rpt-table-wrap mb-4">
    <div class="table-responsive">
        <table class="rpt-table" id="tblBefore">
            <thead>
                <tr class="group-head">
                    <th colspan="2" style="text-align:left">Akun</th>
                    <th style="text-align:center;width:90px">Tipe</th>
                    <th style="text-align:right;width:160px">Debit</th>
                    <th style="text-align:right;width:160px">Kredit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $currentType = null;
                    $typeLabels = ['asset'=>'Asset','liability'=>'Liability','equity'=>'Equity','revenue'=>'Revenue','expense'=>'Expense'];
                    $typePills  = ['asset'=>'type-asset','liability'=>'type-liability','equity'=>'type-equity','revenue'=>'type-revenue','expense'=>'type-expense'];
                ?>
                <?php $__empty_1 = true; $__currentLoopData = $balances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if(strtolower($b->type) !== $currentType): ?>
                        <?php $currentType = strtolower($b->type); ?>
                        <tr class="group-sep">
                            <td colspan="5">
                                <span class="<?php echo e($typePills[$currentType] ?? 'type-asset'); ?> type-badge">
                                    <?php echo e($typeLabels[$currentType] ?? ucfirst($currentType)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="td-code" style="width:80px"><?php echo e($b->code); ?></td>
                        <td class="td-name"><?php echo e($b->name); ?></td>
                        <td style="text-align:center">
                            <span class="<?php echo e($typePills[strtolower($b->type)] ?? 'type-asset'); ?> type-badge">
                                <?php echo e($b->type); ?>

                            </span>
                        </td>
                        <td class="num <?php echo e($b->debit > 0 ? 'dr' : 'zero'); ?>">
                            <?php echo e($b->debit > 0 ? 'Rp'.number_format($b->debit,0,',','.') : '—'); ?>

                        </td>
                        <td class="num <?php echo e($b->credit > 0 ? 'cr' : 'zero'); ?>">
                            <?php echo e($b->credit > 0 ? 'Rp'.number_format($b->credit,0,',','.') : '—'); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px 16px;color:#94A3B8">
                            <div style="font-size:32px;margin-bottom:8px">📊</div>
                            <div style="font-weight:600;color:#475569">Tidak ada data periode ini</div>
                            <div style="font-size:12px;margin-top:4px">Pastikan ada transaksi pada bulan yang dipilih</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="padding-left:16px;font-weight:800;font-size:12.5px;letter-spacing:.04em;text-transform:uppercase">TOTAL</td>
                    <td class="num <?php echo e($isBalanced ? 'balanced' : 'unbalanced'); ?>">Rp<?php echo e(number_format($totalDr,0,',','.')); ?></td>
                    <td class="num <?php echo e($isBalanced ? 'balanced' : 'unbalanced'); ?>">Rp<?php echo e(number_format($totalCr,0,',','.')); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


<div class="section-heading no-print">
    <div class="dot" style="background:#16A34A"></div>
    Neraca Saldo Setelah Penyesuaian — <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?>

</div>

<div class="report-toolbar no-print">
    <div class="toolbar-left">
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="searchAfter" placeholder="Cari akun..." oninput="filterTable('tblAfter',this.value)">
        </div>
        <select id="filterTypeAfter" class="form-select form-select-sm" style="width:140px;border-radius:8px;font-size:12.5px" onchange="filterType('tblAfter',this.value)">
            <option value="">Semua Tipe</option>
            <option value="asset">Asset</option>
            <option value="liability">Liability</option>
            <option value="equity">Equity</option>
            <option value="revenue">Revenue</option>
            <option value="expense">Expense</option>
        </select>
    </div>
    <div class="toolbar-right">
        <span style="font-size:12px;color:#94A3B8"><?php echo e($adjustedBalances->count()); ?> akun</span>
    </div>
</div>

<div class="rpt-table-wrap">
    <div class="table-responsive">
        <table class="rpt-table" id="tblAfter">
            <thead>
                <tr class="group-head" style="background:#064E3B">
                    <th colspan="2" style="text-align:left">Akun</th>
                    <th style="text-align:center;width:90px">Tipe</th>
                    <th style="text-align:right;width:160px">Debit</th>
                    <th style="text-align:right;width:160px">Kredit</th>
                </tr>
            </thead>
            <tbody>
                <?php $currentType = null; ?>
                <?php $__empty_1 = true; $__currentLoopData = $adjustedBalances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if(strtolower($b->type) !== $currentType): ?>
                        <?php $currentType = strtolower($b->type); ?>
                        <tr class="group-sep">
                            <td colspan="5">
                                <span class="<?php echo e($typePills[$currentType] ?? 'type-asset'); ?> type-badge">
                                    <?php echo e($typeLabels[$currentType] ?? ucfirst($currentType)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="td-code" style="width:80px"><?php echo e($b->code); ?></td>
                        <td class="td-name"><?php echo e($b->name); ?></td>
                        <td style="text-align:center">
                            <span class="<?php echo e($typePills[strtolower($b->type)] ?? 'type-asset'); ?> type-badge">
                                <?php echo e($b->type); ?>

                            </span>
                        </td>
                        <td class="num <?php echo e($b->debit > 0 ? 'dr' : 'zero'); ?>">
                            <?php echo e($b->debit > 0 ? 'Rp'.number_format($b->debit,0,',','.') : '—'); ?>

                        </td>
                        <td class="num <?php echo e($b->credit > 0 ? 'cr' : 'zero'); ?>">
                            <?php echo e($b->credit > 0 ? 'Rp'.number_format($b->credit,0,',','.') : '—'); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px 16px;color:#94A3B8">
                            <div style="font-size:32px;margin-bottom:8px">📊</div>
                            <div style="font-weight:600;color:#475569">Tidak ada data periode ini</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="padding-left:16px;font-weight:800;font-size:12.5px;letter-spacing:.04em;text-transform:uppercase">TOTAL</td>
                    <td class="num <?php echo e($isAdjBalanced ? 'balanced' : 'unbalanced'); ?>">Rp<?php echo e(number_format($totalAdjDr,0,',','.')); ?></td>
                    <td class="num <?php echo e($isAdjBalanced ? 'balanced' : 'unbalanced'); ?>">Rp<?php echo e(number_format($totalAdjCr,0,',','.')); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function filterTable(tableId, query) {
    const rows = document.querySelectorAll(`#${tableId} tbody tr:not(.group-sep)`);
    const q = query.toLowerCase();
    rows.forEach(row => {
        const code = row.querySelector('.td-code')?.textContent.toLowerCase() ?? '';
        const name = row.querySelector('.td-name')?.textContent.toLowerCase() ?? '';
        row.style.display = (code.includes(q) || name.includes(q)) ? '' : 'none';
    });
}

function filterType(tableId, type) {
    const rows = document.querySelectorAll(`#${tableId} tbody tr:not(.group-sep)`);
    rows.forEach(row => {
        const badge = row.querySelector('.type-badge');
        const t = badge?.textContent.trim().toLowerCase() ?? '';
        row.style.display = (!type || t.includes(type)) ? '' : 'none';
    });
}

function exportExcel() {
    alert('Export Excel: Integrasikan dengan route /accounting/trial-balance/export?month=<?php echo e($month); ?>');
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/accounting/trial-balance.blade.php ENDPATH**/ ?>