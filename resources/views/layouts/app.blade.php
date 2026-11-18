<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'SiagaAsuh')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-sa-ink antialiased">
    {{-- Kontainer selebar HP, di desktop tampil di tengah --}}
    <div class="sa-screen relative mx-auto min-h-screen max-w-[430px] shadow-2xl shadow-sa-600/20">
        @yield('content')
    </div>
</body>
</html>
