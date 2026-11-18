@extends('layouts.app')
@section('title', 'Akun - SiagaAsuh')

@section('content')
<div class="px-4 pb-10 pt-5">
    {{-- Header: beranda, pencarian, pindai --}}
    <header class="flex items-center gap-3">
        <div class="flex min-w-0 flex-1 items-center gap-2 rounded-full bg-white py-1.5 pl-1.5 pr-4 shadow-[0_6px_16px_-8px_rgba(106,34,196,.5)]">
            <a href="{{ route('home') }}" class="sa-soft flex h-9 w-9 shrink-0 items-center justify-center rounded-full" aria-label="Beranda">
                <x-icon name="home" class="h-[18px] w-[18px]" />
            </a>
            <input type="search" placeholder="Cari pengasuh di sekitar kamu..."
                   class="min-w-0 flex-1 bg-transparent text-[13px] font-medium text-sa-ink placeholder:text-sa-muted focus:outline-none">
        </div>
        <button type="button" class="sa-tile flex h-12 w-12 shrink-0 items-center justify-center rounded-full" aria-label="Pindai">
            <x-icon name="scan" class="h-6 w-6" />
        </button>
    </header>

    {{-- Kartu profil --}}
    <section class="sa-card mt-6 flex items-center gap-4 rounded-[26px] px-5 py-5">
        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/avatar-default.png') }}"
             alt="{{ $user->name }}" class="h-[82px] w-[82px] shrink-0 rounded-full object-cover ring-4 ring-white/90">
        <div class="min-w-0 flex-1">
            <div class="flex items-start justify-between gap-2">
                <h1 class="text-[14px] font-bold leading-tight">{{ $user->name }}</h1>
                <span class="shrink-0 text-[11px] font-semibold">{{ $roleLabel }}</span>
            </div>
            <p class="mt-1.5 flex items-center gap-1.5 text-[11.5px] font-semibold">
                <x-icon name="star" class="h-3.5 w-3.5 text-amber-400" fill="currentColor" stroke="1" />
                Level {{ $level }} · {{ $points }} Poin
            </p>
            <div class="mt-2.5">@include('partials.badges')</div>
        </div>
    </section>

    {{-- Jenis layanan --}}
    <div class="mt-7 flex items-end justify-between">
        <div>
            <h2 class="text-[27px] font-extrabold leading-none text-sa-700">Jenis Layanan</h2>
            <p class="mt-2 text-[12px] text-sa-muted">Pilih layanan yang sesuai dengan kebutuhanmu</p>
        </div>
        <a href="#" class="flex items-center gap-1 pb-0.5 text-[12px] font-bold text-sa-600">Lihat Semua <x-icon name="arrow-right" class="h-3.5 w-3.5" stroke="2.4" /></a>
    </div>

    <section class="sa-card mt-4 grid grid-cols-3 gap-3 rounded-[26px] p-4">
        @php
            $services = [
                ['Cari<br>Pengasuh', 'Temukan pengasuh<br>terbaik di sekitar kamu', 'search', '#'],
                ['Notifikasi', 'Dapatkan informasi<br>terbaru seputar<br>pesananmu', 'bell', route('notifications')],
                ['Mode<br>Malam', 'Nikmati tampilan<br>lebih nyaman', 'moon', '#'],
            ];
        @endphp
        @foreach ($services as [$title, $desc, $icon, $href])
            <a href="{{ $href }}" class="flex min-h-[118px] flex-col rounded-2xl border-[1.5px] border-white/90 p-2.5">
                <x-icon :name="$icon" class="h-[22px] w-[22px]" stroke="2.2" />
                <span class="mt-2 text-[12px] font-bold leading-tight">{!! $title !!}</span>
                <span class="mt-1 text-[8.5px] leading-snug text-white/85">{!! $desc !!}</span>
            </a>
        @endforeach
    </section>

    {{-- Daftar menu --}}
    <div class="mt-5 space-y-3.5">
        @php
            $rows = [
                ['badge-percent', 'Layanan Lainnya', 'Akses fitur tambahan untuk pengalaman lebih baik'],
                ['map-pin', 'Informasi Terdekat', 'Lihat pengasuh terdekat dari lokasi kamu'],
                ['star', 'Verifikasi Akun', 'Pastikan akun kamu sudah terverifikasi'],
            ];
        @endphp
        @foreach ($rows as [$icon, $title, $desc])
            <a href="#" class="sa-white flex items-center gap-3 rounded-[22px] px-4 py-4">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sa-100 text-sa-600">
                    <x-icon :name="$icon" class="h-[18px] w-[18px]" :fill="$icon === 'star' ? 'currentColor' : 'none'" />
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block text-[13px] font-bold">{{ $title }}</span>
                    <span class="mt-0.5 block text-[11px] leading-snug text-sa-muted">{{ $desc }}</span>
                </span>
                <x-icon name="chevron-right" class="h-5 w-5 shrink-0 text-sa-muted" />
            </a>
        @endforeach
    </div>

    {{-- Pil bawah --}}
    <div class="mt-5 flex gap-2.5">
        @foreach (['Bantuan & Panduan', 'Kebijakan Privasi', 'Pengaturan'] as $label)
            <a href="#" class="sa-tile flex-1 rounded-full px-2 py-2.5 text-center text-[11.5px] font-bold">{{ $label }}</a>
        @endforeach
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-5 text-center">
        @csrf
        <button class="text-[12px] font-semibold text-sa-muted underline">Keluar dari akun</button>
    </form>
</div>
@endsection
