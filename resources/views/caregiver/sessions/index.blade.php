@extends('layouts.caregiver')

@section('title', 'Sesi Pengasuhan')
@section('page-title', 'Daftar Sesi Pengasuhan')
@section('page-subtitle', 'Kelola permintaan, sesi berjalan & riwayat')

@section('content')
<div class="space-y-4">
    {{-- Filter Status Pills --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        @php
            $statusAktif = request('status');
            $filters = [
                '' => 'Semua',
                'menunggu' => 'Menunggu',
                'berjalan' => 'Berjalan',
                'selesai' => 'Selesai',
                'ditolak' => 'Ditolak',
            ];
        @endphp
        @foreach($filters as $val => $label)
            <a href="{{ route('caregiver.sessions.index', array_filter(['status' => $val ?: null])) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition
               {{ $statusAktif === ($val ?: null) ? 'bg-siaga text-white shadow-sm' : 'bg-purple-50 text-gray-600 hover:bg-purple-100' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Mobile Card List --}}
    <div class="space-y-3">
        @forelse($sessions as $s)
            @php
                $statusBadge = match ($s->status) {
                    'berjalan' => 'bg-blue-100 text-blue-700',
                    'selesai'  => 'bg-green-100 text-green-700',
                    'menunggu' => 'bg-yellow-100 text-yellow-700',
                    'ditolak'  => 'bg-red-100 text-red-700',
                    default    => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <div class="card p-4 border border-purple-100 space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <h4 class="font-bold text-sm text-gray-800 truncate">{{ $s->child->nama }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">Ortu: {{ $s->parent->name }} &middot; {{ $s->parent->phone }}</p>
                    </div>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full flex-shrink-0 {{ $statusBadge }}">
                        {{ ucfirst($s->status) }}
                    </span>
                </div>

                <div class="flex items-center justify-between text-xs text-gray-500 pt-2 border-t border-gray-100">
                    <span class="uppercase font-semibold text-purple-700">{{ $s->jenis_layanan }}</span>
                    <span>{{ $s->created_at->format('d M Y, H:i') }}</span>
                </div>

                @if($s->catatan_orang_tua)
                    <div class="p-2.5 bg-gray-50 rounded-xl text-[11px] text-gray-600">
                        <strong class="text-gray-700">Catatan Orang Tua:</strong> {{ $s->catatan_orang_tua }}
                    </div>
                @endif

                @if($s->ringkasan)
                    <div class="p-2.5 bg-purple-50/60 rounded-xl text-[11px] text-gray-700">
                        <strong class="text-siaga">Ringkasan Anda:</strong> {{ $s->ringkasan }}
                    </div>
                @endif

                {{-- Aksi sesuai status --}}
                @if($s->status === 'menunggu')
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <form action="{{ route('caregiver.sessions.confirm', $s->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="terima">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 rounded-xl transition">
                                <i class="fa-solid fa-check mr-1"></i> Terima
                            </button>
                        </form>
                        <form action="{{ route('caregiver.sessions.confirm', $s->id) }}" method="POST" data-confirm="Tolak permintaan sesi ini?">
                            @csrf
                            <input type="hidden" name="action" value="tolak">
                            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold py-2 rounded-xl transition">
                                <i class="fa-solid fa-xmark mr-1"></i> Tolak
                            </button>
                        </form>
                    </div>
                @elseif($s->status === 'berjalan')
                    <div class="pt-1">
                        <a href="{{ route('caregiver.dashboard') }}" class="w-full btn-primary text-xs py-2 rounded-xl flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-flag-checkered"></i> Akhiri Sesi di Beranda
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-card">
                <div class="empty-card__icon"><i class="fas fa-clipboard-list"></i></div>
                <h3 class="empty-card__title">Belum Ada Sesi</h3>
                <p class="empty-card__text">Permintaan dan riwayat sesi pengasuhan akan tampil di sini.</p>
            </div>
        @endforelse
    </div>

    <div class="pt-2">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
