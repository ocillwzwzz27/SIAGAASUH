@extends('layouts.parent')

@section('title', 'Cari Pengasuh Terpercaya')
@section('page-title', 'Pilih Mitra Pengasuh')
@section('page-subtitle', 'Daftar pengasuh bersertifikat dan telah terverifikasi oleh tim SiagaAsuh')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    {{-- Search & Filter --}}
    <div class="card p-4">
        <div class="flex items-center gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" placeholder="Cari nama pengasuh atau keahlian khusus..." class="input pl-10 text-sm">
            </div>
            <div class="flex items-center gap-2">
                <button class="btn-primary text-xs py-2.5 px-4 flex items-center gap-1.5">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </div>
    </div>

    {{-- Caregivers Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($caregivers as $cg)
            <div class="card hover:shadow-lg transition p-5 flex flex-col justify-between border border-purple-100">
                <div>
                    {{-- Header Pengasuh --}}
                    <div class="flex items-start gap-3.5 mb-3">
                        <div class="w-14 h-14 bg-purple-100 text-siaga rounded-2xl flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-sm">
                            {{ strtoupper(substr($cg->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <h3 class="font-bold text-base text-gray-800 truncate">{{ $cg->user->name }}</h3>
                                <i class="fas fa-check-circle text-green-500 text-xs" title="Terverifikasi"></i>
                            </div>
                            <div class="flex items-center gap-1 text-xs text-yellow-500 mt-0.5">
                                <i class="fas fa-star"></i>
                                <span class="font-bold text-gray-800">{{ $cg->rating > 0 ? $cg->rating : '4.8' }}</span>
                                <span class="text-gray-400">({{ $cg->total_ulasan }} ulasan)</span>
                            </div>
                            <span class="text-[11px] text-gray-500 block mt-0.5">
                                <i class="fas fa-phone-alt text-[10px] text-gray-400"></i> {{ $cg->user->phone }}
                            </span>
                        </div>
                    </div>

                    {{-- Kompetensi & Pengalaman --}}
                    <div class="space-y-1.5 text-xs text-gray-600 bg-gray-50 p-3 rounded-xl mb-4">
                        <div>
                            <span class="font-semibold text-gray-700">Kompetensi:</span>
                            <p class="text-gray-600 mt-0.5 line-clamp-2">{{ $cg->kompetensi ?? 'Perawatan bayi & anak' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Pengalaman:</span>
                            <p class="text-gray-600 mt-0.5 line-clamp-1">{{ $cg->pengalaman ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Tarif & Tombol Pesan --}}
                <div class="pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-3 text-xs">
                        <span class="text-gray-500">Mulai dari:</span>
                        <span class="font-extrabold text-siaga text-sm">Rp {{ number_format($cg->tarif_harian ?? 150000, 0, ',', '.') }}<span class="text-[10px] text-gray-400 font-normal">/hari</span></span>
                    </div>

                    <a href="{{ route('parent.sessions.create', ['caregiver_id' => $cg->user_id]) }}" class="w-full btn-primary text-center text-xs py-2.5 rounded-xl block font-semibold shadow-md shadow-purple-500/20 hover:shadow-purple-500/40 transition">
                        Aktifkan Sesi Pengasuhan
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 card text-gray-400">
                <i class="fas fa-user-slash text-4xl mb-2"></i>
                <p class="text-sm">Tidak ada pengasuh terverifikasi saat ini.</p>
            </div>
        @endforelse
    </div>

    <div>
        {{ $caregivers->links() }}
    </div>
</div>
@endsection
