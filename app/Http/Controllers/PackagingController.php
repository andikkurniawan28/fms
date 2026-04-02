<?php

namespace App\Http\Controllers;

use App\Models\Packaging;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PackagingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Packaging::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('packaging.edit', $row->id);
                    $deleteUrl = route('packaging.destroy', $row->id);

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

        return view('packaging.index');
    }

    public function create()
    {
        return view('packaging.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:packagings,name',
        ]);

        Packaging::create([
            'name' => $request->name,
        ]);

        return redirect()->route('packaging.index')->with('success', 'packaging berhasil ditambahkan.');
    }

    public function edit(Packaging $packaging)
    {
        return view('packaging.edit', compact('packaging'));
    }

    public function update(Request $request, Packaging $packaging)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:packagings,name,' . $packaging->id,
        ]);

        $packaging->update([
            'name' => $request->name,
        ]);

        return redirect()->route('packaging.index')->with('success', 'packaging berhasil diperbarui.');
    }

    public function destroy(Packaging $packaging)
    {
        $packaging->delete();

        return redirect()->route('packaging.index')->with('success', 'packaging berhasil dihapus.');
    }
}
