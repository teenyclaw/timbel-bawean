@if(empty($currentShift) && config('pos.require_open_shift', true) && !request()->routeIs('pos.shift.*'))
    <div class="mb-5 p-4 bg-amber-50/90 border border-amber-200/80 text-amber-900 rounded-2xl text-sm flex flex-wrap items-center justify-between gap-3 shadow-sm">
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Shift kasir belum dibuka. Buka shift sebelum menerima pembayaran.
        </span>
        <a href="{{ route('pos.shift.show') }}" class="ui-btn-primary text-sm py-2">Buka shift</a>
    </div>
@elseif(!empty($currentShift) && !request()->routeIs('pos.shift.*'))
    <div class="mb-5 p-3 bg-emerald-50/90 border border-emerald-200/80 text-emerald-800 rounded-2xl text-xs flex justify-between items-center shadow-sm">
        <span class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Shift aktif sejak {{ $currentShift->opened_at->format('H:i') }}
        </span>
        <a href="{{ route('pos.shift.show') }}" class="font-medium text-emerald-700 hover:underline">Kelola shift →</a>
    </div>
@endif
