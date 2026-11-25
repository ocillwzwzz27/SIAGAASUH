@extends('layouts.caregiver')

@section('title', 'Profil & Dokumen Pengasuh')
@section('page-title', 'Profil & Dokumen KTP')
@section('page-subtitle', 'Lengkapi data untuk verifikasi admin')

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
                <p class="text-xs font-bold">Status: {{ ucfirst($statusVerif) }}</p>
                @if($statusVerif === 'ditolak' && $profile->alasan_penolakan)
                    <p class="text-[11px] mt-0.5">{{ $profile->alasan_penolakan }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="card p-4 border border-purple-100">
        <form action="{{ route('caregiver.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- NIK --}}
            <div>
                <label class="label font-bold">Nomor Induk Kependudukan (NIK)</label>
                <input type="text" name="nik" value="{{ old('nik', $profile->nik) }}" required maxlength="16" placeholder="16 digit nomor KTP" class="input font-mono text-sm">
            </div>

            {{-- Kompetensi --}}
            <div>
                <label class="label font-bold">Kompetensi &amp; Keahlian</label>
                <textarea name="kompetensi" rows="3" required placeholder="Contoh: Sertifikasi pelatihan bayi, First Aid, stimulasi motorik anak..." class="input text-sm">{{ old('kompetensi', $profile->kompetensi) }}</textarea>
            </div>

            {{-- Pengalaman --}}
            <div>
                <label class="label font-bold">Pengalaman Kerja</label>
                <textarea name="pengalaman" rows="3" required placeholder="Contoh: 3 tahun merawat anak balita di daycare..." class="input text-sm">{{ old('pengalaman', $profile->pengalaman) }}</textarea>
            </div>

            {{-- Tarif Layanan (Stack nyaman untuk mobile 430px) --}}
            <div class="space-y-3 pt-1">
                <p class="text-xs font-bold text-gray-700 uppercase">Tarif Layanan (Rp)</p>
                <div>
                    <label class="label text-xs font-semibold">Tarif Harian</label>
                    <input type="number" name="tarif_harian" value="{{ old('tarif_harian', $profile->tarif_harian) }}" placeholder="150000" class="input text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label text-xs font-semibold">Tarif Darurat</label>
                        <input type="number" name="tarif_darurat" value="{{ old('tarif_darurat', $profile->tarif_darurat) }}" placeholder="200000" class="input text-sm">
                    </div>
                    <div>
                        <label class="label text-xs font-semibold">Tarif Malam</label>
                        <input type="number" name="tarif_malam" value="{{ old('tarif_malam', $profile->tarif_malam) }}" placeholder="180000" class="input text-sm">
                    </div>
                </div>
            </div>

            {{-- Upload Foto KTP --}}
            <div>
                <label class="label font-bold">Foto Kartu Identitas (KTP)</label>
                <input type="file" name="foto_identitas" accept="image/*" class="input text-xs py-2">
                @if($profile->foto_identitas)
                    <div class="mt-2 p-2.5 bg-green-50 rounded-xl border border-green-100 text-xs text-green-700 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        <span>KTP sudah terunggah. Unggah lagi jika ingin mengganti.</span>
                    </div>
                @endif
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
