<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CurrentOutletService;
use Illuminate\Http\Request;

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

    public function edit(User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'cashier'], true), 404);

        $outlets = \App\Models\Outlet::query()->where('is_active', true)->orderBy('name')->get();
        $assignedOutletIds = $user->isAdmin() ? [] : $user->outlets()->pluck('outlets.id')->all();

        return view('admin.users.edit', compact('user', 'outlets', 'assignedOutletIds'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(in_array($user->role, ['admin', 'cashier'], true), 404);

        if ($user->isCashier()) {
            $data = $request->validate([
                'outlet_ids' => 'required|array|min:1',
                'outlet_ids.*' => 'integer|exists:outlets,id',
            ]);

            $user->outlets()->sync($data['outlet_ids']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Akses cabang kasir diperbarui.');
    }
}
