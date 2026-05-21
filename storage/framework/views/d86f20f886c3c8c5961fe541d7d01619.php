<?php $__env->startSection('title','Neraca Keuangan'); ?>
<?php $__env->startSection('page-title','Neraca Keuangan (Balance Sheet)'); ?>

<?php $__env->startPush('styles'); ?>
<style>
@media print {
    .no-print{display:none!important}
    #page-content{padding:0!important}
    .card{box-shadow:none!important;border:1px solid #ddd!important}
}
.bs-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f1f5f9; font-size:13.5px; }
.bs-row:last-child { border-bottom:none; }
.bs-sub { font-size:11px; color:#94a3b8; margin-top:2px; }
.bs-total { display:flex; justify-content:space-between; padding:12px 0; font-weight:800; font-size:14px; border-top:2px solid #0f172a; margin-top:6px; }
.bs-grand { background:#0f172a; color:#fff; border-radius:10px; padding:14px 18px; display:flex; justify-content:space-between; font-weight:800; font-size:16px; margin-top:8px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header no-print">
    <div>
        <h1>Neraca Keuangan</h1>
        <p>Laporan posisi keuangan — Aset, Kewajiban, dan Modal</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline-primary no-print">
        <i class="bi bi-printer me-1"></i> Cetak
    </button>
</div>

<div class="card mb-3 no-print">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Per Akhir Bulan</label>
                <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>


<div class="text-center mb-4 d-none d-print-block">
    <h4 class="fw-bold">BizTrack UMKM</h4>
    <h5>Neraca Keuangan (Balance Sheet)</h5>
    <p>Per <?php echo e(\Carbon\Carbon::parse($dateTo)->format('d F Y')); ?></p>
    <p style="font-size:12px">Dicetak: <?php echo e(now()->format('d M Y H:i')); ?></p>
</div>


<?php
$balanced = abs($totalAssets - ($totalLiabilities + $totalEquity)) < 1;
?>

<?php if(!$balanced): ?>
<div class="alert alert-warning mb-3" style="font-size:13px;border-radius:10px">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Neraca belum seimbang!</strong>
    Total Aset (Rp<?php echo e(number_format($totalAssets,0,',','.')); ?>) ≠
    Total Kewajiban+Modal (Rp<?php echo e(number_format($totalLiabilities+$totalEquity,0,',','.')); ?>).
    Periksa jurnal modal awal atau penyesuaian.
</div>
<?php endif; ?>


<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;color:#1a56db"><i class="bi bi-bank2"></i></div>
            <div>
                <div class="stat-label">Total Aset</div>
                <div class="stat-value" style="font-size:17px;color:#1a56db">Rp<?php echo e(number_format($totalAssets,0,',','.')); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;color:#dc2626"><i class="bi bi-credit-card"></i></div>
            <div>
                <div class="stat-label">Total Kewajiban</div>
                <div class="stat-value" style="font-size:17px;color:#dc2626">Rp<?php echo e(number_format($totalLiabilities,0,',','.')); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="bi bi-person-circle"></i></div>
            <div>
                <div class="stat-label">Total Ekuitas</div>
                <div class="stat-value" style="font-size:17px;color:#16a34a">Rp<?php echo e(number_format($totalEquity,0,',','.')); ?></div>
                <div class="stat-sub">Termasuk laba/rugi berjalan</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-bold text-primary">
                <i class="bi bi-bank2 me-2"></i>ASET
            </div>
            <div class="card-body" style="padding:20px 24px">
                <div style="text-align:center;margin-bottom:16px">
                    <div class="fw-bold" style="font-size:14px">BizTrack UMKM</div>
                    <div style="font-size:12px;color:#64748b">Per <?php echo e(\Carbon\Carbon::parse($dateTo)->format('d F Y')); ?></div>
                </div>

                <div class="fw-bold mb-2" style="font-size:12px;text-transform:uppercase;color:#374151;letter-spacing:.05em">Aset Lancar</div>
                <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bs-row">
                    <span class="text-muted ps-3">
                        <code style="font-size:10px"><?php echo e($a->code); ?></code> <?php echo e($a->name); ?>

                    </span>
                    <span class="fw-semibold">Rp<?php echo e(number_format($a->balance,0,',','.')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bs-row text-muted fst-italic ps-3">Tidak ada aset tercatat</div>
                <?php endif; ?>

                <div class="bs-total">
                    <span>TOTAL ASET</span>
                    <span class="text-primary">Rp<?php echo e(number_format($totalAssets,0,',','.')); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header fw-bold text-danger">
                <i class="bi bi-credit-card me-2"></i>KEWAJIBAN & EKUITAS
            </div>
            <div class="card-body" style="padding:20px 24px">
                <div style="text-align:center;margin-bottom:16px">
                    <div class="fw-bold" style="font-size:14px">BizTrack UMKM</div>
                    <div style="font-size:12px;color:#64748b">Per <?php echo e(\Carbon\Carbon::parse($dateTo)->format('d F Y')); ?></div>
                </div>

                
                <div class="fw-bold mb-2" style="font-size:12px;text-transform:uppercase;color:#374151;letter-spacing:.05em">Kewajiban</div>
                <?php $__empty_1 = true; $__currentLoopData = $liabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bs-row">
                    <span class="text-muted ps-3">
                        <code style="font-size:10px"><?php echo e($l->code); ?></code> <?php echo e($l->name); ?>

                    </span>
                    <span class="fw-semibold">Rp<?php echo e(number_format($l->balance,0,',','.')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bs-row text-muted fst-italic ps-3">Tidak ada kewajiban</div>
                <?php endif; ?>
                <div class="bs-total">
                    <span>Total Kewajiban</span>
                    <span class="text-danger">Rp<?php echo e(number_format($totalLiabilities,0,',','.')); ?></span>
                </div>

                <div style="margin:16px 0 12px;border-top:1px dashed #e2e8f0"></div>

                
                <div class="fw-bold mb-2" style="font-size:12px;text-transform:uppercase;color:#374151;letter-spacing:.05em">Ekuitas (Modal)</div>
                <?php $__currentLoopData = $equities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bs-row">
                    <span class="text-muted ps-3">
                        <code style="font-size:10px"><?php echo e($e->code); ?></code> <?php echo e($e->name); ?>

                    </span>
                    <span class="fw-semibold">Rp<?php echo e(number_format($e->balance,0,',','.')); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <div class="bs-row">
                    <span class="text-muted ps-3 fst-italic">
                        <?php echo e($currentProfit >= 0 ? 'Laba' : 'Rugi'); ?> Periode Berjalan
                    </span>
                    <span class="fw-semibold <?php echo e($currentProfit>=0?'text-success':'text-danger'); ?>">
                        <?php echo e($currentProfit<0?'(':''); ?>Rp<?php echo e(number_format(abs($currentProfit),0,',','.')); ?><?php echo e($currentProfit<0?')':''); ?>

                    </span>
                </div>
                <div class="bs-total">
                    <span>Total Ekuitas</span>
                    <span class="text-success">Rp<?php echo e(number_format($totalEquity,0,',','.')); ?></span>
                </div>

                <div class="bs-grand">
                    <span>TOTAL KEWAJIBAN + EKUITAS</span>
                    <span>Rp<?php echo e(number_format($totalLiabilities+$totalEquity,0,',','.')); ?></span>
                </div>

                <?php if($balanced): ?>
                <div class="text-center mt-2" style="font-size:12px;color:#16a34a;font-weight:700">
                    ✅ Neraca Seimbang (Aset = Kewajiban + Ekuitas)
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/accounting/balance-sheet.blade.php ENDPATH**/ ?>