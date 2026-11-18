<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Admin SiagaAsuh</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">
@php
    $nav = [
        ['admin.dashboard', 'Dashboard', '📊'],
        ['admin.caregivers', 'Verifikasi Pengasuh', '✅'],
        ['admin.users', 'Pengguna', '👥'],
        ['admin.bookings', 'Pesanan', '📅'],
        ['admin.payments', 'Pembayaran', '💳'],
        ['admin.services', 'Layanan', '🧸'],
    ];
@endphp

<div class="flex min-h-screen flex-col md:flex-row">
    {{-- Sidebar --}}
    <aside class="w-full shrink-0 text-white md:w-60" style="background:linear-gradient(180deg,#6D28D9,#A21CAF)">
        <div class="flex items-center gap-2 px-5 py-5">
            <span class="text-2xl">🤱</span>
            <div>
                <p class="text-lg font-bold leading-none">SiagaAsuh</p>
                <p class="text-[11px] text-purple-200">Panel Admin</p>
            </div>
        </div>
        <nav class="flex gap-1 overflow-x-auto px-3 pb-3 md:flex-col md:pb-0">
            @foreach ($nav as [$route, $label, $icon])
                <a href="{{ route($route) }}"
                   class="flex items-center gap-3 whitespace-nowrap rounded-xl px-3 py-2.5 text-sm {{ request()->routeIs($route . '*') ? 'bg-white/20 font-semibold' : 'text-purple-100 hover:bg-white/10' }}">
                    <span>{{ $icon }}</span>{{ $label }}
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- Konten --}}
    <div class="flex-1">
        <header class="flex items-center justify-between border-b bg-white px-6 py-4">
            <h1 class="text-lg font-bold text-purple-800">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-4 text-sm">
                <span class="text-gray-500">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg border px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50">Keluar</button>
                </form>
            </div>
        </header>

        <main class="p-6">
            @if (session('success'))
                <div class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
