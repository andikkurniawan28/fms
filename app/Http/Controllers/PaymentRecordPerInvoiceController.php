<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentRecordPerInvoiceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id)
    {
        $order = Order::whereId($id)->get()->last();
        return view('order.payment_record_per_invoice', compact('order'));
    }
}
