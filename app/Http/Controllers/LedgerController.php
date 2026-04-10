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

        // $request = new stdClass();

        // $request->account_id = 1;
        // $request->date_from = "2026-04-01";
        // $request->date_to = "2026-04-30";

        $account = Account::findOrFail($request->account_id);

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


        // saldo berjalan berdasarkan normal balance
        $balance = 0;

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
            'data' => $ledger
        ]);
    }
}
