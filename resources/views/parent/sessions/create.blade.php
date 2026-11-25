@extends('layouts.parent')

@section('title', 'Pesan Sesi Pengasuhan')
@section('page-title', 'Aktifkan Sesi Pengasuhan')
@section('page-subtitle', 'Pilih anak dan jenis layanan pengasuhan yang Anda butuhkan')

@section('content')
<div class="space-y-4">
    {{-- Detail Pengasuh Terpilih (Sesuai Figma) --}}
    <div class="card p-4 border border-purple-200 bg-purple-50/30">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 bg-siaga text-white rounded-2xl flex items-center justify-center font-bold text-lg shadow-md flex-shrink-0">
                {{ strtoupper(substr($caregiver->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <h3 class="font-bold text-base text-gray-800 truncate">{{ $caregiver->name }}</h3>
                <div class="flex flex-wrap items-center gap-2 text-xs text-yellow-500 mt-0.5">
                    <i class="fas fa-star"></i>
                    @php $cgRating = $caregiver->caregiverProfile->rating ?? 0; @endphp
                    <span class="font-bold text-gray-800">{{ $cgRating > 0 ? number_format($cgRating, 1) : 'Baru' }}</span>
                    <span class="text-gray-400">({{ $caregiver->caregiverProfile->total_ulasan ?? 0 }} ulasan)</span>
                    <span class="text-green-600 font-semibold"><i class="fas fa-shield-alt"></i> Terverifikasi</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Booking Sesi Sesuai Flowchart 2 --}}
    <div class="card">
        <form action="{{ route('parent.sessions.store') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="caregiver_id" value="{{ $caregiver->id }}">

            {{-- 1. Pilih Anak yang Memiliki Wearable --}}
            <div>
                <label class="label font-bold text-gray-800">1. Pilih Buah Hati yang Akan Diasuh</label>
                @if($children->isEmpty())
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-xs text-yellow-800">
                        <p class="font-semibold mb-1">Anda belum mendaftarkan data anak!</p>
                        <a href="{{ route('parent.children.create') }}" class="text-siaga underline font-bold">Tambah data anak terlebih dahulu &rarr;</a>
                    </div>
                @else
                    <div class="space-y-2 mt-2">
                        @foreach($children as $ch)
                            <label class="flex items-center justify-between p-3.5 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-purple-400 has-[:checked]:border-siaga has-[:checked]:bg-purple-50/50 transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="child_id" value="{{ $ch->id }}" required class="text-siaga focus:ring-purple-400">
                                    <div>
                                        <div class="font-bold text-sm text-gray-800">{{ $ch->nama }} ({{ $ch->umur }})</div>
                                        <div class="text-xs text-gray-500">
                                            @if($ch->activeDevice)
                                                <span class="text-green-600 font-medium">✓ Perangkat: {{ $ch->activeDevice->device_code }} ({{ $ch->activeDevice->nama_perangkat }})</span>
                                            @else
                                                <span class="text-yellow-600">Perangkat wearable belum terhubung</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 2. Pilih Jenis Layanan (Figma: Harian, Darurat, Malam) --}}
            <div>
                <label class="label font-bold text-gray-800">2. Pilih Jenis Layanan</label>
                <div class="grid grid-cols-3 gap-3 mt-2">
                    <label class="flex flex-col items-center p-3.5 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-purple-400 has-[:checked]:border-siaga has-[:checked]:bg-purple-50 transition text-center">
                        <input type="radio" name="jenis_layanan" value="harian" class="sr-only" checked>
                        <i class="fas fa-sun text-siaga text-xl mb-1.5"></i>
                        <span class="text-xs font-bold text-gray-800">Harian</span>
                        <span class="text-[10px] text-gray-500 mt-0.5">Rp {{ number_format($caregiver->caregiverProfile->tarif_harian ?? 150000, 0, ',', '.') }}</span>
                    </label>

                    <label class="flex flex-col items-center p-3.5 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-purple-400 has-[:checked]:border-siaga has-[:checked]:bg-purple-50 transition text-center">
                        <input type="radio" name="jenis_layanan" value="darurat" class="sr-only">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl mb-1.5"></i>
                        <span class="text-xs font-bold text-gray-800">Darurat</span>
                        <span class="text-[10px] text-gray-500 mt-0.5">Rp {{ number_format($caregiver->caregiverProfile->tarif_darurat ?? 200000, 0, ',', '.') }}</span>
                    </label>

                    <label class="flex flex-col items-center p-3.5 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-purple-400 has-[:checked]:border-siaga has-[:checked]:bg-purple-50 transition text-center">
                        <input type="radio" name="jenis_layanan" value="malam" class="sr-only">
                        <i class="fas fa-moon text-indigo-600 text-xl mb-1.5"></i>
                        <span class="text-xs font-bold text-gray-800">Malam</span>
                        <span class="text-[10px] text-gray-500 mt-0.5">Rp {{ number_format($caregiver->caregiverProfile->tarif_malam ?? 180000, 0, ',', '.') }}</span>
                    </label>
                </div>
            </div>

            {{-- 3. Catatan Tambahan --}}
            <div>
                <label class="label text-xs font-bold text-gray-600 uppercase">Catatan / Kebutuhan Khusus Anak (Opsional)</label>
                <textarea name="catatan_orang_tua" rows="3" class="input text-sm" placeholder="Contoh: Waktu tidur siang pukul 13.00, minum obat alergi bila batuk..."></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" @if($children->isEmpty()) disabled @endif
                    class="w-full btn-primary text-white py-3 rounded-2xl font-bold shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 transition">
                    Kirim Permintaan Pengasuhan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
