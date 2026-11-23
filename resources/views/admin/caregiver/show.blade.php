@extends('layouts.admin')

@section('title', 'Tinjau Profil Pengasuh')
@section('page-title', 'Tinjau Berkas Pengasuh')
@section('page-subtitle', 'Verifikasi identitas dan kompetensi calon pengasuh sesuai SOP SiagaAsuh')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('admin.caregiver.index') }}" class="inline-flex items-center gap-2 text-sm text-siaga font-semibold hover:underline">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengasuh
    </a>

    {{-- Status Banner --}}
    <div class="p-4 rounded-2xl flex items-center justify-between
        @if($profile->status_verifikasi === 'terverifikasi') bg-green-50 border border-green-200 text-green-800
        @elseif($profile->status_verifikasi === 'menunggu') bg-yellow-50 border border-yellow-200 text-yellow-800
        @else bg-red-50 border border-red-200 text-red-800 @endif">
        <div class="flex items-center gap-3">
            <i class="fas text-xl
                @if($profile->status_verifikasi === 'terverifikasi') fa-check-circle text-green-600
                @elseif($profile->status_verifikasi === 'menunggu') fa-clock text-yellow-600
                @else fa-times-circle text-red-600 @endif"></i>
            <div>
                <p class="font-bold text-sm">Status Saat Ini: {{ strtoupper($profile->status_verifikasi) }}</p>
                @if($profile->alasan_penolakan)
                    <p class="text-xs mt-0.5">Alasan penolakan sebelumnya: {{ $profile->alasan_penolakan }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Kartu Detail Profil --}}
    <div class="card space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Data Pribadi --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Data Pribadi</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500">Nama Lengkap</dt>
                        <dd class="font-semibold text-gray-800">{{ $profile->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Email</dt>
                        <dd class="text-gray-800">{{ $profile->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Nomor Telepon / WA</dt>
                        <dd class="text-gray-800">{{ $profile->user->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Nomor Induk Kependudukan (NIK)</dt>
                        <dd class="font-mono text-gray-800">{{ $profile->nik ?? 'Belum diisi' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Tarif & Reputasi --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Tarif & Layanan</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500">Tarif Pengasuh Harian</dt>
                        <dd class="font-semibold text-siaga">Rp {{ number_format($profile->tarif_harian ?? 0, 0, ',', '.') }}/hari</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Tarif Pengasuh Darurat</dt>
                        <dd class="font-semibold text-siaga">Rp {{ number_format($profile->tarif_darurat ?? 0, 0, ',', '.') }}/sesi</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Tarif Pengasuh Malam</dt>
                        <dd class="font-semibold text-siaga">Rp {{ number_format($profile->tarif_malam ?? 0, 0, ',', '.') }}/malam</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Kompetensi & Pengalaman --}}
        <div class="border-t border-gray-100 pt-4 space-y-4">
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kompetensi & Keahlian</h4>
                <div class="p-3 bg-gray-50 rounded-xl text-sm text-gray-700 leading-relaxed">
                    {{ $profile->kompetensi ?? 'Belum ada data kompetensi yang diisi.' }}
                </div>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pengalaman Kerja</h4>
                <div class="p-3 bg-gray-50 rounded-xl text-sm text-gray-700 leading-relaxed">
                    {{ $profile->pengalaman ?? 'Belum ada data pengalaman yang diisi.' }}
                </div>
            </div>
        </div>

        {{-- Foto KTP / Identitas --}}
        <div class="border-t border-gray-100 pt-4">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Foto Kartu Identitas (KTP)</h4>
            @if($profile->foto_identitas)
                <img src="{{ asset('storage/' . $profile->foto_identitas) }}" alt="Foto KTP" class="max-h-64 rounded-xl border border-gray-200">
            @else
                <div class="p-6 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl text-center text-gray-400 text-sm">
                    <i class="fas fa-id-card text-3xl mb-1"></i>
                    <p>Foto identitas belum diunggah oleh pengasuh.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Tindakan Admin Sesuai Flowchart 1 (Setujui atau Tolak + Alasan) --}}
    <div class="card border-2 border-purple-200">
        <h3 class="font-bold text-gray-800 text-base mb-1">Tindakan Verifikasi</h3>
        <p class="text-xs text-gray-500 mb-4">Pengasuh yang terverifikasi akan langsung tampil di daftar pencarian orang tua.</p>

        <form action="{{ route('admin.caregiver.verify', $profile->id) }}" method="POST" id="verifyForm">
            @csrf
            <input type="hidden" name="action" id="actionInput" value="terverifikasi">

            {{-- Input Alasan Penolakan (Hidden by default) --}}
            <div id="alasanContainer" class="hidden mb-4">
                <label class="label text-red-600 font-semibold">Alasan Penolakan (Wajib jika ditolak):</label>
                <textarea name="alasan_penolakan" rows="3" class="input border-red-300 focus:ring-red-400"
                    placeholder="Contoh: Foto KTP buram dan tidak terbaca, silakan unggah ulang."></textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="submitVerify('terverifikasi')" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Setujui & Verifikasi
                </button>
                <button type="button" onclick="toggleTolak()" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2">
                    <i class="fas fa-times-circle"></i> Tolak Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleTolak() {
        const container = document.getElementById('alasanContainer');
        const actionInput = document.getElementById('actionInput');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            actionInput.value = 'ditolak';
        } else {
            document.getElementById('verifyForm').submit();
        }
    }

    function submitVerify(action) {
        document.getElementById('actionInput').value = action;
        document.getElementById('verifyForm').submit();
    }
</script>
@endsection
