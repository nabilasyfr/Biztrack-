<?php $__env->startSection('title','Kertas Kerja'); ?>
<?php $__env->startSection('page-title','Kertas Kerja (Worksheet)'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ─── Worksheet table ──────────────────────────────────────── */
.ws-table { border-collapse: collapse; width: 100%; }
.ws-table th,
.ws-table td { font-size: 11.5px; white-space: nowrap; padding: 5px 8px; }

/* Header row 1: group labels */
.ws-table thead tr:first-child th {
    text-align: center;
    background: #0f172a;
    color: #fff;
    font-weight: 700;
    border: 1px solid #1e293b;
}
/* Header row 1: account name cell */
.ws-table thead tr:first-child th.col-akun {
    text-align: left;
    padding-left: 12px;
    vertical-align: middle;
    min-width: 200px;
}
/* Header row 2: Debit / Kredit sub-labels */
.ws-table thead tr:last-child th {
    background: #1e293b;
    color: #94a3b8;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .05em;
    border: 1px solid #263347;
    text-align: right;
}

/* Body cells */
.ws-table tbody tr { border-bottom: 1px solid #e2e8f0; }
.ws-table tbody tr:hover { background: #f8fafc; }
.ws-table tbody td { border: 1px solid #e2e8f0; vertical-align: middle; }

/* Number cells */
.num-cell { text-align: right; font-variant-numeric: tabular-nums; }
.text-dr  { color: #1d4ed8; font-weight: 600; }
.text-cr  { color: #15803d; font-weight: 600; }

/* Net income row */
.row-net-income { background: #f0fdf4 !important; }
.row-net-loss   { background: #fff1f2 !important; }

/* Footer */
.ws-table tfoot tr td {
    background: #0f172a;
    color: #fff;
    font-weight: 800;
    font-size: 11.5px;
    border: 1px solid #1e293b;
}

/* Balance indicator badges */
.balance-ok   { background: #16a34a; color: #fff; }
.balance-fail { background: #dc2626; color: #fff; }

/* ─── Print ────────────────────────────────────────────────── */
@media print {
    .no-print { display: none !important; }
    #page-content { padding: 0 !important; }
    .card { box-shadow: none !important; }
    .ws-table th, .ws-table td { font-size: 8.5px !important; padding: 3px 5px !important; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header no-print">
    <div>
        <h1>Kertas Kerja (Worksheet)</h1>
        <p class="text-muted mb-0">Neraca lajur 10 kolom — standar AIS</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
    </div>
</div>


<div class="card mb-3 no-print">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1 fw-semibold">Periode</label>
                <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>


<div class="text-center mb-3 d-none d-print-block">
    <h4 class="fw-bold mb-0">BizTrack UMKM — Kertas Kerja (Neraca Lajur)</h4>
    <p class="mb-0">Periode: <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?></p>
</div>

<?php
/*
 * ─── Aggregate totals untuk footer & net income ──────────────────────────
 */
$totNsDr  = $rows->sum('nsDr');
$totNsCr  = $rows->sum('nsCr');
$totAdjDr = $rows->sum('adjDr');
$totAdjCr = $rows->sum('adjCr');
$totNsdDr = $rows->sum('nsdDr');
$totNsdCr = $rows->sum('nsdCr');

// REVISI LOGIKA FILTER TOTAL: Supaya kalkulasi total bawah sinkron dengan baris tabel yang di-filter
$totLrDr  = $rows->filter(fn($r) => !in_array(substr($r->acc->code, 0, 1), ['1', '2', '3']))->sum('lrDr');
$totLrCr  = $rows->filter(fn($r) => !in_array(substr($r->acc->code, 0, 1), ['1', '2', '3']))->sum('lrCr');
$totNerDr = $rows->filter(fn($r) => in_array(substr($r->acc->code, 0, 1), ['1', '2', '3']))->sum('nerDr');
$totNerCr = $rows->filter(fn($r) => in_array(substr($r->acc->code, 0, 1), ['1', '2', '3']))->sum('nerCr');

// Net income (positif = laba, negatif = rugi)
$netIncome = $totLrCr - $totLrDr;

// Setelah penambahan baris net income, total keempat kolom seharusnya seimbang
$grandLrDr  = $totLrDr  + ($netIncome > 0 ? $netIncome : 0);
$grandLrCr  = $totLrCr  + ($netIncome < 0 ? abs($netIncome) : 0);
$grandNerDr = $totNerDr + ($netIncome < 0 ? abs($netIncome) : 0);
$grandNerCr = $totNerCr + ($netIncome > 0 ? $netIncome : 0);

// Helper format angka: tampilkan '-' jika nol
$fmt = fn($n) => $n > 0 ? number_format($n, 0, ',', '.') : '-';
$fmtAbs = fn($n) => number_format(abs($n), 0, ',', '.');

// Balance checks
$nsBalanced  = abs($totNsDr  - $totNsCr)  < 0.01;
$nsdBalanced = abs($totNsdDr - $totNsdCr) < 0.01;
$lrBalanced  = abs($grandLrDr - $grandLrCr)  < 0.01;
$nerBalanced = abs($grandNerDr - $grandNerCr) < 0.01;
?>

<div class="card">
    <div class="card-header d-flex align-items-center gap-2 flex-wrap">
        <i class="bi bi-grid-3x3-gap me-1"></i>
        <strong>Kertas Kerja — <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?></strong>

        
        <span class="badge <?php echo e($nsBalanced ? 'balance-ok' : 'balance-fail'); ?> ms-1">
            NS <?php echo e($nsBalanced ? '✓ Seimbang' : '✗ Tidak Seimbang'); ?>

        </span>
        <span class="badge <?php echo e($nsdBalanced ? 'balance-ok' : 'balance-fail'); ?>">
            NSD <?php echo e($nsdBalanced ? '✓ Seimbang' : '✗ Tidak Seimbang'); ?>

        </span>
        <span class="badge <?php echo e($lrBalanced ? 'balance-ok' : 'balance-fail'); ?>">
            L/R <?php echo e($lrBalanced ? '✓ Seimbang' : '✗ Tidak Seimbang'); ?>

        </span>
        <span class="badge <?php echo e($nerBalanced ? 'balance-ok' : 'balance-fail'); ?>">
            Neraca <?php echo e($nerBalanced ? '✓ Seimbang' : '✗ Tidak Seimbang'); ?>

        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 ws-table">
                
                <thead>
                    
                    <tr>
                        <th class="col-akun" rowspan="2">Nama Akun</th>
                        <th colspan="2">Neraca Saldo</th>
                        <th colspan="2">Penyesuaian</th>
                        <th colspan="2">NS Disesuaikan</th>
                        <th colspan="2">Laba / Rugi</th>
                        <th colspan="2">Neraca</th>
                    </tr>
                    
                    <tr>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                        <th class="num-cell">Debit</th> <th class="num-cell">Kredit</th>
                    </tr>
                </thead>

                
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Ambil digit pertama dari kode akun untuk membedakan kategori (1=Aset, 2=Utang, 3=Modal, 4=Pendapatan, 5=Beban)
                        $accountFirstDigit = substr($r->acc->code, 0, 1);
                        $isRealAccount = in_array($accountFirstDigit, ['1', '2', '3']);
                    ?>
                    <tr>
                        
                        <td class="ps-3">
                            <code style="font-size:10px;color:#64748b"><?php echo e($r->acc->code); ?></code>
                            <span class="ms-1"><?php echo e($r->acc->name); ?></span>
                            <span class="badge bg-light text-secondary ms-1" style="font-size:9px"><?php echo e($r->acc->type); ?></span>
                        </td>

                        
                        <td class="num-cell <?php echo e($r->nsDr > 0 ? 'text-dr' : ''); ?>"><?php echo e($fmt($r->nsDr)); ?></td>
                        <td class="num-cell <?php echo e($r->nsCr > 0 ? 'text-cr' : ''); ?>"><?php echo e($fmt($r->nsCr)); ?></td>

                        
                        <td class="num-cell <?php echo e($r->adjDr > 0 ? 'text-dr' : ''); ?>"><?php echo e($fmt($r->adjDr)); ?></td>
                        <td class="num-cell <?php echo e($r->adjCr > 0 ? 'text-cr' : ''); ?>"><?php echo e($fmt($r->adjCr)); ?></td>

                        
                        <td class="num-cell <?php echo e($r->nsdDr > 0 ? 'text-dr' : ''); ?>"><?php echo e($fmt($r->nsdDr)); ?></td>
                        <td class="num-cell <?php echo e($r->nsdCr > 0 ? 'text-cr' : ''); ?>"><?php echo e($fmt($r->nsdCr)); ?></td>

                        
                        <td class="num-cell <?php echo e((!$isRealAccount && $r->lrDr > 0) ? 'text-dr' : ''); ?>">
                            <?php echo e($isRealAccount ? '-' : $fmt($r->lrDr)); ?>

                        </td>
                        <td class="num-cell <?php echo e((!$isRealAccount && $r->lrCr > 0) ? 'text-cr' : ''); ?>">
                            <?php echo e($isRealAccount ? '-' : $fmt($r->lrCr)); ?>

                        </td>

                        
                        <td class="num-cell <?php echo e(($isRealAccount && $r->nerDr > 0) ? 'text-dr' : ''); ?>">
                            <?php echo e($isRealAccount ? $fmt($r->nerDr) : '-'); ?>

                        </td>
                        <td class="num-cell <?php echo e(($isRealAccount && $r->nerCr > 0) ? 'text-cr' : ''); ?>">
                            <?php echo e($isRealAccount ? $fmt($r->nerCr) : '-'); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">Tidak ada data untuk periode ini</td>
                    </tr>
                    <?php endif; ?>

                    
                    <?php if($netIncome != 0): ?>
                    <tr class="<?php echo e($netIncome > 0 ? 'row-net-income' : 'row-net-loss'); ?>">
                        <td class="ps-3 fw-bold <?php echo e($netIncome > 0 ? 'text-success' : 'text-danger'); ?>">
                            <?php if($netIncome > 0): ?>
                                <i class="bi bi-arrow-up-circle me-1"></i>Laba Bersih
                            <?php else: ?>
                                <i class="bi bi-arrow-down-circle me-1"></i>Rugi Bersih
                            <?php endif; ?>
                        </td>
                        
                        <td colspan="6" style="background: transparent;"></td>
                        
                        <?php if($netIncome > 0): ?>
                            
                            <td class="num-cell fw-bold text-dr"><?php echo e($fmtAbs($netIncome)); ?></td>
                            <td class="num-cell">-</td>
                        <?php else: ?>
                            
                            <td class="num-cell">-</td>
                            <td class="num-cell fw-bold text-cr"><?php echo e($fmtAbs($netIncome)); ?></td>
                        <?php endif; ?>
                        
                        <?php if($netIncome > 0): ?>
                            
                            <td class="num-cell">-</td>
                            <td class="num-cell fw-bold text-cr"><?php echo e($fmtAbs($netIncome)); ?></td>
                        <?php endif; ?>
                    </tr>
                    <?php endif; ?>
                </tbody>

                
                <tfoot>
                    <tr>
                        <td class="ps-3">TOTAL</td>
                        
                        <td class="num-cell"><?php echo e(number_format($totNsDr,  0, ',', '.')); ?></td>
                        <td class="num-cell"><?php echo e(number_format($totNsCr,  0, ',', '.')); ?></td>
                        
                        <td class="num-cell"><?php echo e(number_format($totAdjDr, 0, ',', '.')); ?></td>
                        <td class="num-cell"><?php echo e(number_format($totAdjCr, 0, ',', '.')); ?></td>
                        
                        <td class="num-cell"><?php echo e(number_format($totNsdDr, 0, ',', '.')); ?></td>
                        <td class="num-cell"><?php echo e(number_format($totNsdCr, 0, ',', '.')); ?></td>
                        
                        <td class="num-cell"><?php echo e(number_format($grandLrDr,  0, ',', '.')); ?></td>
                        <td class="num-cell"><?php echo e(number_format($grandLrCr,  0, ',', '.')); ?></td>
                        
                        <td class="num-cell"><?php echo e(number_format($grandNerDr, 0, ',', '.')); ?></td>
                        <td class="num-cell"><?php echo e(number_format($grandNerCr, 0, ',', '.')); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/accounting/worksheet.blade.php ENDPATH**/ ?>