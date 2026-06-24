<div x-data="{ mobileOpen: false }"
     @toggle-mobile-nav.window="mobileOpen = !mobileOpen"
     @keydown.escape.window="mobileOpen = false">

    {{-- Mobile backdrop --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="md:hidden fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm"
         @click="mobileOpen = false"
         style="display: none;"></div>

    {{-- Mobile drawer --}}
    <aside x-show="mobileOpen"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="md:hidden fixed inset-y-0 left-0 z-50 w-[min(17rem,85vw)] flex flex-col bg-white/95 backdrop-blur-xl border-r border-slate-200/80 shadow-2xl pt-14"
           style="display: none;">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 shrink-0">
            <span class="text-sm font-semibold text-slate-700">Menu</span>
            <button type="button" @click="mobileOpen = false" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100" aria-label="Tutup menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto py-3 space-y-0.5 px-2 min-h-0" @click="if ($event.target.closest('a')) mobileOpen = false">
            @include('partials.theme.staff-nav-items')
        </nav>
        <div class="p-4 border-t border-slate-100 shrink-0">
            <div class="text-xs text-slate-500 truncate">{{ auth()->user()->name }}</div>
            <div class="text-[10px] text-slate-400">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Kasir' }}</div>
        </div>
    </aside>
</div>

{{-- Desktop sidebar --}}
<aside class="hidden md:flex w-56 lg:w-60 shrink-0 h-full min-h-0 flex-col bg-white/70 backdrop-blur-xl border-r border-slate-200/80 shadow-sm z-20">
    <nav class="flex-1 overflow-y-auto py-4 space-y-0.5 px-2 min-h-0">
        @include('partials.theme.staff-nav-items')
    </nav>
    <div class="p-4 border-t border-slate-100 shrink-0">
        <div class="text-xs text-slate-500 truncate">{{ auth()->user()->name }}</div>
        <div class="text-[10px] text-slate-400">{{ auth()->user()->isAdmin() ? 'Administrator' : 'Kasir' }}</div>
    </div>
</aside>

{{-- Mobile bottom nav --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-30 bg-white/95 backdrop-blur-xl border-t border-slate-200/80 safe-area-pb">
    <div class="flex items-stretch justify-around h-14">
        <a href="{{ route('pos.cashier') }}" class="flex flex-col items-center justify-center flex-1 text-[10px] font-medium {{ request()->routeIs('pos.cashier*') ? 'text-teal-700' : 'text-slate-500' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            Kasir
        </a>
        <a href="{{ route('pos.queue') }}" class="flex flex-col items-center justify-center flex-1 text-[10px] font-medium relative {{ request()->routeIs('pos.queue') || request()->routeIs('pos.orders.*') ? 'text-teal-700' : 'text-slate-500' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
            Antrian
            @if(($pendingCount ?? 0) > 0)
                <span class="absolute top-1 right-[calc(50%-1.25rem)] bg-amber-400 text-amber-950 text-[9px] font-bold min-w-[1rem] h-4 px-0.5 rounded-full inline-flex items-center justify-center">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('pos.tables.index') }}" class="flex flex-col items-center justify-center flex-1 text-[10px] font-medium {{ request()->routeIs('pos.tables.*') ? 'text-teal-700' : 'text-slate-500' }}">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/></svg>
            Meja
        </a>
        <button type="button"
                @click="$dispatch('toggle-mobile-nav')"
                class="flex flex-col items-center justify-center flex-1 text-[10px] font-medium text-slate-500 hover:text-teal-700">
            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            Lainnya
        </button>
    </div>
</nav>
