<?php $__env->startSection('title','Laporan Inventori'); ?>
<?php $__env->startSection('page-title','Laporan Inventori'); ?>

<?php $__env->startPush('styles'); ?>
<style>
@media print {
    .no-print { display: none !important; }
    #page-content { padding: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header no-print">
    <div>
        <h1>Laporan Inventori</h1>
        <p>Status stok semua produk</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline-primary">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>


<div class="card mb-3 no-print">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Filter Stok</label>
                <select name="filter" class="form-select">
                    <option value="all" <?php echo e($filter==='all'?'selected':''); ?>>Semua Produk</option>
                    <option value="low" <?php echo e($filter==='low'?'selected':''); ?>>Stok Rendah</option>
                    <option value="out" <?php echo e($filter==='out'?'selected':''); ?>>Habis / 0</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-filter me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="<?php echo e(route('reports.inventory')); ?>" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>


<div class="text-center mb-3 d-none d-print-block">
    <h3 class="fw-bold">BizTrack UMKM — Laporan Inventori</h3>
    <p class="text-muted">Dicetak: <?php echo e(now()->format('d M Y H:i')); ?></p>
</div>


<div class="row g-3 mb-4">
    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;color:#1a56db;"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="stat-label">Total Produk</div>
                <div class="stat-value"><?php echo e($products->count()); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2;color:#dc2626;"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-label">Stok Rendah</div>
                <div class="stat-value" style="color:#dc2626"><?php echo e($lowStockCount); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="stat-label">Nilai Stok (HPP)</div>
                <div class="stat-value" style="font-size:15px">Rp<?php echo e(number_format($totalValue,0,',','.')); ?></div>
            </div>
        </div>
    </div>
</div>


<?php if($lowStockCount > 0 && $filter === 'all'): ?>
<div class="alert alert-warning d-flex align-items-center gap-2 mb-3" style="border-radius:10px; font-size:13.5px;">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div><strong>Perhatian!</strong> Ada <?php echo e($lowStockCount); ?> produk dengan stok di bawah minimum.
    <a href="?filter=low" class="alert-link">Lihat produk stok rendah →</a></div>
</div>
<?php endif; ?>


<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard-data me-2"></i>
            <?php if($filter==='low'): ?> Produk Stok Rendah
            <?php elseif($filter==='out'): ?> Produk Habis
            <?php else: ?> Semua Produk (<?php echo e($products->count()); ?>)
            <?php endif; ?>
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th class="text-end">H. Beli</th>
                        <th class="text-end">H. Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min.</th>
                        <th class="text-end pe-4">Nilai Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $currentCat = null; ?>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if($currentCat !== $p->category): ?>
                    <?php $currentCat = $p->category; ?>
                    <tr class="table-light">
                        <td colspan="9" class="ps-4 fw-bold text-muted" style="font-size:11px; text-transform:uppercase; letter-spacing:.05em">
                            <?php echo e($p->category); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="ps-4"><code style="font-size:11px"><?php echo e($p->code); ?></code></td>
                        <td class="fw-semibold"><?php echo e($p->name); ?></td>
                        <td><?php echo e($p->category); ?></td>
                        <td class="text-muted" style="font-size:12px"><?php echo e($p->supplier ?: '-'); ?></td>
                        <td class="text-end">Rp<?php echo e(number_format($p->cost_price,0,',','.')); ?></td>
                        <td class="text-end">Rp<?php echo e(number_format($p->selling_price,0,',','.')); ?></td>
                        <td class="text-center">
                            <?php if($p->stock === 0): ?>
                                <span class="badge bg-danger">HABIS</span>
                            <?php elseif($p->isLowStock()): ?>
                                <span class="badge bg-warning text-dark"><?php echo e($p->stock); ?> ⚠️</span>
                            <?php else: ?>
                                <span class="fw-semibold text-success"><?php echo e($p->stock); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center text-muted"><?php echo e($p->min_stock); ?></td>
                        <td class="text-end pe-4 fw-bold">Rp<?php echo e(number_format($p->stock * $p->cost_price,0,',','.')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="9" class="text-center text-muted py-5">Tidak ada produk ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if($products->count() > 0): ?>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="8" class="ps-4 fw-bold">TOTAL NILAI STOK</td>
                        <td class="text-end pe-4 fw-bold">Rp<?php echo e(number_format($totalValue,0,',','.')); ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/reports/inventory.blade.php ENDPATH**/ ?>