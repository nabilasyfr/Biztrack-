<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Product, InventoryLog, SaleItem};

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%")
                  ->orWhere('category', 'like', "%{$request->search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Product::distinct()->pluck('category')->filter()->sort()->values();

        // Attach total sold qty per product
        $productIds = $products->pluck('id');
        $soldQtyMap = SaleItem::whereIn('product_id', $productIds)
            ->selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->pluck('total_sold', 'product_id');

        return view('products.index', compact('products', 'categories', 'soldQtyMap'));
    }

    public function create()
    {
        $categories = Product::distinct()->pluck('category')->filter()->sort()->values();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'          => 'required|unique:products,code|max:50',
            'name'          => 'required|max:200',
            'category'      => 'required|max:100',
            'supplier'      => 'nullable|max:200',
            'selling_price' => 'required|numeric|min:0',
            'cost_price'    => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'min_stock'     => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        // Log initial stock
        InventoryLog::create([
            'product_id'   => $product->id,
            'type'         => 'restock',
            'quantity'     => $product->stock,
            'stock_before' => 0,
            'stock_after'  => $product->stock,
            'reference'    => 'INITIAL-STOCK',
            'notes'        => 'Stok awal produk baru',
        ]);

        return redirect()->route('products.index')->with('success', "Produk {$product->name} berhasil ditambahkan.");
    }

    public function edit(Product $product)
    {
        $categories = Product::distinct()->pluck('category')->filter()->sort()->values();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code'          => "required|unique:products,code,{$product->id}|max:50",
            'name'          => 'required|max:200',
            'category'      => 'required|max:100',
            'supplier'      => 'nullable|max:200',
            'selling_price' => 'required|numeric|min:0',
            'cost_price'    => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'min_stock'     => 'required|integer|min:0',
        ]);

        $oldStock = $product->stock;
        $product->update($validated);

        // Log stock adjustment if changed
        if ($oldStock != $validated['stock']) {
            $diff = $validated['stock'] - $oldStock;
            InventoryLog::create([
                'product_id'   => $product->id,
                'type'         => $diff > 0 ? 'restock' : 'adjustment',
                'quantity'     => abs($diff),
                'stock_before' => $oldStock,
                'stock_after'  => $validated['stock'],
                'reference'    => 'MANUAL-ADJUSTMENT',
                'notes'        => 'Penyesuaian stok manual oleh ' . session('biztrack_name'),
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => "Produk {$product->name} berhasil diperbarui."]);
        }

        return redirect()->route('products.show', $product)->with('success', "Produk {$product->name} berhasil diperbarui.");
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();
        return redirect()->route('products.index')->with('success', "Produk {$name} berhasil dihapus.");
    }

    public function show(Product $product)
    {
        // Inventory logs (20 most recent)
        $logs = $product->inventoryLogs()->orderBy('created_at', 'desc')->limit(20)->get();

        // Sale transactions for this product
        $saleTransactions = SaleItem::with('sale')
            ->where('product_id', $product->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        // Total qty sold
        $totalSold = SaleItem::where('product_id', $product->id)->sum('quantity');

        return view('products.show', compact('product', 'logs', 'saleTransactions', 'totalSold'));
    }

    public function inventoryLog(Request $request)
    {
        $query = InventoryLog::with('product')->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $logs = $query->paginate(20)->withQueryString();
        return view('products.inventory-log', compact('logs'));
    }
}
