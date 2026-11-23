@extends('layouts.admin')

@section('title', 'Verifikasi Pengasuh')
@section('page-title', 'Verifikasi Mitra Pengasuh')
@section('page-subtitle', 'Tinjau berkas identitas dan setujui atau tolak pendaftaran pengasuh')

@section('content')
<div class="card">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase">
                    <th class="py-3.5 px-4">Nama Pengasuh</th>
                    <th class="py-3.5 px-4">NIK & Kontak</th>
                    <th class="py-3.5 px-4">Kompetensi</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($pengasuh as $p)
                    <tr class="hover:bg-purple-50/40 transition">
                        <td class="py-4 px-4 font-medium text-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-purple-100 text-siaga rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold">{{ $p->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $p->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="font-mono text-xs">{{ $p->nik ?? 'Belum diisi' }}</div>
                            <div class="text-xs text-gray-500">{{ $p->user->phone }}</div>
                        </td>
                        <td class="py-4 px-4 text-xs text-gray-600 max-w-xs truncate">
                            {{ $p->kompetensi ?? '-' }}
                        </td>
                        <td class="py-4 px-4">
                            @if($p->status_verifikasi === 'terverifikasi')
                                <span class="badge-verified"><i class="fas fa-check"></i> Terverifikasi</span>
                            @elseif($p->status_verifikasi === 'menunggu')
                                <span class="badge-pending"><i class="fas fa-clock"></i> Menunggu</span>
                            @else
                                <span class="badge-rejected"><i class="fas fa-times"></i> Ditolak</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <a href="{{ route('admin.caregiver.show', $p->id) }}" class="btn-primary text-xs py-1.5 px-3 inline-flex items-center gap-1.5">
                                <i class="fas fa-search"></i> Tinjau
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">Belum ada mitra pengasuh yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pengasuh->links() }}
    </div>
</div>
@endsection
