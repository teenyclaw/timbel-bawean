<a href="{{ route('pos.orders.show', $order) }}" class="block ui-card p-5 hover:border-teal-300/80 hover:shadow-lg transition group">
    <div class="flex justify-between items-start mb-2">
        <span class="font-mono font-bold text-teal-700 group-hover:text-teal-800">{{ $order->order_number }}</span>
        <span class="text-xs text-slate-400">{{ $order->created_at?->format('d/m/Y H:i') }}</span>
    </div>
    <div class="font-semibold text-slate-900">{{ $order->displayCustomer() }}</div>
    <div class="flex items-center gap-2 mt-1">
        <span class="text-xs px-2 py-0.5 rounded-lg {{ $order->statusEnum()->badgeClass() }}">{{ $order->statusEnum()->label() }}</span>
        @if($order->diningTable)
            <span class="text-xs text-teal-600">{{ $order->sourceEnum()->label() }}</span>
        @else
            <span class="text-xs text-slate-500">{{ $order->customer_phone }}</span>
        @endif
    </div>
    <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between text-sm">
        <span class="text-slate-500">{{ $order->items->sum('qty') }} item</span>
        <span class="font-bold text-slate-900">{{ $order->formattedTotal() }}</span>
    </div>
</a>
