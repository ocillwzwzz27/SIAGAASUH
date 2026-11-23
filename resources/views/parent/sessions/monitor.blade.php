@extends('layouts.parent')

@section('title', 'Live Monitor Kondisi Anak')
@section('page-title', 'Live Monitor IoT: ' . $session->child->nama)
@section('page-subtitle', 'Pemantauan detak jantung, kadar oksigen, dan sensor pengaman secara langsung')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header Status Bar --}}
    <div class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <span class="w-3.5 h-3.5 bg-green-500 rounded-full animate-ping"></span>
            <div>
                <h3 class="font-bold text-gray-800 text-sm">Transmisi Real-Time Aktif</h3>
                <p class="text-xs text-gray-400">Pengasuh: {{ $session->caregiver->name }} ({{ $session->caregiver->phone }})</p>
            </div>
        </div>
        <a href="{{ route('parent.sessions.show', $session->id) }}" class="btn-outline text-xs py-1.5 px-3">
            Detail Sesi
        </a>
    </div>

    {{-- Kotak Peringatan Bahaya (Muncul otomatis jika melewati ambang batas) --}}
    <div id="alertBox" class="hidden p-4 rounded-2xl bg-red-100 border-2 border-red-400 text-red-800 animate-bounce">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
            <div>
                <h4 class="font-bold text-sm" id="alertTitle">PERINGATAN SENSOR!</h4>
                <p class="text-xs mt-0.5" id="alertDesc">Data sensor mendeteksi kondisi di luar batas normal.</p>
            </div>
        </div>
    </div>

    {{-- Sensor Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- 1. Heart Rate / BPM --}}
        <div class="card p-6 text-center border-t-4 border-red-500 relative overflow-hidden">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full mx-auto flex items-center justify-center text-3xl mb-3">
                <i class="fas fa-heartbeat animate-pulse" id="heartIcon"></i>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Detak Jantung (BPM)</p>
            <div class="text-5xl font-extrabold text-gray-800 my-2" id="bpmValue">
                {{ $session->latestReading->bpm ?? '--' }}
            </div>
            <p class="text-xs font-semibold" id="bpmStatus">
                @if($session->latestReading)
                    <span class="{{ $session->latestReading->bpm > 140 || $session->latestReading->bpm < 60 ? 'text-red-600' : 'text-green-600' }}">
                        {{ strtoupper($session->latestReading->status_bpm) }}
                    </span>
                @else
                    <span class="text-gray-400">Menunggu sinyal...</span>
                @endif
            </p>
            <span class="text-[10px] text-gray-400 block mt-2">Batas Normal: 60 - 140 BPM</span>
        </div>

        {{-- 2. SpO2 Oxygen --}}
        <div class="card p-6 text-center border-t-4 border-blue-500">
            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full mx-auto flex items-center justify-center text-3xl mb-3">
                <i class="fas fa-lungs"></i>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kadar Oksigen (SpO2)</p>
            <div class="text-5xl font-extrabold text-blue-600 my-2" id="spo2Value">
                {{ $session->latestReading->spo2 ?? '--' }}<span class="text-2xl font-normal text-gray-400">%</span>
            </div>
            <p class="text-xs font-semibold text-green-600" id="spo2Status">
                @if($session->latestReading && $session->latestReading->spo2)
                    {{ $session->latestReading->spo2 >= 95 ? 'OPTIMAL' : 'WASPADA' }}
                @else
                    <span class="text-gray-400">Menunggu data...</span>
                @endif
            </p>
            <span class="text-[10px] text-gray-400 block mt-2">Batas Normal: ≥ 95%</span>
        </div>

        {{-- 3. Reed Switch / Security Sensor --}}
        <div class="card p-6 text-center border-t-4 border-green-500" id="reedCard">
            <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center text-3xl mb-3" id="reedIconBg">
                <i class="fas fa-fingerprint text-green-600" id="reedIcon"></i>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Perangkat</p>
            <div class="text-xl font-extrabold text-gray-800 my-4" id="reedValue">
                @if($session->latestReading)
                    {{ $session->latestReading->reed_status ? 'TERLEPAS DARI ANAK' : 'TERPASANG AMAN' }}
                @else
                    TERPASANG AMAN
                @endif
            </div>
            <p class="text-xs text-gray-500" id="reedSub">
                Sensor magnetik wearable
            </p>
            <span class="text-[10px] text-gray-400 block mt-2" id="updatedAt">
                Terakhir update: {{ $session->latestReading ? $session->latestReading->recorded_at->diffForHumans() : 'baru saja' }}
            </span>
        </div>

    </div>

    {{-- Simulator IoT Box untuk Pengujian --}}
    <div class="card bg-purple-50/60 border border-purple-200 p-5">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <i class="fas fa-microchip text-siaga"></i>
                <h4 class="font-bold text-sm text-purple-950">🧪 Alat Uji Sensor Wearable (Simulasi Kirim Data IoT)</h4>
            </div>
            <span class="text-xs text-purple-700 font-mono">POST /api/v1/sensor</span>
        </div>
        <p class="text-xs text-purple-800 mb-4">
            Kamu bisa klik tombol di bawah untuk menyimulasikan data sensor yang dikirim oleh mikrokontroler wearable anak ke server:
        </p>

        @php
            $dev = $session->child->activeDevice;
        @endphp

        @if($dev)
            <div class="flex flex-wrap gap-2.5">
                <button type="button" onclick="sendSimulatedData({{ $dev->id }}, '{{ $dev->device_code }}', '{{ $dev->device_token }}', 85, 98, false)" class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-2 px-3.5 rounded-xl shadow-sm transition">
                    ❤️ Kirim Normal (85 BPM, 98% SpO2)
                </button>
                <button type="button" onclick="sendSimulatedData({{ $dev->id }}, '{{ $dev->device_code }}', '{{ $dev->device_token }}', 152, 97, false)" class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold py-2 px-3.5 rounded-xl shadow-sm transition">
                    🚨 Kirim BPM Tinggi (152 BPM)
                </button>
                <button type="button" onclick="sendSimulatedData({{ $dev->id }}, '{{ $dev->device_code }}', '{{ $dev->device_token }}', 80, 91, false)" class="bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold py-2 px-3.5 rounded-xl shadow-sm transition">
                    ⚠️ Kirim SpO2 Rendah (91%)
                </button>
                <button type="button" onclick="sendSimulatedData({{ $dev->id }}, '{{ $dev->device_code }}', '{{ $dev->device_token }}', 90, 98, true)" class="bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold py-2 px-3.5 rounded-xl shadow-sm transition">
                    🧲 Kirim Status Wearable Dilepas
                </button>
            </div>
        @else
            <p class="text-xs text-red-600 font-semibold">Anak ini belum memiliki perangkat wearable yang terdaftar.</p>
        @endif
    </div>

</div>

@push('scripts')
<script>
    // Auto-polling sensor setiap 3 detik
    const sessionId = {{ $session->id }};
    const sensorUrl = "{{ route('parent.sessions.sensor', $session->id) }}";

    function fetchSensorData() {
        fetch(sensorUrl)
            .then(res => res.json())
            .then(data => {
                if (data.bpm) {
                    document.getElementById('bpmValue').innerText = data.bpm;
                    const bpmStatus = document.getElementById('bpmStatus');
                    bpmStatus.innerText = data.status_bpm.toUpperCase();
                    bpmStatus.className = 'text-xs font-semibold ' + (data.bpm > 140 || data.bpm < 60 ? 'text-red-600' : 'text-green-600');
                }
                if (data.spo2) {
                    document.getElementById('spo2Value').innerHTML = data.spo2 + '<span class="text-2xl font-normal text-gray-400">%</span>';
                }
                if (data.reed_status !== undefined) {
                    const reedVal = document.getElementById('reedValue');
                    const reedCard = document.getElementById('reedCard');
                    if (data.reed_status) {
                        reedVal.innerText = 'TERLEPAS DARI ANAK';
                        reedVal.className = 'text-xl font-extrabold text-red-600 my-4';
                        reedCard.className = 'card p-6 text-center border-t-4 border-red-500 bg-red-50/50';
                    } else {
                        reedVal.innerText = 'TERPASANG AMAN';
                        reedVal.className = 'text-xl font-extrabold text-gray-800 my-4';
                        reedCard.className = 'card p-6 text-center border-t-4 border-green-500';
                    }
                }
                if (data.recorded_at) {
                    document.getElementById('updatedAt').innerText = 'Terakhir update: ' + data.recorded_at;
                }

                // Cek alert
                const alertBox = document.getElementById('alertBox');
                if ((data.bpm && (data.bpm > 140 || data.bpm < 60)) || (data.spo2 && data.spo2 < 95) || data.reed_status) {
                    alertBox.classList.remove('hidden');
                } else {
                    alertBox.classList.add('hidden');
                }
            })
            .catch(err => console.log('Polling err:', err));
    }

    setInterval(fetchSensorData, 3000);

    // Fungsi kirim data simulasi IoT via API
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
