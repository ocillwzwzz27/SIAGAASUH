<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title') – SiagaAsuh</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/siagaasuh.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
<div class="screen">
<main class="app auth">

    <div class="auth-hero">
        <a href="{{ route('home') }}" class="auth-logo" aria-label="SiagaAsuh">
            <svg viewBox="0 0 64 64" class="mark">
                <path d="M32 58C10 42 3 31 3 21 3 12 10 6 18 6c6 0 11 3 14 8z" fill="#fff"/>
                <path d="M32 58C54 42 61 31 61 21 61 12 54 6 46 6c-6 0-11 3-14 8z" fill="#F39A1E"/>
                <circle cx="32" cy="15" r="6" fill="#F39A1E" stroke="#7B2FBE" stroke-width="2"/>
                <path d="M23 36c0-7 4-11 9-11s9 4 9 11c0 5-4 9-9 14-5-5-9-9-9-14z" fill="#7B2FBE"/>
                <circle cx="32" cy="32" r="3.5" fill="#fff"/>
            </svg>
            <span class="word">
                <b><i>Siaga</i><em>Asuh</em></b>
                <small>Pengasuh Terpercaya Kapan Saja</small>
            </span>
        </a>
        <h1>@yield('heading')</h1>
        <p>@yield('subheading')</p>
    </div>

    <section class="auth-card">
        @yield('content')
    </section>

</main>
</div>
</body>
</html>
