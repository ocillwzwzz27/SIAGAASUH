@extends('layouts.caregiver')

@section('title', 'Profil & Dokumen Pengasuh')
@section('page-title', 'Profil & Dokumen Mitra')
@section('page-subtitle', 'Kelengkapan berkas resmi PT SiagaAsuh')

@section('content')
<div class="space-y-4">
    {{-- Status Verifikasi Badge --}}
    @php $statusVerif = $profile->status_verifikasi ?? 'menunggu'; @endphp
    <div class="p-3.5 rounded-2xl border flex items-center justify-between
        @if($statusVerif === 'terverifikasi') bg-green-50 border-green-200 text-green-800
        @elseif($statusVerif === 'ditolak') bg-red-50 border-red-200 text-red-800
        @else bg-yellow-50 border-yellow-200 text-yellow-800 @endif">
        <div class="flex items-center gap-2.5">
            <i class="fa-solid {{ $statusVerif === 'terverifikasi' ? 'fa-circle-check' : ($statusVerif === 'ditolak' ? 'fa-circle-exclamation' : 'fa-clock') }}"></i>
            <div>
                <p class="text-xs font-bold">Status Verifikasi: {{ ucfirst($statusVerif) }}</p>
                @if($statusVerif === 'ditolak' && $profile->alasan_penolakan)
                    <p class="text-[11px] mt-0.5">{{ $profile->alasan_penolakan }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Ringkasan Akun Utama --}}
    <div class="card p-4 border border-purple-100 flex items-center gap-3">
        <div class="w-11 h-11 rounded-full bg-purple-100 text-siaga flex items-center justify-center font-bold text-base flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <h4 class="font-bold text-sm text-gray-900 truncate">{{ auth()->user()->name }}</h4>
            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }} &middot; {{ auth()->user()->phone }}</p>
        </div>
    </div>

    {{-- Tarif Layanan yang Ditetapkan oleh Admin PT (Read-Only) --}}
    <div class="card p-4 border border-purple-100 bg-purple-50/40 space-y-2.5">
        <div class="flex items-center justify-between">
            <p class="text-xs font-bold text-siaga uppercase">Tarif Layanan (Ditetapkan Admin PT)</p>
            <span class="text-[10px] text-gray-500">Sesuai zona &amp; standar PT</span>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <div class="bg-white rounded-xl p-2.5 text-center border border-purple-100">
                <p class="text-[10px] text-gray-400 font-semibold">Harian</p>
                <p class="text-xs font-extrabold text-gray-800 mt-0.5">
                    {{ $profile->tarif_harian ? 'Rp ' . number_format($profile->tarif_harian, 0, ',', '.') : 'Menunggu PT' }}
                </p>
            </div>
            <div class="bg-white rounded-xl p-2.5 text-center border border-purple-100">
                <p class="text-[10px] text-gray-400 font-semibold">Darurat</p>
                <p class="text-xs font-extrabold text-gray-800 mt-0.5">
                    {{ $profile->tarif_darurat ? 'Rp ' . number_format($profile->tarif_darurat, 0, ',', '.') : 'Menunggu PT' }}
                </p>
            </div>
            <div class="bg-white rounded-xl p-2.5 text-center border border-purple-100">
                <p class="text-[10px] text-gray-400 font-semibold">Malam</p>
                <p class="text-xs font-extrabold text-gray-800 mt-0.5">
                    {{ $profile->tarif_malam ? 'Rp ' . number_format($profile->tarif_malam, 0, ',', '.') : 'Menunggu PT' }}
                </p>
            </div>
        </div>
    </div>

    <div class="card p-4 border border-purple-100">
        <form action="{{ route('caregiver.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="label font-bold">Nomor Induk Kependudukan (NIK - 16 Digit)</label>
                <input type="text" name="nik" value="{{ old('nik', $profile->nik) }}" required maxlength="16" placeholder="16 digit nomor KTP" class="input font-mono text-sm">
            </div>

            <div>
                <label class="label font-bold">Pendidikan Terakhir</label>
                <select name="pendidikan_terakhir" required class="input text-sm">
                    <option value="">-- Pilih Pendidikan Terakhir --</option>
                    @foreach(['SMP / Sederajat', 'SMA / SMK', 'D3 Kebidanan / Keperawatan', 'S1 PAUD / Psikologi / Kesehatan', 'Lainnya'] as $pend)
                        <option value="{{ $pend }}" @selected(old('pendidikan_terakhir', $profile->pendidikan_terakhir) === $pend)>{{ $pend }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label font-bold">Kontak Darurat Keluarga</label>
                <input type="text" name="kontak_darurat" value="{{ old('kontak_darurat', $profile->kontak_darurat) }}" required placeholder="Contoh: Ibu Siti / 08123456789" class="input text-sm">
            </div>

            <div>
                <label class="label font-bold">Alamat Domisili Lengkap</label>
                <textarea name="alamat_domisili" rows="2" required placeholder="Jalan, RT/RW, Desa/Kelurahan, Kecamatan, Kota..." class="input text-sm">{{ old('alamat_domisili', $profile->alamat_domisili) }}</textarea>
            </div>

            <div>
                <label class="label font-bold">Kompetensi &amp; Keahlian</label>
                <textarea name="kompetensi" rows="3" required placeholder="Contoh: Perawatan bayi, First Aid, stimulasi motorik anak..." class="input text-sm">{{ old('kompetensi', $profile->kompetensi) }}</textarea>
            </div>

            <div>
                <label class="label font-bold">Pengalaman Mengasuh &amp; Latar Belakang Hidup</label>
                <textarea name="pengalaman" rows="3" required minlength="20" placeholder="Ceritakan pengalaman merawat anak & motivasi Anda..." class="input text-sm">{{ old('pengalaman', $profile->pengalaman) }}</textarea>
            </div>

            {{-- Upload Berkas Wajib PT SiagaAsuh --}}
            <div class="space-y-3 pt-2 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-700 uppercase">Dokumen Kelayakan Mitra (JPG/PNG/PDF, maks. 3MB)</p>

                @php
                    $docs = [
                        ['name' => 'foto_identitas', 'label' => '1. Foto KTP', 'val' => $profile->foto_identitas],
                        ['name' => 'dokumen_skck', 'label' => '2. SKCK Aktif', 'val' => $profile->dokumen_skck],
                        ['name' => 'dokumen_surat_sehat', 'label' => '3. Surat Keterangan Sehat', 'val' => $profile->dokumen_surat_sehat],
                        ['name' => 'dokumen_surat_pengalaman', 'label' => '4. Surat Pengalaman / Sertifikat Mengasuh', 'val' => $profile->dokumen_surat_pengalaman],
                    ];
                @endphp

                @foreach($docs as $doc)
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 space-y-1.5">
                        <div class="flex items-center justify-between gap-2">
                            <label class="text-xs font-bold text-gray-800">{{ $doc['label'] }}</label>
                            @if($doc['val'])
                                <a href="{{ asset('storage/' . $doc['val']) }}" target="_blank" class="text-[11px] font-semibold text-green-700 hover:underline">
                                    <i class="fas fa-check-circle"></i> Lihat Berkas
                                </a>
                            @else
                                <span class="text-[10px] font-bold text-red-600">Belum diunggah</span>
                            @endif
                        </div>
                        <input type="file" name="{{ $doc['name'] }}" accept=".jpg,.jpeg,.png,.pdf" class="input text-xs py-1.5 bg-white" {{ $doc['val'] ? '' : 'required' }}>
                    </div>
                @endforeach
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-primary py-3 rounded-2xl font-bold shadow-lg shadow-purple-500/30">
                    Simpan &amp; Ajukan Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
