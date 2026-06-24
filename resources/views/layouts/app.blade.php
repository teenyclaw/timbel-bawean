<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('head')
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
            <div class="p-5 border-b border-slate-700">
                <div class="font-bold text-lg">{{ config('app.name') }}</div>
                <div class="text-xs text-slate-400">Nasi Timbel · QR Menu & POS</div>
                @if(!empty($currentOutlet))
                    <div class="mt-3">
                        @if(($accessibleOutlets ?? collect())->count() > 1)
                            <form method="POST" action="{{ route('outlet.switch') }}">
                                @csrf
                                <label class="text-xs text-slate-400 block mb-1">Cabang aktif</label>
                                <select name="outlet_id" onchange="this.form.submit()" class="w-full bg-slate-800 border border-slate-600 text-white text-xs rounded-lg px-2 py-1.5">
                                    @foreach($accessibleOutlets as $o)
                                        <option value="{{ $o->id }}" @selected($o->id === $currentOutlet->id)>{{ $o->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @else
                            <div class="text-xs text-slate-300 mt-1">{{ $currentOutlet->name }}</div>
                        @endif
                    </div>
                @endif
            </div>
            <nav class="flex-1 p-3 space-y-1 text-sm">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Dashboard</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Kategori</a>
                    <a href="{{ route('admin.menu-items.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.menu-items.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Menu</a>
                    <a href="{{ route('admin.menu-copy.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.menu-copy.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Copy Menu</a>
                    <a href="{{ route('admin.inventory.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.inventory.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Stok</a>
                    <a href="{{ route('admin.loyalty.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.loyalty.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Loyalty</a>
                    <a href="{{ route('admin.notifications.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.notifications.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Notifikasi</a>
                    <a href="{{ route('admin.outlets.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.outlets.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Cabang / Outlet</a>
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Pengguna</a>
                    <a href="{{ route('admin.tables.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.tables.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Meja & QR</a>
                    <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Riwayat Order</a>
                    <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Laporan</a>
                    <a href="{{ route('admin.shifts.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('admin.shifts.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Shift Kasir</a>
                @endif
                <a href="{{ route('pos.shift.show') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('pos.shift.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Shift Saya</a>
                <a href="{{ route('pos.tables.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('pos.tables.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">Meja / Open Bill</a>
                <a href="{{ route('pos.queue') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('pos.queue') || request()->routeIs('pos.orders.*') || request()->routeIs('pos.payment') || request()->routeIs('pos.receipt') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">
                    Kasir / Antrian
                    <span id="pending-badge" class="ml-1 hidden inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-xs bg-amber-500 text-white rounded-full"></span>
                </a>
                <a href="{{ route('kitchen.display') }}" target="_blank" class="block px-3 py-2 rounded-lg {{ request()->routeIs('kitchen.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">
                    Layar Dapur (KDS)
                    <span id="kitchen-badge" class="ml-1 hidden inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-xs bg-orange-500 text-white rounded-full"></span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-700 text-xs text-slate-400">
                <div class="text-white font-medium">{{ auth()->user()->name }}</div>
                <div class="capitalize">{{ auth()->user()->role }}</div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-red-300 hover:text-red-200">Keluar</button>
                </form>
            </div>
        </aside>
        <main class="flex-1 p-6 overflow-auto">
            @if(!empty($currentOutlet))
                <div class="mb-4 text-xs text-slate-500">Cabang: <strong class="text-slate-700">{{ $currentOutlet->name }}</strong></div>
            @endif
            @if(empty($currentShift) && config('pos.require_open_shift', true) && !request()->routeIs('pos.shift.*'))
                <div class="mb-4 p-3 bg-amber-50 border border-amber-200 text-amber-900 rounded-lg text-sm flex flex-wrap items-center justify-between gap-2">
                    <span>Shift kasir belum dibuka. Buka shift sebelum menerima pembayaran.</span>
                    <a href="{{ route('pos.shift.show') }}" class="font-medium underline">Buka shift →</a>
                </div>
            @elseif(!empty($currentShift))
                <div class="mb-4 p-2 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs flex justify-between items-center">
                    <span>Shift aktif sejak {{ $currentShift->opened_at->format('H:i') }}</span>
                    <a href="{{ route('pos.shift.show') }}" class="underline">Kelola shift</a>
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
