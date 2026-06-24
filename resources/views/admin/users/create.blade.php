@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<a href="{{ route('admin.users.index') }}" class="text-sm text-teal-700 font-medium mb-4 inline-block hover:underline">← Daftar pengguna</a>
<h1 class="ui-page-title mb-1">Tambah Pengguna</h1>
<p class="text-sm text-slate-500 mb-6">Buat akun admin atau kasir baru</p>

<form method="POST" action="{{ route('admin.users.store') }}" class="ui-card p-5 max-w-lg space-y-4"
      x-data="{ role: '{{ old('role', 'cashier') }}' }">
    @csrf

    <div>
        <label class="text-sm font-medium text-slate-700">Nama *</label>
        <input type="text" name="name" value="{{ old('name') }}" required
            class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
        @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Email *</label>
        <input type="email" name="email" value="{{ old('email') }}" required
            class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
        @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Password *</label>
        <input type="password" name="password" required autocomplete="new-password"
            class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
        @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Konfirmasi Password *</label>
        <input type="password" name="password_confirmation" required autocomplete="new-password"
            class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Role *</label>
        <select name="role" x-model="role" required class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
            <option value="cashier">Kasir</option>
            <option value="admin">Admin</option>
        </select>
        @error('role')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div x-show="role === 'cashier'" x-cloak class="space-y-2 pt-2 border-t border-slate-100">
        <p class="text-sm font-medium text-slate-700">Cabang yang boleh diakses *</p>
        @forelse($outlets as $outlet)
            <label class="flex items-center gap-2 text-sm border border-slate-200 rounded-xl px-3 py-2 hover:border-teal-300">
                <input type="checkbox" name="outlet_ids[]" value="{{ $outlet->id }}"
                    @checked(in_array($outlet->id, old('outlet_ids', [])))>
                {{ $outlet->name }}
                <span class="text-slate-400 font-mono text-xs">/o/{{ $outlet->slug }}</span>
            </label>
        @empty
            <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-xl p-3">Belum ada cabang aktif. Buat cabang dulu.</p>
        @endforelse
        @error('outlet_ids')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    <button type="submit" class="ui-btn-primary">Simpan Pengguna</button>
</form>
@endsection
