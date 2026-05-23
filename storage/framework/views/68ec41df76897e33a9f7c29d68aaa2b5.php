<?php $__env->startSection('title','Bagan Akun'); ?>
<?php $__env->startSection('page-title','Bagan Akun (Chart of Accounts)'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Bagan Akun (CoA)</h1>
        <p>Daftar semua akun dalam sistem akuntansi</p>
    </div>
</div>

<?php
$typeLabels = [
    'asset'     => ['Aset', 'primary', 'bi-bank'],
    'liability' => ['Kewajiban', 'warning', 'bi-exclamation-circle'],
    'equity'    => ['Modal/Ekuitas', 'success', 'bi-person-circle'],
    'revenue'   => ['Pendapatan', 'info', 'bi-graph-up'],
    'expense'   => ['Beban', 'danger', 'bi-wallet2'],
];
?>

<div class="row g-3">
    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php [$label, $color, $icon] = $typeLabels[$type] ?? [$type, 'secondary', 'bi-circle']; ?>
    <div class="col-12">
        <div class="card">
            <div class="card-header text-<?php echo e($color); ?>">
                <i class="bi <?php echo e($icon); ?> me-2"></i><?php echo e($label); ?>

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
                        <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="ps-4"><code class="fw-bold"><?php echo e($acc->code); ?></code></td>
                            <td class="fw-semibold"><?php echo e($acc->name); ?></td>
                            <td class="text-muted" style="font-size:12px"><?php echo e($acc->description); ?></td>
                            <
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/accounting/coa.blade.php ENDPATH**/ ?>