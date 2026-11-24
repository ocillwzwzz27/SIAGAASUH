<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiagaAsuh - @yield('title', 'Sistem Pengasuhan Terpercaya')</title>

    @include('layouts.partials.assets')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">

<div class="flex h-screen overflow-hidden">
    @include('layouts.partials.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        @include('layouts.partials.topbar')

        <main class="app-main flex-1 overflow-y-auto p-6">
            @include('layouts.partials.flash')
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
