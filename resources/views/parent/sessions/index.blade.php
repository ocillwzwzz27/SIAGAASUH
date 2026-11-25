@extends('layouts.parent')

@section('title', 'Riwayat Sesi Pengasuhan')
@section('page-title', 'Riwayat & Aktivitas Sesi')
@section('page-subtitle', 'Daftar riwayat sesi pengasuhan anak')

@section('content')
<div class="space-y-3">
    @forelse($sessions as $s)
        @php
            $statusBadge = match ($s->status) {
                'berjalan'   => 'bg-blue-100 text-blue-700',
                'selesai'    => 'bg-green-100 text-green-700',
                'menunggu'   => 'bg-yellow-100 text-yellow-700',
                'ditolak'    => 'bg-red-100 text-red-700',
                default      => 'bg-gray-100 text-gray-600',
            };
        @endphp
        <div class="card p-4 border border-purple-100 space-y-3">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <h4 class="font-bold text-sm text-gray-800 truncate">{{ $s->child->nama }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">Pengasuh: {{ $s->caregiver->name }}</p>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full flex-shrink-0 {{ $statusBadge }}">
                    {{ ucfirst($s->status) }}
                </span>
            </div>

            <div class="flex items-center justify-between text-xs text-gray-500 pt-2 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="uppercase font-semibold text-purple-700">{{ $s->jenis_layanan }}</span>
                    <span>&middot;</span>
                    <span>{{ $s->started_at ? $s->started_at->format('d M Y, H:i') : $s->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <div class="pt-1">
                @if($s->status === 'berjalan')
                    <a href="{{ route('parent.sessions.monitor', $s->id) }}" class="w-full btn-primary text-xs py-2 rounded-xl flex items-center justify-center gap-1.5">
                        <i class="fas fa-heartbeat"></i> Buka Live Monitor
                    </a>
                @else
                    <a href="{{ route('parent.sessions.show', $s->id) }}" class="w-full btn-outline text-xs py-2 rounded-xl flex items-center justify-center gap-1">
                        Lihat Detail Sesi
                    </a>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-card">
            <div class="empty-card__icon"><i class="fas fa-calendar-times"></i></div>
            <h3 class="empty-card__title">Belum Ada Riwayat Sesi</h3>
            <p class="empty-card__text">Anda belum memiliki riwayat atau permintaan sesi pengasuhan.</p>
            <a href="{{ route('parent.caregivers.index') }}" class="btn-primary text-xs py-2.5 px-4 mt-4 inline-flex items-center gap-1.5">
                <i class="fas fa-search"></i> Cari Pengasuh
            </a>
        </div>
    @endforelse

    <div class="pt-2">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
