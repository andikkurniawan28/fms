<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use stdClass;

class LedgerController extends Controller
{
    public function index()
    {
        return view('ledger.index', [
            'accounts' => Account::orderBy('code')->get()
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'account_id' => 'required'
        ]);

        $account = Account::findOrFail($request->account_id);

        // === SALDO SEBELUM PERIODE ===
        $before = JournalItem::join('journals','journals.id','=','journal_items.journal_id')
            ->where('account_id', $request->account_id)
            ->where('journals.date', '<', $request->date_from)
            ->selectRaw('
                SUM(journal_items.debit) as debit,
                SUM(journal_items.credit) as credit
            ')
            ->first();

        if ($account->normal_balance == 'Debit') {
            $saldo_awal = ($before->debit ?? 0) - ($before->credit ?? 0);
        } else {
            $saldo_awal = ($before->credit ?? 0) - ($before->debit ?? 0);
        }

        // === DATA PERIODE ===
        $ledger = JournalItem::with('journal')
            ->where('account_id', $request->account_id)
            ->whereHas('journal', function ($q) use ($request) {
                $q->whereBetween('date', [
                    $request->date_from,
                    $request->date_to
                ]);
            })
            ->join('journals', 'journals.id', '=', 'journal_items.journal_id')
            ->orderBy('journals.date')
            ->orderBy('journal_items.id')
            ->select(
                'journal_items.*',
                'journals.date',
                'journals.code',
                'journals.description'
            )
            ->get();

        // === SALDO BERJALAN (DIMULAI DARI SALDO AWAL) ===
        $balance = $saldo_awal;

        foreach ($ledger as $row) {

            if ($account->normal_balance == 'Debit') {
                $balance += ($row->debit - $row->credit);
            } else {
                $balance += ($row->credit - $row->debit);
            }

            $row->balance = $balance;
        }

        return response()->json([
            'account' => $account,
            'saldo_awal' => $saldo_awal,
            'data' => $ledger
        ]);
    }
}
