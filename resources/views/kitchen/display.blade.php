@extends('layouts.kitchen')

@section('title', 'Layar Dapur')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-slate-500 inline-flex items-center gap-2">
            <span data-realtime-indicator class="w-2 h-2 rounded-full bg-teal-400"></span>
            Live · update otomatis saat ada order baru
        </p>
        <div class="flex gap-2 text-xs">
            <span class="px-2.5 py-1.5 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 font-medium">Antrian: <strong id="count-pending">{{ $columns['counts']['pending'] }}</strong></span>
            <span class="px-2.5 py-1.5 rounded-xl bg-orange-50 border border-orange-200/80 text-orange-800 font-medium">Masak: <strong id="count-cooking">{{ $columns['counts']['cooking'] }}</strong></span>
            <span class="px-2.5 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 font-medium">Siap: <strong id="count-ready">{{ $columns['counts']['ready'] }}</strong></span>
        </div>
    </div>

    <div id="kitchen-board" class="grid lg:grid-cols-3 gap-4">
        <section class="ui-card min-h-[60vh] overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 font-semibold text-amber-700 bg-amber-50/50">Antrian Baru</div>
            <div id="column-pending" class="p-3 space-y-3">
                @forelse($columns['columns']['pending'] as $order)
                    @include('kitchen.partials.ticket', ['order' => $order, 'column' => 'pending'])
                @empty
                    <p class="text-sm text-slate-400 text-center py-8">Kosong</p>
                @endforelse
            </div>
        </section>
        <section class="ui-card min-h-[60vh] overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 font-semibold text-orange-700 bg-orange-50/50">Sedang Dimasak</div>
            <div id="column-cooking" class="p-3 space-y-3">
                @forelse($columns['columns']['cooking'] as $order)
                    @include('kitchen.partials.ticket', ['order' => $order, 'column' => 'cooking'])
                @empty
                    <p class="text-sm text-slate-400 text-center py-8">Kosong</p>
                @endforelse
            </div>
        </section>
        <section class="ui-card min-h-[60vh] overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 font-semibold text-emerald-700 bg-emerald-50/50">Siap Disajikan</div>
            <div id="column-ready" class="p-3 space-y-3">
                @forelse($columns['columns']['ready'] as $order)
                    @include('kitchen.partials.ticket', ['order' => $order, 'column' => 'ready'])
                @empty
                    <p class="text-sm text-slate-400 text-center py-8">Kosong</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
    @include('partials.realtime-client', ['realtimeMode' => 'kitchen'])
@endpush
