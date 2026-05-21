<?php $__env->startSection('title','Pengeluaran'); ?>
<?php $__env->startSection('page-title','Manajemen Pengeluaran'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Pengeluaran</h1>
        <p>Catatan semua pengeluaran operasional</p>
    </div>
    <a href="<?php echo e(route('expenses.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Pengeluaran
    </a>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef2f2; color:#dc2626;"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="stat-label">Total Pengeluaran</div>
                <div class="stat-value">Rp<?php echo e(number_format($totalExpenses,0,',','.')); ?></div>
                <div class="stat-sub">Sepanjang waktu</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Filter Bulan</label>
                <input type="month" name="month" class="form-control" value="<?php echo e(request('month', now()->format('Y-m'))); ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-filter me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="<?php echo e(route('expenses.index')); ?>" class="btn btn-outline-secondary w-100">Semua</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Daftar Pengeluaran</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Tanggal</th>
                    <th>Nama Pengeluaran</th>
                    <th>Keterangan</th>
                    <th class="text-end pe-4">Jumlah</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="ps-4 text-muted"><?php echo e(\Carbon\Carbon::parse($exp->expense_date)->format('d/m/Y')); ?></td>
                    <td class="fw-semibold"><?php echo e($exp->name); ?></td>
                    <td class="text-muted" style="font-size:12px"><?php echo e($exp->notes ?: '-'); ?></td>
                    <td class="text-end pe-4 fw-bold text-danger">Rp<?php echo e(number_format($exp->amount,0,',','.')); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('expenses.edit',$exp)); ?>" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="<?php echo e(route('expenses.destroy',$exp)); ?>" class="d-inline"
                              onsubmit="return confirm('Hapus pengeluaran ini?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center text-muted py-5">Belum ada pengeluaran tercatat</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($expenses->hasPages()): ?>
        <div class="d-flex justify-content-center py-3"><?php echo e($expenses->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/expenses/index.blade.php ENDPATH**/ ?>