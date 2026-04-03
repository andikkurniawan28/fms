<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Packaging;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with(['productCategory', 'packaging']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->productCategory->name ?? '-';
                })
                ->addColumn('packaging', function ($row) {
                    return $row->packaging->name ?? '-';
                })
                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('productCategory', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('packaging', function ($query, $keyword) {
                    $query->whereHas('packaging', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('product.edit', $row->id);
                    $deleteUrl = route('product.destroy', $row->id);

                    return '<div class="btn-group">
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

        return view('product.index');
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $packagings = Packaging::all();

        return view('product.create', compact('categories', 'packagings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
        ]);

        // Ambil semua packaging
        $packagings = Packaging::all();

        foreach ($packagings as $packaging) {

            $priceField = 'price_' . $packaging->id;

            // Skip kalau tidak diisi (optional, kalau mau wajib bisa divalidasi)
            if (!$request->filled($priceField)) {
                continue;
            }

            Product::create([
                'product_category_id' => $request->product_category_id,
                'packaging_id' => $packaging->id,
                'name' => $request->name,
                'price' => $request->$priceField,
                'minimum_order' => $request->minimum_order,
                'base_price' => 0,
                'cost' => 0,
            ]);
        }

        return redirect()->route('product.index')->with('success', 'produk berhasil ditambahkan untuk semua packaging.');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $packagings = Packaging::all();

        return view('product.edit', compact('product', 'categories', 'packagings'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            // 'packaging_id' => 'required|exists:packagings,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'base_price' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
        ]);

        $product->update([
            'product_category_id' => $request->product_category_id,
            // 'packaging_id' => $request->packaging_id,
            'name' => $request->name,
            'price' => $request->price,
            'base_price' => $request->base_price ?? 0,
            'cost' => $request->cost ?? 0,
        ]);

        return redirect()->route('product.index')->with('success', 'produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('product.index')->with('success', 'produk berhasil dihapus.');
    }
}
