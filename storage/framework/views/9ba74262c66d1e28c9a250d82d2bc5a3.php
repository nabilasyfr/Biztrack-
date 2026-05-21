<?php $__env->startSection('title','Riwayat Penjualan'); ?>
<?php $__env->startSection('page-title','Riwayat Penjualan'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Riwayat Penjualan</h1>
        <p>Semua transaksi penjualan</p>
    </div>
    <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-primary">
        <i class="bi bi-upc-scan me-1"></i> Buka Kasir
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Cari Invoice</label>
                <input type="text" name="search" class="form-control" placeholder="Nomor invoice..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Tanggal</label>
                <input type="date" name="date" class="form-control" value="<?php echo e(request('date')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">Pembayaran</label>
                <select name="payment" class="form-select">
                    <option value="">Semua</option>
                    <option value="cash" <?php echo e(request('payment')==='cash'?'selected':''); ?>>Tunai</option>
                    <option value="dana" <?php echo e(request('payment')==='dana'?'selected':''); ?>>Dana</option>
                    <option value="qris" <?php echo e(request('payment')==='qris'?'selected':''); ?>>QRIS</option>
                    <option value="transfer" <?php echo e(request('payment')==='transfer'?'selected':''); ?>>Transfer</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-2">
                <a href="<?php echo e(route('sales.index')); ?>" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt me-2"></i>Transaksi (<?php echo e($sales->total()); ?>)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Invoice</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th class="text-center">Item</th>
                        <th class="text-center">Pembayaran</th>
                        <th class="text-end pe-4">Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <a href="<?php echo e(route('sales.show',$sale)); ?>" class="fw-semibold text-primary text-decoration-none">
                                <?php echo e($sale->invoice_number); ?>

                            </a>
                        </td>
                        <td class="text-muted"><?php echo e($sale->created_at->format('d/m/Y H:i')); ?></td>
                        <td><?php echo e($sale->user->name ?? '-'); ?></td>
                        <td class="text-center"><?php echo e($sale->items->sum('quantity')); ?></td>
                        <td class="text-center">
                            <span class="badge badge-<?php echo e($sale->payment_method); ?>"><?php echo e(strtoupper($sale->payment_method)); ?></span>
                        </td>
                        <td class="text-end pe-4 fw-bold">Rp<?php echo e(number_format($sale->total_amount,0,',','.')); ?></td>
                        <td class="text-center">
                            <a href="<?php echo e(route('sales.receipt',$sale)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Struk">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-muted py-5">Tidak ada transaksi</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($sales->hasPages()): ?>
        <div class="d-flex justify-content-center py-3"><?php echo e($sales->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/sales/index.blade.php ENDPATH**/ ?>