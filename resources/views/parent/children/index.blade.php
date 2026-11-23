@extends('layouts.parent')

@section('title', 'Data Anak & Wearable')
@section('page-title', 'Kelola Buah Hati & Perangkat')
@section('page-subtitle', 'Daftarkan profil anak dan tautkan kode perangkat wearable pemantau')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-gray-800 text-lg">Daftar Anak Terdaftar</h3>
        <a href="{{ route('parent.children.create') }}" class="btn-primary text-xs py-2.5 px-4 flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Data Anak
        </a>
    </div>

    @if($children->isEmpty())
        <div class="card text-center py-12 text-gray-400">
            <i class="fas fa-baby text-5xl mb-3 text-purple-200"></i>
            <h4 class="font-bold text-gray-700 text-base">Belum Ada Data Anak</h4>
            <p class="text-xs text-gray-500 mt-1 mb-4">Tambahkan profil anak Anda agar dapat dipasangkan dengan perangkat wearable.</p>
            <a href="{{ route('parent.children.create') }}" class="btn-primary text-xs py-2.5 px-4 inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Anak Pertama
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($children as $ch)
                <div class="card space-y-4 border border-purple-100">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 bg-purple-100 text-siaga rounded-2xl flex items-center justify-center font-bold text-xl">
                                👶
                            </div>
                            <div>
                                <h4 class="font-bold text-base text-gray-800">{{ $ch->nama }}</h4>
                                <p class="text-xs text-gray-500">Usia: {{ $ch->umur }} · {{ ucfirst($ch->jenis_kelamin) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('parent.children.edit', $ch->id) }}" class="text-xs text-gray-400 hover:text-siaga p-1.5 rounded-lg hover:bg-purple-50">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('parent.children.destroy', $ch->id) }}" method="POST" onsubmit="return confirm('Hapus data anak ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-gray-400 hover:text-red-500 p-1.5 rounded-lg hover:bg-red-50">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-600">
                        <span class="font-semibold text-gray-700">Catatan Kesehatan:</span>
                        <p class="mt-0.5">{{ $ch->catatan_kesehatan ?? 'Tidak ada catatan riwayat alergi atau penyakit khusus.' }}</p>
                    </div>

                    {{-- Perangkat Wearable Terhubung --}}
                    <div class="pt-3 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="fas fa-clock text-siaga"></i> Perangkat Wearable
                            </span>
                        </div>

                        @if($ch->devices->isEmpty())
                            <div class="p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                                <p class="text-[11px] text-yellow-800 mb-2">Belum ada perangkat wearable yang ditautkan ke anak ini.</p>
                                <form action="{{ route('parent.children.addDevice', $ch->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="nama_perangkat" placeholder="Nama Alat (Smartband)" required class="input text-xs py-1.5 flex-1">
                                    <input type="text" name="device_code" placeholder="Kode Unik (WEARABLE-xxx)" required class="input text-xs py-1.5 flex-1 font-mono">
                                    <button type="submit" class="btn-primary text-xs py-1.5 px-3 whitespace-nowrap">
                                        Tautkan
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="space-y-1.5">
                                @foreach($ch->devices as $dev)
                                    <div class="p-2.5 bg-purple-50/50 rounded-xl border border-purple-100 flex items-center justify-between text-xs">
                                        <div>
                                            <span class="font-semibold text-gray-800">{{ $dev->nama_perangkat }}</span>
                                            <span class="font-mono text-purple-700 ml-2">[{{ $dev->device_code }}]</span>
                                        </div>
                                        <span class="badge-verified text-[10px]">Aktif</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
