<div class="customer-card overflow-hidden flex gap-3 p-3">
    @if($item->photoUrl())
        <img src="{{ $item->photoUrl() }}" alt="{{ $item->name }}" class="w-20 h-20 object-cover rounded-xl shrink-0">
    @else
        <div class="w-20 h-20 bg-slate-100 rounded-xl shrink-0 flex items-center justify-center text-slate-400 text-xs">No foto</div>
    @endif
    <div class="flex-1 min-w-0">
        <div class="font-semibold text-slate-900">{{ $item->name }}</div>
        @if($item->description)
            <div class="text-xs text-slate-500 mt-0.5 line-clamp-2">{{ $item->description }}</div>
        @endif
        <div class="font-bold text-teal-600 mt-1">{{ $item->formattedPrice() }}</div>
        @if($item->hasModifiers())
            <div class="text-xs text-teal-700/70 mt-0.5">Tersedia varian</div>
        @endif
        @include('partials.modifier-picker', [
            'item' => $item,
            'formAction' => isset($table)
                ? route('customer.table.cart.add', [$outlet->slug, $table->token])
                : route('customer.cart.add', $outlet->slug),
        ])
    </div>
</div>
