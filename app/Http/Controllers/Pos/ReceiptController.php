<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Concerns\AssertsCurrentOutlet;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    use AssertsCurrentOutlet;

    public function show(Request $request, Order $order, ?OrderPayment $payment = null)
    {
        $this->assertOrderInCurrentOutlet($order);
        $order->load(['items', 'outlet', 'payments', 'diningTable']);

        if ($payment && $payment->order_id !== $order->id) {
            abort(404);
        }

        if (! $payment) {
            $payment = $order->payments()->where('status', OrderPayment::STATUS_PAID)->latest('paid_at')->first();
        }

        $items = $payment
            ? $order->items->whereIn('id', $payment->item_ids ?? [])
            : $order->items;

        $autoPrint = $request->boolean('auto', config('receipt.auto_print', true));

        return view('pos.receipt', compact('order', 'payment', 'items', 'autoPrint'));
    }
}
