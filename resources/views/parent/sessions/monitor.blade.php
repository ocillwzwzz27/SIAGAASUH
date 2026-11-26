@extends('layouts.parent')

@section('title', 'Live Monitor Kondisi Anak')
@section('page-title', 'Live Monitor: ' . $session->child->nama)
@section('page-subtitle', 'Pantau detak jantung, status perangkat & info pengasuh')

@push('scripts')
    @vite('resources/js/pages/monitor.js')
@endpush

@section('content')
@php
    $reading = $session->latestReading;
    $dev = $session->child->activeDevice;
    $readings = $session->sensorReadings()->latest()->take(5)->get();
    $checkins = $session->checkins;
    $lastCheckin = $session->latestCheckin;
    $cg = $session->caregiver;
    $cgProfile = $cg->caregiverProfile;

    $hasCoords = $reading && $reading->latitude !== null && $reading->longitude !== null;
    $lat = $hasCoords ? $reading->latitude : null;
    $lng = $hasCoords ? $reading->longitude : null;
    $mapEmbedUrl = $hasCoords
        ? sprintf(
            'https://www.openstreetmap.org/export/embed.html?bbox=%f,%f,%f,%f&layer=mapnik&marker=%f,%f',
            $lng - 0.004, $lat - 0.004, $lng + 0.004, $lat + 0.004, $lat, $lng
        )
        : '';
    $gmapsUrl = $hasCoords ? "https://www.google.com/maps?q={$lat},{$lng}" : '#';

    $refTime = $lastCheckin?->checked_in_at ?? $session->started_at ?? now();
    $checkinMinutes = (int) $refTime->diffInMinutes(now());
    $isCheckinOverdue = $checkinMinutes >= 60;

    // Format nomor WA (08xxx -> 628xxx)
    $rawPhone = preg_replace('/[^0-9]/', '', $cg->phone ?? '');
    $waPhone = str_starts_with($rawPhone, '0') ? '62' . substr($rawPhone, 1) : $rawPhone;
    $waText = urlencode("Halo {$cg->name}, saya orang tua dari {$session->child->nama} ingin menanyakan kondisi anak pada sesi pengasuhan SiagaAsuh.");
    $waUrl = $waPhone ? "https://wa.me/{$waPhone}?text={$waText}" : '#';

    $sertifikatPath = $cgProfile?->dokumen_surat_pengalaman;
    $isCertImage = $sertifikatPath && preg_match('/\.(jpg|jpeg|png|webp)$/i', $sertifikatPath);
@endphp

<div id="monitor"
     class="space-y-4"
     data-sensor-url="{{ route('parent.sessions.sensor', $session->id) }}"
     data-simulate-url="{{ route('api.sensor.store') }}">

    {{-- 1. Header Status Sesi Aktif --}}
    <div class="monitor-status">
        <div class="monitor-status__info">
            <div class="live-dot flex-shrink-0">
                <span class="live-dot__ping"></span>
                <span class="live-dot__core"></span>
            </div>
            <div class="min-w-0">
                <h3 class="monitor-status__title truncate">Pemantauan Aktif</h3>
                <p class="monitor-status__meta">
                    Mulai {{ $session->started_at ? $session->started_at->format('H:i') : '-' }} WIB
                    &middot; Layanan {{ ucfirst($session->jenis_layanan) }}
                </p>
            </div>
        </div>
        <a href="{{ route('parent.sessions.show', $session->id) }}" class="btn-outline btn-outline--sm flex-shrink-0">
            <i class="fas fa-info-circle"></i> Detail
        </a>
    </div>

    {{-- 2. Alert Box Kritis (Muncul Otomatis saat BPM Kritis / Gelang Terlepas) --}}
    <div id="alertBox" class="alert-critical">
        <div class="alert-critical__inner">
            <div class="alert-critical__icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="min-w-0 flex-1">
                <h4 class="alert-critical__title" id="alertTitle">PERINGATAN KONDISI KRITIS!</h4>
                <p class="alert-critical__desc" id="alertDesc">Data sensor mendeteksi kondisi di luar batas normal. Segera hubungi pengasuh!</p>
                @if($waPhone)
                    <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1.5 mt-2 bg-green-600 hover:bg-green-700 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg transition">
                        <i class="fab fa-whatsapp"></i> Hubungi Pengasuh via WA
                    </a>
                @endif
            </div>
            <button type="button" id="alertClose" class="alert-critical__close flex-shrink-0" aria-label="Tutup peringatan">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- 3. Dua Indikator Sensor Utama: Detak Jantung & Status Perangkat --}}
    <div class="grid grid-cols-2 gap-3">
        {{-- Detak Jantung (BPM) --}}
        <div class="sensor-card sensor-card--heart">
            <div class="sensor-icon sensor-icon--heart"><i class="fas fa-heartbeat"></i></div>
            <p class="sensor-label">Detak Jantung</p>
            <div class="sensor-reading">
                <span class="bpm-num sensor-value" id="bpmValue">{{ $reading->bpm ?? '--' }}</span>
                <span class="sensor-unit block mt-0.5">BPM</span>
            </div>
            <p class="sensor-status" id="bpmStatus">
                @if($reading)
                    <span class="{{ ($reading->bpm > 140 || $reading->bpm < 60) ? 'is-bad' : 'is-ok' }}">
                        {{ strtoupper($reading->status_bpm) }}
                    </span>
                @else
                    <span class="is-idle">Menunggu sinyal</span>
                @endif
            </p>
            <div class="sensor-note">Normal: 60–140 BPM</div>
        </div>

        {{-- Status Perangkat (Reed Switch) --}}
        <div class="sensor-card sensor-card--device" id="reedCard">
            <div class="sensor-icon sensor-icon--device"><i class="fas fa-shield-alt"></i></div>
            <p class="sensor-label">Status Perangkat</p>
            <div class="sensor-device-value text-sm sm:text-base" id="reedValue">
                @if($reading)
                    {{ $reading->reed_status ? '⚠️ TERLEPAS' : '✅ TERPASANG' }}
                @else
                    ✅ TERPASANG
                @endif
            </div>
            <p class="sensor-sub">Sensor pengaman gelang</p>
            <div class="sensor-note" id="updatedAt">
                Update: {{ $reading ? $reading->recorded_at->diffForHumans() : 'baru saja' }}
            </div>
        </div>
    </div>

    {{-- 3B. Live Lokasi Anak (GPS dari Wearable IoT) --}}
    <div class="card p-4 border border-purple-100 space-y-3">
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-purple-100 text-siaga flex items-center justify-center text-sm">
                    <i class="fas fa-map-marker-alt"></i>
                </span>
                <div>
                    <h4 class="text-xs font-bold text-gray-900">Live Lokasi Anak (GPS IoT)</h4>
                    <p class="text-[11px] text-gray-500" id="locationNameText">
                        {{ $reading?->location_name ?? ($hasCoords ? 'Titik lokasi perangkat aktif' : 'Menunggu sinyal GPS perangkat...') }}
                    </p>
                </div>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $hasCoords ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}" id="locationStatusBadge">
                {{ $hasCoords ? 'GPS Aktif' : 'Menunggu' }}
            </span>
        </div>

        <div class="rounded-2xl overflow-hidden border border-purple-100 bg-gray-50 relative {{ $hasCoords ? '' : 'hidden' }}" id="locationMapWrap">
            <iframe id="locationMapFrame"
                    src="{{ $mapEmbedUrl }}"
                    class="w-full h-44 border-0"
                    loading="lazy"
                    title="Peta Lokasi Anak"></iframe>
        </div>

        <div id="locationEmptyBox" class="p-4 rounded-2xl bg-gray-50 border border-dashed border-gray-200 text-center text-xs text-gray-500 {{ $hasCoords ? 'hidden' : '' }}">
            <i class="fas fa-satellite-dish text-purple-300 text-lg mb-1 block"></i>
            Belum ada koordinat GPS yang dikirim oleh perangkat wearable.
        </div>

        <div class="flex items-center justify-between gap-2 pt-1">
            <span class="text-[11px] font-mono text-gray-500 truncate" id="locationCoords">
                {{ $hasCoords ? number_format($lat, 5) . ', ' . number_format($lng, 5) : 'Koordinat: --' }}
            </span>
            <a id="locationGmapsBtn"
               href="{{ $gmapsUrl }}"
               target="_blank"
               class="btn-primary text-[11px] py-1.5 px-3 rounded-xl flex-shrink-0 inline-flex items-center gap-1.5 {{ $hasCoords ? '' : 'pointer-events-none opacity-50' }}">
                <i class="fas fa-location-arrow"></i> Buka Google Maps
            </a>
        </div>
    </div>

    {{-- 3C. Timeline Verifikasi Foto Kondisi Anak (Tiap 1 Jam) --}}
    <div class="card p-4 border border-purple-100 space-y-3">
        <div class="flex items-center justify-between gap-2">
            <div>
                <h4 class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                    <i class="fas fa-camera text-siaga"></i> Foto Kondisi Anak (Tiap 1 Jam)
                </h4>
                <p class="text-[11px] text-gray-500 mt-0.5">Verifikasi berkala dari pengasuh</p>
            </div>
            <span id="checkinStatusBadge" class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $isCheckinOverdue ? 'bg-amber-100 text-amber-800' : ($lastCheckin ? 'bg-green-100 text-green-700' : 'bg-purple-50 text-siaga') }}">
                @if($lastCheckin)
                    {{ $isCheckinOverdue ? 'Perlu Update Baru' : 'Terupdate ' . $lastCheckin->checked_in_at->diffForHumans() }}
                @else
                    {{ $isCheckinOverdue ? 'Menunggu Foto Pertama' : 'Aktif' }}
                @endif
            </span>
        </div>

        <div id="checkinList" class="space-y-3">
            @forelse($checkins as $ci)
                <div class="p-3 rounded-2xl bg-purple-50/40 border border-purple-100 space-y-2" data-checkin-id="{{ $ci->id }}">
                    <div class="flex items-center justify-between text-[11px] text-gray-500">
                        <span class="font-bold text-siaga">
                            <i class="fas fa-clock mr-1"></i>Pukul {{ $ci->checked_in_at->format('H:i') }} WIB
                        </span>
                        <span>{{ $ci->checked_in_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ asset('storage/' . $ci->foto_path) }}" target="_blank" class="block relative rounded-xl overflow-hidden border border-purple-100">
                        <img src="{{ asset('storage/' . $ci->foto_path) }}" alt="Kondisi {{ $session->child->nama }}" class="w-full h-44 object-cover">
                        <span class="absolute bottom-2 right-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded-lg">
                            <i class="fas fa-expand-alt mr-1"></i>Perbesar
                        </span>
                    </a>
                    @if($ci->catatan)
                        <p class="text-xs text-gray-700 bg-white p-2.5 rounded-xl border border-purple-50">
                            {{ $ci->catatan }}
                        </p>
                    @endif
                </div>
            @empty
                <div id="checkinEmpty" class="p-4 rounded-2xl bg-gray-50 border border-dashed border-gray-200 text-center text-xs text-gray-500">
                    <i class="fas fa-images text-purple-300 text-lg mb-1 block"></i>
                    Belum ada foto kondisi anak yang dikirim pengasuh pada sesi ini.
                </div>
            @endforelse
        </div>
    </div>

    {{-- 4. Info Mitra Pengasuh Bertugas + Kontak WA + Bukti Lisensi/Sertifikasi --}}
    <div class="card p-4 border border-purple-100 space-y-3.5">
        <div class="flex items-center justify-between gap-2">
            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pengasuh Bertugas</h4>
            <span class="badge-verified text-[10px]">
                <i class="fas fa-check-circle"></i> Terverifikasi PT
            </span>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-purple-100 text-siaga flex items-center justify-center font-bold text-lg flex-shrink-0">
                {{ strtoupper(substr($cg->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-sm text-gray-900 truncate">{{ $cg->name }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    <i class="fab fa-whatsapp text-green-600 mr-1"></i>{{ $cg->phone ?? '-' }}
                </p>
                @if($cgProfile?->pendidikan_terakhir)
                    <p class="text-[11px] text-purple-700 font-medium mt-0.5 truncate">
                        <i class="fas fa-graduation-cap mr-1"></i>{{ $cgProfile->pendidikan_terakhir }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Tombol Hubungi WA & Telepon --}}
        @if($waPhone)
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ $waUrl }}" target="_blank"
                   class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2.5 px-3 rounded-xl flex items-center justify-center gap-1.5 transition shadow-sm">
                    <i class="fab fa-whatsapp text-sm"></i> Chat WhatsApp
                </a>
                <a href="tel:{{ $cg->phone }}"
                   class="btn-outline text-xs py-2.5 px-3 rounded-xl flex items-center justify-center gap-1.5">
                    <i class="fas fa-phone-alt"></i> Telepon
                </a>
            </div>
        @endif

        {{-- Lisensi & Sertifikasi Pengasuh --}}
        <div class="pt-3 border-t border-gray-100 space-y-2">
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                    <i class="fas fa-award text-amber-500"></i> Lisensi &amp; Sertifikasi Mengasuh
                </span>
                @if($sertifikatPath)
                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full">
                        Tersertifikasi
                    </span>
                @else
                    <span class="bg-gray-100 text-gray-500 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                        Belum Dilampirkan
                    </span>
                @endif
            </div>

            @if($cgProfile?->kompetensi)
                <p class="text-[11px] text-gray-600 bg-gray-50 p-2.5 rounded-xl leading-relaxed">
                    <strong class="text-gray-700">Keahlian:</strong> {{ $cgProfile->kompetensi }}
                </p>
            @endif

            @if($sertifikatPath)
                @if($isCertImage)
                    <a href="{{ asset('storage/' . $sertifikatPath) }}" target="_blank" class="block group">
                        <div class="relative rounded-xl overflow-hidden border border-purple-100 bg-gray-50">
                            <img src="{{ asset('storage/' . $sertifikatPath) }}"
                                 alt="Sertifikat / Lisensi {{ $cg->name }}"
                                 class="w-full h-36 object-cover group-hover:opacity-95 transition">
                            <span class="absolute bottom-2 right-2 bg-black/65 text-white text-[10px] font-semibold px-2.5 py-1 rounded-lg">
                                <i class="fas fa-expand-alt mr-1"></i> Perbesar Sertifikat
                            </span>
                        </div>
                    </a>
                @else
                    <a href="{{ asset('storage/' . $sertifikatPath) }}" target="_blank"
                       class="w-full py-2 px-3 rounded-xl bg-purple-50 hover:bg-purple-100 text-siaga text-xs font-bold flex items-center justify-center gap-2 transition">
                        <i class="fas fa-file-pdf"></i> Lihat Dokumen Sertifikat / Lisensi
                    </a>
                @endif
            @endif
        </div>
    </div>

    {{-- 5. Riwayat Pembacaan Sensor Terbaru --}}
    @if($readings->count() > 0)
        <div class="card p-4 border border-purple-100">
            <h4 class="log-title text-sm"><i class="fas fa-history"></i> Riwayat Sensor Terbaru</h4>
            <div class="table-scroll">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>BPM</th>
                            <th>Perangkat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($readings as $r)
                            @php $isRowAlert = ($r->bpm < 60 || $r->bpm > 140 || $r->reed_status); @endphp
                            <tr class="{{ $isRowAlert ? 'is-critical' : '' }}">
                                <td>{{ $r->recorded_at->format('H:i:s') }}</td>
                                <td class="{{ ($r->bpm < 60 || $r->bpm > 140) ? 'is-bad' : 'is-ok' }}">{{ $r->bpm }}</td>
                                <td>
                                    @if($r->reed_status)
                                        <span class="reed-state is-bad">⚠️ Lepas</span>
                                    @else
                                        <span class="reed-state is-ok">✅ Pasang</span>
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

    {{-- 6. Panel Simulasi Uji Sensor (Collapsible agar tidak membingungkan Orang Tua) --}}
    <details class="simulator">
        <summary class="cursor-pointer list-none flex items-center justify-between gap-2">
            <div class="simulator__title-group">
                <div class="simulator__icon"><i class="fas fa-microchip"></i></div>
                <div>
                    <h4 class="simulator__title">Mode Uji Sensor Wearable</h4>
                    <p class="simulator__subtitle">Ketuk untuk simulasi kirim data IoT</p>
                </div>
            </div>
            <i class="fas fa-chevron-down text-xs text-purple-600"></i>
        </summary>

        <div class="mt-3 pt-3 border-t border-purple-200/70">
            @if($dev)
                @php
                    $presets = [
                        ['class' => 'ok',     'icon' => 'fa-heart',                'label' => 'Normal (85 BPM + GPS)',      'bpm' => 85,  'spo2' => 98, 'reed' => false, 'lat' => -6.77452, 'lng' => 110.84152, 'loc' => 'GondangManis, Kudus'],
                        ['class' => 'danger', 'icon' => 'fa-exclamation-triangle', 'label' => 'BPM Tinggi (152 BPM)',       'bpm' => 152, 'spo2' => 97, 'reed' => false, 'lat' => -6.77510, 'lng' => 110.84210, 'loc' => 'Taman Kota Kudus'],
                        ['class' => 'device', 'icon' => 'fa-magnet',               'label' => 'Gelang Terlepas',            'bpm' => 90,  'spo2' => 98, 'reed' => true,  'lat' => -6.77390, 'lng' => 110.84080, 'loc' => 'Area Rumah Anak'],
                    ];
                @endphp
                <div class="simulator__actions">
                    @foreach($presets as $p)
                        <button type="button"
                                class="sim-btn sim-btn--{{ $p['class'] }}"
                                data-sim
                                data-device-code="{{ $dev->device_code }}"
                                data-device-token="{{ $dev->device_token }}"
                                data-bpm="{{ $p['bpm'] }}"
                                data-spo2="{{ $p['spo2'] }}"
                                data-reed="{{ $p['reed'] ? 'true' : 'false' }}"
                                data-lat="{{ $p['lat'] }}"
                                data-lng="{{ $p['lng'] }}"
                                data-loc="{{ $p['loc'] }}">
                            <i class="fas {{ $p['icon'] }}"></i> {{ $p['label'] }}
                        </button>
                    @endforeach
                </div>
            @else
                <div class="simulator__empty">
                    <i class="fas fa-exclamation-circle"></i>
                    Anak ini belum memiliki perangkat wearable yang terdaftar.
                </div>
            @endif
        </div>
    </details>

</div>
@endsection
