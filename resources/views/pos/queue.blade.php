@extends('layouts.app')

@section('title', 'Antrian Kasir')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="ui-page-title">Antrian Pesanan</h1>
        <p class="ui-page-subtitle text-slate-500">Kelola pesanan masuk dari QR & kasir</p>
    </div>
    <div class="flex items-center gap-3 text-sm">
        <span class="inline-flex items-center gap-1.5 text-slate-500 bg-white/80 border border-slate-200/80 px-3 py-1.5 rounded-xl">
            <span data-realtime-indicator class="w-2 h-2 rounded-full bg-teal-400"></span>
            Live
        </span>
        <a href="{{ route('kitchen.display') }}" target="_blank" class="text-teal-700 hover:text-teal-800 font-medium">Layar Dapur ↗</a>
        <a href="{{ route('pos.tables.index') }}" class="text-teal-700 hover:text-teal-800 font-medium">Meja</a>
    </div>
</div>

<div id="order-list" class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
    @forelse($orders as $order)
        @include('pos.partials.order-card', ['order' => $order])
    @empty
        <div id="empty-state" class="col-span-full text-center py-16 text-slate-500 ui-card">Tidak ada pesanan menunggu.</div>
    @endforelse
</div>
@endsection

@push('scripts')
    @include('partials.realtime-client', ['realtimeMode' => 'pos'])
@endpush
