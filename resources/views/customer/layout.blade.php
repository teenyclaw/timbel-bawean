<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('title', 'Menu') — {{ $outlet->name }}</title>
    @include('partials.theme.head')
    <style>
        .customer-header {
            background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #14b8a6 100%);
        }
        .customer-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 16px rgba(15, 23, 42, 0.04);
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .customer-card:hover {
            box-shadow: 0 4px 6px rgba(15, 23, 42, 0.05), 0 12px 32px rgba(15, 23, 42, 0.07);
            border-color: rgba(153, 246, 228, 0.8);
        }
    </style>
    @stack('head')
</head>
<body class="customer-bg text-slate-900 min-h-screen pb-24">
    <header class="sticky top-0 z-20 customer-header text-white px-4 py-3.5 shadow-lg shadow-teal-900/15">
        <div class="max-w-lg mx-auto flex items-center justify-between">
            <div>
                <div class="font-bold text-lg tracking-tight">{{ $outlet->name }}</div>
                <div class="text-xs text-teal-100/90">
                    @if(isset($table))
                        {{ $table->name }} · Pesan dari meja
                    @else
                        Pesan online · Nasi Timbel Bawean
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if(isset($table))
                    <a href="{{ route('customer.table.bill', [$outlet->slug, $table->token]) }}"
                       class="text-xs bg-white/15 hover:bg-white/25 backdrop-blur border border-white/20 px-3 py-1.5 rounded-xl font-medium transition">
                        Bill
                    </a>
                @endif
                <a href="{{ isset($table) ? route('customer.table.cart', [$outlet->slug, $table->token]) : route('customer.cart', $outlet->slug) }}"
                   class="relative inline-flex items-center justify-center w-11 h-11 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur border border-white/20 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6h15l-1.5 9h-12z"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/><path d="M6 6L5 3H2"/></svg>
                    @if(($cartCount ?? 0) > 0)
                        <span class="absolute -top-1.5 -right-1.5 min-w-[1.2rem] h-[1.2rem] px-1 text-[10px] font-bold bg-amber-400 text-amber-950 rounded-full flex items-center justify-center shadow">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    @if(session('success') || session('error'))
        <div class="max-w-lg mx-auto px-4 pt-3 space-y-2">
            @if(session('success'))
                <div class="p-3 bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-sm rounded-xl">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="p-3 bg-red-50 border border-red-200/80 text-red-800 text-sm rounded-xl">{{ session('error') }}</div>
            @endif
        </div>
    @endif

    <div class="max-w-lg mx-auto p-4">@yield('content')</div>
    @stack('scripts')
</body>
</html>
