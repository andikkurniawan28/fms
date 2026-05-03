<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Journal;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Termin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::with(['customer', 'user'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer', fn($row) => $row->customer->name ?? '-')
                ->addColumn('user', fn($row) => $row->user->name ?? '-')
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
                    $editUrl = route('order.edit', $row->id);
                    $showUrl = route('order.show', $row->id);
                    $deleteUrl = route('order.destroy', $row->id);
                    $recap = route('order.payment_record_per_invoice', $row->id);

                    return '<div class="btn-group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                                <a href="' . $showUrl . '" class="btn btn-sm btn-info">Tampil</a>
                                <a href="' . $recap . '" class="btn btn-sm btn-secondary">Pelunasan</a>
                                <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Hapus data ini?\')" style="display:inline-block;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('order.index');
    }

    // public function show(Order $order)
    // {
    //     $order->load(['customer', 'user', 'items']);

    //     return view('order.show', compact('order'));
    // }

    public function show(Order $order)
    {
        $order->load(['customer', 'user', 'items', 'payment']);

        $payments = $order->payment->sortBy('id');

        // DP = pembayaran pertama
        $dp = $payments->first()?->total ?? 0;

        // Total semua pembayaran
        $total_payment = $payments->sum('total');

        // Pembayaran setelah DP
        $pembayaran = $total_payment - $dp;

        // Sisa
        $sisa = $order->grand_total - $total_payment;

        return view('order.show', compact(
            'order',
            'dp',
            'pembayaran',
            'sisa',
            'total_payment'
        ));
    }

    public function create()
    {
        return view('order.create', [
            'customers' => Customer::all(),
            // 'products' => Product::with('packaging', 'productCategory')->get(),
            'terminals' => Termin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'termin_id' => 'required|exists:termins,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required',
        ]);

        // helper clean angka (hapus titik)
        $clean = function ($val) {
            return (double) str_replace('.', '', $val ?? 0);
        };

        DB::beginTransaction();

        try {
            $code = 'ORD' . date('YmdHis');

            $subtotal = 0;

            foreach ($request->items as $item) {
                $price = $clean($item['price']);
                $qty = $item['qty'];

                $subtotal += $qty * $price;
            }

            $discount = $clean($request->discount);
            $expenses = $clean($request->expenses);
            $taxes = $clean($request->taxes);

            $grandTotal = $subtotal - $discount + $expenses + $taxes;

            $paid = $clean($request->paid);
            $left = $grandTotal - $paid;

            $order = Order::create([
                'code' => $code,
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id(),
                'termin_id' => $request->termin_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'expenses' => $expenses,
                'taxes' => $taxes,
                'grand_total' => $grandTotal,
                'paid' => $paid,
                'left' => $left,
                'status' => $paid == 0 ? 'Belum Bayar' : ($left > 0 ? 'Sudah DP' : 'Lunas'),
            ]);

            // Catat di Jurnal
            Journal::logReceivable($order);

            // insert items
            foreach ($request->items as $item) {
                $price = $clean($item['price']);
                $qty = $item['qty'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product' => $item['product_id'],
                    'qty' => $qty,
                    'price' => $price,
                    'amount' => $qty * $price,
                ]);
            }

            // 🔥 Payment (kalau ada pembayaran)
            if ($paid > 0) {
                $payment = Payment::create([
                    'code' => 'PAY' . date('YmdHis'),
                    'date' => $request->date,
                    'customer_id' => $request->customer_id,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'total' => $paid,
                    'via' => $request->via,
                ]);

                // Catat Jurnal
                Journal::logPayment($payment);
            }

            DB::commit();

            return redirect()->route('order.index')
                ->with('success', 'Order berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Order $order)
    {
        $order->load('items');

        return view('order.edit', [
            'order' => $order,
            'customers' => Customer::all(),
            // 'products' => Product::with('packaging', 'productCategory')->get(),
            'terminals' => Termin::all(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'termin_id' => 'required|exists:termins,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required',
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

            $discount = $clean($request->discount);
            $expenses = $clean($request->expenses);
            $taxes = $clean($request->taxes);

            $grandTotal = $subtotal - $discount + $expenses + $taxes;

            $paid = $clean($request->paid);
            $left = $grandTotal - $paid;

            // 🧠 status sama seperti store
            $status = $paid == 0
                ? 'Belum Bayar'
                : ($left > 0 ? 'Sudah DP' : 'Lunas');

            // ✅ update order
            $order->update([
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id(),
                'termin_id' => $request->termin_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'expenses' => $expenses,
                'taxes' => $taxes,
                'grand_total' => $grandTotal,
                'paid' => $paid,
                'left' => $left,
                'status' => $status,
            ]);

            // Hapus dulu, buat baru
            Journal::where('order_id', $order->id)->delete();
            Journal::logReceivable($order);

            // 🔥 replace items (best practice simple)
            $order->items()->delete();

            foreach ($request->items as $item) {
                $price = $clean($item['price']);
                $qty = $item['qty'];

                OrderItem::create([
                    'order_id' => $order->id,
                    // 'product_id' => $item['product_id'],
                    'product' => $item['product_id'],
                    'qty' => $qty,
                    'price' => $price,
                    'amount' => $qty * $price,
                ]);
            }

            // 🔥 HANDLE PAYMENT (ini beda dari store, harus hati-hati)
            $existingPayment = Payment::where('order_id', $order->id)->first();

            if ($paid > 0) {

                if ($existingPayment) {
                    $existingPayment->update([
                        'date' => $request->date,
                        'customer_id' => $request->customer_id,
                        'user_id' => auth()->id(),
                        'total' => $paid,
                    ]);
                    $payment = $existingPayment;
                } else {
                    $payment = Payment::create([
                        'code' => 'PAY' . date('YmdHis'),
                        'date' => $request->date,
                        'customer_id' => $request->customer_id,
                        'user_id' => auth()->id(),
                        'order_id' => $order->id,
                        'total' => $paid,
                    ]);
                }
                Journal::where('payment_id', $payment->id)->delete();
                Journal::logPayment($payment);

            } else {
                if ($existingPayment) {
                    $existingPayment->delete();
                    $payment = $existingPayment;
                    Journal::where('payment_id', $payment->id)->delete();
                }
            }

            DB::commit();

            return redirect()->route('order.index')
                ->with('success', 'Order berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Order berhasil dihapus.');
    }
}
