@extends('layouts.parent')

@section('title', 'Cari Pengasuh Terpercaya')
@section('page-title', 'Pilih Mitra Pengasuh')
@section('page-subtitle', 'Pengasuh bersertifikat & terverifikasi')

@section('content')
<div class="space-y-4">
    {{-- Search & Filter --}}
    <form action="{{ route('parent.caregivers.index') }}" method="GET" class="space-y-3">
        @if(request('layanan'))
            <input type="hidden" name="layanan" value="{{ request('layanan') }}">
        @endif
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cari nama atau keahlian..."
                    class="input pl-9 text-xs py-2.5 rounded-2xl">
            </div>
            <button type="submit" class="btn-primary text-xs py-2.5 px-4 rounded-2xl flex-shrink-0">
                Cari
            </button>
        </div>

        {{-- Filter Pills Layanan --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            @php
                $layananAktif = request('layanan');
                $filters = [
                    '' => 'Semua',
                    'harian' => 'Harian',
                    'darurat' => 'Darurat',
                    'malam' => 'Malam',
                    'langganan' => 'Langganan',
                ];
            @endphp
            @foreach($filters as $val => $label)
                <a href="{{ route('parent.caregivers.index', array_filter(['layanan' => $val ?: null, 'q' => request('q')])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition
                   {{ $layananAktif === ($val ?: null) ? 'bg-siaga text-white shadow-sm' : 'bg-purple-50 text-gray-600 hover:bg-purple-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </form>

    {{-- Caregivers List (Mobile Card) --}}
    <div class="space-y-3">
        @forelse($caregivers as $cg)
            <div class="card p-4 border border-purple-100 space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 bg-purple-100 text-siaga rounded-2xl flex items-center justify-center font-bold text-lg flex-shrink-0">
                        {{ strtoupper(substr($cg->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <h3 class="font-bold text-sm text-gray-800 truncate">{{ $cg->user->name }}</h3>
                            <i class="fas fa-check-circle text-green-500 text-xs flex-shrink-0" title="Terverifikasi"></i>
                        </div>
                        <div class="flex items-center gap-1 text-xs text-yellow-500 mt-0.5">
                            <i class="fas fa-star"></i>
                            <span class="font-bold text-gray-800">{{ $cg->rating > 0 ? number_format($cg->rating, 1) : 'Baru' }}</span>
                            <span class="text-gray-400">({{ $cg->total_ulasan ?? 0 }} ulasan)</span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1 line-clamp-2">{{ $cg->kompetensi ?? 'Perawatan bayi & anak' }}</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-3">
                    <div>
                        <span class="text-[10px] text-gray-400 block">Mulai dari</span>
                        <span class="font-extrabold text-siaga text-sm">
                            Rp {{ number_format($cg->tarif_harian ?? 150000, 0, ',', '.') }}<span class="text-[10px] text-gray-400 font-normal">/hari</span>
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('parent.caregivers.show', $cg->id) }}" class="btn-outline text-xs py-2 px-3 rounded-xl">
                            Profil
                        </a>
                        <a href="{{ route('parent.sessions.create', ['caregiver_id' => $cg->user_id]) }}" class="btn-primary text-xs py-2 px-3.5 rounded-xl">
                            Pesan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-card">
                <div class="empty-card__icon"><i class="fas fa-user-slash"></i></div>
                <h3 class="empty-card__title">Pengasuh Tidak Ditemukan</h3>
                <p class="empty-card__text">Belum ada pengasuh terverifikasi yang sesuai pencarian Anda.</p>
            </div>
        @endforelse
    </div>

    <div>
        {{ $caregivers->links() }}
    </div>
</div>
@endsection
