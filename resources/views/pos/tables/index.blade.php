@extends('layouts.app')

@section('title', 'Meja')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="ui-page-title">Meja & Open Bill</h1>
        <p class="ui-page-subtitle">Kelola bill aktif per meja</p>
    </div>
    <a href="{{ route('pos.queue') }}" class="text-sm text-teal-700 font-medium hover:text-teal-800">Antrian pesanan →</a>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach($tables as $table)
        @php $bill = $table->activeBill(); @endphp
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 {{ $bill ? 'border-teal-300/80 ring-2 ring-teal-100 shadow-sm' : 'shadow-sm' }}">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <div class="font-bold text-lg">{{ $table->name }}</div>
                    <div class="text-xs text-slate-500">{{ $bill ? 'Bill aktif' : 'Kosong' }}</div>
                </div>
                @if($bill)
                    <span class="px-2 py-0.5 rounded text-xs {{ $bill->statusEnum()->badgeClass() }}">{{ $bill->statusEnum()->label() }}</span>
                @else
                    <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">Tersedia</span>
                @endif
            </div>

            @if($bill)
                <div class="text-sm mb-3 space-y-1">
                    <div class="font-mono text-purple-700">{{ $bill->order_number }}</div>
                    <div>{{ $bill->items->sum('qty') }} item · <strong>{{ $bill->formattedTotal() }}</strong></div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('pos.tables.order', $bill) }}" class="text-sm bg-teal-600 hover:bg-teal-700 text-white px-3 py-1.5 rounded-xl font-medium transition">Waiter Order</a>
                    <form method="POST" action="{{ route('pos.tables.close', $bill) }}">@csrf
                        <button type="submit" class="text-sm bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-xl font-medium transition">Close & Bayar</button>
                    </form>
                </div>
            @else
                <form method="POST" action="{{ route('pos.tables.open', $table) }}">@csrf
                    <button type="submit" class="w-full text-sm border border-slate-300 px-3 py-2 rounded-lg hover:bg-slate-50">Buka Bill (Waiter)</button>
                </form>
            @endif
        </div>
    @endforeach
</div>

@if($tables->isEmpty())
    <div class="text-center py-16 text-slate-500 bg-white rounded-xl border">Belum ada meja. Admin dapat menambahkan di menu Meja.</div>
@endif
@endsection
