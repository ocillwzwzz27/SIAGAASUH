@extends('layouts.caregiver')

@section('title', 'Profil & Dokumen Pengasuh')
@section('page-title', 'Kelola Profil & Dokumen Identitas')
@section('page-subtitle', 'Lengkapi data identitas dan kompetensi Anda agar dapat diverifikasi oleh admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="card">
        <form action="{{ route('caregiver.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- NIK --}}
            <div>
                <label class="label font-bold">Nomor Induk Kependudukan (NIK)</label>
                <input type="text" name="nik" value="{{ old('nik', $profile->nik) }}" required maxlength="16" placeholder="16 digit nomor KTP" class="input font-mono text-sm">
            </div>

            {{-- Kompetensi --}}
            <div>
                <label class="label font-bold">Kompetensi & Keahlian</label>
                <textarea name="kompetensi" rows="3" required placeholder="Contoh: Sertifikasi pelatihan bayi, First Aid, stimulasi motorik anak..." class="input text-sm">{{ old('kompetensi', $profile->kompetensi) }}</textarea>
            </div>

            {{-- Pengalaman --}}
            <div>
                <label class="label font-bold">Pengalaman Kerja</label>
                <textarea name="pengalaman" rows="3" required placeholder="Contoh: 3 tahun merawat anak balita di daycare, pernah mengasuh bayi baru lahir..." class="input text-sm">{{ old('pengalaman', $profile->pengalaman) }}</textarea>
            </div>

            {{-- Tarif Layanan --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="label text-xs font-semibold">Tarif Harian (Rp)</label>
                    <input type="number" name="tarif_harian" value="{{ old('tarif_harian', $profile->tarif_harian) }}" placeholder="150000" class="input text-sm">
                </div>
                <div>
                    <label class="label text-xs font-semibold">Tarif Darurat (Rp)</label>
                    <input type="number" name="tarif_darurat" value="{{ old('tarif_darurat', $profile->tarif_darurat) }}" placeholder="200000" class="input text-sm">
                </div>
                <div>
                    <label class="label text-xs font-semibold">Tarif Malam (Rp)</label>
                    <input type="number" name="tarif_malam" value="{{ old('tarif_malam', $profile->tarif_malam) }}" placeholder="180000" class="input text-sm">
                </div>
            </div>

            {{-- Upload Foto KTP --}}
            <div>
                <label class="label font-bold">Unggah Foto Kartu Identitas (KTP)</label>
                <input type="file" name="foto_identitas" accept="image/*" class="input text-xs py-2">
                @if($profile->foto_identitas)
                    <div class="mt-2 text-xs text-green-600 flex items-center gap-1">
                        <i class="fas fa-check-circle"></i> KTP sudah terunggah. Unggah lagi jika ingin mengganti.
                    </div>
                @endif
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-primary py-3 rounded-2xl font-bold shadow-lg shadow-purple-500/30">
                    Simpan & Ajukan Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
