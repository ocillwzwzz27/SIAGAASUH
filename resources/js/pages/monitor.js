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

        if (hasValue(data.spo2)) {
            const ok = data.spo2 >= SPO2_MIN;
            el.spo2Value.textContent = data.spo2;
            setStatus(el.spo2Status, ok ? 'OPTIMAL' : 'WASPADA', ok ? 'is-ok' : 'is-bad');
        }

        if (data.reed_status !== undefined) {
            el.reedValue.textContent = data.reed_status ? '⚠️ TERLEPAS DARI ANAK' : '✅ TERPASANG AMAN';
            el.reedValue.classList.toggle('is-alert', Boolean(data.reed_status));
            el.reedCard.classList.toggle('is-alert', Boolean(data.reed_status));
        }

        if (data.recorded_at) {
            el.updatedAt.textContent = `Terakhir update: ${data.recorded_at}`;
        }

        renderAlert(data);
    };

    const renderAlert = (data) => {
        const bpmCritical = data.bpm && (data.bpm > BPM_MAX || data.bpm < BPM_MIN);
        const spo2Critical = data.spo2 && data.spo2 < SPO2_MIN;
        const reedCritical = data.reed_status;

        if (!bpmCritical && !spo2Critical && !reedCritical) {
            el.alertBox.classList.remove('is-visible');
            return;
        }

        const messages = [];
        if (bpmCritical) messages.push(`BPM ${data.bpm} (${data.bpm > BPM_MAX ? 'TERLALU TINGGI' : 'TERLALU RENDAH'})`);
        if (spo2Critical) messages.push(`SpO2 ${data.spo2}% (DI BAWAH NORMAL)`);
        if (reedCritical) messages.push('Perangkat TERLEPAS dari anak!');

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
