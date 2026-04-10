<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Production::with(['customer', 'user'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer', fn($row) => $row->customer->name ?? '-')
                ->addColumn('user', fn($row) => $row->user->name ?? '-')
                ->addColumn('order', fn($row) => $row->order->code ?? '-')
                ->filterColumn('customer', function ($query, $keyword) {
                    $query->whereHas('customer', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('user', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('production.edit', $row->id);
                    $showUrl = route('production.show', $row->id);
                    $deleteUrl = route('production.destroy', $row->id);

                    return '<div class="btn-group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                                <a href="' . $showUrl . '" class="btn btn-sm btn-info">Tampil</a>
                                <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Hapus data ini?\')" style="display:inline-block;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('production.index');
    }

    public function show(Production $production)
    {
        $production->load(['customer', 'user', 'items']);

        return view('production.show', compact('production'));
    }

    public function create()
    {
        return view('production.create', [
            'customers' => Customer::all(),
            // 'products' => Product::with('packaging', 'productCategory')->get(),
            // 'orders' => Order::all(),
            'orders' => Order::whereDoesntHave('production')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'due_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'required|exists:orders,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required',
            'items.*.description' => 'required',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required',
        ]);

        // helper clean angka (hapus titik)
        $clean = function ($val) {
            return (double) str_replace('.', '', $val ?? 0);
        };

        DB::beginTransaction();

        try {
            $code = 'SPK' . date('YmdHis');

            $subtotal = 0;

            foreach ($request->items as $item) {
                $price = $clean($item['price']);
                $qty = $item['qty'];

                $subtotal += $qty * $price;
            }

            $production = Production::create([
                'code' => $code,
                'date' => $request->date,
                'due_date' => $request->due_date,
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id(),
                'order_id' => $request->order_id,
                'subtotal' => $subtotal,
            ]);

            // insert items
            foreach ($request->items as $item) {
                $price = $clean($item['price']);
                $qty = $item['qty'];

                ProductionItem::create([
                    'production_id' => $production->id,
                    'product' => $item['product_id'],
                    'description' => $item['description'],
                    'qty' => $qty,
                    'price' => $price,
                    'amount' => $qty * $price,
                ]);
            }

            DB::commit();

            return redirect()->route('production.index')
                ->with('success', 'SPK berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Production $production)
    {
        $production->load('items');

        return view('production.edit', [
            'production' => $production,
            'customers' => Customer::all(),
            // 'products' => Product::with('packaging', 'productCategory')->get(),
            // 'orders' => Order::all(),
            'orders' => Order::whereDoesntHave('production')->get(),
        ]);
    }

    public function update(Request $request, Production $production)
    {
        $request->validate([
            'date' => 'required|date',
            'due_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'required|exists:orders,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required',
            'items.*.description' => 'required',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required',
        ]);

        $clean = function ($val) {
            return (double) str_replace('.', '', $val ?? 0);
        };

        DB::beginTransaction();

        try {

            // 🔢 hitung ulang subtotal
            $subtotal = 0;

            foreach ($request->items as $item) {
                $price = $clean($item['price']);
                $qty = $item['qty'];

                $subtotal += $qty * $price;
            }

            // ✅ update production
            $production->update([
                'date' => $request->date,
                'due_date' => $request->due_date,
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id(),
                'order_id' => $request->order_id,
                'subtotal' => $subtotal,
            ]);

            // 🔥 replace items (best practice simple)
            $production->items()->delete();

            foreach ($request->items as $item) {
                $price = $clean($item['price']);
                $qty = $item['qty'];

                ProductionItem::create([
                    'production_id' => $production->id,
                    // 'product_id' => $item['product_id'],
                    'product' => $item['product_id'],
                    'description' => $item['description'],
                    'qty' => $qty,
                    'price' => $price,
                    'amount' => $qty * $price,
                ]);
            }

            DB::commit();

            return redirect()->route('production.index')
                ->with('success', 'SPK berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Production $production)
    {
        $production->delete();

        return redirect()->route('production.index')->with('success', 'SPK berhasil dihapus.');
    }
}
