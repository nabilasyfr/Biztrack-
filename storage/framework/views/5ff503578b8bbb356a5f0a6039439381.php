<?php $__env->startSection('title','Produk'); ?>
<?php $__env->startSection('page-title','Produk & Inventori'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Produk</h1>
        <p>Kelola semua produk dan stok barang</p>
    </div>
    <?php if(session('biztrack_role')==='owner'): ?>
    <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </a>
    <?php endif; ?>
</div>


<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label mb-1">Cari Produk</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, kode, atau kategori..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e(request('category')===$cat?'selected':''); ?>><?php echo e($cat); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Cari</button>
            </div>
            <div class="col-md-2">
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-box-seam me-2"></i>Daftar Produk (<?php echo e($products->total()); ?>)</span>
        <a href="<?php echo e(route('inventory.log')); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left-right me-1"></i>Log Inventori
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">H. Beli</th>
                        <th class="text-end">H. Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min.</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4"><code style="font-size:12px"><?php echo e($p->code); ?></code></td>
                        <td>
                            <a href="<?php echo e(route('products.show',$p)); ?>" class="text-decoration-none fw-semibold"><?php echo e($p->name); ?></a>
                            <?php if($p->supplier): ?><div class="text-muted" style="font-size:11px"><?php echo e($p->supplier); ?></div><?php endif; ?>
                        </td>
                        <td><span class="badge bg-light text-dark"><?php echo e($p->category); ?></span></td>
                        <td class="text-end">Rp<?php echo e(number_format($p->cost_price,0,',','.')); ?></td>
                        <td class="text-end fw-semibold">Rp<?php echo e(number_format($p->selling_price,0,',','.')); ?></td>
                        <td class="text-center">
                            <?php if($p->isLowStock()): ?>
                                <span class="badge bg-danger"><?php echo e($p->stock); ?></span>
                            <?php else: ?>
                                <span class="fw-semibold"><?php echo e($p->stock); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center text-muted"><?php echo e($p->min_stock); ?></td>
                        <td class="text-center pe-4">
                            <?php if(session('biztrack_role')==='owner'): ?>
                            <a href="<?php echo e(route('products.edit',$p)); ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('products.destroy',$p)); ?>" class="d-inline"
                                  onsubmit="return confirm('Hapus produk <?php echo e($p->name); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            <?php else: ?>
                            <a href="<?php echo e(route('products.show',$p)); ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center text-muted py-5">Tidak ada produk ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($products->hasPages()): ?>
        <div class="d-flex justify-content-center py-3">
            <?php echo e($products->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/products/index.blade.php ENDPATH**/ ?>