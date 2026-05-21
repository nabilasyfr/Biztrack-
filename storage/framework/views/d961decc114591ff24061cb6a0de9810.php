<?php $__env->startSection('title','Laporan Penjualan'); ?>
<?php $__env->startSection('page-title','Laporan Penjualan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
@media print {
    .no-print { display: none !important; }
    #page-content { padding: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header no-print">
    <div>
        <h1>Laporan Penjualan</h1>
        <p>Analisis pendapatan harian & bulanan</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline-primary">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>


<div class="card mb-3 no-print">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Periode</label>
                <select name="period" class="form-select" onchange="this.form.submit()">
                    <option value="monthly" <?php echo e($period==='monthly'?'selected':''); ?>>Bulanan</option>
                    <option value="daily"   <?php echo e($period==='daily'?'selected':''); ?>>Harian</option>
                </select>
            </div>
            <?php if($period === 'daily'): ?>
            <div class="col-md-3">
                <label class="form-label mb-1">Tanggal</label>
                <input type="date" name="date" class="form-control" value="<?php echo e($date); ?>">
            </div>
            <?php else: ?>
            <div class="col-md-3">
                <label class="form-label mb-1">Bulan</label>
                <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>">
            </div>
            <?php endif; ?>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>


<div class="text-center mb-3 d-none d-print-block">
    <h3 class="fw-bold">BizTrack UMKM — Laporan Penjualan</h3>
    <p class="text-muted"><?php echo e($title); ?></p>
    <p class="text-muted" style="font-size:12px">Dicetak: <?php echo e(now()->format('d M Y H:i')); ?></p>
</div>


<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;color:#1a56db;"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="stat-label">Total Omset</div>
                <div class="stat-value" style="font-size:16px">Rp<?php echo e(number_format($totalRevenue,0,',','.')); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed;color:#ea580c;"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="stat-label">HPP (Modal)</div>
                <div class="stat-value" style="font-size:16px">Rp<?php echo e(number_format($totalCogs,0,',','.')); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-graph-up-arrow"></i></div>
            <div>
                <div class="stat-label">Laba Kotor</div>
                <div class="stat-value" style="font-size:16px; color:#16a34a">Rp<?php echo e(number_format($totalProfit,0,',','.')); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="bi bi-receipt"></i></div>
            <div>
                <div class="stat-label">Jumlah Transaksi</div>
                <div class="stat-value" style="font-size:20px"><?php echo e($sales->count()); ?></div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-pie-chart me-2"></i>Pendapatan per Metode Bayar</div>
            <div class="card-body">
                <?php
                $pmLabels = ['cash'=>'Tunai','dana'=>'Dana','qris'=>'QRIS','transfer'=>'Transfer Bank'];
                $pmColors = ['cash'=>'success','dana'=>'info','qris'=>'warning','transfer'=>'primary'];
                ?>
                <?php $__currentLoopData = $byPayment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold" style="font-size:13px"><?php echo e($pmLabels[$pm] ?? $pm); ?></span>
                        <span class="fw-bold" style="font-size:13px">Rp<?php echo e(number_format($amount,0,',','.')); ?></span>
                    </div>
                    <div class="progress" style="height:8px; border-radius:6px;">
                        <div class="progress-bar bg-<?php echo e($pmColors[$pm] ?? 'secondary'); ?>" style="width:<?php echo e($totalRevenue > 0 ? ($amount/$totalRevenue*100) : 0); ?>%; border-radius:6px;"></div>
                    </div>
                    <div class="text-muted mt-1" style="font-size:11px">
                        <?php echo e($totalRevenue > 0 ? number_format($amount/$totalRevenue*100,1) : 0); ?>% dari total
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($byPayment->isEmpty()): ?>
                <p class="text-muted text-center py-3 mb-0">Tidak ada data</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart me-2"></i>Ringkasan Profit</div>
            <div class="card-body">
                <table class="table table-sm mb-0" style="font-size:13.5px">
                    <tr>
                        <td class="text-muted">Total Penjualan (Omset)</td>
                        <td class="text-end fw-bold">Rp<?php echo e(number_format($totalRevenue,0,',','.')); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">(-) Harga Pokok Penjualan (HPP)</td>
                        <td class="text-end fw-bold text-danger">(Rp<?php echo e(number_format($totalCogs,0,',','.')); ?>)</td>
                    </tr>
                    <tr class="table-light">
                        <td class="fw-bold">= Laba Kotor</td>
                        <td class="text-end fw-bold text-success" style="font-size:16px">Rp<?php echo e(number_format($totalProfit,0,',','.')); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pt-2 text-muted" style="font-size:12px">
                            Margin Kotor:
                            <strong><?php echo e($totalRevenue > 0 ? number_format(($totalProfit/$totalRevenue)*100,1) : 0); ?>%</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header"><i class="bi bi-table me-2"></i><?php echo e($title); ?></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px">
                <thead>
                    <tr>
                        <th class="ps-4">Invoice</th>
                        <th>Waktu</th>
                        <th>Item</th>
                        <th class="text-center">Pembayaran</th>
                        <th class="text-end">Omset</th>
                        <th class="text-end">HPP</th>
                        <th class="text-end pe-4">Laba</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $grandRevenue = 0; $grandCogs = 0; $grandProfit = 0; ?>
                    <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $saleRevenue = $sale->total_amount;
                        $saleCogs = $sale->items->sum(fn($i) => $i->cost_price * $i->quantity);
                        $saleProfit = $saleRevenue - $saleCogs;
                        $grandRevenue += $saleRevenue;
                        $grandCogs    += $saleCogs;
                        $grandProfit  += $saleProfit;
                    ?>
                    <tr>
                        <td class="ps-4 fw-semibold text-primary"><?php echo e($sale->invoice_number); ?></td>
                        <td class="text-muted"><?php echo e($sale->created_at->format('d/m H:i')); ?></td>
                        <td><?php echo e($sale->items->sum('quantity')); ?> item</td>
                        <td class="text-center">
                            <span class="badge badge-<?php echo e($sale->payment_method); ?>"><?php echo e(strtoupper($sale->payment_method)); ?></span>
                        </td>
                        <td class="text-end">Rp<?php echo e(number_format($saleRevenue,0,',','.')); ?></td>
                        <td class="text-end text-muted">Rp<?php echo e(number_format($saleCogs,0,',','.')); ?></td>
                        <td class="text-end pe-4 fw-bold text-success">Rp<?php echo e(number_format($saleProfit,0,',','.')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-muted py-5">Tidak ada transaksi pada periode ini</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if($sales->count() > 0): ?>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="4" class="ps-4 fw-bold">TOTAL (<?php echo e($sales->count()); ?> transaksi)</td>
                        <td class="text-end fw-bold">Rp<?php echo e(number_format($grandRevenue,0,',','.')); ?></td>
                        <td class="text-end fw-bold">Rp<?php echo e(number_format($grandCogs,0,',','.')); ?></td>
                        <td class="text-end pe-4 fw-bold text-success">Rp<?php echo e(number_format($grandProfit,0,',','.')); ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/reports/sales.blade.php ENDPATH**/ ?>