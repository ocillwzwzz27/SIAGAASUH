@extends('layouts.parent')

@section('title', 'Detail Sesi #' . $session->id)
@section('page-title', 'Detail Sesi Pengasuhan #' . $session->id)

@section('content')
<div class="space-y-4">
    {{-- Status Banner --}}
    <div class="card p-4 border-l-4
        @if($session->status === 'berjalan') border-blue-500 bg-blue-50/40
        @elseif($session->status === 'selesai') border-green-500 bg-green-50/40
        @elseif($session->status === 'menunggu') border-yellow-500 bg-yellow-50/40
        @else border-gray-400 @endif">
        <div class="flex flex-col gap-3">
            <div>
                <span class="text-[11px] uppercase font-bold text-gray-500 tracking-wider">Status Sesi:</span>
                <h3 class="text-lg font-extrabold text-gray-800 mt-0.5">{{ strtoupper($session->status) }}</h3>
                <p class="text-xs text-gray-500 mt-1">
                    @if($session->status === 'menunggu')
                        Permintaan telah dikirim ke pengasuh. Menunggu konfirmasi...
                    @elseif($session->status === 'berjalan')
                        Pengasuhan sedang aktif berlangsung.
                    @elseif($session->status === 'selesai')
                        Sesi pengasuhan telah selesai dilakukan.
                    @endif
                </p>
            </div>
            @if($session->status === 'berjalan')
                <a href="{{ route('parent.sessions.monitor', $session->id) }}" class="w-full btn-primary text-xs py-2.5 px-4 flex items-center justify-center gap-2 shadow-md rounded-xl">
                    <i class="fas fa-heartbeat text-red-300"></i> Pantau Sensor Sekarang
                </a>
            @elseif($session->status === 'menunggu')
                <form action="{{ route('parent.sessions.cancel', $session->id) }}" method="POST" data-confirm="Batalkan permintaan sesi ini?">
                    @csrf
                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-bold underline">
                        Batalkan Sesi
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Detail Info --}}
    <div class="card space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">Anak</span>
                <p class="font-bold text-gray-800 mt-0.5">{{ $session->child->nama }} ({{ $session->child->umur }})</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">Mitra Pengasuh</span>
                <p class="font-bold text-purple-700 mt-0.5">{{ $session->caregiver->name }}</p>
                <p class="text-xs text-gray-500">{{ $session->caregiver->phone }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">Layanan</span>
                <p class="font-semibold text-gray-800 mt-0.5 uppercase">{{ $session->jenis_layanan }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">Waktu Selesai</span>
                <p class="text-gray-700 mt-0.5">{{ $session->ended_at ? $session->ended_at->format('d M Y H:i') : '-' }}</p>
            </div>
        </div>

        @if($session->ringkasan)
            <div class="pt-4 border-t border-gray-100">
                <span class="text-xs text-gray-400 uppercase font-bold">Ringkasan Pengasuhan dari Pengasuh:</span>
                <div class="p-3 bg-purple-50/60 rounded-xl text-sm text-gray-800 mt-1.5 leading-relaxed">
                    {{ $session->ringkasan }}
                </div>
            </div>
        @endif
    </div>

    {{-- Kartu Pembayaran Terpadu (QRIS / Transfer PT SiagaAsuh) --}}
    @if(!in_array($session->status, ['ditolak', 'dibatalkan']))
        @php
            $payBadge = match ($session->status_pembayaran) {
                'lunas'   => 'bg-green-100 text-green-700',
                'pending' => 'bg-amber-100 text-amber-800',
                'gagal'   => 'bg-red-100 text-red-700',
                default   => 'bg-gray-100 text-gray-700',
            };
            $payLabel = match ($session->status_pembayaran) {
                'lunas'   => 'Lunas',
                'pending' => 'Menunggu Pembayaran',
                'gagal'   => 'Gagal',
                default   => 'Belum Dibayar',
            };
        @endphp
        <div class="card p-4 border border-purple-100 space-y-3">
            <div class="flex items-center justify-between gap-2">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fas fa-wallet text-siaga"></i> Pembayaran Layanan
                </h4>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $payBadge }}">
                    {{ $payLabel }}
                </span>
            </div>

            <div class="flex items-center justify-between p-3 bg-purple-50/50 rounded-xl border border-purple-100">
                <div>
                    <span class="text-[11px] text-gray-500 block">Total Biaya ({{ ucfirst($session->jenis_layanan) }})</span>
                    <span class="text-lg font-extrabold text-siaga">
                        Rp {{ number_format((float) ($session->total_biaya ?? 150000), 0, ',', '.') }}
                    </span>
                </div>
                @if($session->order_id)
                    <span class="text-[10px] font-mono text-gray-400">{{ $session->order_id }}</span>
                @endif
            </div>

            @if($session->status_pembayaran === 'lunas')
                <div class="text-xs text-green-700 bg-green-50 p-2.5 rounded-xl border border-green-100 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span>
                        Dibayar {{ $session->paid_at ? $session->paid_at->format('d M Y, H:i') . ' WIB' : '' }}
                        {{ $session->metode_pembayaran ? 'via ' . strtoupper(str_replace('_', ' ', $session->metode_pembayaran)) : '' }}
                    </span>
                </div>
            @else
                <form action="{{ route('parent.sessions.pay', $session->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="label text-xs font-bold text-gray-700">Pilih Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2 mt-1.5">
                            @foreach([
                                'qris'         => ['icon' => 'fa-qrcode',           'label' => 'QRIS SiagaAsuh'],
                                'transfer_bca' => ['icon' => 'fa-building-columns', 'label' => 'Transfer BCA'],
                                'transfer_bri' => ['icon' => 'fa-building-columns', 'label' => 'Transfer BRI'],
                                'ewallet'      => ['icon' => 'fa-mobile-screen',    'label' => 'E-Wallet / DANA'],
                            ] as $val => $opt)
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="{{ $val }}" class="peer sr-only" @checked($val === 'qris')>
                                    <div class="p-2.5 rounded-xl border-2 border-gray-200 text-xs font-semibold text-gray-700 flex items-center gap-2 peer-checked:border-siaga peer-checked:bg-purple-50 peer-checked:text-siaga transition">
                                        <i class="fas {{ $opt['icon'] }}"></i>
                                        <span>{{ $opt['label'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-gray-50 border border-dashed border-gray-200 flex items-center gap-3 text-xs text-gray-600">
                        <div class="w-12 h-12 rounded-xl bg-white border border-purple-100 flex items-center justify-center text-siaga text-2xl flex-shrink-0">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-gray-800">Rekening Resmi PT SiagaAsuh</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">Scan QRIS atau transfer sesuai nominal, lalu tekan tombol konfirmasi di bawah.</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-primary py-2.5 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5">
                        <i class="fas fa-check-circle"></i> Konfirmasi &amp; Bayar Sekarang
                    </button>
                </form>
            @endif
        </div>
    @endif

    {{-- Rating & Ulasan (Muncul saat sesi selesai) --}}
    @if($session->status === 'selesai')
        <div class="card p-4 border border-purple-100 space-y-3">
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fas fa-star text-amber-500"></i> Rating &amp; Ulasan Pengasuh
            </h4>

            @if($session->rating !== null)
                <div class="p-3.5 bg-amber-50/60 border border-amber-100 rounded-2xl space-y-1.5">
                    <div class="flex items-center gap-1 text-amber-500 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= round($session->rating) ? 'text-amber-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="font-bold text-gray-800 text-xs ml-1.5">{{ number_format($session->rating, 1) }} / 5.0</span>
                    </div>
                    @if($session->ulasan)
                        <p class="text-xs text-gray-700 leading-relaxed">"{{ $session->ulasan }}"</p>
                    @endif
                </div>
            @else
                <form action="{{ route('parent.sessions.rate', $session->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="label text-xs font-bold text-gray-700">Berikan Penilaian Bintang</label>
                        <div class="grid grid-cols-5 gap-2 mt-1.5">
                            @for($star = 1; $star <= 5; $star++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $star }}" class="peer sr-only" @checked($star === 5) required>
                                    <div class="py-2 rounded-xl border-2 border-gray-200 text-center text-xs font-bold text-gray-600 peer-checked:border-amber-400 peer-checked:bg-amber-50 peer-checked:text-amber-600 transition">
                                        {{ $star }} ★
                                    </div>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div>
                        <label class="label text-xs font-bold text-gray-700">Ulasan Anda (Opsional)</label>
                        <textarea name="ulasan" rows="2" maxlength="500" class="input text-xs" placeholder="Bagaimana pengalaman pengasuhan bersama {{ $session->caregiver->name }}?"></textarea>
                    </div>

                    <button type="submit" class="w-full btn-primary py-2.5 rounded-xl text-xs font-bold">
                        Kirim Rating &amp; Ulasan
                    </button>
                </form>
            @endif
        </div>
    @endif

    {{-- Galeri Foto Kondisi Anak Selama Sesi --}}
    @if($session->checkins->isNotEmpty())
        <div class="card p-4 border border-purple-100 space-y-3">
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fas fa-camera text-siaga"></i> Riwayat Foto Kondisi Anak ({{ $session->checkins->count() }})
            </h4>
            <div class="space-y-3">
                @foreach($session->checkins as $ci)
                    <div class="p-3 rounded-2xl bg-purple-50/40 border border-purple-100 space-y-2">
                        <div class="flex items-center justify-between text-[11px] text-gray-500">
                            <span class="font-bold text-siaga"><i class="fas fa-clock mr-1"></i>Pukul {{ $ci->checked_in_at->format('H:i') }} WIB</span>
                            <span>{{ $ci->checked_in_at->format('d M Y') }}</span>
                        </div>
                        <a href="{{ asset('storage/' . $ci->foto_path) }}" target="_blank" class="block rounded-xl overflow-hidden border border-purple-100">
                            <img src="{{ asset('storage/' . $ci->foto_path) }}" alt="Kondisi {{ $session->child->nama }}" class="w-full h-40 object-cover">
                        </a>
                        @if($ci->catatan)
                            <p class="text-xs text-gray-700 bg-white p-2 rounded-xl border border-purple-50">{{ $ci->catatan }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Log Sensor Terakhir --}}
    @if($session->sensorReadings->isNotEmpty())
        <div class="card p-4 border border-purple-100 space-y-3">
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fas fa-heartbeat text-red-500"></i> Log Sensor IoT Terakhir
            </h4>
            <div class="table-scroll">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>BPM</th>
                            <th>Sensor Pintu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($session->sensorReadings->take(10) as $r)
                            @php $isRowAlert = ($r->bpm < 60 || $r->bpm > 140 || $r->reed_status); @endphp
                            <tr class="{{ $isRowAlert ? 'is-critical' : '' }}">
                                <td>{{ $r->recorded_at->format('H:i:s') }}</td>
                                <td class="{{ ($r->bpm < 60 || $r->bpm > 140) ? 'is-bad' : 'is-ok' }}">{{ $r->bpm }}</td>
                                <td>
                                    @if($r->reed_status)
                                        <span class="reed-state is-bad">🚨 Terbuka</span>
                                    @else
                                        <span class="reed-state is-ok">🚪 Aman</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isRowAlert)
                                        <span class="status-pill status-pill--critical">KRITIS</span>
                                    @else
                                        <span class="status-pill status-pill--normal">NORMAL</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
