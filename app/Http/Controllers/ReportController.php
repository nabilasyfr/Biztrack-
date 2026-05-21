<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Sale, SaleItem, Product, Expense};
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $month  = $request->get('month', now()->format('Y-m'));
        $date   = $request->get('date', now()->format('Y-m-d'));

        $query = Sale::with('items.product');

        if ($period === 'daily') {
            $query->whereDate('created_at', $date);
            $title = 'Laporan Penjualan Harian - ' . Carbon::parse($date)->format('d F Y');
        } else {
            [$year, $mon] = explode('-', $month);
            $query->whereYear('created_at', $year)->whereMonth('created_at', $mon);
            $title = 'Laporan Penjualan Bulanan - ' . Carbon::parse($month . '-01')->format('F Y');
        }

        $sales = $query->orderBy('created_at', 'desc')->get();
        $totalRevenue = $sales->sum('total_amount');
        $totalCogs = $sales->flatMap->items->sum(fn($i) => $i->cost_price * $i->quantity);
        $totalProfit = $totalRevenue - $totalCogs;
        $byPayment = $sales->groupBy('payment_method')->map->sum('total_amount');

        return view('reports.sales', compact(
            'sales', 'totalRevenue', 'totalCogs', 'totalProfit',
            'byPayment', 'period', 'month', 'date', 'title'
        ));
    }

    public function inventory(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = Product::query();

        if ($filter === 'low') {
            $query->whereColumn('stock', '<=', 'min_stock');
        } elseif ($filter === 'out') {
            $query->where('stock', 0);
        }

        $products = $query->orderBy('category')->orderBy('name')->get();
        $totalValue = $products->sum(fn($p) => $p->stock * $p->cost_price);
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->count();
        $outOfStockCount = Product::where('stock', 0)->count();

        return view('reports.inventory', compact(
            'products', 'totalValue', 'lowStockCount', 'outOfStockCount', 'filter'
        ));
    }

    public function financial(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $sales    = Sale::whereYear('created_at', $year)->whereMonth('created_at', $mon)->get();
        $expenses = Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $mon)->get();

        $revenue  = $sales->sum('total_amount');
        $cogs     = $sales->flatMap(fn($s) => $s->items)->sum(fn($i) => $i->cost_price * $i->quantity);
        $grossProfit = $revenue - $cogs;
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $grossProfit - $totalExpenses;

        // By day chart data
        $dailyRevenue = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $mon, $year);
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $mon, $d);
            $dailyRevenue[$d] = $sales->filter(fn($s) => $s->created_at->format('Y-m-d') === $dateStr)->sum('total_amount');
        }

        return view('reports.financial', compact(
            'revenue', 'cogs', 'grossProfit', 'totalExpenses', 'netProfit',
            'expenses', 'month', 'dailyRevenue', 'daysInMonth'
        ));
    }
}
