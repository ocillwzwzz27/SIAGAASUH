/**
 * Live monitor sensor wearable (halaman orang tua).
 * Data dari server dikirim lewat atribut data-* pada #monitor.
 */
const POLL_INTERVAL_MS = 3000;
const BPM_MAX = 140;
const BPM_MIN = 60;
const SPO2_MIN = 95;

const root = document.getElementById('monitor');

if (root) {
    const { sensorUrl, simulateUrl } = root.dataset;

    const el = {
        bpmValue: document.getElementById('bpmValue'),
        bpmStatus: document.getElementById('bpmStatus'),
        spo2Value: document.getElementById('spo2Value'),
        spo2Status: document.getElementById('spo2Status'),
        reedCard: document.getElementById('reedCard'),
        reedValue: document.getElementById('reedValue'),
        updatedAt: document.getElementById('updatedAt'),
        alertBox: document.getElementById('alertBox'),
        alertDesc: document.getElementById('alertDesc'),
        alertClose: document.getElementById('alertClose'),
        locationMapWrap: document.getElementById('locationMapWrap'),
        locationEmptyBox: document.getElementById('locationEmptyBox'),
        locationMapFrame: document.getElementById('locationMapFrame'),
        locationCoords: document.getElementById('locationCoords'),
        locationNameText: document.getElementById('locationNameText'),
        locationStatusBadge: document.getElementById('locationStatusBadge'),
        locationGmapsBtn: document.getElementById('locationGmapsBtn'),
        checkinList: document.getElementById('checkinList'),
        checkinEmpty: document.getElementById('checkinEmpty'),
        checkinStatusBadge: document.getElementById('checkinStatusBadge'),
    };

    const hasValue = (v) => v !== undefined && v !== null;

    const setStatus = (target, text, level) => {
        const span = document.createElement('span');
        span.className = level;
        span.textContent = text;
        target.replaceChildren(span);
    };

    const render = (data) => {
        if (hasValue(data.bpm)) {
            const critical = data.bpm > BPM_MAX || data.bpm < BPM_MIN;
            el.bpmValue.textContent = data.bpm;
            setStatus(
                el.bpmStatus,
                (data.status_bpm || (critical ? 'KRITIS' : 'NORMAL')).toUpperCase(),
                critical ? 'is-bad' : 'is-ok',
            );
        }

        if (hasValue(data.spo2) && el.spo2Value && el.spo2Status) {
            const ok = data.spo2 >= SPO2_MIN;
            el.spo2Value.textContent = data.spo2;
            setStatus(el.spo2Status, ok ? 'OPTIMAL' : 'WASPADA', ok ? 'is-ok' : 'is-bad');
        }

        if (data.reed_status !== undefined && el.reedValue && el.reedCard) {
            el.reedValue.textContent = data.reed_status ? '🚨 PINTU TERBUKA' : '🚪 PINTU AMAN';
            el.reedValue.classList.toggle('is-alert', Boolean(data.reed_status));
            el.reedCard.classList.toggle('is-alert', Boolean(data.reed_status));
        }

        if (data.recorded_at) {
            el.updatedAt.textContent = `Terakhir update: ${data.recorded_at}`;
        }

        if (hasValue(data.latitude) && hasValue(data.longitude)) {
            const lat = Number(data.latitude);
            const lng = Number(data.longitude);
            const embedUrl = `https://www.openstreetmap.org/export/embed.html?bbox=${lng - 0.004},${lat - 0.004},${lng + 0.004},${lat + 0.004}&layer=mapnik&marker=${lat},${lng}`;

            if (el.locationMapFrame && el.locationMapFrame.dataset.coords !== `${lat},${lng}`) {
                el.locationMapFrame.src = embedUrl;
                el.locationMapFrame.dataset.coords = `${lat},${lng}`;
            }
            el.locationMapWrap?.classList.remove('hidden');
            el.locationEmptyBox?.classList.add('hidden');
            if (el.locationCoords) el.locationCoords.textContent = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
            if (el.locationNameText) el.locationNameText.textContent = data.location_name || 'Titik lokasi perangkat aktif';
            if (el.locationStatusBadge) {
                el.locationStatusBadge.textContent = 'GPS Aktif';
                el.locationStatusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700';
            }
            if (el.locationGmapsBtn) {
                el.locationGmapsBtn.href = `https://www.google.com/maps?q=${lat},${lng}`;
                el.locationGmapsBtn.classList.remove('pointer-events-none', 'opacity-50');
            }
        }

        if (data.latest_checkin && el.checkinList) {
            const ci = data.latest_checkin;
            if (!el.checkinList.querySelector(`[data-checkin-id="${ci.id}"]`)) {
                el.checkinEmpty?.remove();
                const card = document.createElement('div');
                card.className = 'p-3 rounded-2xl bg-purple-50/40 border border-purple-100 space-y-2';
                card.dataset.checkinId = ci.id;
                card.innerHTML = `
                    <div class="flex items-center justify-between text-[11px] text-gray-500">
                        <span class="font-bold text-siaga"><i class="fas fa-clock mr-1"></i>Pukul ${ci.checked_in_at_time} WIB</span>
                        <span>${ci.checked_in_at_human}</span>
                    </div>
                    <a href="${ci.foto_url}" target="_blank" class="block relative rounded-xl overflow-hidden border border-purple-100">
                        <img src="${ci.foto_url}" alt="Kondisi Anak" class="w-full h-44 object-cover">
                        <span class="absolute bottom-2 right-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded-lg">
                            <i class="fas fa-expand-alt mr-1"></i>Perbesar
                        </span>
                    </a>
                `;
                if (ci.catatan) {
                    const note = document.createElement('p');
                    note.className = 'text-xs text-gray-700 bg-white p-2.5 rounded-xl border border-purple-50';
                    note.textContent = ci.catatan;
                    card.appendChild(note);
                }
                el.checkinList.prepend(card);
            }
            if (el.checkinStatusBadge) {
                el.checkinStatusBadge.textContent = `Terupdate ${ci.checked_in_at_human}`;
                el.checkinStatusBadge.className = 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-green-100 text-green-700';
            }
        }

        renderAlert(data);
    };

    const renderAlert = (data) => {
        const bpmCritical = data.bpm && (data.bpm > BPM_MAX || data.bpm < BPM_MIN);
        const reedCritical = data.reed_status;

        if (!bpmCritical && !reedCritical) {
            el.alertBox.classList.remove('is-visible');
            return;
        }

        const messages = [];
        if (bpmCritical) messages.push(`BPM ${data.bpm} (${data.bpm > BPM_MAX ? 'TERLALU TINGGI' : 'TERLALU RENDAH'})`);
        if (reedCritical) messages.push('Sensor pintu terdeteksi TERBUKA / TERLEWATI!');

        el.alertDesc.textContent = `${messages.join(' | ')} — Segera hubungi pengasuh!`;
        el.alertBox.classList.add('is-visible');
        el.alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    const fetchSensorData = () =>
        fetch(sensorUrl, { headers: { Accept: 'application/json' } })
            .then((res) => res.json())
            .then(render)
            .catch((err) => console.log('Polling error:', err));

    el.alertClose?.addEventListener('click', () => el.alertBox.classList.remove('is-visible'));

    // --- Simulator IoT: tombol [data-sim] ---
    document.querySelectorAll('[data-sim]').forEach((button) => {
        button.addEventListener('click', () => {
            const d = button.dataset;

            fetch(simulateUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify({
                    device_code: d.deviceCode,
                    device_token: d.deviceToken,
                    bpm: Number(d.bpm),
                    spo2: Number(d.spo2),
                    reed_status: d.reed === 'true',
                    latitude: d.lat ? Number(d.lat) : null,
                    longitude: d.lng ? Number(d.lng) : null,
                    location_name: d.loc || null,
                }),
            })
                .then((res) => res.json())
                .then((result) => {
                    console.log('Simulasi sukses:', result);
                    fetchSensorData();
                })
                .catch((err) => alert(`Gagal mengirim simulasi sensor: ${err}`));
        });
    });

    setInterval(fetchSensorData, POLL_INTERVAL_MS);
    setTimeout(fetchSensorData, 500);
}
