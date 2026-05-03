<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\JournalItem;

class BalanceSheetController extends Controller
{
    public function index()
    {
        return view('report.balance_sheet');
    }

    public function process(Request $request)
{
    $date_from = $request->date_from;
    $date_to   = $request->date_to;

    $accounts = Account::orderBy('code')->get();

    // foreach($accounts as $account){

    //     $total = JournalItem::join('journals','journals.id','=','journal_items.journal_id')
    //         ->where('account_id',$account->id)
    //         ->whereBetween('journals.date', [$date_from, $date_to])
    //         ->selectRaw('
    //             SUM(journal_items.debit) as debit,
    //             SUM(journal_items.credit) as credit
    //         ')
    //         ->first();

    //     if($account->normal_balance == 'Debit'){
    //         $balance = ($total->debit ?? 0) - ($total->credit ?? 0);
    //     }else{
    //         $balance = ($total->credit ?? 0) - ($total->debit ?? 0);
    //     }

    //     $account->balance = $balance;
    // }

    foreach ($accounts as $account) {

        // === SALDO SEBELUM PERIODE ===
        $before = JournalItem::join('journals','journals.id','=','journal_items.journal_id')
            ->where('account_id', $account->id)
            ->where('journals.date', '<', $date_from)
            ->selectRaw('
                SUM(journal_items.debit) as debit,
                SUM(journal_items.credit) as credit
            ')
            ->first();

        // === MUTASI PERIODE ===
        $period = JournalItem::join('journals','journals.id','=','journal_items.journal_id')
            ->where('account_id', $account->id)
            ->whereBetween('journals.date', [$date_from, $date_to])
            ->selectRaw('
                SUM(journal_items.debit) as debit,
                SUM(journal_items.credit) as credit
            ')
            ->first();

        // === HITUNG SALDO AWAL ===
        if ($account->normal_balance == 'Debit') {
            $saldo_awal = ($before->debit ?? 0) - ($before->credit ?? 0);
            $mutasi     = ($period->debit ?? 0) - ($period->credit ?? 0);
        } else {
            $saldo_awal = ($before->credit ?? 0) - ($before->debit ?? 0);
            $mutasi     = ($period->credit ?? 0) - ($period->debit ?? 0);
        }

        // === SALDO AKHIR ===
        $account->balance = $saldo_awal + $mutasi;

        // Optional (kalau mau ditampilkan)
        $account->saldo_awal = $saldo_awal;
        $account->mutasi = $mutasi;
    }

    $aset = $accounts->where('group','Aset')->values();
    $kewajiban = $accounts->where('group','Kewajiban')->values();
    $ekuitas = $accounts->where('group','Modal')->values(); // rename
    $pendapatan = $accounts->where('group','Pendapatan')->values();
    $beban = $accounts->where('group','Beban')->values();

    return response()->json([

        'aset' => $aset,
        'kewajiban' => $kewajiban,
        'ekuitas' => $ekuitas,
        'pendapatan' => $pendapatan,
        'beban' => $beban,

        'total_aset' => $aset->sum('balance'),

        'total_kewajiban' => $kewajiban->sum('balance'),

        'total_ekuitas' => $ekuitas->sum('balance'),

        'total_pendapatan' => $pendapatan->sum('balance'),

        'total_beban' => $beban->sum('balance'),

        'total_pasiva' =>
            ($kewajiban->sum('balance')
            + $ekuitas->sum('balance')
            + $pendapatan->sum('balance')
            - $beban->sum('balance')),

        // 'total_laba' => $pendapatan->sum('balance') - $beban->sum('balance'),

    ]);
}
}
