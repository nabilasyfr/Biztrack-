<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Sale, Product, Expense, SaleItem};
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Today's sales
        $salesToday = Sale::whereDate('created_at', $today)->sum('total_amount');
        $transactionsToday = Sale::whereDate('created_at', $today)->count();

        // Monthly revenue
        $monthlyRevenue = Sale::where('created_at', '>=', $thisMonth)->sum('total_amount');

        // Monthly expenses
        $monthlyExpenses = Expense::where('expense_date', '>=', $thisMonth)->sum('amount');

        // Monthly profit (revenue - COGS - expenses)
        $monthlySales = Sale::with('items')->where('created_at', '>=', $thisMonth)->get();
        $cogs = 0;
        foreach ($monthlySales as $sale) {
            foreach ($sale->items as $item) {
                $cogs += $item->cost_price * $item->quantity;
            }
        }
        $monthlyProfit = $monthlyRevenue - $cogs - $monthlyExpenses;

        // Product stats
        $totalProducts = Product::count();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->get();

        // Recent transactions
        $recentSales = Sale::with(['items', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Top selling products this month
        $topProducts = SaleItem::selectRaw('product_id, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->whereHas('sale', fn($q) => $q->where('created_at', '>=', $thisMonth))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'salesToday', 'transactionsToday', 'monthlyRevenue',
            'monthlyExpenses', 'monthlyProfit', 'totalProducts',
            'lowStockProducts', 'recentSales', 'topProducts', 'cogs'
        ));
    }
}
