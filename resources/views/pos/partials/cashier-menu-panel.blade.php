@php
    $allItems = $categories->flatMap->menuItems->merge($uncategorized);
@endphp

<section class="flex-1 min-w-0 flex flex-col" x-data="{
    search: '',
    category: 'all',
    matches(item) {
        const name = item.name.toLowerCase();
        const q = this.search.toLowerCase().trim();
        if (q && !name.includes(q)) return false;
        if (this.category === 'all') return true;
        if (this.category === 'none') return item.categoryId === null;
        return String(item.categoryId) === this.category;
    }
}">
    <div class="shrink-0 p-4 pb-2 bg-white/80 backdrop-blur border-b border-slate-200/80">
        @if(!empty($orderLabel))
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="font-semibold text-slate-800">{{ $orderLabel }}</div>
                    @if(!empty($orderNumber))
                        <div class="text-xs text-slate-500 font-mono">{{ $orderNumber }}</div>
                    @endif
                </div>
                @if(!empty($backUrl))
                    <a href="{{ $backUrl }}" class="text-sm text-teal-600 hover:underline">← Kembali</a>
                @endif
            </div>
        @endif

        <div class="relative mb-3">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="search" x-model="search" placeholder="Cari menu..." class="w-full border border-slate-200 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/30 focus:border-teal-500">
        </div>

        <div class="flex gap-2 overflow-x-auto pb-1 -mx-1 px-1 scrollbar-thin">
            <button type="button" @click="category = 'all'"
                :class="category === 'all' ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-slate-600 border-slate-200 hover:border-teal-300'"
                class="shrink-0 text-sm px-3 py-1.5 rounded-full border font-medium transition">Semua</button>
            @foreach($categories as $cat)
                @if($cat->menuItems->isNotEmpty())
                    <button type="button" @click="category = '{{ $cat->id }}'"
                        :class="category === '{{ $cat->id }}' ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-slate-600 border-slate-200 hover:border-teal-300'"
                        class="shrink-0 text-sm px-3 py-1.5 rounded-full border font-medium transition">{{ $cat->name }}</button>
                @endif
            @endforeach
            @if($uncategorized->isNotEmpty())
                <button type="button" @click="category = 'none'"
                    :class="category === 'none' ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-slate-600 border-slate-200 hover:border-teal-300'"
                    class="shrink-0 text-sm px-3 py-1.5 rounded-full border font-medium transition">Lainnya</button>
            @endif
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-4">
        @if($allItems->isEmpty())
            <div class="text-center py-16 text-slate-500">Belum ada menu tersedia.</div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($allItems as $item)
                    <div x-show="matches({ name: @js($item->name), categoryId: {{ $item->category_id ? $item->category_id : 'null' }} })"
                         x-transition.opacity>
                        @include('pos.partials.product-card', [
                            'item' => $item,
                            'formAction' => $addItemUrl,
                        ])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
