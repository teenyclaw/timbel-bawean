<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->whereIn('role', ['admin', 'cashier'])
            ->with('outlets')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $outlets = Outlet::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.users.create', compact('outlets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,cashier',
            'outlet_ids' => 'required_if:role,cashier|array|min:1',
            'outlet_ids.*' => 'integer|exists:outlets,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        if ($data['role'] === 'cashier') {
            $user->outlets()->sync($data['outlet_ids'] ?? []);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna ' . $user->name . ' berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'cashier'], true), 404);

        $outlets = Outlet::query()->where('is_active', true)->orderBy('name')->get();
        $assignedOutletIds = $user->isAdmin() ? [] : $user->outlets()->pluck('outlets.id')->all();

        return view('admin.users.edit', compact('user', 'outlets', 'assignedOutletIds'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'cashier'], true), 404);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,cashier',
            'outlet_ids' => 'required_if:role,cashier|array|min:1',
            'outlet_ids.*' => 'integer|exists:outlets,id',
        ]);

        if ($user->id === auth()->id() && $data['role'] !== 'admin') {
            return back()->with('error', 'Anda tidak dapat mengubah role akun sendiri menjadi non-admin.');
        }

        if ($user->isAdmin() && $data['role'] !== 'admin' && $this->adminCount() <= 1) {
            return back()->with('error', 'Tidak dapat mengubah role admin terakhir.');
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        if ($data['role'] === 'cashier') {
            $user->outlets()->sync($data['outlet_ids'] ?? []);
        } else {
            $user->outlets()->detach();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna ' . $user->name . ' diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'cashier'], true), 404);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->isAdmin() && $this->adminCount() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        $name = $user->name;
        $user->outlets()->detach();
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna ' . $name . ' dihapus.');
    }

    private function adminCount(): int
    {
        return User::query()->where('role', 'admin')->count();
    }
}
