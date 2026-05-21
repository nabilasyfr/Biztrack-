<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.stat-icon.blue   { background: #eff6ff; color: #1a56db; }
.stat-icon.green  { background: #f0fdf4; color: #16a34a; }
.stat-icon.orange { background: #fff7ed; color: #ea580c; }
.stat-icon.red    { background: #fef2f2; color: #dc2626; }
.stat-icon.purple { background: #f5f3ff; color: #7c3aed; }
.stat-icon.cyan   { background: #ecfeff; color: #0891b2; }
.top-product-item { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
.top-product-item:last-child { border-bottom:none; }
.top-rank { width:24px; height:24px; border-radius:6px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#64748b; flex-shrink:0; }
.top-rank.gold { background:#fef3c7; color:#d97706; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang, <?php echo e(session('biztrack_name')); ?>! Ini ringkasan bisnis Anda.</p>
    </div>
    <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-primary">
        <i class="bi bi-upc-scan me-1"></i> Buka Kasir
    </a>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-label">Penjualan Hari Ini</div>
                <div class="stat-value">Rp<?php echo e(number_format($salesToday,0,',','.')); ?></div>
                <div class="stat-sub"><?php echo e($transactionsToday); ?> transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-graph-up"></i></div>
            <div>
                <div class="stat-label">Omset Bulan Ini</div>
                <div class="stat-value">Rp<?php echo e(number_format($monthlyRevenue,0,',','.')); ?></div>
                <div class="stat-sub"><?php echo e(now()->format('F Y')); ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon <?php echo e($monthlyProfit >= 0 ? 'cyan' : 'red'); ?>">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <div class="stat-label">Laba Bersih Bulan Ini</div>
                <div class="stat-value" style="color:<?php echo e($monthlyProfit >= 0 ? '#0891b2' : '#dc2626'); ?>">
                    Rp<?php echo e(number_format(abs($monthlyProfit),0,',','.')); ?>

                </div>
                <div class="stat-sub">Setelah HPP + pengeluaran</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon <?php echo e($lowStockProducts->count() > 0 ? 'red' : 'green'); ?>">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <div class="stat-label">Stok Rendah</div>
                <div class="stat-value" style="color:<?php echo e($lowStockProducts->count() > 0 ? '#dc2626' : '#16a34a'); ?>">
                    <?php echo e($lowStockProducts->count()); ?>

                </div>
                <div class="stat-sub">dari <?php echo e($totalProducts); ?> produk</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Transaksi Terbaru</span>
                <a href="<?php echo e(route('sales.index')); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No. Invoice</th>
                                <th>Waktu</th>
                                <th>Item</th>
                                <th>Pembayaran</th>
                                <th class="text-end pe-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-4">
                                    <a href="<?php echo e(route('sales.show', $sale)); ?>" class="text-decoration-none fw-semibold text-primary">
                                        <?php echo e($sale->invoice_number); ?>

                                    </a>
                                </td>
                                <td class="text-muted"><?php echo e($sale->created_at->format('d/m H:i')); ?></td>
                                <td><?php echo e($sale->items->sum('quantity')); ?> item</td>
                                <td>
                                    <?php $pm = $sale->payment_method; ?>
                                    <span class="badge badge-<?php echo e($pm); ?>"><?php echo e(strtoupper($pm)); ?></span>
                                </td>
                                <td class="text-end pe-4 fw-bold">Rp<?php echo e(number_format($sale->total_amount,0,',','.')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-trophy me-2"></i>Produk Terlaris</div>
            <div class="card-body py-2">
                <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="top-product-item">
                    <div class="top-rank <?php echo e($i === 0 ? 'gold' : ''); ?>"><?php echo e($i+1); ?></div>
                    <div class="flex-fill overflow-hidden">
                        <div class="fw-semibold text-truncate" style="font-size:13px"><?php echo e($tp->product->name ?? '-'); ?></div>
                        <div class="text-muted" style="font-size:11px"><?php echo e($tp->total_qty); ?> terjual</div>
                    </div>
                    <div class="fw-bold text-primary" style="font-size:12px">
                        Rp<?php echo e(number_format($tp->total_revenue,0,',','.')); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center py-3 mb-0" style="font-size:13px">Belum ada data</p>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($lowStockProducts->count() > 0): ?>
        <div class="card border-danger" style="border: 1.5px solid #fca5a5 !important;">
            <div class="card-header text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Peringatan Stok Rendah</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php $__currentLoopData = $lowStockProducts->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3" style="font-size:13px">
                        <span class="text-truncate me-2"><?php echo e($p->name); ?></span>
                        <span class="badge bg-danger"><?php echo e($p->stock); ?> tersisa</span>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php if($lowStockProducts->count() > 5): ?>
                <div class="text-center py-2">
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-sm btn-outline-danger">
                        +<?php echo e($lowStockProducts->count() - 5); ?> lainnya
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/dashboard/index.blade.php ENDPATH**/ ?>