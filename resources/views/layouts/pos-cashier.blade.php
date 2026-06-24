<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('title', 'Kasir') — {{ $currentOutlet->name ?? config('app.name') }}</title>
    @include('partials.theme.head')
    @stack('head')
</head>
<body class="staff-bg text-slate-900 h-screen overflow-hidden flex flex-col">
    @include('partials.theme.staff-header')
    @include('partials.theme.flash-messages')

    <div class="flex flex-1 min-h-0 relative">
        @include('partials.theme.staff-sidebar')
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
