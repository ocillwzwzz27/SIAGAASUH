@extends('layouts.parent')

@section('title', 'Live Monitor Kondisi Anak')
@section('page-title', 'Live Monitor IoT: ' . $session->child->nama)
@section('page-subtitle', 'Pemantauan detak jantung, kadar oksigen, dan sensor pengaman secara langsung')

@push('scripts')
    @vite('resources/js/pages/monitor.js')
@endpush

@section('content')
@php
    $reading = $session->latestReading;
    $dev = $session->child->activeDevice;
    $readings = $session->sensorReadings()->latest()->take(5)->get();
@endphp

<div id="monitor"
     class="max-w-4xl mx-auto space-y-6"
     data-sensor-url="{{ route('parent.sessions.sensor', $session->id) }}"
     data-simulate-url="{{ route('api.sensor.store') }}">

    {{-- Header Status Bar --}}
    <div class="monitor-status">
        <div class="monitor-status__info">
            <div class="live-dot">
                <span class="live-dot__ping"></span>
                <span class="live-dot__core"></span>
            </div>
            <div>
                <h3 class="monitor-status__title">Transmisi Real-Time Aktif</h3>
                <p class="monitor-status__meta">
                    Pengasuh: <strong>{{ $session->caregiver->name }}</strong>
                    &middot; {{ $session->caregiver->phone }}
                    &middot; Layanan {{ ucfirst($session->jenis_layanan) }}
                </p>
            </div>
        </div>
        <a href="{{ route('parent.sessions.show', $session->id) }}" class="btn-outline btn-outline--sm">
            <i class="fas fa-info-circle"></i> Detail Sesi
        </a>
    </div>

    {{-- Alert Box --}}
    <div id="alertBox" class="alert-critical">
        <div class="alert-critical__inner">
            <div class="alert-critical__icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <h4 class="alert-critical__title" id="alertTitle">⚠️ PERINGATAN KONDISI KRITIS!</h4>
                <p class="alert-critical__desc" id="alertDesc">Data sensor mendeteksi kondisi di luar batas normal. Segera hubungi pengasuh!</p>
            </div>
            <button type="button" id="alertClose" class="alert-critical__close" aria-label="Tutup peringatan">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Sensor Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- BPM --}}
        <div class="sensor-card sensor-card--heart">
            <div class="sensor-icon sensor-icon--heart"><i class="fas fa-heartbeat"></i></div>
            <p class="sensor-label">Detak Jantung</p>
            <div class="sensor-reading">
                <span class="bpm-num sensor-value" id="bpmValue">{{ $reading->bpm ?? '--' }}</span>
                <span class="sensor-unit">BPM</span>
            </div>
            <p class="sensor-status" id="bpmStatus">
                @if($reading)
                    <span class="{{ ($reading->bpm > 140 || $reading->bpm < 60) ? 'is-bad' : 'is-ok' }}">
                        {{ strtoupper($reading->status_bpm) }}
                    </span>
                @else
                    <span class="is-idle">Menunggu sinyal...</span>
                @endif
            </p>
            <div class="sensor-note">Batas Normal: 60 — 140 BPM</div>
        </div>

        {{-- SpO2 --}}
        <div class="sensor-card sensor-card--spo2">
            <div class="sensor-icon sensor-icon--spo2"><i class="fas fa-lungs"></i></div>
            <p class="sensor-label">Kadar Oksigen</p>
            <div class="sensor-reading">
                <span class="sensor-value sensor-value--spo2" id="spo2Value">{{ $reading->spo2 ?? '--' }}</span>
                <span class="sensor-unit sensor-unit--lg">%</span>
            </div>
            <p class="sensor-status" id="spo2Status">
                @if($reading && $reading->spo2)
                    <span class="{{ $reading->spo2 >= 95 ? 'is-ok' : 'is-bad' }}">
                        {{ $reading->spo2 >= 95 ? 'OPTIMAL' : 'WASPADA' }}
                    </span>
                @else
                    <span class="is-idle">Menunggu data...</span>
                @endif
            </p>
            <div class="sensor-note">Batas Normal: ≥ 95%</div>
        </div>

        {{-- Reed Switch --}}
        <div class="sensor-card sensor-card--device" id="reedCard">
            <div class="sensor-icon sensor-icon--device"><i class="fas fa-fingerprint"></i></div>
            <p class="sensor-label">Status Perangkat</p>
            <div class="sensor-device-value" id="reedValue">
                @if($reading)
                    {{ $reading->reed_status ? '⚠️ TERLEPAS DARI ANAK' : '✅ TERPASANG AMAN' }}
                @else
                    ✅ TERPASANG AMAN
                @endif
            </div>
            <p class="sensor-sub">Sensor magnetik wearable</p>
            <div class="sensor-note" id="updatedAt">
                Terakhir update: {{ $reading ? $reading->recorded_at->diffForHumans() : 'baru saja' }}
            </div>
        </div>
    </div>

    {{-- Simulator IoT --}}
    <div class="simulator">
        <div class="simulator__head">
            <div class="simulator__title-group">
                <div class="simulator__icon"><i class="fas fa-microchip"></i></div>
                <div>
                    <h4 class="simulator__title">🧪 Alat Uji Sensor Wearable</h4>
                    <p class="simulator__subtitle">Simulasi kirim data IoT dari mikrokontroler</p>
                </div>
            </div>
            <span class="simulator__endpoint">POST /api/v1/sensor</span>
        </div>

        @if($dev)
            @php
                $presets = [
                    ['class' => 'ok',     'icon' => 'fa-heart',               'label' => 'Normal (85 BPM, 98% SpO2)', 'bpm' => 85,  'spo2' => 98, 'reed' => false],
                    ['class' => 'danger', 'icon' => 'fa-exclamation-triangle', 'label' => 'BPM Tinggi (152 BPM)',      'bpm' => 152, 'spo2' => 97, 'reed' => false],
                    ['class' => 'warn',   'icon' => 'fa-lungs',               'label' => 'SpO2 Rendah (91%)',         'bpm' => 80,  'spo2' => 91, 'reed' => false],
                    ['class' => 'device', 'icon' => 'fa-magnet',              'label' => 'Wearable Dilepas',          'bpm' => 90,  'spo2' => 98, 'reed' => true],
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
                            data-reed="{{ $p['reed'] ? 'true' : 'false' }}">
                        <i class="fas {{ $p['icon'] }}"></i> {{ $p['label'] }}
                    </button>
                @endforeach
            </div>
            <p class="simulator__token">
                <i class="fas fa-info-circle"></i>
                Token device: <code>{{ substr($dev->device_token, 0, 20) }}...</code>
            </p>
        @else
            <div class="simulator__empty">
                <i class="fas fa-exclamation-circle"></i>
                Anak ini belum memiliki perangkat wearable yang terdaftar.
            </div>
        @endif
    </div>

    {{-- Riwayat Pembacaan Sensor --}}
    @if($readings->count() > 0)
        <div class="card">
            <h4 class="log-title"><i class="fas fa-history"></i> Log Sensor Terbaru</h4>
            <div class="table-scroll">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>BPM</th>
                            <th>SpO2</th>
                            <th>Reed</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($readings as $r)
                            <tr class="{{ $r->isCritical() ? 'is-critical' : '' }}">
                                <td>{{ $r->recorded_at->format('H:i:s') }}</td>
                                <td class="{{ ($r->bpm < 60 || $r->bpm > 140) ? 'is-bad' : 'is-ok' }}">{{ $r->bpm }}</td>
                                <td class="{{ ($r->spo2 && $r->spo2 < 95) ? 'is-bad' : 'is-ok' }}">{{ $r->spo2 ?? '-' }}%</td>
                                <td>
                                    @if($r->reed_status)
                                        <span class="reed-state is-bad">⚠️ Lepas</span>
                                    @else
                                        <span class="reed-state is-ok">✅ Pasang</span>
                                    @endif
                                </td>
                                <td>
                                    @if($r->isCritical())
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
