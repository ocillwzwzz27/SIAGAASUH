<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiagaAsuh - @yield('title', 'Aplikasi Pengasuhan Terpercaya')</title>

    @include('layouts.partials.assets')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

@php
    $unreadNotifCount = \App\Models\AppNotification::where('user_id', auth()->id())->where('sudah_dibaca', false)->count();
    $activeCareSession = \App\Models\CareSession::where('parent_id', auth()->id())->where('status', 'berjalan')->first();
@endphp

<div class="parent-app-wrapper">
    <div class="parent-app-container">

        {{-- Top Navigation Header --}}
        @hasSection('custom-header')
            @yield('custom-header')
        @else
            <header class="parent-topbar border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <a href="{{ route('parent.dashboard') }}" class="w-9 h-9 rounded-full bg-purple-50 text-siaga flex items-center justify-center text-sm hover:bg-purple-100 transition">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-sm font-bold text-gray-900 leading-tight">@yield('page-title', 'SiagaAsuh')</h1>
                        <p class="text-[11px] text-gray-400">@yield('page-subtitle', 'Portal Orang Tua')</p>
                    </div>
                </div>

                <div class="parent-top-actions">
                    <a href="{{ route('parent.notifications.index') }}" class="parent-icon-btn" title="Notifikasi">
                        <i class="fa-regular fa-bell"></i>
                        @if($unreadNotifCount > 0)
                            <span class="parent-badge-dot"></span>
                        @endif
                    </a>

                    <button type="button" id="btn-toggle-account" class="parent-icon-btn" title="Akun">
                        <i class="fa-solid fa-user"></i>
                    </button>
                </div>
            </header>
        @endif

        {{-- Flash Notification Messages --}}
        <div class="px-5 pt-3">
            @include('layouts.partials.flash')
        </div>

        {{-- Main Page Content --}}
        <main class="parent-main-content">
            @yield('content')
        </main>

        {{-- Bottom Navigation Bar (Persis Figma) --}}
        <nav class="parent-bottom-bar">
            {{-- Beranda --}}
            @if(request()->routeIs('parent.dashboard'))
                <a href="{{ route('parent.dashboard') }}" class="parent-nav-pill-active">
                    <i class="fa-solid fa-house"></i>
                    <span>Beranda</span>
                </a>
            @else
                <a href="{{ route('parent.dashboard') }}" class="parent-nav-item">
                    <i class="fa-solid fa-house"></i>
                    <span>Beranda</span>
                </a>
            @endif

            {{-- Pengasuh --}}
            @if(request()->routeIs('parent.caregivers.*'))
                <a href="{{ route('parent.caregivers.index') }}" class="parent-nav-pill-active">
                    <i class="fa-solid fa-users"></i>
                    <span>Pengasuh</span>
                </a>
            @else
                <a href="{{ route('parent.caregivers.index') }}" class="parent-nav-item">
                    <i class="fa-solid fa-users"></i>
                    <span>Pengasuh</span>
                </a>
            @endif

            {{-- Monitoring --}}
            @php
                $monitorUrl = $activeCareSession
                    ? route('parent.sessions.monitor', $activeCareSession->id)
                    : route('parent.sessions.index');
            @endphp
            @if(request()->routeIs('parent.sessions.monitor'))
                <a href="{{ $monitorUrl }}" class="parent-nav-pill-active relative">
                    <i class="fa-solid fa-heart-pulse"></i>
                    <span>Monitoring</span>
                    @if($activeCareSession)
                        <span class="absolute top-1 right-3 w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                    @endif
                </a>
            @else
                <a href="{{ $monitorUrl }}" class="parent-nav-item relative">
                    <i class="fa-solid fa-heart-pulse"></i>
                    <span>Monitoring</span>
                    @if($activeCareSession)
                        <span class="absolute top-1 right-2 w-2 h-2 rounded-full bg-green-500"></span>
                    @endif
                </a>
            @endif

            {{-- Account --}}
            <button type="button" id="btn-bottom-account" class="parent-nav-item cursor-pointer">
                <i class="fa-solid fa-user"></i>
                <span>Account</span>
            </button>
        </nav>

        {{-- Slide-up / Drawer Account Modal --}}
        <div id="account-drawer" class="fixed inset-0 z-50 hidden">
            <div id="account-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-xs transition-opacity"></div>
            <div class="absolute bottom-0 inset-x-0 max-w-[430px] mx-auto bg-white rounded-t-3xl p-6 shadow-2xl transition-transform">
                <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-4"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-siaga flex items-center justify-center font-bold text-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-gray-900">{{ auth()->user()->name }}</h3>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            <span class="inline-block mt-1 bg-purple-50 text-siaga text-[10px] font-bold px-2 py-0.5 rounded-full">Orang Tua</span>
                        </div>
                    </div>
                    <button type="button" id="btn-close-account" class="text-gray-400 hover:text-gray-600 p-2">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="space-y-2 border-t border-gray-100 pt-3">
                    <a href="{{ route('parent.children.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-purple-50/60 transition text-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-100 text-siaga flex items-center justify-center">
                                <i class="fa-solid fa-child"></i>
                            </div>
                            <span class="text-sm font-semibold">Data Anak &amp; Wearable</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                    </a>

                    <a href="{{ route('parent.sessions.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-purple-50/60 transition text-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-100 text-siaga flex items-center justify-center">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <span class="text-sm font-semibold">Riwayat Sesi Pengasuhan</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                    </a>

                    <a href="{{ route('parent.notifications.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-purple-50/60 transition text-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-purple-100 text-siaga flex items-center justify-center">
                                <i class="fa-solid fa-bell"></i>
                            </div>
                            <span class="text-sm font-semibold">Notifikasi</span>
                        </div>
                        @if($unreadNotifCount > 0)
                            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadNotifCount }}</span>
                        @else
                            <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                        @endif
                    </a>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-4 pt-3 border-t border-gray-100">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Keluar dari Akun</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@stack('scripts')
</body>
</html>
