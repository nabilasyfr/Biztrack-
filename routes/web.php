<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;

// Auth routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth.biztrack'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::get('/inventory/log', [ProductController::class, 'inventoryLog'])->name('inventory.log');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories/destroy', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/pos', [SaleController::class, 'pos'])->name('pos.index');
    Route::post('/pos/checkout', [SaleController::class, 'checkout'])->name('pos.checkout');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');

    Route::middleware(['role.owner'])->group(function () {
        // Akuntansi Dasar
        Route::get('/accounting/coa',     [AccountingController::class, 'coa'])->name('accounting.coa');
        Route::get('/accounting/journal', [AccountingController::class, 'journal'])->name('accounting.journal');
        Route::get('/accounting/ledger',  [AccountingController::class, 'ledger'])->name('accounting.ledger');
        Route::get('/accounting/modal',   [AccountingController::class, 'modalForm'])->name('accounting.modal');
        Route::post('/accounting/modal',  [AccountingController::class, 'modalStore'])->name('accounting.modal.store');

        // AIS Lengkap
        Route::get('/accounting/adjusting',        [AccountingController::class, 'adjusting'])->name('accounting.adjusting');
        Route::post('/accounting/adjusting',       [AccountingController::class, 'adjustingStore'])->name('accounting.adjusting.store');
        Route::get('/accounting/trial-balance',    [AccountingController::class, 'trialBalance'])->name('accounting.trial-balance');
        Route::get('/accounting/worksheet',        [AccountingController::class, 'worksheet'])->name('accounting.worksheet');
        Route::get('/accounting/income-statement', [AccountingController::class, 'incomeStatement'])->name('accounting.income-statement');
        Route::get('/accounting/balance-sheet',    [AccountingController::class, 'balanceSheet'])->name('accounting.balance-sheet');

        // Pengeluaran
        Route::resource('expenses', ExpenseController::class);

        // Laporan
        Route::get('/reports/sales',      [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/inventory',  [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/reports/financial',  [ReportController::class, 'financial'])->name('reports.financial');
    });
    
});

// TARUH DI SINI (Di luar area login/middleware)
Route::get('/init-migrate', function () {
    try {
        // Menggunakan nama class lengkap agar tidak eror di server
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true
        ]);
        return "🔥 Suksesss! Database ERP Berhasil Dimigrasi dan Diisi Data Awal!";
    } catch (\Exception $e) {
        return "Aduh Eror: " . $e->getMessage();
    }
});