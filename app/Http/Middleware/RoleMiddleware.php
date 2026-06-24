<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! auth()->check() || auth()->user()->role !== $role) {
            if ($request->expectsJson()) {
                abort(403, 'Akses ditolak.');
            }

            if (auth()->user()?->role === 'cashier') {
                return redirect()->route('pos.queue')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('pos.queue')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
