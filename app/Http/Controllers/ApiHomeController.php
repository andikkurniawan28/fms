<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiHomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // 🔥 Hari ini
        $todayOrder = Order::whereDate('date', now())->count();
        $todayRevenue = Order::whereDate('date', now())->sum('grand_total');

        // 🔥 Bulan ini
        $monthlyRevenue = Order::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('grand_total');

        $totalOrder = Order::count();

        // 🔥 Payment & Piutang
        $payment = Payment::whereMonth('date', now()->month)->sum('total');
        $receivable = Order::sum('left');

        $unpaidCount = Order::where('left', '>', 0)->count();

        // 🔥 Produk terlaris
        // $topProduct = OrderItem::select('product_id', DB::raw('SUM(qty) as total'))
        //     ->with('product.packaging')
        //     ->groupBy('product_id')
        //     ->orderByDesc('total')
        //     ->first();
        $topProduct = null;

        // 🔥 Reminder (jatuh tempo hari ini - asumsi pakai date)
        $dueToday = Order::whereDate('date', now())
            ->where('left', '>', 0)
            ->count();

        return response()->json([
            'today' => [
                'order' => $todayOrder,
                'revenue' => $todayRevenue,
            ],
            'monthly_revenue' => $monthlyRevenue,
            'total_order' => $totalOrder,
            'payment' => $payment,
            'receivable' => $receivable,
            'unpaid_count' => $unpaidCount,
            'top_product' => [
                'name' => $topProduct?->product?->name,
                'packaging' => $topProduct?->product?->packaging?->name,
                'total' => $topProduct?->total,
            ],
            'due_today' => $dueToday,
        ]);
    }
}
