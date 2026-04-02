<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductCategory::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = route('product_category.edit', $row->id);
                    $deleteUrl = route('product_category.destroy', $row->id);

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

        return view('product_category.index');
    }

    public function create()
    {
        return view('product_category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
        ]);

        ProductCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('product_category.index')->with('success', 'kategori produk berhasil ditambahkan.');
    }

    public function edit(ProductCategory $product_category)
    {
        return view('product_category.edit', compact('product_category'));
    }

    public function update(Request $request, ProductCategory $product_category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $product_category->id,
        ]);

        $product_category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('product_category.index')->with('success', 'kategori produk berhasil diperbarui.');
    }

    public function destroy(ProductCategory $product_category)
    {
        $product_category->delete();

        return redirect()->route('product_category.index')->with('success', 'kategori produk berhasil dihapus.');
    }
}
