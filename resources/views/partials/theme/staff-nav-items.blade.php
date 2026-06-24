@php
    $nav = fn (bool $active) => 'nav-item ' . ($active ? 'nav-item-active' : 'nav-item-idle');
@endphp

<div class="px-3 pt-2 pb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Operasional</div>

<a href="{{ route('pos.cashier') }}" class="{{ $nav(request()->routeIs('pos.cashier*')) }}">
    <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
    Kasir POS
</a>
<a href="{{ route('pos.queue') }}" class="{{ $nav(request()->routeIs('pos.queue') || request()->routeIs('pos.orders.*') || request()->routeIs('pos.payment') || request()->routeIs('pos.receipt')) }}">
    <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
    Antrian Order
    @if(($pendingCount ?? 0) > 0)
        <span class="ml-auto bg-amber-400 text-amber-950 text-[10px] font-bold min-w-[1.1rem] h-4 px-1 rounded-full inline-flex items-center justify-center">{{ $pendingCount }}</span>
    @endif
</a>
<a href="{{ route('pos.tables.index') }}" class="{{ $nav(request()->routeIs('pos.tables.*')) }}">
    <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
    Meja / Open Bill
</a>
<a href="{{ route('pos.shift.show') }}" class="{{ $nav(request()->routeIs('pos.shift.*') && !request()->routeIs('admin.shifts.*')) }}">
    <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Shift Kasir
</a>
<a href="{{ route('kitchen.display') }}" target="_blank" class="{{ $nav(false) }}">
    <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    Layar Dapur
    <span class="ml-auto text-[10px] text-slate-400">↗</span>
</a>

@if(auth()->user()->isAdmin())
    <div class="my-2 mx-3 border-t border-slate-200/80"></div>
    <div class="px-3 pt-1 pb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Admin</div>

    <a href="{{ route('admin.dashboard') }}" class="{{ $nav(request()->routeIs('admin.dashboard')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        Dashboard
    </a>
    <a href="{{ route('admin.menu-items.index') }}" class="{{ $nav(request()->routeIs('admin.menu-items.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        Menu & Kategori
    </a>
    <a href="{{ route('admin.categories.index') }}" class="{{ $nav(request()->routeIs('admin.categories.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
        Kategori
    </a>
    <a href="{{ route('admin.menu-copy.index') }}" class="{{ $nav(request()->routeIs('admin.menu-copy.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        Copy Menu
    </a>
    <a href="{{ route('admin.inventory.index') }}" class="{{ $nav(request()->routeIs('admin.inventory.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        Stok
    </a>
    <a href="{{ route('admin.outlets.index') }}" class="{{ $nav(request()->routeIs('admin.outlets.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        Cabang & Meja
    </a>
    <a href="{{ route('admin.tables.index') }}" class="{{ $nav(request()->routeIs('admin.tables.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
        Meja & QR
    </a>
    <a href="{{ route('admin.orders.index') }}" class="{{ $nav(request()->routeIs('admin.orders.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Riwayat Order
    </a>
    <a href="{{ route('admin.reports.index') }}" class="{{ $nav(request()->routeIs('admin.reports.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Laporan
    </a>
    <a href="{{ route('admin.shifts.index') }}" class="{{ $nav(request()->routeIs('admin.shifts.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Riwayat Shift
    </a>
    <a href="{{ route('admin.users.index') }}" class="{{ $nav(request()->routeIs('admin.users.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        Pengguna
    </a>
    <a href="{{ route('admin.loyalty.index') }}" class="{{ $nav(request()->routeIs('admin.loyalty.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        Loyalty
    </a>
    <a href="{{ route('admin.notifications.index') }}" class="{{ $nav(request()->routeIs('admin.notifications.*')) }}">
        <svg class="w-5 h-5 shrink-0 opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        Notifikasi
    </a>
@endif
