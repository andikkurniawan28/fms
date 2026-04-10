<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JournalItem;

class CashFlowController extends Controller
{
    public function index()
    {
        return view('report.cash_flow');
    }

    public function process(Request $request)
    {
        $date_from = $request->date_from;
        $date_to   = $request->date_to;

        $cash = JournalItem::join('journals','journals.id','=','journal_items.journal_id')
            ->join('accounts','accounts.id','=','journal_items.account_id')
            ->where('accounts.code','1101')
            ->whereBetween('journals.date',[$date_from,$date_to])
            ->select(
                'journals.date',
                'journals.code',
                'journals.description',
                'journal_items.debit',
                'journal_items.credit'
            )
            ->orderBy('journals.date')
            ->get();

        $balance = 0;

        foreach($cash as $row){

            $balance += ($row->debit - $row->credit);

            $row->balance = $balance;
        }

        return response()->json($cash);
    }
}
