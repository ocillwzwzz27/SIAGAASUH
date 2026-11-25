@extends('layouts.parent')

@section('title', 'Edit Data Anak')
@section('page-title', 'Edit Data Buah Hati')

@section('content')
<div class="space-y-4">
    <div class="card p-4 border border-purple-100">
        <form action="{{ route('parent.children.update', $child->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="label">Nama Lengkap Anak</label>
                <input type="text" name="nama" value="{{ old('nama', $child->nama) }}" required
                    placeholder="Contoh: Muhammad Rayhan" class="input text-sm">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $child->tanggal_lahir->format('Y-m-d')) }}" required class="input text-sm">
                </div>
                <div>
                    <label class="label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="input text-sm">
                        <option value="laki-laki" {{ $child->jenis_kelamin === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ $child->jenis_kelamin === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="label">Foto Anak (Opsional, maks. 2MB)</label>
                @if($child->foto)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ asset('storage/' . $child->foto) }}" alt="{{ $child->nama }}" class="w-12 h-12 rounded-xl object-cover border border-purple-100">
                        <span class="text-[11px] text-gray-500">Foto saat ini tersimpan</span>
                    </div>
                @endif
                <input type="file" name="foto" accept="image/*" class="input text-xs py-2 bg-gray-50">
            </div>

            <div>
                <label class="label">Catatan Kesehatan / Alergi</label>
                <textarea name="catatan_kesehatan" rows="3" class="input text-sm"
                    placeholder="Contoh: Alergi susu sapi, terbiasa minum air hangat...">{{ old('catatan_kesehatan', $child->catatan_kesehatan) }}</textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-primary py-3 rounded-2xl font-bold shadow-lg shadow-purple-500/30">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
