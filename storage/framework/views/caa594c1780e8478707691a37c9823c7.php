
<?php $__env->startSection('title','Manajemen Kategori'); ?>
<?php $__env->startSection('page-title','Manajemen Kategori'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Manajemen Kategori</h1>
        <p>Kelola kategori produk inventori</p>
    </div>
    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Produk
    </a>
</div>


<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if(session('info')): ?>
<div class="alert alert-info alert-dismissible fade show">
    <i class="bi bi-info-circle me-2"></i><?php echo e(session('info')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-3">
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-tags me-2"></i>Daftar Kategori (<?php echo e(count($categoryStats)); ?>)</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Nama Kategori</th>
                                <th class="text-center">Jumlah Produk</th>
                                <th class="text-center">Total Stok</th>
                                <th class="text-center">Total Terjual</th>
                                <?php if(session('biztrack_role')==='owner'): ?>
                                <th class="text-center pe-4">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $categoryStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    <i class="bi bi-tag me-2 text-primary"></i><?php echo e($cat['name']); ?>

                                </td>
                                <td class="text-center"><?php echo e($cat['total']); ?></td>
                                <td class="text-center fw-semibold"><?php echo e($cat['total_stock']); ?></td>
                                <td class="text-center text-success fw-semibold"><?php echo e($cat['total_sold']); ?></td>
                                <?php if(session('biztrack_role')==='owner'): ?>
                                <td class="text-center pe-4">
                                    <button class="btn btn-sm btn-outline-primary me-1"
                                        onclick="openEdit('<?php echo e(addslashes($cat['name'])); ?>')"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php if($cat['total'] == 0): ?>
                                    <form method="POST" action="<?php echo e(route('categories.destroy')); ?>" class="d-inline"
                                        onsubmit="return confirm('Hapus kategori <?php echo e($cat['name']); ?>?')">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="name" value="<?php echo e($cat['name']); ?>">
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <button class="btn btn-sm btn-outline-danger" disabled title="Masih ada produk">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox me-2"></i>Belum ada kategori
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(session('biztrack_role')==='owner'): ?>
    <div class="col-md-4">
        
        <div class="card mb-3" id="editCard" style="display:none!important">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Nama Kategori</div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('categories.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="old_name" id="editOldName">
                    <div class="mb-3">
                        <label class="form-label">Nama Lama</label>
                        <input type="text" id="editOldDisplay" class="form-control" readonly style="background:#f8fafc">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Baru <span class="text-danger">*</span></label>
                        <input type="text" name="new_name" id="editNewName" class="form-control" required maxlength="100">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="closeEdit()">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="card" id="infoCard">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Info</div>
            <div class="card-body" style="font-size:13px; color:#64748b">
                <p class="mb-2">Kategori diambil dari data produk yang sudah ada.</p>
                <p class="mb-2">Kategori baru otomatis muncul saat kamu menambah produk dengan kategori baru.</p>
                <p class="mb-0">Kategori hanya bisa dihapus jika tidak ada produk yang menggunakannya.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openEdit(name) {
    document.getElementById('editOldName').value = name;
    document.getElementById('editOldDisplay').value = name;
    document.getElementById('editNewName').value = name;
    document.getElementById('editCard').style.display = 'block';
    document.getElementById('infoCard').style.display = 'none';
    document.getElementById('editNewName').focus();
}
function closeEdit() {
    document.getElementById('editCard').style.display = 'none';
    document.getElementById('infoCard').style.display = 'block';
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/categories/index.blade.php ENDPATH**/ ?>