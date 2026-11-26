@extends('layouts.admin')

@section('title', 'Detail Sesi #' . $session->id)
@section('page-title', 'Detail Sesi Pengasuhan #' . $session->id)
@section('page-subtitle', 'Informasi lengkap dan catatan riwayat sensor IoT pengasuhan')

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.sessions.index') }}" class="inline-flex items-center gap-2 text-sm text-siaga font-semibold hover:underline">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Sesi
    </a>

    {{-- Ringkasan Sesi --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Informasi Anak</h4>
            <div class="text-base font-bold text-gray-800">{{ $session->child->nama }}</div>
            <p class="text-xs text-gray-500 mt-1">Usia: {{ $session->child->umur }}</p>
            <p class="text-xs text-gray-500">Catatan: {{ $session->child->catatan_kesehatan ?? '-' }}</p>
        </div>

        <div class="card">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Orang Tua & Pengasuh</h4>
            <div class="text-xs text-gray-500">Orang Tua:</div>
            <div class="text-sm font-semibold text-gray-800">{{ $session->parent->name }} ({{ $session->parent->phone }})</div>
            <div class="text-xs text-gray-500 mt-2">Mitra Pengasuh:</div>
            <div class="text-sm font-semibold text-purple-700">{{ $session->caregiver->name }} ({{ $session->caregiver->phone }})</div>
        </div>

        <div class="card">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status & Jadwal</h4>
            <span class="text-xs font-bold px-3 py-1 rounded-full inline-block
                @if($session->status === 'berjalan') bg-blue-100 text-blue-700
                @elseif($session->status === 'selesai') bg-green-100 text-green-700
                @elseif($session->status === 'menunggu') bg-yellow-100 text-yellow-700
                @else bg-gray-100 text-gray-700 @endif">
                STATUS: {{ strtoupper($session->status) }}
            </span>
            <p class="text-xs text-gray-500 mt-2">Mulai: {{ $session->started_at ? $session->started_at->format('d M Y H:i') : '-' }}</p>
            <p class="text-xs text-gray-500">Selesai: {{ $session->ended_at ? $session->ended_at->format('d M Y H:i') : '-' }}</p>
            <div class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="font-bold text-gray-700">Rp {{ number_format((float) ($session->total_biaya ?? 0), 0, ',', '.') }}</span>
                <span class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ $session->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $session->status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM BAYAR' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Ringkasan Sesi dari Pengasuh jika sudah selesai --}}
    @if($session->ringkasan)
        <div class="card bg-purple-50/50 border-purple-200">
            <h4 class="text-xs font-bold text-purple-800 uppercase tracking-wider mb-1">Catatan Akhir Sesi dari Pengasuh</h4>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $session->ringkasan }}</p>
        </div>
    @endif

    {{-- Log Sensor IoT --}}
    <div class="card space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-heartbeat text-siaga"></i>
                <h3 class="font-bold text-gray-800">Riwayat Pembacaan Sensor Perangkat Wearable</h3>
            </div>
            <span class="text-xs text-gray-400">Maks. 50 data terbaru</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase">
                        <th class="py-2.5 px-3">Waktu Rekam</th>
                        <th class="py-2.5 px-3">BPM (Detak Jantung)</th>
                        <th class="py-2.5 px-3">SpO2 (Kadar Oksigen)</th>
                        <th class="py-2.5 px-3">Status Sensor Pintu</th>
                        <th class="py-2.5 px-3">Kondisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs font-mono">
                    @forelse($session->sensorReadings as $r)
                        <tr class="{{ $r->isCritical() ? 'bg-red-50 text-red-700 font-bold' : '' }}">
                            <td class="py-2.5 px-3 font-sans">{{ $r->recorded_at->format('H:i:s - d M Y') }}</td>
                            <td class="py-2.5 px-3">{{ $r->bpm }} bpm</td>
                            <td class="py-2.5 px-3">{{ $r->spo2 ? $r->spo2 . '%' : '-' }}</td>
                            <td class="py-2.5 px-3">
                                @if($r->reed_status)
                                    <span class="text-red-600 font-bold font-sans">🚨 Pintu Terbuka / Terlewati</span>
                                @else
                                    <span class="text-green-600 font-sans">✓ Pintu Aman</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 font-sans">
                                @if($r->isCritical())
                                    <span class="bg-red-200 text-red-800 px-2 py-0.5 rounded text-[10px]">Waspada/Alert</span>
                                @else
                                    <span class="text-green-600 text-[10px]">Normal</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400 font-sans">Belum ada transmisi data dari wearable untuk sesi ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
