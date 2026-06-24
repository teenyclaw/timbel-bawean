<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — {{ config('app.name') }}</title>
    @include('partials.theme.head')
    <style>
        body { overflow: auto; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .login-card {
            background: white;
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow: 0 4px 6px rgba(15, 23, 42, 0.04), 0 20px 60px rgba(15, 23, 42, 0.08);
        }
        .login-input {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            font-size: 0.875rem;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .login-input:focus {
            outline: none;
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
        }
    </style>
</head>
<body class="staff-bg">

<div class="w-full max-w-md relative z-10">
    <div class="text-center mb-8">
        <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-600 to-teal-700 text-white items-center justify-center shadow-lg shadow-teal-600/30 mb-4">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm4 11h-1v3h-3v1h3v3h1v-3h3v-1h-3v-3z"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ config('app.name') }}</h1>
        <p class="text-sm text-slate-500 mt-1">Nasi Timbel Khas Sunda · Bandung</p>
    </div>

    <div class="login-card rounded-2xl p-8">
        @if ($errors->any())
            <div class="mb-5 p-3 bg-red-50 border border-red-200/80 text-red-800 rounded-xl text-sm flex items-start gap-2">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="mb-5 p-3 bg-emerald-50 border border-emerald-200/80 text-emerald-800 rounded-xl text-sm">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input id="email" class="login-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="nama@email.com">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    <input id="password" class="login-input pr-10" type="password" name="password" required autocomplete="current-password" placeholder="Password">
                    <button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-600 focus:ring-teal-500" {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium">Lupa password?</a>
                @endif
            </div>

            <button type="submit" id="submit-btn" class="ui-btn-primary w-full py-3.5 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Masuk ke Dashboard
            </button>
        </form>

        <p class="text-center text-xs text-slate-400 mt-6">Jl. Bawean No. 3 · Buka 07.30–17.00</p>
    </div>

    <div class="flex justify-center gap-3 mt-6 flex-wrap">
        <span class="text-xs text-slate-500 bg-white/80 border border-slate-200/80 rounded-full px-3 py-1">POS Kasir</span>
        <span class="text-xs text-slate-500 bg-white/80 border border-slate-200/80 rounded-full px-3 py-1">QR Menu</span>
        <span class="text-xs text-slate-500 bg-white/80 border border-slate-200/80 rounded-full px-3 py-1">Real-time</span>
    </div>
</div>

<script>
function togglePass() {
    const inp = document.getElementById('password');
    const ico = document.getElementById('eye-icon');
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
}
document.getElementById('login-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = 'Memverifikasi...';
});
</script>
</body>
</html>
