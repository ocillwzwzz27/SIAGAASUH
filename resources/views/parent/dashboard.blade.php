@extends('layouts.parent')

@section('title', 'Beranda')

@section('custom-header')
    <header class="parent-topbar">
        {{-- Lokasi Anda: GondangManis, Kudus --}}
        <div class="parent-location-wrap" title="Ganti Lokasi">
            <div class="parent-location-pin">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div class="parent-location-text">
                <span class="parent-location-label">Lokasi Anda</span>
                <span class="parent-location-name">
                    GondangManis, Kudus
                    <i class="fa-solid fa-chevron-down parent-location-chevron"></i>
                </span>
            </div>
        </div>

        {{-- Action Buttons: Notifikasi & Akun --}}
        <div class="parent-top-actions">
            <a href="{{ route('parent.notifications.index') }}" class="parent-icon-btn" title="Notifikasi">
                <i class="fa-regular fa-bell"></i>
                @if($notifikasi->count() > 0)
                    <span class="parent-badge-dot"></span>
                @endif
            </a>

            <button type="button" id="btn-toggle-account" class="parent-icon-btn" title="Akun Saya">
                <i class="fa-solid fa-user"></i>
            </button>
        </div>
    </header>
@endsection

@section('content')

    {{-- Banner Sesi Berjalan (Jika ada sesi IoT aktif) --}}
    @if($sesi_aktif)
        <div class="mb-4 bg-gradient-to-r from-purple-700 to-indigo-600 rounded-2xl p-4 text-white shadow-md relative overflow-hidden">
            <div class="flex items-center justify-between gap-3 relative z-10">
                <div class="space-y-1 min-w-0">
                    <div class="inline-flex items-center gap-1.5 bg-white/20 px-2 py-0.5 rounded-full text-[10px] font-semibold">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-ping"></span>
                        <span>SESI AKTIF BERJALAN</span>
                    </div>
                    <h3 class="text-xs font-bold truncate">{{ $sesi_aktif->child->nama }} bersama {{ $sesi_aktif->caregiver->name }}</h3>
                </div>
                <a href="{{ route('parent.sessions.monitor', $sesi_aktif->id) }}" class="bg-white text-siaga font-bold px-3 py-1.5 rounded-xl hover:bg-purple-50 transition text-[11px] flex-shrink-0 flex items-center gap-1.5 shadow">
                    <i class="fa-solid fa-heart-pulse text-red-500"></i>
                    <span>Live IoT</span>
                </a>
            </div>
        </div>
    @endif

    {{-- 1. Hero Banner Promo ("Butuh Pengasuh Hari ini?") --}}
    <div class="parent-hero-banner">
        <div class="parent-hero-info">
            <h2 class="parent-hero-title">
                Butuh Pengasuh Hari<br>ini?
            </h2>
            <p class="parent-hero-desc">
                Temukan pengasuh terpercaya dengan cepat dan mudah.
            </p>

            <div class="parent-hero-actions">
                <div class="parent-badge-verified">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span>Terverifikasi</span>
                </div>
                <a href="{{ route('parent.caregivers.index') }}" class="parent-btn-order-now">
                    Pesan Sekarang
                </a>
            </div>
        </div>

        {{-- Graphic Lingkaran Icon 2 Orang --}}
        <div class="parent-hero-graphic">
            <svg class="w-11 h-11 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="7" r="4"/>
                <path d="M2 21v-2a6 6 0 0 1 12 0v2"/>
                <circle cx="18" cy="7" r="3"/>
                <path d="M18 14a5 5 0 0 1 4 5v2"/>
            </svg>
        </div>
    </div>

    {{-- Pagination Dots di Bawah Hero Banner --}}
    <div class="parent-hero-dots">
        <span class="parent-hero-dot active"></span>
        <span class="parent-hero-dot"></span>
        <span class="parent-hero-dot"></span>
    </div>

    {{-- 2. Kategori Layanan (4 Kotak Ikon) --}}
    <div class="parent-services-grid">
        {{-- Pengasuh Darurat --}}
        <a href="{{ route('parent.caregivers.index', ['layanan' => 'darurat']) }}" class="parent-service-item">
            <div class="parent-service-box">
                <svg class="w-7 h-7 text-siaga" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <span class="parent-service-label">Pengasuh<br>Darurat</span>
        </a>

        {{-- Pengasuh Harian --}}
        <a href="{{ route('parent.caregivers.index', ['layanan' => 'harian']) }}" class="parent-service-item">
            <div class="parent-service-box">
                <svg class="w-7 h-7 text-siaga" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2"/><path d="M12 20v2"/>
                    <path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/>
                    <path d="M2 12h2"/><path d="M20 12h2"/>
                    <path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                </svg>
            </div>
            <span class="parent-service-label">Pengasuh<br>Harian</span>
        </a>

        {{-- Pengasuh Langganan --}}
        <a href="{{ route('parent.caregivers.index', ['layanan' => 'langganan']) }}" class="parent-service-item">
            <div class="parent-service-box">
                <svg class="w-7 h-7 text-siaga" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <span class="parent-service-label">Pengasuh<br>Langganan</span>
        </a>

        {{-- Semua Layanan (Kotak Ungu Gradasi) --}}
        <a href="{{ route('parent.caregivers.index') }}" class="parent-service-item">
            <div class="parent-service-box parent-service-box--primary">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="7" height="7" x="3" y="3" rx="1.5"/>
                    <rect width="7" height="7" x="14" y="3" rx="1.5"/>
                    <rect width="7" height="7" x="14" y="14" rx="1.5"/>
                    <rect width="7" height="7" x="3" y="14" rx="1.5"/>
                </svg>
            </div>
            <span class="parent-service-label">Semua<br>Layanan</span>
        </a>
    </div>

    {{-- 3. Pengasuh Terdekat Container --}}
    @php
        $dbCaregivers = \App\Models\CaregiverProfile::with('user')
            ->where('status_verifikasi', 'terverifikasi')
            ->take(3)
            ->get();

        // Data default yang persis seperti pada gambar mockup
        $defaultCaregivers = [
            [
                'name' => 'Triana Putri',
                'rating' => '4.9',
                'ulasan' => 87,
                'id' => $dbCaregivers->get(0)->id ?? null,
            ],
            [
                'name' => 'Putri Dayarsi',
                'rating' => '4.7',
                'ulasan' => 64,
                'id' => $dbCaregivers->get(1)->id ?? null,
            ],
            [
                'name' => 'Siska Putri',
                'rating' => '4.2',
                'ulasan' => 31,
                'id' => $dbCaregivers->get(2)->id ?? null,
            ],
        ];
    @endphp

    <div class="parent-nearby-section">
        <div class="parent-nearby-header">
            <h3 class="parent-nearby-title">Pengasuh Terdekat</h3>
            <a href="{{ route('parent.caregivers.index') }}" class="parent-nearby-link">Lihat Semua</a>
        </div>

        <div class="parent-nearby-grid">
            @foreach($defaultCaregivers as $cg)
                <div class="parent-caregiver-card">
                    <div class="parent-caregiver-avatar">
                        <i class="fa-solid fa-user text-lg"></i>
                    </div>

                    <div>
                        <h4 class="parent-caregiver-name" title="{{ $cg['name'] }}">{{ $cg['name'] }}</h4>
                        <div class="parent-caregiver-rating">
                            <i class="fa-solid fa-star parent-caregiver-star"></i>
                            <span class="parent-caregiver-score">{{ $cg['rating'] }}</span>
                            <span class="parent-caregiver-reviews">({{ $cg['ulasan'] }} ulasan)</span>
                        </div>
                    </div>

                    @php
                        $orderUrl = !empty($cg['id'])
                            ? route('parent.caregivers.show', $cg['id'])
                            : route('parent.caregivers.index');
                    @endphp
                    <a href="{{ $orderUrl }}" class="parent-caregiver-btn">
                        Pesan Sekarang
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 4. Banner Layanan Aman & Terpercaya --}}
    <div class="parent-trust-card">
        <div class="parent-trust-icon-box">
            <svg class="w-6 h-6 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>
        </div>

        <div class="parent-trust-info">
            <h4 class="parent-trust-title">Layanan Aman &amp; Terpercaya</h4>
            <p class="parent-trust-desc">Semua pengasuh telah melalui verifikasi identitas dan latar belakang.</p>
        </div>

        <a href="{{ route('parent.caregivers.index') }}" class="parent-trust-btn">
            Lebih Lanjut
        </a>
    </div>

    {{-- 5. Purple Features Bar (Riwayat, Berita, Anak, Anak Kebutuhan, Lainnya) --}}
    <div class="parent-features-bar">
        <div class="parent-features-grid">
            {{-- Riwayat --}}
            <a href="{{ route('parent.sessions.index') }}" class="parent-feature-item">
                <div class="parent-feature-box">
                    <svg class="w-5 h-5 text-siaga" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <span class="parent-feature-label">Riwayat</span>
            </a>

            {{-- Berita --}}
            <a href="{{ route('parent.notifications.index') }}" class="parent-feature-item">
                <div class="parent-feature-box">
                    <svg class="w-5 h-5 text-siaga" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                        <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                    </svg>
                </div>
                <span class="parent-feature-label">Berita</span>
            </a>

            {{-- Anak --}}
            <a href="{{ route('parent.children.index') }}" class="parent-feature-item">
                <div class="parent-feature-box">
                    <i class="fa-solid fa-baby text-lg text-siaga"></i>
                </div>
                <span class="parent-feature-label">Anak</span>
            </a>

            {{-- Anak Kebutuhan --}}
            <a href="{{ route('parent.caregivers.index', ['kebutuhan' => 'khusus']) }}" class="parent-feature-item">
                <div class="parent-feature-box">
                    <svg class="w-5 h-5 text-siaga" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </div>
                <span class="parent-feature-label">Anak<br>Kebutuhan</span>
            </a>

            {{-- Lainnya --}}
            <a href="{{ route('parent.caregivers.index') }}" class="parent-feature-item">
                <div class="parent-feature-box">
                    <svg class="w-5 h-5 text-siaga" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="6" height="6" x="4" y="4" rx="1.5"/>
                        <rect width="6" height="6" x="14" y="4" rx="1.5"/>
                        <rect width="6" height="6" x="14" y="14" rx="1.5"/>
                        <rect width="6" height="6" x="4" y="14" rx="1.5"/>
                    </svg>
                </div>
                <span class="parent-feature-label">Lainnya</span>
            </a>
        </div>
    </div>

@endsection
