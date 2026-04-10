<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function logReceivable($order)
    {
        $journal = Journal::create([
            'code' => 'JRN'.date('Ymdhis').'O',
            'date' => $order->date,
            'user_id' => $order->user_id,
            'description' => "Piutang order {$order->code}",
            'debit' => $order->grand_total,
            'credit' => $order->grand_total,
            'order_id' => $order->id,
        ]);

        // Piutang usaha bertambah
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => 2,
            'debit' => $order->grand_total,
            'credit' => 0,
        ]);

        // Penjualan produk bertambah
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => 8,
            'credit' => $order->grand_total,
            'debit' => 0,
        ]);
    }

    public static function logPayment($payment)
    {
        $journal = Journal::create([
            'code' => 'JRN'.date('Ymdhis').'P',
            'date' => $payment->date,
            'user_id' => $payment->user_id,
            'description' => "Pembayaran order {$payment->order->code} | {$payment->code}",
            'debit' => $payment->total,
            'credit' => $payment->total,
            'payment_id' => $payment->id,
        ]);

        // Piutang usaha berkurang
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => 2,
            'credit' => $payment->total,
            'debit' => 0,
        ]);

        // Kas bertambah
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => 1,
            'debit' => $payment->total,
            'credit' => 0,
        ]);
    }

    public static function logExpense($expense)
    {
        $journal = Journal::create([
            'code' => 'JRN'.date('Ymdhis').'E',
            'date' => $expense->date,
            'user_id' => $expense->user_id,
            'description' => "Pengeluaran | {$expense->account->name} - {$expense->description}",
            'debit' => $expense->total,
            'credit' => $expense->total,
            'expense_journal_id' => $expense->id,
        ]);

        // Kas berkurang
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => 1,
            'credit' => $expense->total,
            'debit' => 0,
        ]);

        // Beban bertambah
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => $expense->account_id,
            'debit' => $expense->total,
            'credit' => 0,
        ]);
    }



    public static function logIncome($income)
    {
        $journal = Journal::create([
            'code' => 'JRN'.date('Ymdhis').'I',
            'date' => $income->date,
            'user_id' => $income->user_id,
            'description' => "Pemasukan | {$income->account->name} - {$income->description}",
            'debit' => $income->total,
            'credit' => $income->total,
            'income_journal_id' => $income->id,
        ]);

        // Kas bertambah
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => 1,
            'debit' => $income->total,
            'credit' => 0,
        ]);

        // Akun berkurang
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => $income->account_id,
            'credit' => $income->total,
            'debit' => 0,
        ]);
    }
}
