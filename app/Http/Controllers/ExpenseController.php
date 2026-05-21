<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Expense, JournalEntry, JournalLine, Account};
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::orderBy('expense_date', 'desc');

        if ($request->filled('month')) {
            $query->whereYear('expense_date', substr($request->month, 0, 4))
                  ->whereMonth('expense_date', substr($request->month, 5, 2));
        }

        $expenses = $query->paginate(20)->withQueryString();
        $totalExpenses = Expense::sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|max:200',
            'amount'       => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'notes'        => 'nullable|max:500',
        ]);

        DB::beginTransaction();
        try {
            $expense = Expense::create([
                ...$validated,
                'created_by' => session('biztrack_user'),
            ]);

            // AIS: Auto journal entry for expense
            $this->createExpenseJournal($expense);

            DB::commit();
            return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * AIS Logic: Expense journal
     * Debit:  Expense Account (5000)
     * Credit: Cash (1100)
     */
    private function createExpenseJournal(Expense $expense): void
    {
        $expenseAccount = Account::where('code', '5000')->first();
        $cashAccount    = Account::where('code', '1100')->first();

        if (!$expenseAccount || !$cashAccount) return;

        $journal = JournalEntry::create([
            'reference'   => 'EXP-' . $expense->id,
            'description' => "Pengeluaran: {$expense->name}",
            'entry_date'  => $expense->expense_date,
            'expense_id'  => $expense->id,
            'created_by'  => session('biztrack_user'),
        ]);

        JournalLine::create([
            'journal_entry_id' => $journal->id,
            'account_id'       => $expenseAccount->id,
            'debit'            => $expense->amount,
            'credit'           => 0,
            'description'      => $expense->name,
        ]);

        JournalLine::create([
            'journal_entry_id' => $journal->id,
            'account_id'       => $cashAccount->id,
            'debit'            => 0,
            'credit'           => $expense->amount,
            'description'      => "Pembayaran: {$expense->name}",
        ]);
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'name'         => 'required|max:200',
            'amount'       => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'notes'        => 'nullable|max:500',
        ]);

        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
