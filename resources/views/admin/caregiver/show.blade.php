@extends('layouts.admin')

@section('title', 'Tinjau Profil Pengasuh')
@section('page-title', 'Tinjau Berkas Calon Mitra PT')
@section('page-subtitle', 'Verifikasi identitas, SKCK, surat sehat, pengalaman, S&K, dan tetapkan tarif layanan')

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
        <div>
            @if($profile->setuju_sk)
                <span class="bg-white/80 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full border border-green-200">
                    <i class="fas fa-file-signature mr-1"></i> S&amp;K Disetujui {{ $profile->sk_accepted_at?->format('d/m/Y H:i') }}
                </span>
            @else
                <span class="bg-white/80 text-red-700 text-xs font-bold px-3 py-1.5 rounded-full border border-red-200">
                    Belum Setuju S&amp;K
                </span>
            @endif
        </div>
    </div>

    {{-- Kartu Detail Profil & Latar Belakang --}}
    <div class="card space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Data Pribadi &amp; Domisili</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-xs text-gray-500">Nama Lengkap</dt>
                        <dd class="font-semibold text-gray-800">{{ $profile->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Email &amp; WhatsApp</dt>
                        <dd class="text-gray-800">{{ $profile->user->email }} &middot; {{ $profile->user->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">NIK</dt>
                        <dd class="font-mono text-gray-800">{{ $profile->nik ?? 'Belum diisi' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Pendidikan Terakhir</dt>
                        <dd class="text-gray-800">{{ $profile->pendidikan_terakhir ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Kontak Darurat Keluarga</dt>
                        <dd class="text-gray-800">{{ $profile->kontak_darurat ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Alamat Domisili</dt>
                        <dd class="text-gray-800">{{ $profile->alamat_domisili ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Tarif Layanan PT Saat Ini</h3>
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

        {{-- Kompetensi & Pengalaman Hidup --}}
        <div class="border-t border-gray-100 pt-4 space-y-4">
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kompetensi &amp; Keahlian</h4>
                <div class="p-3 bg-gray-50 rounded-xl text-sm text-gray-700 leading-relaxed">
                    {{ $profile->kompetensi ?? 'Belum ada data kompetensi yang diisi.' }}
                </div>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pengalaman Mengasuh &amp; Latar Belakang Hidup</h4>
                <div class="p-3 bg-gray-50 rounded-xl text-sm text-gray-700 leading-relaxed">
                    {{ $profile->pengalaman ?? 'Belum ada data pengalaman yang diisi.' }}
                </div>
            </div>
        </div>

        {{-- 4 Berkas Kelayakan Mitra PT --}}
        <div class="border-t border-gray-100 pt-4">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Berkas Persyaratan Mitra PT SiagaAsuh</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $berkasList = [
                        ['title' => '1. Kartu Identitas (KTP)', 'path' => $profile->foto_identitas, 'icon' => 'fa-id-card'],
                        ['title' => '2. SKCK Aktif', 'path' => $profile->dokumen_skck, 'icon' => 'fa-shield-alt'],
                        ['title' => '3. Surat Keterangan Sehat', 'path' => $profile->dokumen_surat_sehat, 'icon' => 'fa-notes-medical'],
                        ['title' => '4. Surat Pengalaman / Sertifikat Mengasuh', 'path' => $profile->dokumen_surat_pengalaman, 'icon' => 'fa-certificate'],
                    ];
                @endphp

                @foreach($berkasList as $berkas)
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="fas {{ $berkas['icon'] }} text-siaga"></i> {{ $berkas['title'] }}
                            </span>
                            @if($berkas['path'])
                                <a href="{{ asset('storage/' . $berkas['path']) }}" target="_blank" class="text-xs font-bold text-siaga hover:underline">
                                    Buka File <i class="fas fa-external-link-alt ml-0.5 text-[10px]"></i>
                                </a>
                            @endif
                        </div>

                        @if($berkas['path'])
                            @php $ext = strtolower(pathinfo($berkas['path'], PATHINFO_EXTENSION)); @endphp
                            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                <img src="{{ asset('storage/' . $berkas['path']) }}" alt="{{ $berkas['title'] }}" class="w-full h-44 object-cover rounded-xl border border-gray-200">
                            @else
                                <div class="p-4 bg-white rounded-xl border border-gray-200 text-center text-xs text-gray-600">
                                    <i class="fas fa-file-pdf text-red-500 text-2xl mb-1"></i>
                                    <p>Dokumen PDF — klik "Buka File" untuk melihat.</p>
                                </div>
                            @endif
                        @else
                            <div class="p-4 bg-white border border-dashed border-red-200 rounded-xl text-center text-red-400 text-xs">
                                Belum diunggah oleh calon mitra.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tindakan Admin PT: Tetapkan Tarif + Setujui / Tolak --}}
    <div class="card border-2 border-purple-200">
        <h3 class="font-bold text-gray-800 text-base mb-1">Keputusan Verifikasi &amp; Penetapan Tarif PT</h3>
        <p class="text-xs text-gray-500 mb-4">Tetapkan tarif layanan sesuai zona kota/jarak dan standar PT SiagaAsuh sebelum menyetujui mitra.</p>

        <form action="{{ route('admin.caregiver.verify', $profile->id) }}" method="POST" id="verifyForm" class="space-y-4">
            @csrf
            <input type="hidden" name="action" id="actionInput" value="terverifikasi">

            {{-- Penetapan Tarif oleh Admin PT --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-4 bg-purple-50/50 rounded-2xl border border-purple-100">
                <div>
                    <label class="label text-xs font-semibold">Tarif Harian PT (Rp)</label>
                    <input type="number" name="tarif_harian" value="{{ old('tarif_harian', $profile->tarif_harian ?? 150000) }}" min="0" class="input text-sm">
                </div>
                <div>
                    <label class="label text-xs font-semibold">Tarif Darurat PT (Rp)</label>
                    <input type="number" name="tarif_darurat" value="{{ old('tarif_darurat', $profile->tarif_darurat ?? 200000) }}" min="0" class="input text-sm">
                </div>
                <div>
                    <label class="label text-xs font-semibold">Tarif Malam PT (Rp)</label>
                    <input type="number" name="tarif_malam" value="{{ old('tarif_malam', $profile->tarif_malam ?? 180000) }}" min="0" class="input text-sm">
                </div>
            </div>

            {{-- Input Alasan Penolakan (Hidden by default) --}}
            <div id="alasanContainer" class="hidden">
                <label class="label text-red-600 font-semibold">Alasan Penolakan (Wajib jika ditolak):</label>
                <textarea name="alasan_penolakan" rows="3" class="input border-red-300 focus:ring-red-400"
                    placeholder="Contoh: Foto SKCK buram / Surat pengalaman belum sesuai, silakan unggah ulang."></textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" data-verify="approve" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Setujui &amp; Aktifkan Mitra
                </button>
                <button type="button" data-verify="reject" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2">
                    <i class="fas fa-times-circle"></i> Tolak Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/pages/admin-caregiver-show.js')
@endpush
