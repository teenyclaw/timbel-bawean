@php
    $order = (object) $order;
@endphp
<article class="rounded-xl border border-slate-200/90 bg-slate-50/80 p-4 shadow-sm" data-order-id="{{ $order->id }}">
    <div class="flex justify-between items-start gap-2 mb-2">
        <div>
            <div class="font-mono font-bold text-lg text-teal-800">{{ $order->order_number }}</div>
            <div class="font-semibold text-slate-900">{{ $order->customer_label }}</div>
            <div class="text-xs text-slate-500">{{ $order->source_label }} · {{ $order->created_at }} · {{ $order->wait_minutes }} mnt</div>
        </div>
    </div>

    @if($order->notes)
        <div class="text-xs bg-amber-50 border border-amber-200/60 rounded-lg px-2.5 py-1.5 mb-2 text-amber-800">Catatan: {{ $order->notes }}</div>
    @endif

    <ul class="text-sm space-y-1 mb-4">
        @foreach($order->items as $item)
            <li class="flex justify-between gap-2 border-t border-slate-200/80 pt-1.5">
                <span><strong class="text-teal-700">{{ $item['qty'] }}×</strong> {{ $item['name'] }}
                    @if(!empty($item['modifiers']))<span class="text-slate-500 text-xs block">{{ $item['modifiers'] }}</span>@endif
                    @if(!empty($item['note']))<span class="text-slate-400 text-xs block">{{ $item['note'] }}</span>@endif
                </span>
            </li>
        @endforeach
    </ul>

    <div class="flex flex-wrap gap-2">
        @if($column === 'pending')
            <form method="POST" action="{{ url('/kitchen/orders/'.$order->id.'/start') }}">
                @csrf
                <button type="submit" class="text-sm bg-teal-600 hover:bg-teal-700 text-white px-3 py-2 rounded-xl font-medium transition">Mulai Masak</button>
            </form>
        @endif
        @if($column === 'cooking')
            <form method="POST" action="{{ url('/kitchen/orders/'.$order->id.'/ready') }}">
                @csrf
                <button type="submit" class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-xl font-medium transition">Siap Saji</button>
            </form>
        @endif
        @if($column === 'ready')
            <span class="text-xs text-emerald-600 font-medium py-2">Menunggu pelayan...</span>
        @endif
    </div>
</article>
