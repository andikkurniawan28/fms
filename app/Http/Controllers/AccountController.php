<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Account::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('account.edit', $row->id);
                    $deleteUrl = route('account.destroy', $row->id);

                    return '<div class="btn-group" role="group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                                <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Hapus data ini?\')" style="display:inline-block;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('account.index');
    }

    public function create()
    {
        return view('account.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:accounts,name',
        ]);

        Account::create([
            'name' => $request->name,
        ]);

        return redirect()->route('account.index')->with('success', 'akun berhasil ditambahkan.');
    }

    public function edit(Account $account)
    {
        return view('account.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:accounts,name,' . $account->id,
        ]);

        $account->update([
            'name' => $request->name,
        ]);

        return redirect()->route('account.index')->with('success', 'akun berhasil diperbarui.');
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('account.index')->with('success', 'akun berhasil dihapus.');
    }
}
