<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('customer.edit', $row->id);
                    $deleteUrl = route('customer.destroy', $row->id);

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

        return view('customer.index');
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:customers,name',
            'whatsapp' => 'required|string|max:20|unique:customers,whatsapp',
            'description' => 'nullable|string',
        ]);

        Customer::create([
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'description' => $request->description,
            'receivable' => 0,
        ]);

        return redirect()->route('customer.index')->with('success', 'customer berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:customers,name,' . $customer->id,
            'whatsapp' => 'required|string|max:20|unique:customers,whatsapp,' . $customer->id,
            'description' => 'nullable|string',
        ]);

        $customer->update([
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'description' => $request->description,
        ]);

        return redirect()->route('customer.index')->with('success', 'customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customer.index')->with('success', 'customer berhasil dihapus.');
    }
}
