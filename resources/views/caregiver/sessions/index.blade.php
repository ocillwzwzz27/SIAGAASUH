@extends('layouts.caregiver')

@section('title', 'Sesi Pengasuhan')
@section('page-title', 'Daftar Semua Sesi Pengasuhan')
@section('page-subtitle', 'Kelola sesi pengasuhan yang sedang berjalan atau riwayat yang telah selesai')

@section('content')
<div class="card space-y-4 max-w-5xl mx-auto">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase">
                    <th class="py-3.5 px-4">Anak</th>
                    <th class="py-3.5 px-4">Orang Tua</th>
                    <th class="py-3.5 px-4">Layanan</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4">Tanggal</th>
                    <th class="py-3.5 px-4 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($sessions as $s)
                    <tr class="hover:bg-purple-50/30 transition">
                        <td class="py-4 px-4 font-bold text-gray-800">{{ $s->child->nama }}</td>
                        <td class="py-4 px-4">
                            <div class="font-medium text-gray-800">{{ $s->parent->name }}</div>
                            <div class="text-xs text-gray-400">{{ $s->parent->phone }}</div>
                        </td>
                        <td class="py-4 px-4 text-xs uppercase font-medium text-purple-700">{{ $s->jenis_layanan }}</td>
                        <td class="py-4 px-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                @if($s->status === 'berjalan') bg-blue-100 text-blue-700
                                @elseif($s->status === 'selesai') bg-green-100 text-green-700
                                @elseif($s->status === 'menunggu') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-xs text-gray-500">
                            {{ $s->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($s->status === 'menunggu')
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('caregiver.sessions.confirm', $s->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="terima">
                                        <button type="submit" class="bg-green-600 text-white text-xs py-1 px-2.5 rounded-lg font-bold">Terima</button>
                                    </form>
                                    <form action="{{ route('caregiver.sessions.confirm', $s->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="tolak">
                                        <button type="submit" class="bg-red-500 text-white text-xs py-1 px-2.5 rounded-lg font-bold">Tolak</button>
                                    </form>
                                </div>
                            @elseif($s->status === 'berjalan')
                                <a href="{{ route('caregiver.dashboard') }}" class="btn-primary text-xs py-1.5 px-3">
                                    Akhiri Sesi
                                </a>
                            @else
                                <span class="text-xs text-gray-400">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400">Belum ada sesi pengasuhan.</td>
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
