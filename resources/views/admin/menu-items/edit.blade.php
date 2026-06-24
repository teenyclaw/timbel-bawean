@extends('layouts.app')

@section('title', 'Edit — ' . $menuItem->name)

@section('content')
<a href="{{ route('admin.menu-items.index') }}" class="text-sm text-teal-700 font-medium mb-4 inline-block hover:underline">← Kembali ke Menu</a>
<h1 class="ui-page-title mb-1">Edit Menu</h1>
<p class="text-sm text-slate-500 mb-6">{{ $menuItem->name }} · {{ $menuItem->formattedPrice() }}</p>

<form method="POST" action="{{ route('admin.menu-items.update', $menuItem) }}" enctype="multipart/form-data" class="ui-card p-5 mb-6 grid md:grid-cols-3 gap-4">
    @csrf @method('PUT')

    <div class="md:col-span-3 font-semibold text-slate-800 border-b border-slate-100 pb-2">Informasi Menu</div>

    <div>
        <label class="text-sm font-medium text-slate-700">Nama *</label>
        <input type="text" name="name" value="{{ old('name', $menuItem->name) }}" required class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Kategori</label>
        <select name="category_id" class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
            <option value="">— Tanpa kategori —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(old('category_id', $menuItem->category_id) == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Harga (Rp) *</label>
        <input type="number" name="price" value="{{ old('price', $menuItem->price) }}" required min="0" class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-700">Deskripsi</label>
        <input type="text" name="description" value="{{ old('description', $menuItem->description) }}" class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Urutan tampil</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $menuItem->sort_order) }}" min="0" class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Foto</label>
        @if($menuItem->photoUrl())
            <img src="{{ $menuItem->photoUrl() }}" alt="{{ $menuItem->name }}" class="w-20 h-20 object-cover rounded-xl mt-1 mb-2 border border-slate-200">
        @endif
        <input type="file" name="photo" accept="image/*" class="w-full text-sm">
        <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti foto.</p>
    </div>
    <div class="flex items-end">
        @unless($menuItem->track_stock)
            <label class="inline-flex items-center gap-2 text-sm pb-2"><input type="checkbox" name="is_available" value="1" @checked(old('is_available', $menuItem->is_available))> Tersedia</label>
        @else
            <span class="text-xs text-slate-500 pb-2">Ketersediaan otomatis dari stok</span>
        @endunless
    </div>

    <div class="md:col-span-3 font-semibold text-slate-800 border-b border-slate-100 pb-2 pt-2">Pengaturan Stok</div>

    <div>
        <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="track_stock" value="1" @checked(old('track_stock', $menuItem->track_stock))> Lacak stok otomatis</label>
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Stok saat ini</label>
        <input type="number" name="stock_qty" min="0" value="{{ old('stock_qty', $menuItem->stock_qty) }}" class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Batas stok menipis</label>
        <input type="number" name="low_stock_threshold" min="0" value="{{ old('low_stock_threshold', $menuItem->low_stock_threshold) }}" class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
    </div>

    <div class="md:col-span-3 pt-2">
        <button type="submit" class="ui-btn-primary">Simpan Perubahan</button>
    </div>
</form>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="space-y-4">
        <h2 class="font-semibold text-slate-800">Varian / Modifier</h2>

        <form method="POST" action="{{ route('admin.modifier-groups.store', $menuItem) }}" class="ui-card p-4 space-y-3">
            @csrf
            <h3 class="font-semibold text-sm">Tambah Grup Varian</h3>
            <div>
                <label class="text-sm">Nama grup *</label>
                <input type="text" name="name" required placeholder="Contoh: Level Pedas, Ukuran, Topping" class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm">Tipe pilihan</label>
                    <select name="selection_type" class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
                        <option value="single">Pilih satu (radio)</option>
                        <option value="multiple">Pilih banyak (checkbox)</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm">Min. pilih</label>
                    <input type="number" name="min_select" value="1" min="0" max="10" class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
                </div>
            </div>
            <button type="submit" class="ui-btn-primary text-sm py-2">Tambah Grup</button>
        </form>

        @forelse($menuItem->modifierGroups as $group)
            <div class="ui-card p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold">{{ $group->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $group->isSingle() ? 'Pilih satu' : 'Pilih banyak' }} · min {{ $group->min_select }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.modifier-groups.destroy', $group) }}" onsubmit="return confirm('Hapus grup dan semua opsinya?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-600">Hapus grup</button>
                    </form>
                </div>

                <ul class="space-y-1 mb-3 text-sm">
                    @forelse($group->options as $opt)
                        <li class="flex justify-between items-center py-1 border-t border-slate-100">
                            <span>
                                {{ $opt->name }}
                                @if($opt->is_default)<span class="text-xs text-emerald-600">(default)</span>@endif
                                @if($opt->price_adjustment !== 0)<span class="text-xs text-slate-500">{{ $opt->formattedAdjustment() }}</span>@endif
                            </span>
                            <form method="POST" action="{{ route('admin.modifier-options.destroy', $opt) }}">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-500">×</button>
                            </form>
                        </li>
                    @empty
                        <li class="text-xs text-slate-400">Belum ada opsi.</li>
                    @endforelse
                </ul>

                <form method="POST" action="{{ route('admin.modifier-options.store', $group) }}" class="grid grid-cols-3 gap-2 items-end">
                    @csrf
                    <div class="col-span-2">
                        <label class="text-xs">Nama opsi</label>
                        <input type="text" name="name" required placeholder="Level 1 / Large / Keju" class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-sm mt-0.5">
                    </div>
                    <div>
                        <label class="text-xs">+/- Rp</label>
                        <input type="number" name="price_adjustment" value="0" class="w-full border border-slate-200 rounded-lg px-2 py-1.5 text-sm mt-0.5">
                    </div>
                    <label class="col-span-2 text-xs inline-flex items-center gap-1"><input type="checkbox" name="is_default" value="1"> Default</label>
                    <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-sm px-3 py-1.5 rounded-lg">+ Opsi</button>
                </form>
            </div>
        @empty
            <p class="text-sm text-slate-500 ui-card p-4">Menu ini belum punya varian. Pelanggan akan pesan harga dasar saja.</p>
        @endforelse
    </div>

    <div class="ui-card p-4 text-sm text-slate-600">
        <h3 class="font-semibold text-slate-800 mb-2">Contoh setup varian</h3>
        <p class="mb-2"><strong>Ayam Geprek</strong></p>
        <ul class="list-disc pl-5 space-y-1 mb-4">
            <li>Grup "Level Pedas" (single, min 1): Level 1, Level 2 (+2.000), Level 3 (+4.000)</li>
            <li>Grup "Topping" (multiple, min 0): Keju (+3.000), Telur (+5.000)</li>
        </ul>
        <p class="mb-2"><strong>Es Teh Manis</strong></p>
        <ul class="list-disc pl-5 space-y-1">
            <li>Grup "Ukuran" (single, min 1): Regular (default), Jumbo (+3.000)</li>
        </ul>
    </div>
</div>
@endsection
