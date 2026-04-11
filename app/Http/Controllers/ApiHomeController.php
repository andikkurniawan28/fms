<?php

namespace App\Http\Controllers;

use App\Models\ExpenseJournal;
use App\Models\IncomeJournal;
use App\Models\JournalItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Production;
use Illuminate\Http\Request;

class ApiHomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $now = now();

        // =========================
        // TODAY
        // =========================
        $todayOrder = Order::whereDate('date', $now)->count();
        $todaySales = Order::whereDate('date', $now)->sum('grand_total');
        $todayPayment = Payment::whereDate('date', $now)->sum('total');
        $todaySPK = Production::whereDate('date', $now)->count();

        // =========================
        // 🔥 MONTHLY
        // =========================
        $monthlySales = Order::whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('grand_total');

        $monthlyPayment = Payment::whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('total');

        // =========================
        // 🔥 MONTHLY (AKUNTANSI BASE)
        // =========================

        // Pendapatan (Credit)
        $monthlyIncome = JournalItem::join('accounts', 'accounts.id', '=', 'journal_items.account_id')
            ->join('journals', 'journals.id', '=', 'journal_items.journal_id')
            ->where('accounts.group', 'Pendapatan')
            ->whereMonth('journals.date', $now->month)
            ->whereYear('journals.date', $now->year)
            ->sum('journal_items.credit');

        // Beban (Debit)
        $monthlyExpense = JournalItem::join('accounts', 'accounts.id', '=', 'journal_items.account_id')
            ->join('journals', 'journals.id', '=', 'journal_items.journal_id')
            ->where('accounts.group', 'Beban')
            ->whereMonth('journals.date', $now->month)
            ->whereYear('journals.date', $now->year)
            ->sum('journal_items.debit');

        // Profit
        $profit = $monthlyIncome - $monthlyExpense;

        // =========================
        // RECEIVABLE
        // =========================
        $totalReceivable = Order::sum('left');
        $unpaidOrder = Order::where('left', '>', 0)->count();

        // =========================
        // PRODUCTION (SPK)
        // =========================
        $inProduction = Production::count();

        $pendingProduction = Order::whereDoesntHave('production')->count();

        $pendingValue = Order::whereDoesntHave('production')
            ->sum('grand_total');

        $conversionRate = $unpaidOrder > 0
            ? round(($inProduction / ($inProduction + $pendingProduction)) * 100, 2)
            : 0;

        // =========================
        // RESPONSE
        // =========================
        return response()->json([
            'today' => [
                'order' => $todayOrder,
                'sales' => $todaySales,
                'payment' => $todayPayment,
                'spk' => $todaySPK,
            ],

            'monthly' => [
                'sales' => $monthlySales,
                'payment' => $monthlyPayment,
                'income' => $monthlyIncome,
                'expense' => $monthlyExpense,
                'profit' => $profit,
            ],

            'receivable' => [
                'total' => $totalReceivable,
                'unpaid_order' => $unpaidOrder,
            ],

            'production' => [
                'in_production' => $inProduction,
                'pending_order' => $pendingProduction,
                'pending_value' => $pendingValue,
                'conversion_rate' => $conversionRate,
            ],
        ]);
    }
}
