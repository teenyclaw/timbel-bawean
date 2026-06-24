<aside class="w-full sm:w-[360px] lg:w-[400px] shrink-0 bg-white/90 backdrop-blur-xl border-l border-slate-200/80 flex flex-col min-h-0 shadow-[-4px_0_24px_rgba(15,23,42,0.04)]">
    <div class="shrink-0 px-4 py-3 border-b border-slate-100 flex items-center justify-between">
        <h2 class="font-semibold text-slate-800">Keranjang</h2>
        @if(!empty($newOrderUrl))
            <form method="POST" action="{{ $newOrderUrl }}" onsubmit="return confirm('Mulai order baru? Item saat ini tetap tersimpan di antrian jika sudah dikirim.')">
                @csrf
                <button type="submit" class="text-xs text-slate-500 hover:text-teal-600">+ Order Baru</button>
            </form>
        @endif
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-2">
        @if($order->items->isEmpty())
            <div class="flex flex-col items-center justify-center h-full text-center py-12 px-4">
                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 6h15l-1.5 9h-12z"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/><path d="M6 6L5 3H2"/></svg>
                <p class="text-sm text-slate-500">Silakan masukkan pesanan dari pelanggan</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach($order->items as $item)
                    <div class="py-2.5 border-b border-slate-100 last:border-0">
                        <div class="flex gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-sm leading-snug">{{ $item->item_name }}</div>
                                @if($item->modifierSummary())
                                    <div class="text-xs text-purple-600 mt-0.5">{{ $item->modifierSummary() }}</div>
                                @endif
                                @if($item->note)
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $item->note }}</div>
                                @endif
                                <div class="text-xs text-slate-500 mt-1">{{ $item->formattedSubtotal() }}</div>
                                @if($item->is_paid)
                                    <span class="text-xs text-green-600">Lunas</span>
                                @endif
                            </div>
                            @if(!$item->is_paid)
                                <div class="flex flex-col items-end gap-1 shrink-0">
                                    <form method="POST" action="{{ route($updateItemRoute, [$order, $item]) }}" class="flex items-center gap-1">
                                        @csrf @method('PATCH')
                                        <button type="submit" name="qty" value="{{ max(1, $item->qty - 1) }}" class="w-7 h-7 rounded border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm">−</button>
                                        <span class="w-6 text-center text-sm font-medium">{{ $item->qty }}</span>
                                        <button type="submit" name="qty" value="{{ min(99, $item->qty + 1) }}" class="w-7 h-7 rounded border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm">+</button>
                                    </form>
                                    <form method="POST" action="{{ route($removeItemRoute, [$order, $item]) }}" onsubmit="return confirm('Hapus item?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-slate-400 text-sm">×{{ $item->qty }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="shrink-0 border-t border-slate-200 p-4 bg-slate-50">
        <div class="flex items-center justify-between mb-3">
            <span class="text-slate-600">Total</span>
            <span class="text-2xl font-bold text-slate-900">{{ $order->formattedTotal() }}</span>
        </div>

        <div class="flex gap-2 mb-3">
            @if(!empty($submitUrl) && $order->items->isNotEmpty())
                <form method="POST" action="{{ $submitUrl }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full text-sm border border-slate-300 bg-white text-slate-700 py-2.5 rounded-xl hover:bg-slate-50 font-medium">
                        Kirim Dapur
                    </button>
                </form>
            @endif
        </div>

        @if($order->items->isNotEmpty())
            <form method="POST" action="{{ $payUrl }}">
                @csrf
                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-4 rounded-xl flex items-center justify-between px-5 transition">
                    <span>Bayar</span>
                    <span class="flex items-center gap-2">
                        {{ $order->formattedTotal() }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </button>
            </form>
        @else
            <button type="button" disabled class="w-full bg-slate-200 text-slate-400 font-bold py-4 rounded-xl cursor-not-allowed">
                Bayar — Rp 0
            </button>
        @endif
    </div>
</aside>
