<div class="relative">
    @if($item->photoUrl())
        <img src="{{ $item->photoUrl() }}" alt="{{ $item->name }}" class="w-full aspect-square object-cover bg-slate-100">
    @else
        <div class="w-full aspect-square bg-slate-100 flex items-center justify-center text-slate-400 text-xs">No foto</div>
    @endif
    @if($item->stockLabel())
        <span class="absolute top-1.5 left-1.5 text-[10px] font-medium bg-black/50 text-white px-1.5 py-0.5 rounded">{{ $item->stockLabel() }}</span>
    @endif
    @if($item->hasModifiers())
        <span class="absolute top-1.5 right-1.5 text-[10px] font-medium bg-purple-600 text-white px-1.5 py-0.5 rounded">Varian</span>
    @endif
</div>
<div class="p-2.5">
    <div class="font-medium text-sm leading-tight line-clamp-2 min-h-[2.5rem]">{{ $item->name }}</div>
    <div class="text-teal-600 font-semibold text-sm mt-1">{{ $item->formattedPrice() }}</div>
</div>
