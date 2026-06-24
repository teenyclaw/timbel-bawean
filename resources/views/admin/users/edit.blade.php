@extends('layouts.app')

@section('title', 'Edit — ' . $user->name)

@section('content')
<a href="{{ route('admin.users.index') }}" class="text-sm text-teal-700 font-medium mb-4 inline-block hover:underline">← Daftar pengguna</a>
<h1 class="ui-page-title mb-1">Edit Pengguna</h1>
<p class="text-sm text-slate-500 mb-6">{{ $user->email }}</p>

<form method="POST" action="{{ route('admin.users.update', $user) }}" class="ui-card p-5 max-w-lg space-y-4"
      x-data="{ role: '{{ old('role', $user->role) }}' }">
    @csrf @method('PUT')

    <div>
        <label class="text-sm font-medium text-slate-700">Nama *</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
            class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
        @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Email *</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
            class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
        @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="border-t border-slate-100 pt-4">
        <p class="text-sm font-medium text-slate-700 mb-2">Reset Password</p>
        <p class="text-xs text-slate-500 mb-3">Kosongkan jika tidak ingin mengubah password.</p>
        <div class="space-y-3">
            <div>
                <label class="text-sm text-slate-600">Password baru</label>
                <input type="password" name="password" autocomplete="new-password"
                    class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
                @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-sm text-slate-600">Konfirmasi password</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"
                    class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1">
            </div>
        </div>
    </div>

    <div>
        <label class="text-sm font-medium text-slate-700">Role *</label>
        <select name="role" x-model="role" required class="w-full border border-slate-200 rounded-xl px-3 py-2 mt-1"
            @if($user->id === auth()->id()) disabled @endif>
            <option value="cashier">Kasir</option>
            <option value="admin">Admin</option>
        </select>
        @if($user->id === auth()->id())
            <input type="hidden" name="role" value="admin">
            <p class="text-xs text-slate-500 mt-1">Role akun sendiri tidak dapat diubah.</p>
        @endif
        @error('role')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div x-show="role === 'cashier'" x-cloak class="space-y-2 pt-2 border-t border-slate-100">
        <p class="text-sm font-medium text-slate-700">Cabang yang boleh diakses *</p>
        @foreach($outlets as $outlet)
            <label class="flex items-center gap-2 text-sm border border-slate-200 rounded-xl px-3 py-2 hover:border-teal-300">
                <input type="checkbox" name="outlet_ids[]" value="{{ $outlet->id }}"
                    @checked(in_array($outlet->id, old('outlet_ids', $assignedOutletIds)))>
                {{ $outlet->name }}
                <span class="text-slate-400 font-mono text-xs">/o/{{ $outlet->slug }}</span>
            </label>
        @endforeach
        @error('outlet_ids')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="ui-btn-primary">Simpan Perubahan</button>
    </div>
</form>
@endsection
