@extends('layouts.caregiver')

@section('title', 'Beranda Mitra Pengasuh')

@section('custom-header')
    <header class="parent-topbar">
        <div class="parent-location-wrap">
            <div class="parent-location-pin">
                <i class="fa-solid fa-user-nurse"></i>
            </div>
            <div class="parent-location-text">
                <span class="parent-location-label">Mitra Pengasuh</span>
                <span class="parent-location-name">
                    {{ Str::limit(auth()->user()->name, 22) }}
                </span>
            </div>
        </div>

        <div class="parent-top-actions">
            <a href="{{ route('caregiver.notifications.index') }}" class="parent-icon-btn" title="Notifikasi">
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
    @php
        $statusVerif = $profile->status_verifikasi ?? 'menunggu';
    @endphp

    {{-- 1. Hero Banner Mitra Pengasuh --}}
    <div class="parent-hero-banner">
        <div class="parent-hero-info">
            <h2 class="parent-hero-title">
                @if($statusVerif === 'terverifikasi')
                    Siap Melayani<br>Hari Ini?
                @elseif($statusVerif === 'ditolak')
                    Perlu Perbaikan<br>Dokumen
                @else
                    Menunggu<br>Verifikasi Admin
                @endif
            </h2>
            <p class="parent-hero-desc">
                @if($statusVerif === 'terverifikasi')
                    Profil Anda aktif dan dapat dipesan oleh orang tua kapan saja.
                @elseif($statusVerif === 'ditolak')
                    Silakan perbaiki berkas identitas agar akun dapat diaktifkan.
                @else
                    Tim SiagaAsuh sedang meninjau kelengkapan identitas Anda.
                @endif
            </p>

            <div class="parent-hero-actions">
                <div class="parent-badge-verified">
                    <i class="fa-solid fa-star text-amber-300 text-xs"></i>
                    <span>{{ $profile && $profile->rating > 0 ? number_format($profile->rating, 1) : 'Baru' }} ({{ $profile->total_ulasan ?? 0 }})</span>
                </div>
                <a href="{{ $statusVerif === 'terverifikasi' ? route('caregiver.sessions.index') : route('caregiver.profile.edit') }}" class="parent-btn-order-now">
                    {{ $statusVerif === 'terverifikasi' ? 'Kelola Sesi' : 'Lengkapi Profil' }}
                </a>
            </div>
        </div>

        <div class="parent-hero-graphic">
            <i class="fa-solid fa-hand-holding-heart text-4xl text-white"></i>
        </div>
    </div>

    {{-- 2. Status Verifikasi Detail (Sesuai Flowchart 1) --}}
    @if(!$profile || $statusVerif === 'menunggu')
        <div class="mt-4 p-4 rounded-2xl bg-yellow-50 border border-yellow-200 text-yellow-900 flex items-start gap-3">
            <i class="fa-solid fa-clock text-lg text-yellow-600 mt-0.5"></i>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-xs">Status: Menunggu Verifikasi</h4>
                <p class="text-[11px] text-yellow-700 mt-0.5 leading-relaxed">
                    Pastikan NIK, kompetensi, serta 4 berkas wajib (KTP, SKCK, Surat Sehat, Surat Pengalaman) sudah lengkap agar admin segera menyetujui akun Anda.
                </p>
                <a href="{{ route('caregiver.profile.edit') }}" class="text-[11px] font-bold text-yellow-900 underline mt-1.5 inline-block">
                    Periksa Dokumen &rarr;
                </a>
            </div>
        </div>
    @elseif($statusVerif === 'ditolak')
        <div class="mt-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-lg text-red-600 mt-0.5"></i>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-xs">Verifikasi Ditolak</h4>
                <p class="text-[11px] text-red-700 mt-0.5 leading-relaxed">
                    Alasan: <strong>{{ $profile->alasan_penolakan ?? 'Dokumen kurang jelas.' }}</strong>
                </p>
                <a href="{{ route('caregiver.profile.edit') }}" class="btn-primary text-[11px] py-1.5 px-3 rounded-xl mt-2.5 inline-block">
                    Perbaiki Data Sekarang
                </a>
            </div>
        </div>
    @endif

    {{-- 3. Sesi Sedang Berjalan + Form Akhiri Sesi (Sesuai Flowchart 2) --}}
    @if($sesi_aktif)
        <div class="mt-5 card p-4 border-2 border-purple-300 bg-purple-50/40 space-y-3">
            <div class="flex items-center justify-between gap-2">
                <span class="inline-flex items-center gap-1.5 bg-blue-100 text-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-ping"></span>
                    SESI AKTIF BERJALAN
                </span>
                <span class="text-[11px] text-gray-500">
                    Mulai {{ $sesi_aktif->started_at ? $sesi_aktif->started_at->format('H:i') : '-' }} WIB
                </span>
            </div>

            <div>
                <h3 class="font-bold text-sm text-gray-900">Mengasuh: {{ $sesi_aktif->child->nama }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Orang Tua: {{ $sesi_aktif->parent->name }} &middot; {{ $sesi_aktif->parent->phone }}
                </p>
                @if($sesi_aktif->catatan_orang_tua)
                    <div class="mt-2 p-2.5 bg-white rounded-xl border border-purple-100 text-[11px] text-gray-600">
                        <strong class="text-gray-700">Catatan Orang Tua:</strong> {{ $sesi_aktif->catatan_orang_tua }}
                    </div>
                @endif
            </div>

            <form action="{{ route('caregiver.sessions.end', $sesi_aktif->id) }}" method="POST" class="space-y-2.5 pt-2 border-t border-purple-100">
                @csrf
                <label class="label text-[11px] font-bold text-gray-700 uppercase">
                    Ringkasan Sesi Pengasuhan (Wajib Min. 20 Karakter)
                </label>
                <textarea name="ringkasan" rows="3" required minlength="20"
                    placeholder="Catat aktivitas anak: waktu makan, tidur, kondisi kesehatan..."
                    class="input text-xs"></textarea>
                <button type="submit" data-confirm="Apakah Anda yakin ingin mengakhiri sesi pengasuhan ini?"
                    class="w-full btn-primary text-xs py-2.5 rounded-xl font-bold flex items-center justify-center gap-2">
                    <i class="fa-solid fa-flag-checkered"></i>
                    <span>Akhiri Sesi &amp; Kirim Laporan</span>
                </button>
            </form>
        </div>
    @endif

    {{-- 4. Permintaan Sesi Masuk (Hanya tampil jika sudah terverifikasi) --}}
    @if($statusVerif === 'terverifikasi')
    <div class="parent-nearby-section">
        <div class="parent-nearby-header">
            <h3 class="parent-nearby-title">Permintaan Sesi Masuk</h3>
            <a href="{{ route('caregiver.sessions.index') }}" class="parent-nearby-link">Semua Sesi</a>
        </div>

        @if($sesi_menunggu->isEmpty())
            <div class="bg-white rounded-2xl p-4 text-center border border-purple-100">
                <p class="text-xs text-gray-500">Belum ada permintaan sesi pengasuhan baru saat ini.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($sesi_menunggu as $req)
                    <div class="bg-white rounded-2xl p-4 border border-purple-100 shadow-xs space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h4 class="font-bold text-sm text-gray-900 truncate">{{ $req->child->nama }} ({{ $req->child->umur }})</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Ortu: {{ $req->parent->name }} &middot; {{ $req->parent->phone }}</p>
                            </div>
                            <span class="bg-purple-100 text-siaga text-[10px] font-bold px-2.5 py-1 rounded-full uppercase flex-shrink-0">
                                {{ $req->jenis_layanan }}
                            </span>
                        </div>

                        @if($req->catatan_orang_tua)
                            <p class="text-[11px] text-gray-600 bg-gray-50 p-2.5 rounded-xl">
                                <strong class="text-gray-700">Catatan:</strong> {{ $req->catatan_orang_tua }}
                            </p>
                        @endif

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <form action="{{ route('caregiver.sessions.confirm', $req->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="terima">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 rounded-xl transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-check"></i> Terima
                                </button>
                            </form>
                            <form action="{{ route('caregiver.sessions.confirm', $req->id) }}" method="POST" data-confirm="Tolak permintaan sesi ini?">
                                @csrf
                                <input type="hidden" name="action" value="tolak">
                                <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold py-2 rounded-xl transition flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-xmark"></i> Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 5. Ringkasan Statistik Ringkas --}}
    <div class="grid grid-cols-3 gap-2.5 mt-5">
        <div class="card p-3 text-center border border-purple-50">
            <p class="text-[10px] text-gray-400 font-semibold uppercase">Sesi Selesai</p>
            <h3 class="text-lg font-extrabold text-gray-800 mt-0.5">{{ $total_sesi_selesai }}</h3>
        </div>
        <div class="card p-3 text-center border border-purple-50">
            <p class="text-[10px] text-gray-400 font-semibold uppercase">Rating</p>
            <h3 class="text-lg font-extrabold text-amber-500 mt-0.5">★ {{ $profile && $profile->rating > 0 ? number_format($profile->rating, 1) : 'Baru' }}</h3>
        </div>
        <div class="card p-3 text-center border border-purple-50">
            <p class="text-[10px] text-gray-400 font-semibold uppercase">Ulasan</p>
            <h3 class="text-lg font-extrabold text-siaga mt-0.5">{{ $profile->total_ulasan ?? 0 }}</h3>
        </div>
    </div>
    @endif

    {{-- 6. Purple Features Bar (Quick Access seperti Orang Tua) --}}
    <div class="parent-features-bar mt-5">
        <div class="grid grid-cols-4 gap-2">
            <a href="{{ route('caregiver.sessions.index', ['status' => 'menunggu']) }}" class="parent-feature-item">
                <div class="parent-feature-box">
                    <i class="fa-solid fa-inbox text-siaga text-base"></i>
                </div>
                <span class="parent-feature-label">Masuk</span>
            </a>
            <a href="{{ route('caregiver.sessions.index', ['status' => 'selesai']) }}" class="parent-feature-item">
                <div class="parent-feature-box">
                    <i class="fa-solid fa-clock-rotate-left text-siaga text-base"></i>
                </div>
                <span class="parent-feature-label">Riwayat</span>
            </a>
            <a href="{{ route('caregiver.profile.edit') }}" class="parent-feature-item">
                <div class="parent-feature-box">
                    <i class="fa-solid fa-id-card text-siaga text-base"></i>
                </div>
                <span class="parent-feature-label">Profil &amp; Berkas</span>
            </a>
            <a href="{{ route('caregiver.notifications.index') }}" class="parent-feature-item">
                <div class="parent-feature-box">
                    <i class="fa-solid fa-bell text-siaga text-base"></i>
                </div>
                <span class="parent-feature-label">Notifikasi</span>
            </a>
        </div>
    </div>

@endsection
