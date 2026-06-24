@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
<h1 class="text-2xl font-bold mb-6">Pengguna & Akses Cabang</h1>

<div class="bg-white rounded-xl border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-500">
            <tr>
                <th class="p-3">Nama</th>
                <th class="p-3">Email</th>
                <th class="p-3">Role</th>
                <th class="p-3">Cabang</th>
                <th class="p-3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-t">
                    <td class="p-3 font-medium">{{ $user->name }}</td>
                    <td class="p-3">{{ $user->email }}</td>
                    <td class="p-3 capitalize">{{ $user->role }}</td>
                    <td class="p-3">
                        @if($user->isAdmin())
                            <span class="text-slate-500">Semua cabang</span>
                        @elseif($user->outlets->isEmpty())
                            <span class="text-red-600">Belum diassign</span>
                        @else
                            {{ $user->outlets->pluck('name')->join(', ') }}
                        @endif
                    </td>
                    <td class="p-3 text-right">
                        @if($user->isCashier())
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600">Assign cabang</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
