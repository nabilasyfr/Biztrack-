<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — BizTrack UMKM</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1a56db;
            --primary-dark: #1342b0;
            --secondary: #0ea5e9;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --topbar-height: 64px;
            --body-bg: #f1f5f9;
            --card-radius: 12px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--body-bg);
            color: #1e293b;
            margin: 0;
        }

        /* ── Sidebar ── */
        #sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1040;
            overflow-y: auto;
            transition: transform .3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-brand .brand-logo {
            display: flex; align-items: center; gap: 10px;
        }

        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
        }

        .sidebar-brand .brand-name {
            color: #fff;
            font-weight: 800;
            font-size: 16px;
            line-height: 1.2;
        }

        .sidebar-brand .brand-tagline {
            color: #94a3b8;
            font-size: 10px;
            font-weight: 400;
        }

        .sidebar-nav { padding: 12px 0; flex: 1; }

        .nav-section-label {
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 12px 20px 6px;
        }

        .sidebar-nav .nav-link {
            display: flex; align-items: center; gap: 10px;
            color: #94a3b8;
            padding: 10px 20px;
            border-radius: 0;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s;
            position: relative;
        }

        .sidebar-nav .nav-link i { font-size: 16px; width: 20px; flex-shrink: 0; }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: #e2e8f0;
        }

        .sidebar-nav .nav-link.active {
            background: rgba(26,86,219,.15);
            color: #60a5fa;
            border-right: 3px solid #60a5fa;
        }

        .sidebar-nav .nav-link.active i { color: #60a5fa; }

        .sidebar-footer {
            padding: 12px 20px 20px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .user-info {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            background: var(--sidebar-hover);
            border-radius: 10px;
        }

        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 13px;
            flex-shrink: 0;
        }

        .user-name { color: #e2e8f0; font-size: 13px; font-weight: 600; }
        .user-role { color: #64748b; font-size: 11px; }

        /* ── Main wrapper ── */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex; flex-direction: column;
            transition: margin .3s ease;
        }

        /* ── Topbar ── */
        #topbar {
            position: sticky; top: 0; z-index: 1030;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center;
            padding: 0 24px;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
        }

        .topbar-toggle {
            display: none;
            background: none; border: none;
            font-size: 20px; color: #64748b;
            cursor: pointer; padding: 6px;
        }

        .topbar-title {
            font-size: 16px; font-weight: 700; color: #0f172a;
            flex: 1;
        }

        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-badge {
            position: relative; cursor: pointer;
        }

        .topbar-badge .badge-dot {
            position: absolute; top: 2px; right: 2px;
            width: 8px; height: 8px;
            background: var(--danger); border-radius: 50%;
            border: 2px solid #fff;
        }

        /* ── Page content ── */
        #page-content { flex: 1; padding: 24px; }

        /* ── Cards ── */
        .card {
            border: none;
            border-radius: var(--card-radius);
            box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            border-radius: var(--card-radius) var(--card-radius) 0 0 !important;
            padding: 16px 20px;
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 20px;
            display: flex; align-items: center; gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-label { font-size: 12px; color: #64748b; font-weight: 500; margin-bottom: 2px; }
        .stat-value { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1; }
        .stat-sub   { font-size: 11px; color: #94a3b8; margin-top: 4px; }

        /* ── Tables ── */
        .table { font-size: 13.5px; }
        .table th { font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; color: #64748b; background: #f8fafc; }
        .table-hover tbody tr:hover { background: #f8fafc; }

        /* ── Buttons ── */
        .btn { font-weight: 600; font-size: 13px; border-radius: 8px; }
        .btn-sm { font-size: 12px; border-radius: 6px; }

        /* ── Badges ── */
        .badge { font-weight: 600; font-size: 11px; border-radius: 6px; }

        /* ── Forms ── */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #e2e8f0;
            font-size: 13.5px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,86,219,.12);
        }

        .form-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }

        /* ── Alert ── */
        .alert { border-radius: 10px; font-size: 13.5px; }

        /* ── Page header ── */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;
        }

        .page-header p { color: #64748b; font-size: 13px; margin: 2px 0 0; }

        /* ── Low stock badge ── */
        .low-stock { color: var(--danger); font-weight: 700; }

        /* ── Payment method badges ── */
        .badge-cash     { background: #dcfce7; color: #16a34a; }
        .badge-dana     { background: #e0f2fe; color: #0369a1; }
        .badge-qris     { background: #fef3c7; color: #d97706; }
        .badge-transfer { background: #ede9fe; color: #7c3aed; }

        /* ── Overlay (mobile) ── */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 1039;
        }

        /* ── Responsive ── */
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main-wrapper { margin-left: 0; }
            .topbar-toggle { display: block; }
            #sidebar-overlay.show { display: block; }
        }

        /* ── Print ── */
        @media print {
            #sidebar, #topbar, .no-print { display: none !important; }
            #main-wrapper { margin: 0; }
            #page-content { padding: 0; }
        }

        /* ── Scrollbar ── */
        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon"><i class="bi bi-shop"></i></div>
            <div>
                <div class="brand-name">BizTrack UMKM</div>
                <div class="brand-tagline">Smart Retail & Accounting</div>
            </div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <div class="nav-section-label">Penjualan</div>
        <a href="<?php echo e(route('pos.index')); ?>" class="nav-link <?php echo e(request()->routeIs('pos.*') ? 'active' : ''); ?>">
            <i class="bi bi-upc-scan"></i> Kasir / POS
        </a>
        <a href="<?php echo e(route('sales.index')); ?>" class="nav-link <?php echo e(request()->routeIs('sales.*') ? 'active' : ''); ?>">
            <i class="bi bi-receipt"></i> Riwayat Penjualan
        </a>

        <div class="nav-section-label">Inventori</div>
        <a href="<?php echo e(route('products.index')); ?>" class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">
            <i class="bi bi-box-seam"></i> Produk
        </a>
        <a href="<?php echo e(route('inventory.log')); ?>" class="nav-link <?php echo e(request()->routeIs('inventory.*') ? 'active' : ''); ?>">
            <i class="bi bi-arrow-left-right"></i> Log Inventori
        </a>

        <?php if(session('biztrack_role') === 'owner'): ?>
        <div class="nav-section-label">Keuangan</div>
        <a href="<?php echo e(route('accounting.coa')); ?>" class="nav-link <?php echo e(request()->routeIs('accounting.coa') ? 'active' : ''); ?>">
            <i class="bi bi-list-columns-reverse"></i> Bagan Akun (CoA)
        </a>
        <a href="<?php echo e(route('accounting.journal')); ?>" class="nav-link <?php echo e(request()->routeIs('accounting.journal') ? 'active' : ''); ?>">
            <i class="bi bi-journal-text"></i> Jurnal Umum
        </a>
        <a href="<?php echo e(route('accounting.ledger')); ?>" class="nav-link <?php echo e(request()->routeIs('accounting.ledger') ? 'active' : ''); ?>">
            <i class="bi bi-book"></i> Buku Besar
        </a>
        <a href="<?php echo e(route('accounting.modal')); ?>" class="nav-link <?php echo e(request()->routeIs('accounting.modal*') ? 'active' : ''); ?>">
            <i class="bi bi-pencil-square"></i> Input Modal / Jurnal
        </a>
        <a href="<?php echo e(route('accounting.adjusting')); ?>" class="nav-link <?php echo e(request()->routeIs('accounting.adjusting') ? 'active' : ''); ?>">
            <i class="bi bi-wrench"></i> Jurnal Penyesuaian
        </a>
        <a href="<?php echo e(route('expenses.index')); ?>" class="nav-link <?php echo e(request()->routeIs('expenses.*') ? 'active' : ''); ?>">
            <i class="bi bi-wallet2"></i> Pengeluaran
        </a>

        <div class="nav-section-label">Laporan</div>
        <a href="<?php echo e(route('accounting.trial-balance')); ?>" class="nav-link <?php echo e(request()->routeIs('accounting.trial-balance') ? 'active' : ''); ?>">
            <i class="bi bi-table"></i> Neraca Saldo
        </a>
        <a href="<?php echo e(route('accounting.worksheet')); ?>" class="nav-link <?php echo e(request()->routeIs('accounting.worksheet') ? 'active' : ''); ?>">
            <i class="bi bi-grid-3x3-gap"></i> Kertas Kerja
        </a>
        <a href="<?php echo e(route('accounting.income-statement')); ?>" class="nav-link <?php echo e(request()->routeIs('accounting.income-statement') ? 'active' : ''); ?>">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan Laba Rugi
        </a>
        <a href="<?php echo e(route('accounting.balance-sheet')); ?>" class="nav-link <?php echo e(request()->routeIs('accounting.balance-sheet') ? 'active' : ''); ?>">
            <i class="bi bi-bank2"></i> Neraca Keuangan
        </a>

        <div class="nav-section-label">Laporan Bisnis</div>
        <a href="<?php echo e(route('reports.sales')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.sales') ? 'active' : ''); ?>">
            <i class="bi bi-bar-chart-line"></i> Laporan Penjualan
        </a>
        <a href="<?php echo e(route('reports.inventory')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.inventory') ? 'active' : ''); ?>">
            <i class="bi bi-clipboard-data"></i> Laporan Inventori
        </a>
        <a href="<?php echo e(route('reports.financial')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.financial') ? 'active' : ''); ?>">
            <i class="bi bi-graph-up-arrow"></i> Laporan Keuangan
        </a>
        <?php endif; ?>
    </div>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar"><?php echo e(strtoupper(substr(session('biztrack_name','?'), 0, 1))); ?></div>
            <div class="flex-fill overflow-hidden">
                <div class="user-name text-truncate"><?php echo e(session('biztrack_name')); ?></div>
                <div class="user-role"><?php echo e(session('biztrack_role') === 'owner' ? 'Owner' : 'Kasir'); ?></div>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-sm btn-link text-secondary p-1" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<div id="sidebar-overlay" onclick="closeSidebar()"></div>


<div id="main-wrapper">
    
    <div id="topbar">
        <button class="topbar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></div>
        <div class="topbar-right">
            <?php $lowStockCount = \App\Models\Product::whereColumn('stock','<=','min_stock')->count(); ?>
            <?php if($lowStockCount > 0): ?>
            <a href="<?php echo e(route('products.index')); ?>?filter=low" class="btn btn-sm btn-outline-danger topbar-badge" title="<?php echo e($lowStockCount); ?> produk stok rendah">
                <i class="bi bi-exclamation-triangle"></i> <?php echo e($lowStockCount); ?> Stok Rendah
            </a>
            <?php endif; ?>
            <span class="text-muted small d-none d-md-inline"><?php echo e(now()->format('d M Y')); ?></span>
        </div>
    </div>

    
    <div id="page-content">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Ada kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($e); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebar-overlay').classList.toggle('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('show');
}
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\biztrack\resources\views/layouts/app.blade.php ENDPATH**/ ?>