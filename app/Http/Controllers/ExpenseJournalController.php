<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ExpenseJournal;
use App\Models\Journal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ExpenseJournalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ExpenseJournal::with(['account','user']);

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('account', function ($row) {
                    return $row->account->name ?? '-';
                })

                ->addColumn('user', function ($row) {
                    return $row->user->name ?? '-';
                })

                ->addColumn('action', function ($row) {
                    $editUrl = route('expense_journal.edit', $row->id);
                    $deleteUrl = route('expense_journal.destroy', $row->id);

                    return '<div class="btn-group">
                                <a href="'.$editUrl.'" class="btn btn-sm btn-warning">Edit</a>
                                <form action="'.$deleteUrl.'" method="POST"
                                    onsubmit="return confirm(\'Hapus data ini?\')"
                                    style="display:inline-block;">
                                    '.csrf_field().method_field('DELETE').'
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('expense_journal.index');
    }

    public function create()
    {
        return view('expense_journal.create', [
            'accounts' => Account::where('group', 'Beban')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'total' => 'required|numeric',
        ]);

        $expense = ExpenseJournal::create([
            'code' => "EXP".date('YmdHis'),
            'date' => $request->date,
            'account_id' => $request->account_id,
            'user_id' => auth()->id(),
            'total' => $request->total,
        ]);

        Journal::logExpense($expense);

        return redirect()
            ->route('expense_journal.index')
            ->with('success', 'Jurnal pengeluaran berhasil ditambahkan.');
    }

    public function edit(ExpenseJournal $expenseJournal)
    {
        return view('expense_journal.edit', [
            'expenseJournal' => $expenseJournal,
            'accounts' => Account::where('group', 'Beban')->get(),
        ]);
    }

    public function update(Request $request, ExpenseJournal $expenseJournal)
    {
        $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'total' => 'required|numeric',
        ]);

        $expenseJournal->update([
            'date' => $request->date,
            'account_id' => $request->account_id,
            'total' => $request->total,
        ]);

        $expense = $expenseJournal;
        Journal::where('expense_journal_id', $expense->id)->delete();
        Journal::logExpense($expense);

        return redirect()
            ->route('expense_journal.index')
            ->with('success', 'Jurnal pengeluaran berhasil diperbarui.');
    }

    public function destroy(ExpenseJournal $expenseJournal)
    {
        $expenseJournal->delete();

        return redirect()
            ->route('expense_journal.index')
            ->with('success', 'Jurnal pengeluaran berhasil dihapus.');
    }
}
