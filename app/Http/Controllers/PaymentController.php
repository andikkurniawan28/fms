<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Payment::with(['customer', 'user', 'order'])->latest();

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
                ->filterColumn('order', function ($query, $keyword) {
                    $query->whereHas('order', function ($q) use ($keyword) {
                        $q->where('code', $keyword);
                    });
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('payment.edit', $row->id);
                    $showUrl = route('payment.show', $row->id);
                    $deleteUrl = route('payment.destroy', $row->id);

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

        return view('payment.index');
    }

    public function show(Payment $payment)
    {
        $payment->load(['customer', 'user', 'order']);

        return view('payment.show', compact('payment'));
    }

    public function create()
    {
        $orders = Order::where('left', '>', 0)->get();
        return view('payment.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'order_id' => 'required|exists:orders,id',
            'total' => 'required',
            'via' => 'required'
        ]);

        $clean = fn($val) => (double) str_replace('.', '', $val ?? 0);

        DB::beginTransaction();
        try {
            $order = Order::findOrFail($request->order_id);
            $total = $clean($request->total);

            if ($total <= 0) {
                return back()->with('error', 'nominal harus lebih dari 0');
            }

            if ($total > $order->left) {
                return back()->with('error', 'pembayaran melebihi sisa tagihan');
            }

            $payment = Payment::create([
                'code' => 'PAY' . date('YmdHis'),
                'date' => $request->date,
                'customer_id' => $order->customer_id,
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'total' => $total,
                'via' => $request->via,
            ]);

            // Catat Jurnal
            Journal::logPayment($payment);

            $newPaid = $order->paid + $total;
            $left = $order->grand_total - $newPaid;

            $status = $newPaid == 0
                ? 'Belum Bayar'
                : ($left > 0 ? 'Sudah DP' : 'Lunas');

            $order->update([
                'paid' => $newPaid,
                'left' => $left,
                'status' => $status,
            ]);

            DB::commit();

            return redirect()->route('payment.index')
                ->with('success', 'pelunasan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Payment $payment)
    {
        $orders = Order::all();
        return view('payment.edit', compact('payment', 'orders'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'date' => 'required|date',
            'total' => 'required',
            'via' => 'required',
        ]);

        $clean = fn($val) => (double) str_replace('.', '', $val ?? 0);

        DB::beginTransaction();
        try {
            $order = $payment->order;

            $oldTotal = $payment->total;
            $newTotal = $clean($request->total);

            if ($newTotal <= 0) {
                return back()->with('error', 'nominal harus lebih dari 0');
            }

            // rollback dulu
            $order->paid -= $oldTotal;

            if ($newTotal > ($order->grand_total - $order->paid)) {
                return back()->with('error', 'pembayaran melebihi sisa tagihan');
            }

            $payment->update([
                'date' => $request->date,
                'total' => $newTotal,
                'user_id' => auth()->id(),
                'via' => $request->via,
            ]);

            // Catat ulang jurnal
            Journal::where('payment_id', $payment->id)->delete();
            Journal::logPayment($payment);

            // hitung ulang
            $order->paid += $newTotal;
            $order->left = $order->grand_total - $order->paid;

            $order->status = $order->paid == 0
                ? 'Belum Bayar'
                : ($order->left > 0 ? 'Sudah DP' : 'Lunas');

            $order->save();

            DB::commit();

            return redirect()->route('payment.index')
                ->with('success', 'pelunasan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Payment $payment)
    {
        DB::beginTransaction();
        try {
            $order = $payment->order;

            $order->paid -= $payment->total;
            $order->left = $order->grand_total - $order->paid;

            $order->status = $order->paid == 0
                ? 'Belum Bayar'
                : ($order->left > 0 ? 'Sudah DP' : 'Lunas');

            $order->save();

            $payment->delete();

            DB::commit();

            return redirect()->route('payment.index')
                ->with('success', 'pelunasan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
