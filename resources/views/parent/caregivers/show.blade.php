@extends('layouts.parent')

@section('title', 'Detail Pengasuh')
@section('page-title', 'Profil Mitra Pengasuh')
@section('page-subtitle', 'Detail lengkap dan ketersediaan pengasuh terverifikasi')

@section('content')
<div class="space-y-4">
    {{-- Header Kartu Profil --}}
    <div class="card overflow-hidden p-0">
        {{-- Banner Gradient --}}
        <div class="gradient-purple h-24 relative">
            <div class="absolute -bottom-9 left-5">
                <div class="w-18 h-18 bg-white rounded-2xl border-4 border-white shadow-lg flex items-center justify-center text-siaga font-bold text-2xl">
                    {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                </div>
            </div>
            <div class="absolute top-3 right-3">
                <span class="badge-verified text-[11px] px-2.5 py-1">
                    <i class="fas fa-shield-alt mr-1"></i> Terverifikasi SiagaAsuh
                </span>
            </div>
        </div>

        <div class="pt-12 p-5 space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $profile->user->name }}</h2>
                    <div class="flex items-center gap-1.5 mt-1 text-xs text-yellow-500">
                        @if($profile->rating > 0)
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= floor($profile->rating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="text-gray-700 font-bold">{{ number_format($profile->rating, 1) }}</span>
                        @else
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="text-gray-700 font-bold">Baru</span>
                        @endif
                        <span class="text-gray-400">({{ $profile->total_ulasan ?? 0 }} ulasan)</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">
                        <i class="fas fa-phone-alt text-[11px] text-gray-400 mr-1"></i>{{ $profile->user->phone }}
                    </p>
                    @if($profile->pendidikan_terakhir || $profile->alamat_domisili)
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($profile->pendidikan_terakhir)
                                <span class="inline-flex items-center gap-1 bg-purple-50 text-siaga text-[11px] font-semibold px-2.5 py-1 rounded-full">
                                    <i class="fas fa-graduation-cap"></i> {{ $profile->pendidikan_terakhir }}
                                </span>
                            @endif
                            @if($profile->alamat_domisili)
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-[11px] px-2.5 py-1 rounded-full">
                                    <i class="fas fa-map-marker-alt text-siaga"></i> {{ Str::limit($profile->alamat_domisili, 36) }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tarif Layanan --}}
            <div class="grid grid-cols-3 gap-2 pt-1">
                <div class="bg-purple-50 rounded-2xl p-3 text-center border border-purple-100">
                    <i class="fas fa-sun text-siaga text-base mb-1"></i>
                    <p class="text-[10px] text-gray-500 font-semibold uppercase">Harian</p>
                    <p class="font-extrabold text-siaga text-xs mt-0.5">
                        {{ $profile->tarif_harian ? 'Rp ' . number_format($profile->tarif_harian, 0, ',', '.') : 'Sesuai PT' }}
                    </p>
                </div>
                <div class="bg-red-50 rounded-2xl p-3 text-center border border-red-100">
                    <i class="fas fa-exclamation-triangle text-red-500 text-base mb-1"></i>
                    <p class="text-[10px] text-gray-500 font-semibold uppercase">Darurat</p>
                    <p class="font-extrabold text-red-600 text-xs mt-0.5">
                        {{ $profile->tarif_darurat ? 'Rp ' . number_format($profile->tarif_darurat, 0, ',', '.') : 'Sesuai PT' }}
                    </p>
                </div>
                <div class="bg-indigo-50 rounded-2xl p-3 text-center border border-indigo-100">
                    <i class="fas fa-moon text-indigo-600 text-base mb-1"></i>
                    <p class="text-[10px] text-gray-500 font-semibold uppercase">Malam</p>
                    <p class="font-extrabold text-indigo-700 text-xs mt-0.5">
                        {{ $profile->tarif_malam ? 'Rp ' . number_format($profile->tarif_malam, 0, ',', '.') : 'Sesuai PT' }}
                    </p>
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

            {{-- Ulasan Orang Tua --}}
            @if(isset($reviews) && $reviews->isNotEmpty())
                <div class="space-y-2.5 pt-2 border-t border-gray-100">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ulasan Orang Tua</h4>
                    <div class="space-y-2">
                        @foreach($reviews as $rev)
                            <div class="p-3 bg-purple-50/40 rounded-xl border border-purple-100 space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-gray-800">{{ $rev->parent->name }}</span>
                                    <span class="text-amber-500 font-bold">
                                        ★ {{ number_format($rev->rating, 1) }}
                                    </span>
                                </div>
                                @if($rev->ulasan)
                                    <p class="text-xs text-gray-600 leading-relaxed">"{{ $rev->ulasan }}"</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

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
