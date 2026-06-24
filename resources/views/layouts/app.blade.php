<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('title', config('app.name')) — {{ $currentOutlet->name ?? config('app.name') }}</title>
    @include('partials.theme.head')
    @stack('head')
</head>
<body class="staff-bg text-slate-900 h-screen overflow-hidden flex flex-col">
    @include('partials.theme.staff-header')
    @include('partials.theme.flash-messages')

    <div class="flex flex-1 min-h-0 relative">
        @include('partials.theme.staff-sidebar')

        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 staff-main min-h-0 pb-20 md:pb-6">
            @include('partials.theme.shift-notice')
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
