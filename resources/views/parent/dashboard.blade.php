@extends('layouts.parent')

@section('title', 'Beranda')
@section('page-title', 'Halo, ' . auth()->user()->name . ' 👋')
@section('page-subtitle', 'Pantau pengasuhan buah hati Anda dengan tenang bersama SiagaAsuh')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">

    {{-- Banner Sesi Berjalan (Jika ada sesi aktif) --}}
    @if($sesi_aktif)
        <div class="bg-gradient-to-r from-purple-700 via-purple-600 to-indigo-600 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 relative z-10">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 bg-white/20 px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-ping"></span>
                        <span>SESI AKTIF BERJALAN</span>
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold">{{ $sesi_aktif->child->nama }} sedang diasuh oleh {{ $sesi_aktif->caregiver->name }}</h2>
                    <p class="text-xs text-purple-200">
                        Dimulai sejak: {{ $sesi_aktif->started_at ? $sesi_aktif->started_at->format('H:i') : '-' }} WIB · Layanan {{ ucfirst($sesi_aktif->jenis_layanan) }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('parent.sessions.monitor', $sesi_aktif->id) }}" class="bg-white text-siaga font-bold px-5 py-3 rounded-2xl hover:bg-purple-50 transition shadow-lg flex items-center gap-2 text-sm">
                        <i class="fas fa-heartbeat text-red-500 text-lg"></i>
                        <span>Buka Live Monitor IoT</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Hero Banner Promo Persis Figma ("Butuh Pengasuh Hari ini?") --}}
    <div class="gradient-purple rounded-3xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="max-w-md space-y-3 relative z-10">
            <h2 class="text-2xl md:text-3xl font-extrabold leading-tight">Butuh Pengasuh Hari ini?</h2>
            <p class="text-purple-100 text-sm">Temukan pengasuh terpercaya, terverifikasi identitas dan kompetensinya dengan cepat dan mudah.</p>
            <div class="pt-2 flex items-center gap-3">
                <a href="{{ route('parent.caregivers.index') }}" class="bg-white text-siaga px-6 py-2.5 rounded-full font-bold text-sm hover:bg-purple-100 transition shadow-md inline-flex items-center gap-2">
                    <i class="fas fa-search"></i> Pesan Sekarang
                </a>
                <span class="text-xs text-purple-200 flex items-center gap-1.5">
                    <i class="fas fa-shield-alt text-yellow-300"></i> Terverifikasi 100%
                </span>
            </div>
        </div>
        {{-- Decorative Icon --}}
        <div class="absolute -right-6 -bottom-6 text-white/10 text-9xl pointer-events-none">
            <i class="fas fa-baby"></i>
        </div>
    </div>

    {{-- Kategori Layanan (Sesuai Desain Figma) --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-800 text-base">Jenis Layanan</h3>
            <a href="{{ route('parent.caregivers.index') }}" class="text-xs text-siaga font-semibold hover:underline">Semua Layanan &rarr;</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('parent.caregivers.index', ['layanan' => 'darurat']) }}" class="card hover:shadow-md transition text-center group cursor-pointer border border-purple-100 p-4">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl mx-auto flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800">Pengasuh Darurat</h4>
                <p class="text-[11px] text-gray-500 mt-0.5">Siap datang segera</p>
            </a>

            <a href="{{ route('parent.caregivers.index', ['layanan' => 'harian']) }}" class="card hover:shadow-md transition text-center group cursor-pointer border border-purple-100 p-4">
                <div class="w-12 h-12 bg-purple-100 text-siaga rounded-2xl mx-auto flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition">
                    <i class="fas fa-sun"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800">Pengasuh Harian</h4>
                <p class="text-[11px] text-gray-500 mt-0.5">Pagi hingga sore</p>
            </a>

            <a href="{{ route('parent.caregivers.index', ['layanan' => 'malam']) }}" class="card hover:shadow-md transition text-center group cursor-pointer border border-purple-100 p-4">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl mx-auto flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition">
                    <i class="fas fa-moon"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800">Pengasuh Malam</h4>
                <p class="text-[11px] text-gray-500 mt-0.5">Pendampingan tidur</p>
            </a>

            <a href="{{ route('parent.children.index') }}" class="card hover:shadow-md transition text-center group cursor-pointer border border-purple-100 p-4">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl mx-auto flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition">
                    <i class="fas fa-child"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800">Data Anak</h4>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ auth()->user()->children->count() }} Terdaftar</p>
            </a>
        </div>
    </div>

    {{-- Pengasuh Terdekat / Rekomendasi (Sesuai Figma) --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-800 text-base">Pengasuh Terverifikasi Siap Kerja</h3>
            <a href="{{ route('parent.caregivers.index') }}" class="text-xs text-siaga font-semibold hover:underline">Lihat Semua</a>
        </div>

        @php
            $rekomendasi = \App\Models\CaregiverProfile::with('user')
                ->where('status_verifikasi', 'terverifikasi')
                ->latest()
                ->take(3)
                ->get();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($rekomendasi as $cg)
                <div class="card hover:shadow-lg transition p-5 flex flex-col justify-between border border-purple-50">
                    <div class="flex items-start gap-3.5">
                        <div class="w-12 h-12 bg-purple-100 text-siaga rounded-2xl flex items-center justify-center font-bold text-lg flex-shrink-0">
                            {{ strtoupper(substr($cg->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-sm text-gray-800 truncate">{{ $cg->user->name }}</h4>
                            <div class="flex items-center gap-1.5 text-xs text-yellow-500 mt-0.5">
                                <i class="fas fa-star"></i>
                                <span class="font-bold text-gray-800">{{ $cg->rating > 0 ? $cg->rating : 'Baru' }}</span>
                                <span class="text-gray-400">({{ $cg->total_ulasan }} ulasan)</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1 line-clamp-2">{{ $cg->kompetensi ?? 'Pengasuh bersertifikat' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-gray-400 block">Tarif Harian</span>
                            <span class="font-bold text-siaga text-xs">Rp {{ number_format($cg->tarif_harian ?? 150000, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('parent.caregivers.show', $cg->id) }}" class="btn-primary text-xs py-1.5 px-3.5 rounded-xl">
                            Pesan
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-gray-400 card">
                    <p class="text-sm">Belum ada pengasuh terverifikasi yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Banner Keamanan & Notifikasi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Banner Aman & Terpercaya (Figma) --}}
        <div class="card bg-purple-50/70 border-purple-100 p-5 flex items-start gap-4">
            <div class="w-10 h-10 bg-siaga text-white rounded-xl flex items-center justify-center text-lg flex-shrink-0 mt-0.5">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-800 text-sm">Layanan Aman & Terpercaya</h4>
                <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                    Setiap pengasuh SiagaAsuh telah melalui verifikasi NIK KTP, pemeriksaan riwayat kompetensi, dan didukung pemantauan wearable IoT secara real-time.
                </p>
            </div>
        </div>

        {{-- Notifikasi Terbaru (Figma: Notifikasi) --}}
        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                    <i class="fas fa-bell text-siaga"></i> Notifikasi
                </h4>
            </div>
            @if($notifikasi->isEmpty())
                <p class="text-xs text-gray-400 py-3 text-center">Tidak ada notifikasi baru.</p>
            @else
                <div class="space-y-2.5">
                    @foreach($notifikasi as $notif)
                        <div class="p-2.5 rounded-xl bg-gray-50 border border-gray-100 flex items-start gap-2.5">
                            <span class="text-xs mt-0.5">
                                @if($notif->tipe === 'sensor_alert') 🚨
                                @elseif($notif->tipe === 'layanan') 📋
                                @else 🔔 @endif
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-800">{{ $notif->judul }}</p>
                                <p class="text-[11px] text-gray-500 truncate">{{ $notif->pesan }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
