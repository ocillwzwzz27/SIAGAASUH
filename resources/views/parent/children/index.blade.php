@extends('layouts.parent')

@section('title', 'Data Anak & Wearable')
@section('page-title', 'Data Anak & Perangkat')
@section('page-subtitle', 'Kelola profil buah hati dan perangkat IoT')

@section('content')
<div class="space-y-4">
    {{-- Header Bar --}}
    <div class="flex items-center justify-between gap-2">
        <div class="flex items-center gap-2">
            <h3 class="font-extrabold text-gray-900 text-base">Buah Hati Anda</h3>
            <span class="bg-purple-100 text-siaga text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                {{ $children->count() }}
            </span>
        </div>
        <a href="{{ route('parent.children.create') }}"
           class="btn-primary text-xs py-2 px-3.5 rounded-xl inline-flex items-center gap-1.5 shadow-sm flex-shrink-0">
            <i class="fa-solid fa-plus"></i> Tambah Anak
        </a>
    </div>

    @if($children->isEmpty())
        <div class="empty-card">
            <div class="empty-card__icon"><i class="fa-solid fa-child-reaching"></i></div>
            <h3 class="empty-card__title">Belum Ada Data Anak</h3>
            <p class="empty-card__text">Tambahkan profil anak Anda agar dapat dipasangkan dengan perangkat IoT &amp; memesan pengasuh.</p>
            <a href="{{ route('parent.children.create') }}" class="btn-primary text-xs py-2.5 px-4 mt-4 inline-flex items-center gap-1.5 rounded-xl">
                <i class="fa-solid fa-plus"></i> Tambah Anak Pertama
            </a>
        </div>
    @else
        <div class="space-y-3.5">
            @foreach($children as $ch)
                @php
                    $isBoy = $ch->jenis_kelamin === 'laki-laki';
                @endphp
                <div class="bg-white rounded-3xl p-4 border border-purple-100 shadow-xs space-y-3.5">
                    {{-- Profil Anak --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($ch->foto)
                                <img src="{{ asset('storage/' . $ch->foto) }}" alt="{{ $ch->nama }}"
                                     class="w-14 h-14 rounded-2xl object-cover border-2 border-purple-100 flex-shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl flex-shrink-0 {{ $isBoy ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                                    <i class="fa-solid {{ $isBoy ? 'fa-child' : 'fa-child-dress' }}"></i>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="font-bold text-sm text-gray-900 truncate">{{ $ch->nama }}</h4>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                    <span class="bg-purple-50 text-siaga text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        {{ $ch->umur }}
                                    </span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isBoy ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                        {{ ucfirst($ch->jenis_kelamin) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <a href="{{ route('parent.children.edit', $ch->id) }}" title="Edit Data Anak"
                               class="w-8 h-8 rounded-xl bg-purple-50 text-siaga hover:bg-purple-100 flex items-center justify-center text-xs transition">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('parent.children.destroy', $ch->id) }}" method="POST" data-confirm="Hapus data anak ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Data Anak"
                                        class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-xs transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Catatan Kesehatan --}}
                    <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 flex items-start gap-2.5 text-xs">
                        <i class="fa-solid fa-notes-medical text-siaga mt-0.5"></i>
                        <div class="min-w-0">
                            <span class="font-bold text-gray-700 block text-[11px]">Catatan Kesehatan / Alergi</span>
                            <p class="text-gray-600 text-[11px] mt-0.5 leading-relaxed">
                                {{ $ch->catatan_kesehatan ?: 'Tidak ada catatan riwayat alergi atau penyakit khusus.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Perangkat IoT Terhubung --}}
                    <div class="pt-3 border-t border-gray-100 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                <i class="fa-solid fa-microchip text-siaga"></i> Perangkat IoT Wearable
                            </span>
                            @if($ch->devices->isNotEmpty())
                                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    Terhubung
                                </span>
                            @else
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    Belum Terhubung
                                </span>
                            @endif
                        </div>

                        @if($ch->devices->isEmpty())
                            <div class="p-3 bg-purple-50/40 rounded-2xl border border-purple-100 space-y-2">
                                <p class="text-[11px] text-gray-600">Tautkan perangkat IoT agar detak jantung, sensor pintu &amp; GPS anak dapat dipantau.</p>
                                <form action="{{ route('parent.children.addDevice', $ch->id) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <input type="text" name="nama_perangkat" placeholder="Nama Alat (contoh: Smartband Siaga)" required class="input text-xs py-2 bg-white">
                                    <input type="text" name="device_code" placeholder="Kode Unik (contoh: WEARABLE-002)" required class="input text-xs py-2 font-mono bg-white">
                                    <button type="submit" class="btn-primary text-xs py-2 px-3 w-full rounded-xl font-bold">
                                        <i class="fa-solid fa-link mr-1"></i> Tautkan Perangkat IoT
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach($ch->devices as $dev)
                                    <div class="p-3 bg-purple-50/50 rounded-2xl border border-purple-100 space-y-2 text-xs">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="min-w-0">
                                                <span class="font-bold text-gray-900 block truncate">{{ $dev->nama_perangkat }}</span>
                                                <span class="font-mono text-[11px] text-siaga font-semibold">{{ $dev->device_code }}</span>
                                            </div>
                                            <span class="badge-verified text-[10px] flex-shrink-0">Aktif</span>
                                        </div>

                                        <div class="flex items-center justify-between gap-2 pt-2 border-t border-purple-100/80">
                                            <span class="text-[10px] text-gray-500 font-mono truncate">
                                                Token: <strong class="text-gray-700">{{ $dev->device_token }}</strong>
                                            </span>
                                            <button type="button"
                                                    onclick="navigator.clipboard?.writeText('{{ $dev->device_token }}'); this.textContent='Tersalin!'; setTimeout(() => this.textContent='Salin', 1500);"
                                                    class="text-[10px] font-bold text-siaga bg-white px-2.5 py-1 rounded-lg border border-purple-200 hover:bg-purple-100 flex-shrink-0 transition">
                                                Salin
                                            </button>
                                        </div>
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
