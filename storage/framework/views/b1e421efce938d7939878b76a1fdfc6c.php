<?php $__env->startSection('title','Detail Produk'); ?>
<?php $__env->startSection('page-title','Detail Produk'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1><?php echo e($product->name); ?></h1>
        <p>Detail dan riwayat produk</p>
    </div>
    <div class="d-flex gap-2">
        <?php if(session('biztrack_role')==='owner'): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal">
            <i class="bi bi-pencil me-1"></i> Edit Produk
        </button>
        <?php endif; ?>
        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>


<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>


<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">KODE</div>
            <code style="font-size:16px; font-weight:700"><?php echo e($product->code); ?></code>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">STOK</div>
            <div style="font-size:24px; font-weight:800; color:<?php echo e($product->stock == 0 ? '#dc2626' : ($product->isLowStock() ? '#ea580c' : '#16a34a')); ?>">
                <?php echo e($product->stock); ?>

            </div>
            <?php if($product->stock == 0): ?>
                <small class="text-danger">Habis!</small>
            <?php elseif($product->isLowStock()): ?>
                <small class="text-warning">Stok Rendah</small>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">HARGA BELI</div>
            <div style="font-size:16px; font-weight:800; color:#7c3aed">Rp<?php echo e(number_format($product->cost_price,0,',','.')); ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">HARGA JUAL</div>
            <div style="font-size:16px; font-weight:800">Rp<?php echo e(number_format($product->selling_price,0,',','.')); ?></div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">MARGIN</div>
            <div style="font-size:18px; font-weight:800; color:#0891b2">
                <?php echo e(number_format($product->profit_margin, 1)); ?>%
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">TOTAL NILAI STOK</div>
            <div style="font-size:14px; font-weight:800; color:#b45309">
                Rp<?php echo e(number_format($product->stock * $product->cost_price, 0, ',', '.')); ?>

            </div>
            <small class="text-muted" style="font-size:10px">stok × harga beli</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">TOTAL TERJUAL</div>
            <div style="font-size:24px; font-weight:800; color:#0f766e"><?php echo e($totalSold); ?></div>
            <small class="text-muted" style="font-size:10px">unit</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div class="text-muted mb-1" style="font-size:12px">KATEGORI</div>
            <span class="badge bg-light text-dark" style="font-size:13px"><?php echo e($product->category); ?></span>
            <?php if($product->supplier): ?>
            <div class="text-muted mt-1" style="font-size:11px"><?php echo e($product->supplier); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>


<ul class="nav nav-tabs mb-3" id="productTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" id="tab-sales-btn" data-bs-toggle="tab" data-bs-target="#tab-sales" type="button">
            <i class="bi bi-receipt me-1"></i>Riwayat Penjualan
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="tab-log-btn" data-bs-toggle="tab" data-bs-target="#tab-log" type="button">
            <i class="bi bi-arrow-left-right me-1"></i>Log Inventori
        </button>
    </li>
</ul>

<div class="tab-content">
    
    <div class="tab-pane fade show active" id="tab-sales">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Riwayat Transaksi Penjualan (20 terakhir)</span>
                <span class="badge bg-primary"><?php echo e($totalSold); ?> unit terjual</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Invoice</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Laba Item</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $saleTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4 text-muted" style="font-size:12px">
                                    <?php echo e($item->sale->created_at->format('d/m/Y H:i')); ?>

                                </td>
                                <td>
                                    <code style="font-size:11px"><?php echo e($item->sale->invoice_number); ?></code>
                                </td>
                                <td class="text-center fw-bold"><?php echo e($item->quantity); ?></td>
                                <td class="text-end">Rp<?php echo e(number_format($item->unit_price,0,',','.')); ?></td>
                                <td class="text-end fw-semibold">Rp<?php echo e(number_format($item->subtotal,0,',','.')); ?></td>
                                <td class="text-end text-success fw-semibold">
                                    Rp<?php echo e(number_format($item->getProfit(),0,',','.')); ?>

                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox me-2"></i>Belum ada transaksi penjualan
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if($saleTransactions->count() > 0): ?>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="ps-4 fw-semibold">Total</td>
                                <td class="text-center fw-bold"><?php echo e($saleTransactions->sum('quantity')); ?></td>
                                <td></td>
                                <td class="text-end fw-bold">Rp<?php echo e(number_format($saleTransactions->sum('subtotal'),0,',','.')); ?></td>
                                <td class="text-end fw-bold text-success">Rp<?php echo e(number_format($saleTransactions->sum(fn($i) => $i->getProfit()),0,',','.')); ?></td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="tab-pane fade" id="tab-log">
        <div class="card">
            <div class="card-header"><i class="bi bi-arrow-left-right me-2"></i>Log Pergerakan Stok (20 terakhir)</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Waktu</th>
                                <th>Tipe</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Sebelum</th>
                                <th class="text-center">Sesudah</th>
                                <th>Referensi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4 text-muted" style="font-size:12px"><?php echo e($log->created_at->format('d/m/y H:i')); ?></td>
                                <td>
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
                                <td class="text-center fw-semibold"><?php echo e($log->stock_after); ?></td>
                                <td><code style="font-size:11px"><?php echo e($log->reference); ?></code></td>
                                <td class="text-muted" style="font-size:12px"><?php echo e($log->notes); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if(session('biztrack_role')==='owner'): ?>
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">
                    <i class="bi bi-pencil me-2"></i>Edit Produk — <?php echo e($product->name); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('products.update', $product)); ?>" id="editProductForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Produk <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" value="<?php echo e($product->code); ?>" required maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="<?php echo e($product->name); ?>" required maxlength="200">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="category" class="form-control" value="<?php echo e($product->category); ?>" required maxlength="100" list="categoryList">
                            <datalist id="categoryList">
                                <?php $cats = \App\Models\Product::distinct()->pluck('category')->filter()->sort() ?>
                                <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat); ?>">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </datalist>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplier</label>
                            <input type="text" name="supplier" class="form-control" value="<?php echo e($product->supplier); ?>" maxlength="200">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="cost_price" class="form-control" value="<?php echo e($product->cost_price); ?>" required min="0" step="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="selling_price" class="form-control" value="<?php echo e($product->selling_price); ?>" required min="0" step="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control" value="<?php echo e($product->stock); ?>" required min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" name="min_stock" class="form-control" value="<?php echo e($product->min_stock); ?>" required min="0">
                            <div class="form-text">Alert stok rendah jika stok ≤ nilai ini</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/products/show.blade.php ENDPATH**/ ?>