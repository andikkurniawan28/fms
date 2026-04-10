<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\JournalItem;

class ProfitLossController extends Controller
{
    public function index()
    {
        return view('report.profit_loss');
    }

    public function process(Request $request)
    {
        $date_from = $request->date_from;
        $date_to   = $request->date_to;

        $accounts = Account::whereIn('group', ['Pendapatan','Beban'])
            ->orderBy('code')
            ->get();

        foreach ($accounts as $account) {

            $total = JournalItem::join('journals','journals.id','=','journal_items.journal_id')
                ->where('account_id',$account->id)
                ->whereBetween('journals.date',[$date_from,$date_to])
                ->selectRaw('SUM(journal_items.debit) as debit, SUM(journal_items.credit) as credit')
                ->first();

            if($account->normal_balance == 'Debit'){
                $balance = ($total->debit ?? 0) - ($total->credit ?? 0);
            }else{
                $balance = ($total->credit ?? 0) - ($total->debit ?? 0);
            }

            $account->balance = $balance;
        }

        $pendapatan = $accounts->where('group','Pendapatan')->sum('balance');
        $beban = $accounts->where('group','Beban')->sum('balance');

        return response()->json([
            'data' => $accounts,
            'pendapatan' => $pendapatan,
            'beban' => $beban,
            'laba' => $pendapatan - $beban
        ]);
    }
}
