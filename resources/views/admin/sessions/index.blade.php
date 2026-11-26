@extends('layouts.admin')

@section('title', 'Monitor Sesi & IoT')
@section('page-title', 'Pemantauan Seluruh Sesi Pengasuhan')
@section('page-subtitle', 'Monitor aktivitas pengasuhan dan pembacaan sensor anak secara real-time')

@section('content')
<div class="card space-y-4">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase">
                    <th class="py-3.5 px-4">ID Sesi</th>
                    <th class="py-3.5 px-4">Anak & Ortu</th>
                    <th class="py-3.5 px-4">Pengasuh</th>
                    <th class="py-3.5 px-4">Layanan</th>
                    <th class="py-3.5 px-4">Pembayaran</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4">Sensor Terakhir</th>
                    <th class="py-3.5 px-4 text-center">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($sessions as $s)
                    <tr class="hover:bg-purple-50/30 transition">
                        <td class="py-4 px-4 font-mono text-xs text-gray-500">
                            #{{ $s->id }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-semibold text-gray-800">{{ $s->child->nama }}</div>
                            <div class="text-xs text-gray-400">Ortu: {{ $s->parent->name }}</div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-medium text-gray-800">{{ $s->caregiver->name }}</div>
                            <div class="text-xs text-gray-400">{{ $s->caregiver->phone }}</div>
                        </td>
                        <td class="py-4 px-4 text-xs uppercase font-medium text-purple-700">
                            {{ $s->jenis_layanan }}
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $s->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $s->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                            </span>
                            @if($s->total_biaya)
                                <div class="text-[11px] text-gray-500 mt-0.5">Rp {{ number_format((float) $s->total_biaya, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                @if($s->status === 'berjalan') bg-blue-100 text-blue-700 animate-pulse
                                @elseif($s->status === 'selesai') bg-green-100 text-green-700
                                @elseif($s->status === 'menunggu') bg-yellow-100 text-yellow-700
                                @elseif($s->status === 'ditolak') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            @if($s->latestReading)
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold {{ $s->latestReading->bpm > 140 || $s->latestReading->bpm < 60 ? 'text-red-600' : 'text-gray-800' }}">
                                        ❤️ {{ $s->latestReading->bpm }} BPM
                                    </span>
                                    @if($s->latestReading->spo2)
                                        <span class="text-xs text-blue-600 font-semibold">💧 {{ $s->latestReading->spo2 }}%</span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-gray-400">{{ $s->latestReading->recorded_at->diffForHumans() }}</div>
                            @else
                                <span class="text-xs text-gray-400">Belum ada sinyal</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <a href="{{ route('admin.sessions.show', $s->id) }}" class="btn-primary text-xs py-1.5 px-3 inline-flex items-center gap-1">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400">Belum ada sesi pengasuhan terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
