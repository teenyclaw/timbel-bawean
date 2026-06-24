<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('title', 'Kitchen Display') — {{ $outlet->name ?? config('app.name') }}</title>
    @include('partials.theme.head')
    @stack('head')
</head>
<body class="staff-bg text-slate-900 min-h-screen flex flex-col">
    <header class="bg-gradient-to-r from-teal-700 via-teal-600 to-teal-700 text-white shrink-0 flex items-center justify-between px-4 lg:px-6 h-14 shadow-lg shadow-teal-900/10 sticky top-0 z-20">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8h1a4 4 0 010 8h-1M3 8h14v9a4 4 0 01-4 4H7a4 4 0 01-4-4V8z"/></svg>
            </div>
            <div>
                <div class="font-bold text-base">Kitchen Display</div>
                <div class="text-[11px] text-teal-100/90">{{ $outlet->name ?? 'Dapur' }}</div>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm">
            <span id="kitchen-clock" class="text-teal-100 font-mono text-sm bg-white/10 px-2.5 py-1 rounded-lg"></span>
            <a href="{{ route('pos.cashier') }}" class="text-teal-100 hover:text-white text-sm font-medium hover:bg-white/10 px-2.5 py-1 rounded-lg transition">← Kasir</a>
        </div>
    </header>

    @if(session('success') || session('error'))
        <div class="px-4 pt-3 space-y-2 max-w-7xl mx-auto w-full">
            @if(session('success'))
                <div class="p-3 bg-emerald-50 border border-emerald-200/80 text-emerald-800 rounded-xl text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="p-3 bg-red-50 border border-red-200/80 text-red-800 rounded-xl text-sm">{{ session('error') }}</div>
            @endif
        </div>
    @endif

    <div class="flex-1 p-4 md:p-6">@yield('content')</div>

    <script>
    function tickClock() {
        const el = document.getElementById('kitchen-clock');
        if (el) el.textContent = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }
    tickClock();
    setInterval(tickClock, 30000);
    </script>
    @stack('scripts')
</body>
</html>
