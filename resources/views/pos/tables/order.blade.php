@extends('layouts.pos-cashier')

@section('title', $order->diningTable->name)

@section('content')
<div class="flex flex-1 min-w-0 min-h-0">
    @include('pos.partials.cashier-menu-panel', [
        'categories' => $categories,
        'uncategorized' => $uncategorized,
        'addItemUrl' => route('pos.tables.items.add', $order),
        'orderLabel' => $order->diningTable->name,
        'orderNumber' => $order->order_number,
        'backUrl' => route('pos.tables.index'),
    ])

    @include('pos.partials.cashier-cart-panel', [
        'order' => $order,
        'updateItemRoute' => 'pos.tables.items.update',
        'removeItemRoute' => 'pos.tables.items.remove',
        'submitUrl' => route('pos.tables.submit', $order),
        'payUrl' => route('pos.tables.close', $order),
    ])
</div>
@endsection
