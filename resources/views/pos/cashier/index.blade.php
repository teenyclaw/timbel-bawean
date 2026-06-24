@extends('layouts.pos-cashier')

@section('title', 'Kasir')

@section('content')
<div class="flex flex-1 min-w-0 min-h-0">
    @include('pos.partials.cashier-menu-panel', [
        'categories' => $categories,
        'uncategorized' => $uncategorized,
        'addItemUrl' => route('pos.cashier.items.add', $order),
        'orderLabel' => 'Kasir Order',
        'orderNumber' => $order->order_number,
    ])

    @include('pos.partials.cashier-cart-panel', [
        'order' => $order,
        'updateItemRoute' => 'pos.cashier.items.update',
        'removeItemRoute' => 'pos.cashier.items.remove',
        'submitUrl' => route('pos.cashier.submit', $order),
        'payUrl' => route('pos.cashier.pay', $order),
        'newOrderUrl' => route('pos.cashier.new'),
    ])
</div>
@endsection
