<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Account, JournalEntry, JournalLine};
use Carbon\Carbon;

class AccountingController extends Controller
{
    // ─────────────────────────────────────────────
    //  Helper: ambil saldo semua akun (optimized)
    // ─────────────────────────────────────────────
    private function getAllBalances(?string $dateFrom = null, ?string $dateTo = null): \Illuminate\Support\Collection
    {
        $accounts = Account::orderBy('code')->get();

        return $accounts->map(function ($acc) use ($dateFrom, $dateTo) {
            $q = JournalLine::where('account_id', $acc->id);
            if ($dateFrom || $dateTo) {
                $q->whereHas('journalEntry', function ($j) use ($dateFrom, $dateTo) {
                    if ($dateFrom) $j->where('entry_date', '>=', $dateFrom);
                    if ($dateTo)   $j->where('entry_date', '<=', $dateTo);
                });
            }
            $debit  = (float)$q->sum('debit');
            $credit = (float)$q->sum('credit');
            $balance = in_array($acc->type, ['asset','expense'])
                ? $debit - $credit
                : $credit - $debit;

            return (object)[
                'id'           => $acc->id,
                'code'         => $acc->code,
                'name'         => $acc->name,
                'type'         => $acc->type,
                'total_debit'  => $debit,
                'total_credit' => $credit,
                'balance'      => $balance,
            ];
        });
    }

    // ─── CoA ──────────────────────────────────────
    public function coa()
    {
        $accounts = Account::orderBy('code')->get()->groupBy('type');
        return view('accounting.coa', compact('accounts'));
    }

    // ─── Jurnal Umum ──────────────────────────────
    public function journal(Request $request)
    {
        $query = JournalEntry::with(['lines.account'])
            ->orderBy('entry_date','desc')->orderBy('id','desc');
        if ($request->filled('date_from')) $query->where('entry_date','>=',$request->date_from);
        if ($request->filled('date_to'))   $query->where('entry_date','<=',$request->date_to);
        if ($request->filled('search'))    $query->where('reference','like',"%{$request->search}%")
                                                  ->orWhere('description','like',"%{$request->search}%");
        $entries = $query->paginate(20)->withQueryString();
        return view('accounting.journal', compact('entries'));
    }

    // ─── Buku Besar ───────────────────────────────
    public function ledger(Request $request)
    {
        $accounts        = Account::orderBy('code')->get();
        $selectedAccount = null;
        $lines           = collect();

        if ($request->filled('account_id')) {
            $selectedAccount = Account::find($request->account_id);
            if ($selectedAccount) {
                $q = JournalLine::with(['journalEntry'])
                    ->where('account_id', $selectedAccount->id)
                    ->orderBy('journal_entry_id');
                if ($request->filled('date_from'))
                    $q->whereHas('journalEntry', fn($j) => $j->where('entry_date','>=',$request->date_from));
                if ($request->filled('date_to'))
                    $q->whereHas('journalEntry', fn($j) => $j->where('entry_date','<=',$request->date_to));
                $lines = $q->get();
            }
        }
        return view('accounting.ledger', compact('accounts','selectedAccount','lines'));
    }

    // ─── Input Modal / Jurnal Manual ──────────────
    public function modalForm()
    {
        $accounts     = Account::orderBy('code')->get();
        $modalAccount = Account::where('code','3100')->first();
        $totalModal   = $modalAccount ? $modalAccount->getBalance() : 0;
        return view('accounting.modal', compact('accounts','totalModal'));
    }

    public function modalStore(Request $request)
    {
        $request->validate([
            'description'    => 'required|max:255',
            'entry_date'     => 'required|date',
            'debit_account'  => 'required|exists:accounts,id',
            'credit_account' => 'required|exists:accounts,id|different:debit_account',
            'amount'         => 'required|numeric|min:1',
        ],[
            'credit_account.different' => 'Akun debit dan kredit tidak boleh sama.',
        ]);

        DB::beginTransaction();
        try {
            $ref     = 'JRN-'.strtoupper(uniqid());
            $journal = JournalEntry::create([
                'reference'   => $ref,
                'description' => $request->description,
                'entry_date'  => $request->entry_date,
                'created_by'  => session('biztrack_user'),
            ]);
            JournalLine::create(['journal_entry_id'=>$journal->id,'account_id'=>$request->debit_account, 'debit'=>$request->amount,'credit'=>0,'description'=>$request->description]);
            JournalLine::create(['journal_entry_id'=>$journal->id,'account_id'=>$request->credit_account,'debit'=>0,'credit'=>$request->amount,'description'=>$request->description]);
            DB::commit();
            return redirect()->route('accounting.modal')->with('success','Jurnal berhasil disimpan! Ref: '.$ref);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>'Gagal: '.$e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  ADJUSTING ENTRIES (Jurnal Penyesuaian)
    // ─────────────────────────────────────────────
    public function adjusting(Request $request)
    {
        $accounts = Account::orderBy('code')->get();
        $month    = $request->get('month', now()->format('Y-m'));

        $entries = JournalEntry::with(['lines.account'])
            ->where('reference','like','ADJ-%')
            ->orderBy('entry_date','desc')
            ->paginate(15)->withQueryString();

        return view('accounting.adjusting', compact('accounts','entries','month'));
    }

    public function adjustingStore(Request $request)
    {
        $request->validate([
            'description'    => 'required|max:255',
            'entry_date'     => 'required|date',
            'debit_account'  => 'required|exists:accounts,id',
            'credit_account' => 'required|exists:accounts,id|different:debit_account',
            'amount'         => 'required|numeric|min:1',
            'adj_type'       => 'required|in:prepaid,accrued,depreciation,inventory,other',
        ],[
            'credit_account.different' => 'Akun debit dan kredit tidak boleh sama.',
        ]);

        DB::beginTransaction();
        try {
            $ref     = 'ADJ-'.strtoupper(uniqid());
            $journal = JournalEntry::create([
                'reference'   => $ref,
                'description' => '[PENYESUAIAN] '.$request->description,
                'entry_date'  => $request->entry_date,
                'created_by'  => session('biztrack_user'),
            ]);
            JournalLine::create(['journal_entry_id'=>$journal->id,'account_id'=>$request->debit_account, 'debit'=>$request->amount,'credit'=>0,'description'=>$request->description]);
            JournalLine::create(['journal_entry_id'=>$journal->id,'account_id'=>$request->credit_account,'debit'=>0,'credit'=>$request->amount,'description'=>$request->description]);
            DB::commit();
            return redirect()->route('accounting.adjusting')->with('success','Jurnal penyesuaian berhasil disimpan! Ref: '.$ref);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>'Gagal: '.$e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────────
    //  TRIAL BALANCE (Neraca Saldo)
    // ─────────────────────────────────────────────
    public function trialBalance(Request $request)
    {
        $month    = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);
        $dateFrom = "$year-$mon-01";
        $dateTo   = Carbon::parse($dateFrom)->endOfMonth()->format('Y-m-d');

        $accounts = Account::orderBy('code')->get();
        $balances = $accounts->map(function ($acc) use ($dateFrom, $dateTo) {
            $q = JournalLine::where('account_id', $acc->id)
                ->whereHas('journalEntry', fn($j) => $j
                    ->where('entry_date','>=',$dateFrom)
                    ->where('entry_date','<=',$dateTo)
                    ->where('reference','not like','ADJ-%')
                );
            $dr = (float)$q->sum('debit');
            $cr = (float)$q->sum('credit');
            return (object)['code'=>$acc->code,'name'=>$acc->name,'type'=>$acc->type,'debit'=>$dr,'credit'=>$cr];
        })->filter(fn($a) => $a->debit > 0 || $a->credit > 0);

        $adjustedBalances = $accounts->map(function ($acc) use ($dateFrom, $dateTo) {
            $q = JournalLine::where('account_id', $acc->id)
                ->whereHas('journalEntry', fn($j) => $j
                    ->where('entry_date','>=',$dateFrom)
                    ->where('entry_date','<=',$dateTo)
                );
            $dr = (float)$q->sum('debit');
            $cr = (float)$q->sum('credit');
            return (object)['code'=>$acc->code,'name'=>$acc->name,'type'=>$acc->type,'debit'=>$dr,'credit'=>$cr];
        })->filter(fn($a) => $a->debit > 0 || $a->credit > 0);

        $adjEntries = JournalEntry::with('lines.account')
            ->where('reference','like','ADJ-%')
            ->whereBetween('entry_date',[$dateFrom,$dateTo])
            ->get();

        return view('accounting.trial-balance', compact(
            'balances','adjustedBalances','adjEntries','month','dateFrom','dateTo'
        ));
    }

    // ─────────────────────────────────────────────
    //  WORKSHEET (Kertas Kerja / Neraca Lajur 10 Kolom — AIS Standard)
    //
    //  Aturan AIS:
    //  • Neraca Saldo (NS)          = saldo BERSIH akun (normal balance)
    //                                 asset/expense   → debit  jika positif
    //                                 liab/equity/rev → kredit jika positif
    //  • Penyesuaian (ADJ)          = total debit & kredit mentah dari jurnal ADJ-
    //  • NS Disesuaikan (NSD)       = NS ± ADJ, tetap disajikan sebagai saldo bersih
    //                                 pada kolom normal balance masing-masing akun
    //  • Laba/Rugi (L/R)            = hanya akun revenue & expense
    //                                 expense → debit L/R
    //                                 revenue → kredit L/R
    //  • Neraca (Balance Sheet col) = hanya akun asset, liability, equity
    //                                 asset       → debit Neraca
    //                                 liab/equity → kredit Neraca
    //  • Laba Bersih                = selisih kolom L/R → masuk baris penyeimbang
    //                                 Jika laba : +debit L/R, +kredit Neraca
    //                                 Jika rugi  : +kredit L/R, +debit Neraca
    // ─────────────────────────────────────────────
    public function worksheet(Request $request)
    {
        $month    = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);
        $dateFrom = "$year-$mon-01";
        $dateTo   = Carbon::parse($dateFrom)->endOfMonth()->format('Y-m-d');

        $accounts = Account::orderBy('code')->get();

        $rows = $accounts->map(function ($acc) use ($dateFrom, $dateTo) {
            // ── 1. Neraca Saldo (sebelum penyesuaian) ────────────────────────
            //    Ambil total debit & kredit semua jurnal NON-ADJ
            $qNS = JournalLine::where('account_id', $acc->id)
                ->whereHas('journalEntry', fn($j) => $j
                    ->whereBetween('entry_date', [$dateFrom, $dateTo])
                    ->where('reference', 'not like', 'ADJ-%'));
            $nsTotalDr = (float)$qNS->sum('debit');
            $nsTotalCr = (float)$qNS->sum('credit');

            // Saldo bersih NS → normal balance per tipe akun
            $nsNet = in_array($acc->type, ['asset', 'expense'])
                ? $nsTotalDr - $nsTotalCr      // normal balance = debit
                : $nsTotalCr - $nsTotalDr;     // normal balance = kredit

            // Tampilkan pada kolom yang sesuai (nilai selalu ≥ 0 di sini)
            // Jika nsNet < 0 berarti saldo tidak normal (tetap ditampilkan di kolom berlawanan)
            if (in_array($acc->type, ['asset', 'expense'])) {
                $nsDr = $nsNet >= 0 ? $nsNet : 0;
                $nsCr = $nsNet <  0 ? abs($nsNet) : 0;
            } else {
                $nsCr = $nsNet >= 0 ? $nsNet : 0;
                $nsDr = $nsNet <  0 ? abs($nsNet) : 0;
            }

            // ── 2. Penyesuaian (ADJ) ─────────────────────────────────────────
            //    Ditampilkan sebagai debit/kredit MENTAH (sesuai format AIS)
            $qAdj = JournalLine::where('account_id', $acc->id)
                ->whereHas('journalEntry', fn($j) => $j
                    ->whereBetween('entry_date', [$dateFrom, $dateTo])
                    ->where('reference', 'like', 'ADJ-%'));
            $adjDr = (float)$qAdj->sum('debit');
            $adjCr = (float)$qAdj->sum('credit');

            // ── 3. NS Disesuaikan (NSD) ──────────────────────────────────────
            //    Hitung saldo bersih setelah penyesuaian, lalu letakkan
            //    pada kolom normal balance akun tersebut.
            //
            //    Rumus saldo bersih NSD:
            //      asset/expense  : (nsTotalDr + adjDr) - (nsTotalCr + adjCr)
            //      liab/equity/rev: (nsTotalCr + adjCr) - (nsTotalDr + adjDr)
            $nsdNet = in_array($acc->type, ['asset', 'expense'])
                ? ($nsTotalDr + $adjDr) - ($nsTotalCr + $adjCr)
                : ($nsTotalCr + $adjCr) - ($nsTotalDr + $adjDr);

            if (in_array($acc->type, ['asset', 'expense'])) {
                $nsdDr = $nsdNet >= 0 ? $nsdNet : 0;
                $nsdCr = $nsdNet <  0 ? abs($nsdNet) : 0;
            } else {
                $nsdCr = $nsdNet >= 0 ? $nsdNet : 0;
                $nsdDr = $nsdNet <  0 ? abs($nsdNet) : 0;
            }

            // ── 4. Laba/Rugi ─────────────────────────────────────────────────
            //    Hanya akun revenue & expense yang masuk kolom ini.
            //    Gunakan nilai dari NSD (sudah memperhitungkan penyesuaian).
            $lrDr = 0;
            $lrCr = 0;
            if ($acc->type === 'expense') {
                // Expense: saldo normal = debit
                $lrDr = $nsdDr;
                $lrCr = $nsdCr; // saldo tidak normal (langka)
            } elseif ($acc->type === 'revenue') {
                // Revenue: saldo normal = kredit
                $lrCr = $nsdCr;
                $lrDr = $nsdDr; // saldo tidak normal (langka)
            }

            // ── 5. Neraca (Balance Sheet columns) ────────────────────────────
            //    Hanya akun asset, liability, equity yang masuk kolom ini.
            $nerDr = 0;
            $nerCr = 0;
            if ($acc->type === 'asset') {
                $nerDr = $nsdDr;
                $nerCr = $nsdCr;
            } elseif (in_array($acc->type, ['liability', 'equity'])) {
                $nerCr = $nsdCr;
                $nerDr = $nsdDr;
            }

            return (object) [
                'acc'   => $acc,
                // Neraca Saldo
                'nsDr'  => $nsDr,
                'nsCr'  => $nsCr,
                // Penyesuaian
                'adjDr' => $adjDr,
                'adjCr' => $adjCr,
                // NS Disesuaikan
                'nsdDr' => $nsdDr,
                'nsdCr' => $nsdCr,
                // Laba/Rugi
                'lrDr'  => $lrDr,
                'lrCr'  => $lrCr,
                // Neraca
                'nerDr' => $nerDr,
                'nerCr' => $nerCr,
            ];
        })->filter(fn($r) =>
            $r->nsDr > 0 || $r->nsCr > 0 ||
            $r->adjDr > 0 || $r->adjCr > 0
        );

        return view('accounting.worksheet', compact('rows','month','dateFrom','dateTo'));
    }

    // ─────────────────────────────────────────────
    //  INCOME STATEMENT (Laporan Laba Rugi)
    // ─────────────────────────────────────────────
    public function incomeStatement(Request $request)
    {
        $month    = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);
        $dateFrom = "$year-$mon-01";
        $dateTo   = Carbon::parse($dateFrom)->endOfMonth()->format('Y-m-d');

        $accounts = Account::orderBy('code')->get();

        $revenues = $accounts->where('type','revenue')->map(function ($acc) use ($dateFrom,$dateTo) {
            $q  = JournalLine::where('account_id',$acc->id)
                ->whereHas('journalEntry',fn($j)=>$j->whereBetween('entry_date',[$dateFrom,$dateTo]));
            $cr = (float)$q->sum('credit');
            $dr = (float)$q->sum('debit');
            return (object)['code'=>$acc->code,'name'=>$acc->name,'amount'=>$cr - $dr];
        })->filter(fn($a) => $a->amount != 0);

        $expenses = $accounts->where('type','expense')->map(function ($acc) use ($dateFrom,$dateTo) {
            $q  = JournalLine::where('account_id',$acc->id)
                ->whereHas('journalEntry',fn($j)=>$j->whereBetween('entry_date',[$dateFrom,$dateTo]));
            $dr = (float)$q->sum('debit');
            $cr = (float)$q->sum('credit');
            return (object)['code'=>$acc->code,'name'=>$acc->name,'amount'=>$dr - $cr];
        })->filter(fn($a) => $a->amount != 0);

        $totalRevenue  = $revenues->sum('amount');
        $totalExpense  = $expenses->sum('amount');
        $netIncome     = $totalRevenue - $totalExpense;

        return view('accounting.income-statement', compact(
            'revenues','expenses','totalRevenue','totalExpense','netIncome','month','dateFrom','dateTo'
        ));
    }

    // ─────────────────────────────────────────────
    //  BALANCE SHEET (Neraca / Laporan Posisi Keuangan)
    // ─────────────────────────────────────────────
    public function balanceSheet(Request $request)
    {
        $month    = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);
        $dateFrom = "$year-01-01";
        $dateTo   = Carbon::parse("$year-$mon-01")->endOfMonth()->format('Y-m-d');

        $accounts = Account::orderBy('code')->get();

        $getBalance = function ($acc) use ($dateFrom, $dateTo) {
            $q  = JournalLine::where('account_id',$acc->id)
                ->whereHas('journalEntry',fn($j)=>$j->whereBetween('entry_date',[$dateFrom,$dateTo]));
            $dr = (float)$q->sum('debit');
            $cr = (float)$q->sum('credit');
            return in_array($acc->type,['asset','expense']) ? $dr - $cr : $cr - $dr;
        };

        $assets      = $accounts->where('type','asset')    ->map(fn($a)=>(object)['code'=>$a->code,'name'=>$a->name,'balance'=>$getBalance($a)])->filter(fn($a)=>$a->balance!=0);
        $liabilities = $accounts->where('type','liability') ->map(fn($a)=>(object)['code'=>$a->code,'name'=>$a->name,'balance'=>$getBalance($a)])->filter(fn($a)=>$a->balance!=0);
        $equities    = $accounts->where('type','equity')   ->map(fn($a)=>(object)['code'=>$a->code,'name'=>$a->name,'balance'=>$getBalance($a)])->filter(fn($a)=>$a->balance!=0);

        $revenues    = $accounts->where('type','revenue')->sum(fn($a)=>$getBalance($a));
        $expensesSum = $accounts->where('type','expense')->sum(fn($a)=>$getBalance($a));
        $currentProfit = $revenues - $expensesSum;

        $totalAssets      = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');
        $totalEquity      = $equities->sum('balance') + $currentProfit;

        return view('accounting.balance-sheet', compact(
            'assets','liabilities','equities',
            'totalAssets','totalLiabilities','totalEquity',
            'currentProfit','month','dateTo'
        ));
    }
}
