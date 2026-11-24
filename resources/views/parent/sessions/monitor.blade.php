@extends('layouts.parent')

@section('title', 'Live Monitor Kondisi Anak')
@section('page-title', 'Live Monitor IoT: ' . $session->child->nama)
@section('page-subtitle', 'Pemantauan detak jantung, kadar oksigen, dan sensor pengaman secara langsung')

@push('styles')
<style>
    @keyframes bpmPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }
    @keyframes fadeInDown {
        from { opacity:0; transform:translateY(-12px); }
        to { opacity:1; transform:translateY(0); }
    }
    .bpm-num { animation: bpmPulse 1.2s ease-in-out infinite; display:inline-block; }
    .alert-show { animation: fadeInDown 0.4s ease; }
    .sensor-card { background:#fff; border-radius:20px; box-shadow:0 1px 6px rgba(0,0,0,0.06); border:1px solid #f1f0fb; padding:2rem 1.5rem; text-align:center; position:relative; overflow:hidden; transition:box-shadow 0.3s; }
    .sensor-card:hover { box-shadow:0 8px 25px rgba(0,0,0,0.1); }
    .sensor-icon { width:70px; height:70px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px; font-size:2rem; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header Status Bar --}}
    <div style="background:#fff;border-radius:16px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 1px 6px rgba(0,0,0,0.06);border:1px solid #f1f0fb;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="position:relative;width:14px;height:14px;">
                <span style="position:absolute;inset:0;background:#22C55E;border-radius:50%;animation:ping 1.5s cubic-bezier(0,0,0.2,1) infinite;opacity:0.75;"></span>
                <span style="position:absolute;inset:0;background:#22C55E;border-radius:50%;"></span>
            </div>
            <div>
                <h3 style="font-weight:700;color:#111827;font-size:0.95rem;">Transmisi Real-Time Aktif</h3>
                <p style="font-size:0.72rem;color:#9CA3AF;">
                    Pengasuh: <strong style="color:#374151;">{{ $session->caregiver->name }}</strong>
                    &middot; {{ $session->caregiver->phone }}
                    &middot; Layanan {{ ucfirst($session->jenis_layanan) }}
                </p>
            </div>
        </div>
        <a href="{{ route('parent.sessions.show', $session->id) }}"
           style="display:inline-flex;align-items:center;gap:6px;border:2px solid #7B2FBE;color:#7B2FBE;padding:8px 16px;border-radius:10px;font-weight:600;font-size:0.8rem;text-decoration:none;transition:all 0.2s;">
            <i class="fas fa-info-circle"></i> Detail Sesi
        </a>
    </div>

    {{-- Alert Box --}}
    <div id="alertBox" style="display:none;padding:16px 20px;border-radius:16px;background:linear-gradient(135deg,#FEF2F2,#FEE2E2);border:2px solid #FCA5A5;color:#991B1B;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:48px;height:48px;background:#EF4444;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.3rem;flex-shrink:0;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h4 style="font-weight:800;font-size:1rem;" id="alertTitle">⚠️ PERINGATAN KONDISI KRITIS!</h4>
                <p style="font-size:0.8rem;margin-top:3px;color:#B91C1C;" id="alertDesc">Data sensor mendeteksi kondisi di luar batas normal. Segera hubungi pengasuh!</p>
            </div>
            <button onclick="document.getElementById('alertBox').style.display='none';"
                    style="margin-left:auto;width:28px;height:28px;background:rgba(239,68,68,0.15);border:none;border-radius:50%;cursor:pointer;color:#DC2626;font-size:0.85rem;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Sensor Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- BPM --}}
        <div class="sensor-card" style="border-top:4px solid #EF4444;" id="bpmCard">
            <div class="sensor-icon" style="background:#FEE2E2;color:#DC2626;">
                <i class="fas fa-heartbeat" id="heartIcon"></i>
            </div>
            <p style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9CA3AF;">Detak Jantung</p>
            <div style="margin:12px 0 4px;">
                <span class="bpm-num" style="font-size:3.5rem;font-weight:900;color:#111827;line-height:1;" id="bpmValue">
                    {{ $session->latestReading->bpm ?? '--' }}
                </span>
                <span style="font-size:1rem;color:#9CA3AF;font-weight:600;">BPM</span>
            </div>
            <p style="font-size:0.8rem;font-weight:700;margin:4px 0;" id="bpmStatus">
                @if($session->latestReading)
                    <span style="color:{{ ($session->latestReading->bpm > 140 || $session->latestReading->bpm < 60) ? '#DC2626' : '#16A34A' }};">
                        {{ strtoupper($session->latestReading->status_bpm) }}
                    </span>
                @else
                    <span style="color:#9CA3AF;">Menunggu sinyal...</span>
                @endif
            </p>
            <div style="margin-top:12px;background:#F9FAFB;border-radius:8px;padding:6px 10px;font-size:0.68rem;color:#6B7280;">
                Batas Normal: 60 — 140 BPM
            </div>
        </div>

        {{-- SpO2 --}}
        <div class="sensor-card" style="border-top:4px solid #3B82F6;">
            <div class="sensor-icon" style="background:#DBEAFE;color:#2563EB;">
                <i class="fas fa-lungs"></i>
            </div>
            <p style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9CA3AF;">Kadar Oksigen</p>
            <div style="margin:12px 0 4px;">
                <span style="font-size:3.5rem;font-weight:900;color:#1D4ED8;line-height:1;" id="spo2Value">
                    {{ $session->latestReading->spo2 ?? '--' }}
                </span>
                <span style="font-size:1.2rem;color:#9CA3AF;font-weight:600;">%</span>
            </div>
            <p style="font-size:0.8rem;font-weight:700;margin:4px 0;" id="spo2Status">
                @if($session->latestReading && $session->latestReading->spo2)
                    <span style="color:{{ $session->latestReading->spo2 >= 95 ? '#16A34A' : '#DC2626' }};">
                        {{ $session->latestReading->spo2 >= 95 ? 'OPTIMAL' : 'WASPADA' }}
                    </span>
                @else
                    <span style="color:#9CA3AF;">Menunggu data...</span>
                @endif
            </p>
            <div style="margin-top:12px;background:#F9FAFB;border-radius:8px;padding:6px 10px;font-size:0.68rem;color:#6B7280;">
                Batas Normal: ≥ 95%
            </div>
        </div>

        {{-- Reed Switch --}}
        <div class="sensor-card" style="border-top:4px solid #22C55E;" id="reedCard">
            <div class="sensor-icon" style="background:#DCFCE7;color:#15803D;" id="reedIconBg">
                <i class="fas fa-fingerprint" id="reedIcon"></i>
            </div>
            <p style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9CA3AF;">Status Perangkat</p>
            <div style="margin:12px 0;font-size:1.3rem;font-weight:900;color:#111827;line-height:1.3;" id="reedValue">
                @if($session->latestReading)
                    {{ $session->latestReading->reed_status ? '⚠️ TERLEPAS DARI ANAK' : '✅ TERPASANG AMAN' }}
                @else
                    ✅ TERPASANG AMAN
                @endif
            </div>
            <p style="font-size:0.75rem;color:#6B7280;" id="reedSub">Sensor magnetik wearable</p>
            <div style="margin-top:12px;background:#F9FAFB;border-radius:8px;padding:6px 10px;font-size:0.68rem;color:#6B7280;" id="updatedAt">
                Terakhir update: {{ $session->latestReading ? $session->latestReading->recorded_at->diffForHumans() : 'baru saja' }}
            </div>
        </div>
    </div>

    {{-- Simulator IoT --}}
    <div style="background:linear-gradient(135deg,#FAF5FF,#F3E8FF);border:1.5px solid #DDD6FE;border-radius:20px;padding:1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;background:#7B2FBE;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;">
                    <i class="fas fa-microchip"></i>
                </div>
                <div>
                    <h4 style="font-weight:700;font-size:0.9rem;color:#4C1D95;">🧪 Alat Uji Sensor Wearable</h4>
                    <p style="font-size:0.68rem;color:#7C3AED;">Simulasi kirim data IoT dari mikrokontroler</p>
                </div>
            </div>
            <span style="font-size:0.68rem;color:#7C3AED;font-family:monospace;background:#EDE9FE;padding:4px 8px;border-radius:6px;">POST /api/v1/sensor</span>
        </div>

        @php $dev = $session->child->activeDevice; @endphp
        @if($dev)
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:12px;">
                <button type="button"
                    onclick="sendSimulatedData({{ $dev->id }}, '{{ $dev->device_code }}', '{{ $dev->device_token }}', 85, 98, false)"
                    style="background:#16A34A;color:#fff;font-size:0.78rem;font-weight:700;padding:9px 16px;border-radius:10px;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all 0.2s;">
                    <i class="fas fa-heart"></i> Normal (85 BPM, 98% SpO2)
                </button>
                <button type="button"
                    onclick="sendSimulatedData({{ $dev->id }}, '{{ $dev->device_code }}', '{{ $dev->device_token }}', 152, 97, false)"
                    style="background:#DC2626;color:#fff;font-size:0.78rem;font-weight:700;padding:9px 16px;border-radius:10px;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all 0.2s;">
                    <i class="fas fa-exclamation-triangle"></i> BPM Tinggi (152 BPM)
                </button>
                <button type="button"
                    onclick="sendSimulatedData({{ $dev->id }}, '{{ $dev->device_code }}', '{{ $dev->device_token }}', 80, 91, false)"
                    style="background:#D97706;color:#fff;font-size:0.78rem;font-weight:700;padding:9px 16px;border-radius:10px;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all 0.2s;">
                    <i class="fas fa-lungs"></i> SpO2 Rendah (91%)
                </button>
                <button type="button"
                    onclick="sendSimulatedData({{ $dev->id }}, '{{ $dev->device_code }}', '{{ $dev->device_token }}', 90, 98, true)"
                    style="background:#7C3AED;color:#fff;font-size:0.78rem;font-weight:700;padding:9px 16px;border-radius:10px;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all 0.2s;">
                    <i class="fas fa-magnet"></i> Wearable Dilepas
                </button>
            </div>
            <p style="font-size:0.68rem;color:#6D28D9;margin-top:10px;">
                <i class="fas fa-info-circle"></i>
                Token device: <code style="background:#EDE9FE;padding:1px 6px;border-radius:4px;">{{ substr($dev->device_token, 0, 20) }}...</code>
            </p>
        @else
            <div style="text-align:center;padding:1rem;color:#DC2626;font-size:0.85rem;font-weight:600;">
                <i class="fas fa-exclamation-circle"></i>
                Anak ini belum memiliki perangkat wearable yang terdaftar.
            </div>
        @endif
    </div>

    {{-- Riwayat Pembacaan Sensor --}}
    @php $readings = $session->sensorReadings()->latest()->take(5)->get(); @endphp
    @if($readings->count() > 0)
        <div class="card">
            <h4 style="font-weight:700;color:#111827;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-history" style="color:#7B2FBE;"></i> Log Sensor Terbaru
            </h4>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:0.8rem;">
                    <thead>
                        <tr style="background:#F9FAFB;">
                            <th style="padding:8px 12px;text-align:left;font-weight:700;color:#374151;font-size:0.7rem;text-transform:uppercase;">Waktu</th>
                            <th style="padding:8px 12px;text-align:center;font-weight:700;color:#374151;font-size:0.7rem;text-transform:uppercase;">BPM</th>
                            <th style="padding:8px 12px;text-align:center;font-weight:700;color:#374151;font-size:0.7rem;text-transform:uppercase;">SpO2</th>
                            <th style="padding:8px 12px;text-align:center;font-weight:700;color:#374151;font-size:0.7rem;text-transform:uppercase;">Reed</th>
                            <th style="padding:8px 12px;text-align:center;font-weight:700;color:#374151;font-size:0.7rem;text-transform:uppercase;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($readings as $r)
                            <tr style="border-top:1px solid #F3F4F6;{{ $r->isCritical() ? 'background:#FFF1F2;' : '' }}">
                                <td style="padding:9px 12px;color:#6B7280;">{{ $r->recorded_at->format('H:i:s') }}</td>
                                <td style="padding:9px 12px;text-align:center;font-weight:700;color:{{ ($r->bpm < 60 || $r->bpm > 140) ? '#DC2626' : '#15803D' }};">{{ $r->bpm }}</td>
                                <td style="padding:9px 12px;text-align:center;font-weight:700;color:{{ ($r->spo2 && $r->spo2 < 95) ? '#DC2626' : '#15803D' }};">{{ $r->spo2 ?? '-' }}%</td>
                                <td style="padding:9px 12px;text-align:center;">
                                    @if($r->reed_status)
                                        <span style="color:#DC2626;font-size:0.75rem;font-weight:700;">⚠️ Lepas</span>
                                    @else
                                        <span style="color:#15803D;font-size:0.75rem;font-weight:700;">✅ Pasang</span>
                                    @endif
                                </td>
                                <td style="padding:9px 12px;text-align:center;">
                                    @if($r->isCritical())
                                        <span style="background:#FEE2E2;color:#991B1B;font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:999px;">KRITIS</span>
                                    @else
                                        <span style="background:#DCFCE7;color:#15803D;font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:999px;">NORMAL</span>
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

@push('scripts')
<script>
    // --- CSS untuk ping animation ---
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ping { 75%,100% { transform:scale(2); opacity:0; } }
    `;
    document.head.appendChild(style);

    const sessionId = {{ $session->id }};
    const sensorUrl = "{{ route('parent.sessions.sensor', $session->id) }}";
    let lastBpm = null;

    function fetchSensorData() {
        fetch(sensorUrl)
            .then(res => res.json())
            .then(data => {
                if (data.bpm !== undefined && data.bpm !== null) {
                    const bpmEl = document.getElementById('bpmValue');
                    bpmEl.textContent = data.bpm;

                    // Animate BPM change
                    if (lastBpm !== data.bpm) {
                        bpmEl.style.transform = 'scale(1.2)';
                        setTimeout(() => { bpmEl.style.transform = 'scale(1)'; bpmEl.style.transition = 'transform 0.3s'; }, 200);
                        lastBpm = data.bpm;
                    }

                    const isBpmCritical = data.bpm > 140 || data.bpm < 60;
                    const statusEl = document.getElementById('bpmStatus');
                    statusEl.innerHTML = `<span style="color:${isBpmCritical ? '#DC2626' : '#16A34A'};font-weight:700;">${(data.status_bpm || (isBpmCritical ? 'KRITIS' : 'NORMAL')).toUpperCase()}</span>`;
                }

                if (data.spo2 !== undefined && data.spo2 !== null) {
                    document.getElementById('spo2Value').textContent = data.spo2;
                    const isSpo2Ok = data.spo2 >= 95;
                    document.getElementById('spo2Status').innerHTML = `<span style="color:${isSpo2Ok ? '#16A34A' : '#DC2626'};font-weight:700;">${isSpo2Ok ? 'OPTIMAL' : 'WASPADA'}</span>`;
                }

                if (data.reed_status !== undefined) {
                    const reedVal = document.getElementById('reedValue');
                    const reedCard = document.getElementById('reedCard');
                    if (data.reed_status) {
                        reedVal.innerHTML = '⚠️ TERLEPAS DARI ANAK';
                        reedVal.style.color = '#DC2626';
                        reedCard.style.borderTopColor = '#EF4444';
                        reedCard.style.background = '#FFF1F2';
                    } else {
                        reedVal.innerHTML = '✅ TERPASANG AMAN';
                        reedVal.style.color = '#111827';
                        reedCard.style.borderTopColor = '#22C55E';
                        reedCard.style.background = '#fff';
                    }
                }

                if (data.recorded_at) {
                    document.getElementById('updatedAt').textContent = 'Terakhir update: ' + data.recorded_at;
                }

                // Alert logic
                const bpmCritical = data.bpm && (data.bpm > 140 || data.bpm < 60);
                const spo2Critical = data.spo2 && data.spo2 < 95;
                const reedCritical = data.reed_status;
                const alertBox = document.getElementById('alertBox');

                if (bpmCritical || spo2Critical || reedCritical) {
                    let msgs = [];
                    if (bpmCritical) msgs.push(`BPM ${data.bpm} (${data.bpm > 140 ? 'TERLALU TINGGI' : 'TERLALU RENDAH'})`);
                    if (spo2Critical) msgs.push(`SpO2 ${data.spo2}% (DI BAWAH NORMAL)`);
                    if (reedCritical) msgs.push('Perangkat TERLEPAS dari anak!');
                    document.getElementById('alertDesc').textContent = msgs.join(' | ') + ' — Segera hubungi pengasuh!';
                    alertBox.style.display = 'block';
                    alertBox.classList.add('alert-show');
                    // Auto scroll ke alert
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    alertBox.style.display = 'none';
                }
            })
            .catch(err => console.log('Polling error:', err));
    }

    setInterval(fetchSensorData, 3000);
    // Fetch sekali langsung saat halaman dimuat
    setTimeout(fetchSensorData, 500);

    function sendSimulatedData(devId, devCode, devToken, bpm, spo2, reed) {
        fetch('/api/v1/sensor', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                device_code: devCode,
                device_token: devToken,
                bpm: bpm,
                spo2: spo2,
                reed_status: reed
            })
        })
        .then(res => res.json())
        .then(result => {
            console.log('Simulasi sukses:', result);
            fetchSensorData();
        })
        .catch(err => alert('Gagal mengirim simulasi sensor: ' + err));
    }
</script>
@endpush
@endsection
