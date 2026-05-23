<?php $__env->startSection('title','Edit Produk'); ?>
<?php $__env->startSection('page-title','Edit Produk'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Edit Produk</h1>
        <p>Perbarui informasi produk: <strong><?php echo e($product->name); ?></strong></p>
    </div>
    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Produk</div>
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('products.update', $product)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Produk <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('code', $product->code)); ?>" required>
                            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('name', $product->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="category" class="form-control" value="<?php echo e(old('category', $product->category)); ?>"
                                   list="cat-list" required>
                            <datalist id="cat-list">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c); ?>"><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </datalist>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Supplier</label>
                            <input type="text" name="supplier" class="form-control" value="<?php echo e(old('supplier', $product->supplier)); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Beli (HPP)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="cost_price" class="form-control"
                                       value="<?php echo e(old('cost_price', $product->cost_price)); ?>" min="0" step="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="selling_price" class="form-control"
                                       value="<?php echo e(old('selling_price', $product->selling_price)); ?>" min="0" step="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok Saat Ini</label>
                            <input type="number" name="stock" class="form-control"
                                   value="<?php echo e(old('stock', $product->stock)); ?>" min="0">
                            <div class="form-text">Mengubah stok akan membuat log penyesuaian otomatis</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stok Minimum</label>
                            <input type="number" name="min_stock" class="form-control"
                                   value="<?php echo e(old('min_stock', $product->min_stock)); ?>" min="0">
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Perbarui Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/products/edit.blade.php ENDPATH**/ ?>