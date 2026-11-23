@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')
@section('page-subtitle', 'Ringkasan aktivitas platform dan pemantauan sistem SiagaAsuh')

@section('content')
<div class="space-y-6">
    {{-- Grid Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Pengguna --}}
        <div class="card flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_pengguna'] }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $stats['total_orang_tua'] }} Ortu · {{ $stats['total_pengasuh'] }} Pengasuh</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 text-siaga rounded-2xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-users"></i>
            </div>
        </div>

        {{-- Pengasuh Menunggu Verifikasi --}}
        <div class="card flex items-center justify-between border-l-4 border-yellow-400">
            <div>
                <p class="text-xs font-semibold text-yellow-700 uppercase tracking-wider">Menunggu Verifikasi</p>
                <h3 class="text-2xl font-bold text-yellow-800 mt-1">{{ $stats['pengasuh_menunggu'] }}</h3>
                <a href="{{ route('admin.caregiver.index') }}" class="text-xs text-yellow-600 hover:underline mt-0.5 inline-block">Tinjau sekarang &rarr;</a>
            </div>
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-2xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>

        {{-- Pengasuh Terverifikasi --}}
        <div class="card flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengasuh Terverifikasi</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['pengasuh_terverifikasi'] }}</h3>
                <p class="text-xs text-green-600 mt-0.5"><i class="fas fa-check-circle"></i> Siap Melayani</p>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-user-check"></i>
            </div>
        </div>

        {{-- Sesi Aktif Berjalan --}}
        <div class="card flex items-center justify-between border-l-4 border-siaga">
            <div>
                <p class="text-xs font-semibold text-purple-700 uppercase tracking-wider">Sesi Sedang Berjalan</p>
                <h3 class="text-2xl font-bold text-siaga mt-1">{{ $stats['sesi_aktif'] }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">Dari total {{ $stats['total_sesi'] }} sesi</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 text-siaga rounded-2xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-heartbeat animate-pulse"></i>
            </div>
        </div>
    </div>

    {{-- 2 Kolom Konten Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pengasuh yang Perlu Verifikasi --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-yellow-400 rounded-full"></span>
                    <h3 class="font-bold text-gray-800">Pengasuh Perlu Verifikasi</h3>
                </div>
                <a href="{{ route('admin.caregiver.index') }}" class="text-xs text-siaga font-semibold hover:underline">Lihat Semua</a>
            </div>

            @if($pengasuh_menunggu->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-check-double text-3xl mb-2 text-green-400"></i>
                    <p class="text-sm">Semua pengasuh sudah selesai ditinjau!</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($pengasuh_menunggu as $item)
                        <div class="py-3.5 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 text-siaga rounded-full flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm text-gray-800">{{ $item->user->name }}</h4>
                                    <p class="text-xs text-gray-500">NIK: {{ $item->nik ?? '-' }} · Telp: {{ $item->user->phone }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.caregiver.show', $item->id) }}" class="btn-primary text-xs py-1.5 px-3">
                                Tinjau
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sesi Pengasuhan Terbaru --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-purple-600 rounded-full"></span>
                    <h3 class="font-bold text-gray-800">Sesi Pengasuhan Terkini</h3>
                </div>
                <a href="{{ route('admin.sessions.index') }}" class="text-xs text-siaga font-semibold hover:underline">Monitor Semua</a>
            </div>

            @if($sesi_terbaru->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-calendar-times text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada riwayat sesi pengasuhan.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($sesi_terbaru as $s)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-semibold text-sm text-gray-800">{{ $s->child->nama }}</h4>
                                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full
                                        @if($s->status === 'berjalan') bg-blue-100 text-blue-700
                                        @elseif($s->status === 'selesai') bg-green-100 text-green-700
                                        @elseif($s->status === 'menunggu') bg-yellow-100 text-yellow-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ $s->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Ortu: {{ $s->parent->name }} · Pengasuh: {{ $s->caregiver->name }}
                                </p>
                            </div>
                            <a href="{{ route('admin.sessions.show', $s->id) }}" class="text-gray-400 hover:text-siaga text-sm">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
