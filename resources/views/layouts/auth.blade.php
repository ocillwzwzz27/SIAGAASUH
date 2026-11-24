<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiagaAsuh — @yield('title', 'Masuk')</title>

    @include('layouts.partials.assets')
    @vite('resources/css/auth.css')
    @stack('styles')
</head>
<body class="auth-page">
    @include('layouts.partials.auth-hero')

    <div class="auth-panel-right">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
