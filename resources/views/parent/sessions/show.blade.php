@extends('layouts.parent')

@section('title', 'Detail Sesi #' . $session->id)
@section('page-title', 'Detail Sesi Pengasuhan #' . $session->id)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('parent.sessions.index') }}" class="inline-flex items-center gap-2 text-sm text-siaga font-semibold hover:underline">
        <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
    </a>

    {{-- Status Banner --}}
    <div class="card p-6 border-l-4
        @if($session->status === 'berjalan') border-blue-500 bg-blue-50/40
        @elseif($session->status === 'selesai') border-green-500 bg-green-50/40
        @elseif($session->status === 'menunggu') border-yellow-500 bg-yellow-50/40
        @else border-gray-400 @endif">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs uppercase font-bold text-gray-500 tracking-wider">Status Sesi:</span>
                <h3 class="text-xl font-extrabold text-gray-800 mt-0.5">{{ strtoupper($session->status) }}</h3>
                <p class="text-xs text-gray-500 mt-1">
                    @if($session->status === 'menunggu')
                        Permintaan telah dikirim ke pengasuh. Menunggu konfirmasi...
                    @elseif($session->status === 'berjalan')
                        Pengasuhan sedang aktif berlangsung.
                    @elseif($session->status === 'selesai')
                        Sesi pengasuhan telah selesai dilakukan.
                    @endif
                </p>
            </div>
            @if($session->status === 'berjalan')
                <a href="{{ route('parent.sessions.monitor', $session->id) }}" class="btn-primary text-xs py-2 px-4 flex items-center gap-2 shadow-md">
                    <i class="fas fa-heartbeat text-red-300"></i> Pantau Sensor Sekarang
                </a>
            @elseif($session->status === 'menunggu')
                <form action="{{ route('parent.sessions.cancel', $session->id) }}" method="POST" data-confirm="Batalkan permintaan sesi ini?">
                    @csrf
                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-bold underline">
                        Batalkan Sesi
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Detail Info --}}
    <div class="card space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">Anak</span>
                <p class="font-bold text-gray-800 mt-0.5">{{ $session->child->nama }} ({{ $session->child->umur }})</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">Mitra Pengasuh</span>
                <p class="font-bold text-purple-700 mt-0.5">{{ $session->caregiver->name }}</p>
                <p class="text-xs text-gray-500">{{ $session->caregiver->phone }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">Layanan</span>
                <p class="font-semibold text-gray-800 mt-0.5 uppercase">{{ $session->jenis_layanan }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">Waktu Selesai</span>
                <p class="text-gray-700 mt-0.5">{{ $session->ended_at ? $session->ended_at->format('d M Y H:i') : '-' }}</p>
            </div>
        </div>

        @if($session->ringkasan)
            <div class="pt-4 border-t border-gray-100">
                <span class="text-xs text-gray-400 uppercase font-bold">Ringkasan Pengasuhan dari Pengasuh:</span>
                <div class="p-3 bg-purple-50/60 rounded-xl text-sm text-gray-800 mt-1.5 leading-relaxed">
                    {{ $session->ringkasan }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
