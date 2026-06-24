@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="ui-page-title">Pengguna</h1>
        <p class="ui-page-subtitle">Kelola akun staff, role, dan akses cabang kasir</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="ui-btn-primary text-sm">+ Tambah Pengguna</a>
</div>

<div class="ui-card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50/80 text-left text-slate-500">
            <tr>
                <th class="p-3 font-medium">Nama</th>
                <th class="p-3 font-medium">Email</th>
                <th class="p-3 font-medium">Role</th>
                <th class="p-3 font-medium">Cabang</th>
                <th class="p-3 font-medium text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr class="border-t border-slate-100">
                    <td class="p-3 font-medium text-slate-900">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="text-[10px] bg-teal-100 text-teal-800 px-1.5 py-0.5 rounded ml-1">Anda</span>
                        @endif
                    </td>
                    <td class="p-3 text-slate-600">{{ $user->email }}</td>
                    <td class="p-3">
                        <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-medium {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-teal-100 text-teal-800' }}">
                            {{ $user->isAdmin() ? 'Admin' : 'Kasir' }}
                        </span>
                    </td>
                    <td class="p-3 text-slate-600">
                        @if($user->isAdmin())
                            <span class="text-slate-500">Semua cabang</span>
                        @elseif($user->outlets->isEmpty())
                            <span class="text-red-600 font-medium">Belum diassign</span>
                        @else
                            {{ $user->outlets->pluck('name')->join(', ') }}
                        @endif
                    </td>
                    <td class="p-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-teal-700 hover:text-teal-800 font-medium text-xs">Edit</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-xs font-medium">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500">Belum ada pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
