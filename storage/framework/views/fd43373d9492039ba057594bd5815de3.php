<?php $__env->startSection('title','Log Inventori'); ?>
<?php $__env->startSection('page-title','Log Pergerakan Inventori'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Log Inventori</h1>
        <p>Semua pergerakan stok barang</p>
    </div>
    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Filter Tipe</label>
                <select name="type" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="sale" <?php echo e(request('type')==='sale'?'selected':''); ?>>Penjualan</option>
                    <option value="restock" <?php echo e(request('type')==='restock'?'selected':''); ?>>Restock</option>
                    <option value="adjustment" <?php echo e(request('type')==='adjustment'?'selected':''); ?>>Adjustment</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-filter me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-arrow-left-right me-2"></i>Log Inventori</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>Produk</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Sebelum</th>
                        <th class="text-center">Sesudah</th>
                        <th>Referensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4 text-muted" style="font-size:12px"><?php echo e($log->created_at->format('d/m/Y H:i')); ?></td>
                        <td class="fw-semibold"><?php echo e($log->product->name ?? '-'); ?></td>
                        <td class="text-center">
                            <?php if($log->type==='sale'): ?><span class="badge bg-primary">Penjualan</span>
                            <?php elseif($log->type==='restock'): ?><span class="badge bg-success">Restock</span>
                            <?php else: ?><span class="badge bg-secondary">Adjustment</span><?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="<?php echo e($log->type==='sale' ? 'text-danger' : 'text-success'); ?> fw-bold">
                                <?php echo e($log->type==='sale' ? '-' : '+'); ?><?php echo e($log->quantity); ?>

                            </span>
                        </td>
                        <td class="text-center"><?php echo e($log->stock_before); ?></td>
                        <td class="text-center fw-bold"><?php echo e($log->stock_after); ?></td>
                        <td><code style="font-size:11px"><?php echo e($log->reference); ?></code></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-muted py-5">Tidak ada log inventori</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($logs->hasPages()): ?>
        <div class="d-flex justify-content-center py-3"><?php echo e($logs->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/products/inventory-log.blade.php ENDPATH**/ ?>