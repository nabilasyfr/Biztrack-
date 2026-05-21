<?php $__env->startSection('title','Neraca Saldo'); ?>
<?php $__env->startSection('page-title','Neraca Saldo (Trial Balance)'); ?>

<?php $__env->startPush('styles'); ?>
<style>
@media print {
    .no-print{display:none!important}
    #page-content{padding:0!important}
    .card{box-shadow:none!important;border:1px solid #ddd!important}
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header no-print">
    <div>
        <h1>Neraca Saldo</h1>
        <p>Ringkasan saldo semua akun sebelum & setelah penyesuaian</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-primary no-print">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
    </div>
</div>


<div class="card mb-3 no-print">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Periode</label>
                <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>


<div class="text-center mb-3 d-none d-print-block">
    <h4 class="fw-bold">BizTrack UMKM — Neraca Saldo</h4>
    <p class="text-muted">Periode: <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?></p>
</div>

<?php
$totalDr     = $balances->sum('debit');
$totalCr     = $balances->sum('credit');
$totalAdjDr  = $adjustedBalances->sum('debit');
$totalAdjCr  = $adjustedBalances->sum('credit');
?>


<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;color:#1a56db"><i class="bi bi-table"></i></div>
            <div>
                <div class="stat-label">Total Debit (Sblm Penyesuaian)</div>
                <div class="stat-value" style="font-size:16px">Rp<?php echo e(number_format($totalDr,0,',','.')); ?></div>
                <div class="stat-sub <?php echo e($totalDr == $totalCr ? 'text-success' : 'text-danger'); ?>">
                    <?php echo e($totalDr == $totalCr ? '✅ Seimbang' : '❌ Tidak Seimbang'); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-label">Total Debit (Stlh Penyesuaian)</div>
                <div class="stat-value" style="font-size:16px">Rp<?php echo e(number_format($totalAdjDr,0,',','.')); ?></div>
                <div class="stat-sub <?php echo e($totalAdjDr == $totalAdjCr ? 'text-success' : 'text-danger'); ?>">
                    <?php echo e($totalAdjDr == $totalAdjCr ? '✅ Seimbang' : '❌ Tidak Seimbang'); ?>

                </div>
            </div>
        </div>
    </div>
</div>


<?php if($adjEntries->count() > 0): ?>
<div class="alert alert-warning mb-3" style="font-size:13px;border-radius:10px">
    <i class="bi bi-wrench me-2"></i>
    <strong><?php echo e($adjEntries->count()); ?> jurnal penyesuaian</strong> ditemukan pada periode ini.
    Kolom "Disesuaikan" sudah termasuk nilai penyesuaian tersebut.
</div>
<?php endif; ?>


<div class="card mb-3">
    <div class="card-header fw-bold">
        <i class="bi bi-table me-2"></i>
        Neraca Saldo Sebelum Penyesuaian — <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?>

    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:80px">Kode</th>
                        <th>Nama Akun</th>
                        <th class="text-center" style="width:100px">Tipe</th>
                        <th class="text-end" style="width:160px">Debit</th>
                        <th class="text-end pe-4" style="width:160px">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $balances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4"><code style="font-size:11px"><?php echo e($b->code); ?></code></td>
                        <td><?php echo e($b->name); ?></td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark" style="font-size:10px"><?php echo e($b->type); ?></span>
                        </td>
                        <td class="text-end <?php echo e($b->debit > 0 ? 'text-primary fw-semibold' : 'text-muted'); ?>">
                            <?php echo e($b->debit > 0 ? 'Rp'.number_format($b->debit,0,',','.') : '-'); ?>

                        </td>
                        <td class="text-end pe-4 <?php echo e($b->credit > 0 ? 'text-success fw-semibold' : 'text-muted'); ?>">
                            <?php echo e($b->credit > 0 ? 'Rp'.number_format($b->credit,0,',','.') : '-'); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi periode ini</td></tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3" class="ps-4 fw-bold">TOTAL</td>
                        <td class="text-end fw-bold">Rp<?php echo e(number_format($totalDr,0,',','.')); ?></td>
                        <td class="text-end pe-4 fw-bold">Rp<?php echo e(number_format($totalCr,0,',','.')); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header fw-bold text-success">
        <i class="bi bi-check2-square me-2"></i>
        Neraca Saldo Setelah Penyesuaian — <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?>

    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:80px">Kode</th>
                        <th>Nama Akun</th>
                        <th class="text-center" style="width:100px">Tipe</th>
                        <th class="text-end" style="width:160px">Debit</th>
                        <th class="text-end pe-4" style="width:160px">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $adjustedBalances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4"><code style="font-size:11px"><?php echo e($b->code); ?></code></td>
                        <td><?php echo e($b->name); ?></td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark" style="font-size:10px"><?php echo e($b->type); ?></span>
                        </td>
                        <td class="text-end <?php echo e($b->debit > 0 ? 'text-primary fw-semibold' : 'text-muted'); ?>">
                            <?php echo e($b->debit > 0 ? 'Rp'.number_format($b->debit,0,',','.') : '-'); ?>

                        </td>
                        <td class="text-end pe-4 <?php echo e($b->credit > 0 ? 'text-success fw-semibold' : 'text-muted'); ?>">
                            <?php echo e($b->credit > 0 ? 'Rp'.number_format($b->credit,0,',','.') : '-'); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi periode ini</td></tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3" class="ps-4 fw-bold">TOTAL</td>
                        <td class="text-end fw-bold">Rp<?php echo e(number_format($totalAdjDr,0,',','.')); ?></td>
                        <td class="text-end pe-4 fw-bold">Rp<?php echo e(number_format($totalAdjCr,0,',','.')); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/accounting/trial-balance.blade.php ENDPATH**/ ?>