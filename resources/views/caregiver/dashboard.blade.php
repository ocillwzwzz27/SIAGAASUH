@extends('layouts.caregiver')

@section('title', 'Dashboard Pengasuh')
@section('page-title', 'Halo, ' . auth()->user()->name . ' 👋')
@section('page-subtitle', 'Pantau permintaan layanan pengasuhan dan kelola sesi aktif Anda')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- Banner Status Verifikasi Sesuai Flowchart 1 --}}
    @if(!$profile || $profile->status_verifikasi === 'menunggu')
        <div class="p-5 rounded-2xl bg-yellow-50 border border-yellow-200 text-yellow-800 flex items-start gap-4">
            <i class="fas fa-clock text-2xl text-yellow-600 mt-1"></i>
            <div class="flex-1">
                <h4 class="font-bold text-sm">Status: Menunggu Verifikasi Admin</h4>
                <p class="text-xs text-yellow-700 mt-1">
                    Profil dan berkas identitas Anda sedang ditinjau oleh tim SiagaAsuh. Anda akan mulai tampil di pencarian orang tua setelah disetujui.
                </p>
                <a href="{{ route('caregiver.profile.edit') }}" class="text-xs font-bold text-yellow-800 underline mt-2 inline-block">
                    Periksa atau Lengkapi Dokumen &rarr;
                </a>
            </div>
        </div>
    @elseif($profile->status_verifikasi === 'ditolak')
        <div class="p-5 rounded-2xl bg-red-50 border border-red-200 text-red-800 flex items-start gap-4">
            <i class="fas fa-exclamation-circle text-2xl text-red-600 mt-1"></i>
            <div class="flex-1">
                <h4 class="font-bold text-sm">Status: Verifikasi Ditolak</h4>
                <p class="text-xs text-red-700 mt-1">
                    Alasan: <strong class="font-semibold">{{ $profile->alasan_penolakan ?? 'Dokumen kurang jelas.' }}</strong>
                </p>
                <a href="{{ route('caregiver.profile.edit') }}" class="btn-primary text-xs py-1.5 px-3 rounded-xl mt-3 inline-block">
                    Perbaiki Dokumen Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="p-4 rounded-2xl bg-green-50 border border-green-200 text-green-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-xl text-green-600"></i>
                <div>
                    <h4 class="font-bold text-sm">Akun Terverifikasi & Aktif</h4>
                    <p class="text-xs text-green-700">Profil Anda aktif dan tampil di pencarian orang tua.</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs text-yellow-500 font-bold">★ {{ $profile->rating ?? 4.8 }}</span>
                <span class="text-[11px] text-gray-400 block">{{ $profile->total_ulasan ?? 0 }} ulasan</span>
            </div>
        </div>
    @endif

    {{-- Permintaan Sesi Masuk (Perlu Konfirmasi Terima/Tolak) --}}
    @if($sesi_menunggu->isNotEmpty())
        <div class="card border-2 border-purple-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-purple-600 rounded-full animate-ping"></span>
                    <h3 class="font-bold text-gray-800 text-base">Permintaan Sesi Pengasuhan Baru!</h3>
                </div>
                <span class="text-xs bg-purple-100 text-purple-800 font-bold px-2.5 py-1 rounded-full">
                    {{ $sesi_menunggu->count() }} Permintaan
                </span>
            </div>

            <div class="space-y-3">
                @foreach($sesi_menunggu as $req)
                    <div class="p-4 rounded-2xl bg-purple-50/60 border border-purple-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">Mengasuh: {{ $req->child->nama }} ({{ $req->child->umur }})</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Orang Tua: {{ $req->parent->name }} · {{ $req->parent->phone }}</p>
                            <p class="text-xs text-purple-700 font-semibold mt-1">Layanan {{ ucfirst($req->jenis_layanan) }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('caregiver.sessions.confirm', $req->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="terima">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 px-4 rounded-xl shadow-sm transition">
                                    <i class="fas fa-check"></i> Terima Sesi
                                </button>
                            </form>
                            <form action="{{ route('caregiver.sessions.confirm', $req->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="tolak">
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-2 px-4 rounded-xl shadow-sm transition">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Sesi Sedang Berjalan (Bisa Diakhiri + Isi Ringkasan) --}}
    @if($sesi_aktif)
        <div class="card border-l-4 border-blue-500 bg-blue-50/20 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="badge-active">SESI AKTIF BERJALAN</span>
                    <h3 class="text-xl font-bold text-gray-800 mt-2">Sedang Mengasuh: {{ $sesi_aktif->child->nama }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Mulai sejak: {{ $sesi_aktif->started_at ? $sesi_aktif->started_at->format('H:i') : '-' }} WIB</p>
                </div>
            </div>

            {{-- Form Akhiri Sesi Sesuai Flowchart 2 --}}
            <div class="pt-3 border-t border-gray-200">
                <form action="{{ route('caregiver.sessions.end', $sesi_aktif->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="label font-bold text-xs text-gray-700 uppercase">
                            Catatan & Ringkasan Sesi Pengasuhan (Wajib Diisi untuk Orang Tua):
                        </label>
                        <textarea name="ringkasan" rows="3" required
                            placeholder="Ceritakan aktivitas anak hari ini: waktu makan, tidur siang, kondisi kesehatan, dan hal penting lainnya..."
                            class="input text-sm"></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan sesi pengasuhan ini?');"
                        class="btn-primary text-xs py-2.5 px-5 rounded-xl font-bold flex items-center gap-2">
                        <i class="fas fa-flag-checkered"></i> Akhiri Sesi & Kirim Laporan
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="card p-4 text-center">
            <p class="text-xs text-gray-400 uppercase font-semibold">Total Sesi Selesai</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $total_sesi_selesai }}</h3>
        </div>
        <div class="card p-4 text-center">
            <p class="text-xs text-gray-400 uppercase font-semibold">Rating Pengasuh</p>
            <h3 class="text-2xl font-bold text-yellow-500 mt-1">★ {{ $profile->rating ?? 4.8 }}</h3>
        </div>
        <div class="card p-4 text-center col-span-2 md:col-span-1">
            <p class="text-xs text-gray-400 uppercase font-semibold">Total Ulasan</p>
            <h3 class="text-2xl font-bold text-purple-700 mt-1">{{ $profile->total_ulasan ?? 0 }}</h3>
        </div>
    </div>

</div>
@endsection
