<header class="bg-gradient-to-r from-teal-700 via-teal-600 to-teal-700 text-white shrink-0 flex items-center justify-between px-4 lg:px-6 h-14 shadow-lg shadow-teal-900/10 z-30">
    <div class="flex items-center gap-3 min-w-0">
        <button type="button"
                class="md:hidden p-2 -ml-1 rounded-lg text-white/90 hover:bg-white/15 transition"
                @click="$dispatch('toggle-mobile-nav')"
                aria-label="Buka menu navigasi">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('pos.cashier') }}" class="flex items-center gap-2.5 min-w-0 group">
            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center shrink-0 group-hover:bg-white/30 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm4 11h-1v3h-3v1h3v3h1v-3h3v-1h-3v-3z"/></svg>
            </div>
            <div class="min-w-0">
                <div class="font-bold text-base leading-tight truncate">{{ config('app.name') }}</div>
                @if(!empty($currentOutlet))
                    <div class="text-[11px] text-teal-100/90 truncate hidden sm:block">{{ $currentOutlet->name }}</div>
                @endif
            </div>
        </a>
        <span class="hidden lg:inline-flex items-center gap-1.5 text-[11px] bg-white/15 backdrop-blur px-2.5 py-1 rounded-full border border-white/10">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span> Online
        </span>
    </div>

    <div class="flex items-center gap-2 sm:gap-3">
        @if(($accessibleOutlets ?? collect())->count() > 1)
            <form method="POST" action="{{ route('outlet.switch') }}" class="hidden md:block">
                @csrf
                <select name="outlet_id" onchange="this.form.submit()"
                    class="bg-white/15 border border-white/20 text-white text-xs rounded-lg px-2 py-1.5 max-w-[140px] backdrop-blur">
                    @foreach($accessibleOutlets as $o)
                        <option value="{{ $o->id }}" @selected($o->id === $currentOutlet->id) class="text-slate-900">{{ $o->name }}</option>
                    @endforeach
                </select>
            </form>
        @endif

        @if(!empty($currentShift))
            <span class="hidden sm:inline text-xs text-teal-100 bg-white/10 px-2.5 py-1 rounded-lg">Shift {{ $currentShift->opened_at->format('H:i') }}</span>
        @elseif(config('pos.require_open_shift', true))
            <a href="{{ route('pos.shift.show') }}" class="text-xs bg-amber-400 text-amber-950 font-medium px-2.5 py-1 rounded-lg hover:bg-amber-300 transition">Buka Shift</a>
        @endif

        <a href="{{ route('pos.queue') }}"
           class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 backdrop-blur text-sm font-medium px-3 py-1.5 rounded-xl border border-white/10 transition">
            Antrian
            @if(($pendingCount ?? 0) > 0)
                <span class="bg-amber-400 text-amber-950 text-xs font-bold min-w-[1.25rem] h-5 px-1 rounded-full inline-flex items-center justify-center">{{ $pendingCount }}</span>
            @endif
        </a>

        <div class="hidden sm:flex items-center gap-2 pl-2 border-l border-white/20">
            <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="hidden lg:inline text-sm text-teal-50 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-teal-200 hover:text-white text-xs font-medium px-2 py-1 rounded-lg hover:bg-white/10 transition">Keluar</button>
            </form>
        </div>
    </div>
</header>
