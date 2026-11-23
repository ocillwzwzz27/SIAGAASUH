@extends('layouts.parent')

@section('title', 'Detail Pengasuh')
@section('page-title', 'Profil Mitra Pengasuh')
@section('page-subtitle', 'Detail lengkap dan ketersediaan pengasuh terverifikasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('parent.caregivers.index') }}" class="inline-flex items-center gap-2 text-sm text-siaga font-semibold hover:underline">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengasuh
    </a>

    {{-- Header Kartu Profil --}}
    <div class="card overflow-hidden p-0">
        {{-- Banner Gradient --}}
        <div class="gradient-purple h-28 relative">
            <div class="absolute -bottom-10 left-6">
                <div class="w-20 h-20 bg-white rounded-2xl border-4 border-white shadow-xl flex items-center justify-center text-siaga font-bold text-3xl">
                    {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                </div>
            </div>
            <div class="absolute top-4 right-4">
                <span class="badge-verified text-xs px-3 py-1.5">
                    <i class="fas fa-shield-alt mr-1"></i> Terverifikasi SiagaAsuh
                </span>
            </div>
        </div>

        <div class="pt-14 p-6 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $profile->user->name }}</h2>
                    <div class="flex items-center gap-2 mt-1 text-sm text-yellow-500">
                        @php $rating = $profile->rating > 0 ? $profile->rating : 4.8; @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= floor($rating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="text-gray-700 font-bold">{{ $rating }}</span>
                        <span class="text-gray-400">({{ $profile->total_ulasan }} ulasan)</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-phone-alt text-xs text-gray-400 mr-1"></i>{{ $profile->user->phone }}
                    </p>
                </div>
            </div>

            {{-- Tarif Layanan --}}
            <div class="grid grid-cols-3 gap-3 pt-2">
                <div class="bg-purple-50 rounded-2xl p-4 text-center border border-purple-100">
                    <i class="fas fa-sun text-siaga text-xl mb-1"></i>
                    <p class="text-[11px] text-gray-500 font-semibold uppercase">Harian</p>
                    <p class="font-extrabold text-siaga text-sm mt-0.5">Rp {{ number_format($profile->tarif_harian ?? 150000, 0, ',', '.') }}</p>
                </div>
                <div class="bg-red-50 rounded-2xl p-4 text-center border border-red-100">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl mb-1"></i>
                    <p class="text-[11px] text-gray-500 font-semibold uppercase">Darurat</p>
                    <p class="font-extrabold text-red-600 text-sm mt-0.5">Rp {{ number_format($profile->tarif_darurat ?? 200000, 0, ',', '.') }}</p>
                </div>
                <div class="bg-indigo-50 rounded-2xl p-4 text-center border border-indigo-100">
                    <i class="fas fa-moon text-indigo-600 text-xl mb-1"></i>
                    <p class="text-[11px] text-gray-500 font-semibold uppercase">Malam</p>
                    <p class="font-extrabold text-indigo-700 text-sm mt-0.5">Rp {{ number_format($profile->tarif_malam ?? 180000, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Kompetensi & Pengalaman --}}
            <div class="space-y-3 pt-2">
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kompetensi & Keahlian</h4>
                    <div class="p-3.5 bg-gray-50 rounded-xl text-sm text-gray-700 leading-relaxed">
                        {{ $profile->kompetensi ?? 'Perawatan bayi dan anak usia dini.' }}
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Riwayat Pengalaman</h4>
                    <div class="p-3.5 bg-gray-50 rounded-xl text-sm text-gray-700 leading-relaxed">
                        {{ $profile->pengalaman ?? 'Berpengalaman di bidang pengasuhan anak.' }}
                    </div>
                </div>
            </div>

            {{-- Tombol Pesan --}}
            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <a href="{{ route('parent.sessions.create', ['caregiver_id' => $profile->user_id]) }}"
                   class="flex-1 btn-primary text-center py-3.5 rounded-2xl font-bold shadow-lg shadow-purple-500/25 hover:shadow-purple-500/40 transition text-sm">
                    <i class="fas fa-calendar-check mr-2"></i> Aktifkan Sesi Pengasuhan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
