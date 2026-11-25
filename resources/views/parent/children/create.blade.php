@extends('layouts.parent')

@section('title', 'Tambah Data Anak')
@section('page-title', 'Tambah Data Buah Hati')

@section('content')
<div class="space-y-4">
    <div class="card p-4 border border-purple-100">
        <form action="{{ route('parent.children.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="label">Nama Lengkap Anak</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Muhammad Rayhan" class="input text-sm">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="input text-sm">
                </div>
                <div>
                    <label class="label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="input text-sm">
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="label">Foto Anak (Opsional, maks. 2MB)</label>
                <input type="file" name="foto" accept="image/*" class="input text-xs py-2 bg-gray-50">
            </div>

            <div>
                <label class="label">Catatan Kesehatan / Alergi / Kebiasaan</label>
                <textarea name="catatan_kesehatan" rows="3" placeholder="Contoh: Alergi susu sapi, terbiasa minum air hangat..." class="input text-sm">{{ old('catatan_kesehatan') }}</textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-primary py-3 rounded-2xl font-bold shadow-lg shadow-purple-500/30">
                    Simpan Data Anak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
