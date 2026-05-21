<?php $__env->startSection('title','Jurnal Umum'); ?>
<?php $__env->startSection('page-title','Jurnal Umum'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Jurnal Umum</h1>
        <p>Semua entri jurnal akuntansi yang dibuat otomatis</p>
    </div>
    <a href="<?php echo e(route('accounting.coa')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-list-columns-reverse me-1"></i> Bagan Akun
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Cari Referensi</label>
                <input type="text" name="search" class="form-control" placeholder="No. invoice..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-journal-text me-2"></i>Entri Jurnal (<?php echo e($entries->total()); ?>)</div>
    <div class="card-body p-0">
        <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="border-bottom p-3 px-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="fw-bold text-primary me-2"><?php echo e($entry->reference); ?></span>
                    <span class="text-muted" style="font-size:12px"><?php echo e(\Carbon\Carbon::parse($entry->entry_date)->format('d F Y')); ?></span>
                </div>
                <span class="text-muted" style="font-size:11px">#<?php echo e($entry->id); ?></span>
            </div>
            <p class="text-muted mb-2" style="font-size:13px"><?php echo e($entry->description); ?></p>
            <table class="table table-sm mb-0" style="font-size:13px">
                <thead>
                    <tr class="table-light">
                        <th style="width:100px">Kode</th>
                        <th>Nama Akun</th>
                        <th class="text-end" style="width:150px">Debit</th>
                        <th class="text-end" style="width:150px">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $entry->lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><code style="font-size:11px"><?php echo e($line->account->code ?? '-'); ?></code></td>
                        <td><?php echo e($line->account->name ?? '-'); ?></td>
                        <td class="text-end <?php echo e($line->debit > 0 ? 'fw-bold text-primary' : 'text-muted'); ?>">
                            <?php echo e($line->debit > 0 ? 'Rp'.number_format($line->debit,0,',','.') : '-'); ?>

                        </td>
                        <td class="text-end <?php echo e($line->credit > 0 ? 'fw-bold text-success' : 'text-muted'); ?>">
                            <?php echo e($line->credit > 0 ? 'Rp'.number_format($line->credit,0,',','.') : '-'); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr class="table-light fw-bold">
                        <td colspan="2" class="text-end">Total</td>
                        <td class="text-end text-primary">Rp<?php echo e(number_format($entry->lines->sum('debit'),0,',','.')); ?></td>
                        <td class="text-end text-success">Rp<?php echo e(number_format($entry->lines->sum('credit'),0,',','.')); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center text-muted py-5">
            <i class="bi bi-journal-x" style="font-size:36px; display:block; margin-bottom:8px;"></i>
            Belum ada entri jurnal
        </div>
        <?php endif; ?>

        <?php if($entries->hasPages()): ?>
        <div class="d-flex justify-content-center py-3"><?php echo e($entries->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/accounting/journal.blade.php ENDPATH**/ ?>