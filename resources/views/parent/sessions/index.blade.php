@extends('layouts.parent')

@section('title', 'Riwayat Sesi Pengasuhan')
@section('page-title', 'Riwayat & Aktivitas Sesi')
@section('page-subtitle', 'Daftar riwayat sesi pengasuhan anak yang pernah dilakukan')

@section('content')
<div class="card space-y-4 max-w-5xl mx-auto">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase">
                    <th class="py-3.5 px-4">Anak</th>
                    <th class="py-3.5 px-4">Pengasuh</th>
                    <th class="py-3.5 px-4">Layanan</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4">Waktu Mulai</th>
                    <th class="py-3.5 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($sessions as $s)
                    <tr class="hover:bg-purple-50/30 transition">
                        <td class="py-4 px-4 font-bold text-gray-800">{{ $s->child->nama }}</td>
                        <td class="py-4 px-4 text-gray-700">{{ $s->caregiver->name }}</td>
                        <td class="py-4 px-4 text-xs uppercase font-medium text-purple-700">{{ $s->jenis_layanan }}</td>
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
                        <td class="py-4 px-4 text-xs text-gray-500">
                            {{ $s->started_at ? $s->started_at->format('d M Y H:i') : ($s->created_at->format('d M Y H:i')) }}
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($s->status === 'berjalan')
                                <a href="{{ route('parent.sessions.monitor', $s->id) }}" class="btn-primary text-xs py-1.5 px-3 inline-flex items-center gap-1.5">
                                    <i class="fas fa-heartbeat"></i> Live Monitor
                                </a>
                            @else
                                <a href="{{ route('parent.sessions.show', $s->id) }}" class="btn-outline text-xs py-1.5 px-3 inline-flex items-center gap-1">
                                    Detail
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400">Belum ada riwayat sesi pengasuhan.</td>
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
