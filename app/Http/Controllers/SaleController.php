<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Sale,
    SaleItem,
    Product,
    InventoryLog,
    JournalEntry,
    JournalLine,
    Account
};
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function pos()
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.pos', compact('products'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,dana,qris,transfer',
            'cash_received' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            $total = 0;
            $itemsData = [];

            // VALIDASI STOCK + HITUNG TOTAL
            foreach ($request->items as $item) {

                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['qty']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tidak cukup. Tersedia {$product->stock}"
                    ], 422);
                }

                $subtotal = $product->selling_price * $item['qty'];
                $total += $subtotal;

                $itemsData[] = [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal
                ];
            }

            // CASH / KEMBALIAN FIX
            if ($request->payment_method == 'cash') {

                $cashReceived = (float) $request->cash_received;

                if ($cashReceived < $total) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uang tidak cukup'
                    ], 422);
                }

                $changeAmount = $cashReceived - $total;

            } else {

                $cashReceived = $total;
                $changeAmount = 0;
            }

            // GENERATE INVOICE
            $invoiceNumber = Sale::generateInvoiceNumber();

            // CREATE SALE
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => session('biztrack_user'),
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'cash_received' => $cashReceived,
                'change_amount' => $changeAmount,
            ]);

            // CREATE SALE ITEM + KURANGI STOCK
            foreach ($itemsData as $itemData) {

                $product = $itemData['product'];
                $qty = $itemData['qty'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->selling_price,
                    'cost_price' => $product->cost_price,
                    'subtotal' => $itemData['subtotal'],
                ]);

                $stockBefore = $product->stock;

                $product->decrement('stock', $qty);

                $stockAfter = $product->fresh()->stock;

                InventoryLog::create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity' => $qty,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference' => $invoiceNumber,
                    'notes' => "Penjualan {$invoiceNumber}",
                ]);
            }

            // AUTO JOURNAL
            $this->createSaleJournal($sale);

            DB::commit();

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'invoice_number' => $invoiceNumber,
                'total' => $total,
                'change' => $changeAmount,
                'message' => 'Transaksi berhasil'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function createSaleJournal(Sale $sale): void
    {
        $debitAccountCode = match ($sale->payment_method) {
            'cash' => '1100',
            'dana' => '1110',
            'qris' => '1120',
            'transfer' => '1120',
            default => '1100',
        };

        $debitAccount = Account::where('code', $debitAccountCode)->first();
        $creditAccount = Account::where('code', '4100')->first();

        if (!$debitAccount || !$creditAccount) {
            return;
        }

        $journal = JournalEntry::create([
            'reference' => $sale->invoice_number,
            'description' => 'Penjualan - ' . $sale->invoice_number,
            'entry_date' => now()->toDateString(),
            'sale_id' => $sale->id,
            'created_by' => session('biztrack_user'),
        ]);

        JournalLine::create([
            'journal_entry_id' => $journal->id,
            'account_id' => $debitAccount->id,
            'debit' => $sale->total_amount,
            'credit' => 0,
        ]);

        JournalLine::create([
            'journal_entry_id' => $journal->id,
            'account_id' => $creditAccount->id,
            'debit' => 0,
            'credit' => $sale->total_amount,
        ]);
    }

    public function index(Request $request)
    {
        $query = Sale::with(['items.product', 'user'])
            ->orderBy('created_at', 'desc');

        $sales = $query->paginate(20);

        return view('sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'user']);
        return view('sales.show', compact('sale'));
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['items.product', 'user']);
        return view('sales.receipt', compact('sale'));
    }
}