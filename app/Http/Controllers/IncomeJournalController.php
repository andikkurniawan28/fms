<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\IncomeJournal;
use App\Models\Journal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class IncomeJournalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = IncomeJournal::with(['account','user']);

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('account', function ($row) {
                    return $row->account->name ?? '-';
                })

                ->addColumn('user', function ($row) {
                    return $row->user->name ?? '-';
                })

                ->addColumn('action', function ($row) {
                    $editUrl = route('income_journal.edit', $row->id);
                    $deleteUrl = route('income_journal.destroy', $row->id);

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

        return view('income_journal.index');
    }

    public function create()
    {
        return view('income_journal.create', [
            'accounts' => Account::whereIn('id', [5, 10])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'total' => 'required|numeric',
            'description' => 'required',
        ]);

        $income = IncomeJournal::create([
            'code' => "INC".date('YmdHis'),
            'date' => $request->date,
            'account_id' => $request->account_id,
            'user_id' => auth()->id(),
            'total' => $request->total,
            'description' => $request->description,
        ]);

        Journal::logIncome($income);

        return redirect()
            ->route('income_journal.index')
            ->with('success', 'Jurnal pemasukan berhasil ditambahkan.');
    }

    public function edit(IncomeJournal $incomeJournal)
    {
        return view('income_journal.edit', [
            'incomeJournal' => $incomeJournal,
            'accounts' => Account::whereIn('id', [5, 10])->get(),
        ]);
    }

    public function update(Request $request, IncomeJournal $incomeJournal)
    {
        $request->validate([
            'date' => 'required|date',
            'account_id' => 'required|exists:accounts,id',
            'total' => 'required|numeric',
            'description' => 'required',
        ]);

        $incomeJournal->update([
            'date' => $request->date,
            'account_id' => $request->account_id,
            'total' => $request->total,
            'description' => $request->description,
        ]);

        $income = $incomeJournal;
        Journal::where('income_journal_id', $income->id)->delete();
        Journal::logIncome($income);

        return redirect()
            ->route('income_journal.index')
            ->with('success', 'Jurnal pemasukan berhasil diperbarui.');
    }

    public function destroy(IncomeJournal $incomeJournal)
    {
        $incomeJournal->delete();

        return redirect()
            ->route('income_journal.index')
            ->with('success', 'Jurnal pemasukan berhasil dihapus.');
    }
}
