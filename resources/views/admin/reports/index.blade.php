@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<h1 class="text-2xl font-bold mb-6">Laporan Penjualan</h1>

<form method="GET" class="bg-white rounded-xl border p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="text-sm">Dari</label>
        <input type="date" name="from" value="{{ $from }}" class="border rounded-lg px-3 py-2 mt-1 block">
    </div>
    <div>
        <label class="text-sm">Sampai</label>
        <input type="date" name="to" value="{{ $to }}" class="border rounded-lg px-3 py-2 mt-1 block">
    </div>
    <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg">Filter</button>
    <a href="{{ route('admin.reports.export', ['from' => $from, 'to' => $to]) }}" class="border px-4 py-2 rounded-lg text-sm">Export CSV</a>
</form>

<div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border p-4">
        <div class="text-sm text-slate-500">Order lunas</div>
        <div class="text-2xl font-bold">{{ $stats['orders'] }}</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-sm text-slate-500">Omzet</div>
        <div class="text-2xl font-bold text-emerald-700">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="text-sm text-slate-500">Pembayaran</div>
        <div class="text-2xl font-bold">{{ $stats['payments'] }}</div>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-4 mb-6 text-sm">
    <div class="bg-white rounded-xl border p-4">Tunai: <strong>Rp {{ number_format($stats['cash'], 0, ',', '.') }}</strong></div>
    <div class="bg-white rounded-xl border p-4">Transfer: <strong>Rp {{ number_format($stats['transfer'], 0, ',', '.') }}</strong></div>
    <div class="bg-white rounded-xl border p-4">QRIS: <strong>Rp {{ number_format($stats['qris'], 0, ',', '.') }}</strong></div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border p-4">
        <h2 class="font-semibold mb-3">Menu Terlaris</h2>
        @forelse($topItems as $row)
            <div class="flex justify-between py-2 border-t text-sm">
                <span>{{ $row['item_name'] }}</span>
                <span>{{ $row['total_qty'] }} pcs · Rp {{ number_format($row['total_revenue'], 0, ',', '.') }}</span>
            </div>
        @empty
            <p class="text-slate-500 text-sm">Tidak ada data.</p>
        @endforelse
    </div>
    <div class="bg-white rounded-xl border p-4">
        <h2 class="font-semibold mb-3">Order Terbaru (periode)</h2>
        @forelse($recentOrders as $order)
            <a href="{{ route('pos.orders.show', $order) }}" class="flex justify-between py-2 border-t text-sm hover:bg-slate-50">
                <span class="font-mono">{{ $order->order_number }}</span>
                <span>{{ $order->formattedTotal() }}</span>
            </a>
        @empty
            <p class="text-slate-500 text-sm">Tidak ada order.</p>
        @endforelse
    </div>
</div>
@endsection
