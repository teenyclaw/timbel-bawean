@if(session('success') || session('error'))
    <div class="shrink-0 px-4 pt-2 space-y-2">
        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200/80 text-emerald-800 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-3 bg-red-50 border border-red-200/80 text-red-800 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif
    </div>
@endif
