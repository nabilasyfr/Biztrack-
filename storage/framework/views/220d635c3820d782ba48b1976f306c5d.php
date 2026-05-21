<?php $__env->startSection('title','Laporan Keuangan'); ?>
<?php $__env->startSection('page-title','Laporan Keuangan'); ?>

<?php $__env->startPush('styles'); ?>
<style>
@media print {
    .no-print { display: none !important; }
    #page-content { padding: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; break-inside: avoid; }
}
.laba-box {
    background: linear-gradient(135deg, #0f172a, #1e3a5f);
    border-radius: 16px;
    color: #fff;
    padding: 28px;
}
.income-row { display:flex; justify-content:space-between; align-items:center; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size:14px; }
.income-row:last-child { border-bottom:none; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header no-print">
    <div>
        <h1>Laporan Keuangan</h1>
        <p>Ringkasan laba-rugi bulanan</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline-primary">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>


<div class="card mb-4 no-print">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Bulan</label>
                <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Tampilkan</button>
            </div>
        </form>
    </div>
</div>


<div class="text-center mb-4 d-none d-print-block">
    <h3 class="fw-bold">BizTrack UMKM</h3>
    <h5>Laporan Laba Rugi — <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?></h5>
    <p class="text-muted" style="font-size:12px">Dicetak: <?php echo e(now()->format('d M Y H:i')); ?></p>
</div>

<div class="row g-3 mb-4">
    
    <div class="col-lg-4">
        <div class="laba-box h-100">
            <div style="font-size:12px; color:#94a3b8; text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px;">
                <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?>

            </div>
            <div style="font-size:13px; color:#cbd5e1; margin-bottom:20px;">Ringkasan Laba Rugi</div>

            <div class="d-flex justify-content-between mb-2">
                <span style="color:#94a3b8; font-size:13px">Pendapatan Penjualan</span>
                <span class="fw-bold" style="color:#fff">Rp<?php echo e(number_format($revenue,0,',','.')); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span style="color:#94a3b8; font-size:13px">(-) HPP</span>
                <span class="fw-bold" style="color:#fca5a5">(Rp<?php echo e(number_format($cogs,0,',','.')); ?>)</span>
            </div>
            <div style="border-top:1px solid rgba(255,255,255,.15); margin:10px 0;"></div>
            <div class="d-flex justify-content-between mb-3">
                <span style="color:#e2e8f0; font-size:13px; font-weight:600">Laba Kotor</span>
                <span class="fw-bold" style="color:#86efac">Rp<?php echo e(number_format($grossProfit,0,',','.')); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span style="color:#94a3b8; font-size:13px">(-) Beban Operasional</span>
                <span class="fw-bold" style="color:#fca5a5">(Rp<?php echo e(number_format($totalExpenses,0,',','.')); ?>)</span>
            </div>
            <div style="border-top:1px solid rgba(255,255,255,.15); margin:10px 0;"></div>
            <div class="d-flex justify-content-between align-items-center">
                <span style="font-size:15px; font-weight:700; color:#fff">LABA BERSIH</span>
                <span style="font-size:22px; font-weight:800; color:<?php echo e($netProfit >= 0 ? '#4ade80' : '#f87171'); ?>">
                    Rp<?php echo e(number_format(abs($netProfit),0,',','.')); ?>

                </span>
            </div>
            <?php if($netProfit < 0): ?>
            <div class="text-center mt-2" style="font-size:12px; color:#f87171;">⚠ RUGI</div>
            <?php endif; ?>

            <div style="border-top:1px solid rgba(255,255,255,.1); margin:16px 0 12px;"></div>
            <div class="d-flex justify-content-between" style="font-size:12px; color:#64748b;">
                <span>Margin Kotor</span>
                <span><?php echo e($revenue > 0 ? number_format(($grossProfit/$revenue)*100,1) : 0); ?>%</span>
            </div>
            <div class="d-flex justify-content-between" style="font-size:12px; color:#64748b;">
                <span>Margin Bersih</span>
                <span><?php echo e($revenue > 0 ? number_format(($netProfit/$revenue)*100,1) : 0); ?>%</span>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart-line me-2"></i>Omset Harian — <?php echo e(\Carbon\Carbon::parse($month.'-01')->format('F Y')); ?></div>
            <div class="card-body">
                <canvas id="dailyChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-file-earmark-text me-2"></i>Laporan Laba Rugi</div>
            <div class="card-body">
                <div class="income-row">
                    <span class="text-muted">Pendapatan Penjualan</span>
                    <strong>Rp<?php echo e(number_format($revenue,0,',','.')); ?></strong>
                </div>
                <div class="income-row">
                    <span class="text-muted">Harga Pokok Penjualan (HPP)</span>
                    <strong class="text-danger">(Rp<?php echo e(number_format($cogs,0,',','.')); ?>)</strong>
                </div>
                <div class="income-row" style="background:#f0fdf4; margin:0 -20px; padding:10px 20px; border-radius:8px;">
                    <span class="fw-bold">Laba Kotor</span>
                    <strong class="text-success">Rp<?php echo e(number_format($grossProfit,0,',','.')); ?></strong>
                </div>
                <div class="mt-2 mb-1" style="font-size:12px; font-weight:700; text-transform:uppercase; color:#64748b; letter-spacing:.04em;">Beban Operasional</div>
                <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="income-row" style="font-size:13px;">
                    <span class="text-muted"><?php echo e($exp->name); ?></span>
                    <span class="text-danger">(Rp<?php echo e(number_format($exp->amount,0,',','.')); ?>)</span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="income-row text-muted" style="font-size:13px; font-style:italic;">Tidak ada pengeluaran</div>
                <?php endif; ?>
                <div class="income-row">
                    <span class="fw-semibold text-muted">Total Beban Operasional</span>
                    <strong class="text-danger">(Rp<?php echo e(number_format($totalExpenses,0,',','.')); ?>)</strong>
                </div>
                <div style="background:#<?php echo e($netProfit >= 0 ? 'f0fdf4' : 'fef2f2'); ?>; margin:8px -20px 0; padding:14px 20px; border-radius:8px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold" style="font-size:15px">LABA BERSIH</span>
                        <strong class="<?php echo e($netProfit >= 0 ? 'text-success' : 'text-danger'); ?>" style="font-size:18px">
                            Rp<?php echo e(number_format(abs($netProfit),0,',','.')); ?>

                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-wallet2 me-2"></i>Detail Pengeluaran Bulan Ini</div>
            <div class="card-body p-0">
                <?php if($expenses->count() > 0): ?>
                <table class="table table-hover mb-0" style="font-size:13px">
                    <thead>
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Nama</th>
                            <th class="text-end pe-4">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="ps-4 text-muted"><?php echo e($exp->expense_date->format('d/m')); ?></td>
                            <td><?php echo e($exp->name); ?></td>
                            <td class="text-end pe-4 text-danger fw-semibold">Rp<?php echo e(number_format($exp->amount,0,',','.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="ps-4 fw-bold">Total</td>
                            <td class="text-end pe-4 fw-bold text-danger">Rp<?php echo e(number_format($totalExpenses,0,',','.')); ?></td>
                        </tr>
                    </tfoot>
                </table>
                <?php else: ?>
                <div class="text-center text-muted py-5">
                    <i class="bi bi-wallet" style="font-size:36px; display:block; margin-bottom:8px;"></i>
                    Tidak ada pengeluaran bulan ini
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = Array.from({length: <?php echo e($daysInMonth); ?>}, (_, i) => i + 1);
const data   = [<?php echo e(implode(',', array_values($dailyRevenue))); ?>];
const ctx    = document.getElementById('dailyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Omset (Rp)',
            data,
            backgroundColor: 'rgba(26,86,219,0.7)',
            borderColor: 'rgba(26,86,219,1)',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: v => 'Rp ' + (v/1000).toLocaleString('id-ID') + 'k'
                },
                grid: { color: '#f1f5f9' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\biztrack\resources\views/reports/financial.blade.php ENDPATH**/ ?>