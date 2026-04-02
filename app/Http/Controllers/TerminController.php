<?php

namespace App\Http\Controllers;

use App\Models\Termin;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TerminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Termin::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('termin.edit', $row->id);
                    $deleteUrl = route('termin.destroy', $row->id);

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

        return view('termin.index');
    }

    public function create()
    {
        return view('termin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:termins,name',
        ]);

        Termin::create([
            'name' => $request->name,
        ]);

        return redirect()->route('termin.index')->with('success', 'termin berhasil ditambahkan.');
    }

    public function edit(Termin $termin)
    {
        return view('termin.edit', compact('termin'));
    }

    public function update(Request $request, Termin $termin)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:termins,name,' . $termin->id,
        ]);

        $termin->update([
            'name' => $request->name,
        ]);

        return redirect()->route('termin.index')->with('success', 'termin berhasil diperbarui.');
    }

    public function destroy(Termin $termin)
    {
        $termin->delete();

        return redirect()->route('termin.index')->with('success', 'termin berhasil dihapus.');
    }
}
