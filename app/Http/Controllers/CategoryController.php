<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Product::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter();

        $categoryStats = [];
        foreach ($categories as $cat) {
            $products = Product::where('category', $cat)->get();
            $categoryStats[] = [
                'name'        => $cat,
                'total'       => $products->count(),
                'total_stock' => $products->sum('stock'),
                'total_sold'  => \App\Models\SaleItem::whereIn('product_id', $products->pluck('id'))->sum('quantity'),
            ];
        }

        return view('categories.index', compact('categoryStats'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string|max:100',
        ]);

        $old = trim($request->old_name);
        $new = trim($request->new_name);

        if ($old === $new) {
            return back()->with('error', 'Nama kategori sama, tidak ada perubahan.');
        }

        $count = Product::where('category', $old)->update(['category' => $new]);

        return back()->with('success', "Kategori '{$old}' berhasil diubah menjadi '{$new}' ({$count} produk diperbarui).");
    }

    public function destroy(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        $name  = trim($request->name);
        $count = Product::where('category', $name)->count();

        if ($count > 0) {
            return back()->with('error', "Kategori '{$name}' tidak bisa dihapus karena masih digunakan oleh {$count} produk.");
        }

        return back()->with('success', "Kategori '{$name}' berhasil dihapus.");
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        $name = trim($request->name);

        $exists = Product::where('category', $name)->exists();
        if ($exists) {
            return back()->with('error', "Kategori '{$name}' sudah ada.");
        }

        // Kategori baru disimpan dengan membuat produk placeholder — tidak perlu,
        // cukup simpan ke session agar bisa dipilih saat tambah produk.
        // Untuk pendekatan tanpa tabel baru, kategori baru baru "aktif" setelah
        // ada produk yang menggunakannya. Kita redirect dengan pesan info.
        return back()->with('info', "Kategori '{$name}' siap digunakan. Kategori akan muncul setelah ada produk yang menggunakannya.");
    }
}