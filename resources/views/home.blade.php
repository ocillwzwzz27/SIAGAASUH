<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>SiagaAsuh – Pengasuh Terpercaya Kapan Saja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/siagaasuh.css') }}">
</head>
<body>
@include('partials.icons')

<div class="screen">
<main class="app">

    {{-- Status bar (dekoratif, sesuai desain) --}}
    <div class="statusbar">
        <span class="time">09:00 AM</span>
        <span class="sys">
            <svg viewBox="0 0 20 16" class="sig"><rect x="0" y="11" width="3" height="5" rx="1"/><rect x="5" y="8" width="3" height="8" rx="1"/><rect x="10" y="4.5" width="3" height="11.5" rx="1"/><rect x="15" y="0" width="3" height="16" rx="1"/></svg>
            <svg viewBox="0 0 24 18" class="wifi"><path d="M12 17.5 8.6 14a4.8 4.8 0 0 1 6.8 0L12 17.5Zm-6-6.2a8.5 8.5 0 0 1 12 0l-1.7 1.8a6 6 0 0 0-8.6 0L6 11.3Zm-4.2-4.3a14.5 14.5 0 0 1 20.4 0l-1.7 1.8a12 12 0 0 0-17 0L1.8 7Z"/></svg>
            <svg viewBox="0 0 30 16" class="bat"><rect x="0.8" y="0.8" width="25" height="14.4" rx="4" fill="none" stroke-width="1.6"/><rect x="3.5" y="3.5" width="19.5" height="9" rx="2"/><rect x="27.5" y="5" width="2.5" height="6" rx="1.2"/></svg>
        </span>
    </div>

    {{-- Header lokasi + logo --}}
    <header class="top">
        <div class="loc">
            <svg class="ic pin"><use href="#i-pin"/></svg>
            <div>
                <small>Lokasi Anda</small>
                <strong>{{ $location }}</strong>
            </div>
        </div>
        <a href="{{ route('home') }}" class="logo" aria-label="SiagaAsuh">
            <svg viewBox="0 0 64 64" class="mark">
                <path d="M32 58C10 42 3 31 3 21 3 12 10 6 18 6c6 0 11 3 14 8z" fill="#7B2FBE"/>
                <path d="M32 58C54 42 61 31 61 21 61 12 54 6 46 6c-6 0-11 3-14 8z" fill="#F39A1E"/>
                <circle cx="32" cy="15" r="6" fill="#F39A1E" stroke="#fff" stroke-width="2"/>
                <path d="M23 36c0-7 4-11 9-11s9 4 9 11c0 5-4 9-9 14-5-5-9-9-9-14z" fill="#fff"/>
                <circle cx="32" cy="32" r="3.5" fill="#7B2FBE"/>
                <path d="M26 43c0-4 2.7-6.5 6-6.5s6 2.5 6 6.5c0 2.5-2.5 5-6 8-3.5-3-6-5.5-6-8z" fill="#F39A1E"/>
            </svg>
            <span class="word">
                <b><i>Siaga</i><em>Asuh</em></b>
                <small>Pengasuh Terpercaya Kapan Saja</small>
            </span>
        </a>
    </header>

    {{-- Banner utama --}}
    <section class="hero">
        <h1>Butuh Pengasuh Hari<br>ini?</h1>
        <p>Temukan pengasuh terpercaya dengan cepat dan mudah.</p>
        <div class="hero-actions">
            <span class="badge">
                <svg viewBox="0 0 24 24" class="shield"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" fill="#fff"/><path d="m9 12 2 2 4-4" fill="none" stroke="#7B2FBE" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Terverifikasi
            </span>
            <a href="#" class="btn-white">Pesan Sekarang</a>
        </div>
        <div class="hero-circle"><svg class="ic"><use href="#i-users"/></svg></div>
    </section>

    {{-- Menu layanan --}}
    <nav class="quick" aria-label="Layanan">
        @foreach ($services as $s)
            <a href="{{ $s['url'] }}" class="q-item">
                <span class="q-tile {{ $s['active'] ? 'is-active' : '' }}">
                    <svg class="ic"><use href="#i-{{ $s['icon'] }}"/></svg>
                </span>
                <span class="q-label">@foreach ($s['label'] as $l){{ $l }}<br>@endforeach</span>
            </a>
        @endforeach
    </nav>

    {{-- Pengasuh terdekat --}}
    <section class="nearby">
        <div class="nearby-head">
            <h2>Pengasuh Terdekat</h2>
            <a href="#">Lihat Semua</a>
        </div>
        <div class="cg-row">
            @foreach ($caregivers as $c)
                <article class="cg">
                    <div class="avatar"><svg class="ic"><use href="#i-user"/></svg></div>
                    <h3>{{ $c['name'] }}</h3>
                    <div class="rate">
                        <svg class="star"><use href="#i-star"/></svg>
                        <b>{{ $c['rating'] }}</b>
                        <span>({{ $c['reviews'] }} ulasan)</span>
                    </div>
                    <a href="#" class="btn-purple">Pesan Sekarang</a>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Info keamanan --}}
    <section class="safe">
        <div class="safe-icon"><svg class="ic"><use href="#i-shield-check"/></svg></div>
        <div class="safe-text">
            <h4>Layanan Aman &amp; Terpercaya</h4>
            <p>Semua pengasuh telah melalui verifikasi identitas dan latar belakang.</p>
        </div>
        <a href="#" class="btn-outline">Lebih Lanjut</a>
    </section>

    {{-- Navigasi bawah --}}
    <nav class="dock" aria-label="Menu utama">
        @foreach ($nav as $n)
            <a href="{{ $n['url'] }}" class="d-item">
                <span class="d-tile"><svg class="ic"><use href="#i-{{ $n['icon'] }}"/></svg></span>
                <span class="d-label">@foreach ($n['label'] as $l){{ $l }}<br>@endforeach</span>
            </a>
        @endforeach
    </nav>

</main>
</div>
</body>
</html>
