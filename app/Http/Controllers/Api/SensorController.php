<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\SensorReading;
use App\Models\CareSession;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'device_code' => 'required|string',
            'device_token' => 'required|string',
            'bpm' => 'required|integer|min:0|max:300',
            'spo2' => 'nullable|integer|min:0|max:100',
            'reed_status' => 'required|boolean',
        ]);

        // Validasi device
        $device = Device::where('device_code', $request->device_code)
            ->where('device_token', $request->device_token)
            ->where('aktif', true)
            ->first();

        if (!$device) {
            return response()->json(['error' => 'Perangkat tidak valid atau tidak aktif.'], 401);
        }

        // Cari sesi aktif untuk anak ini
        $session = CareSession::where('child_id', $device->child_id)
            ->where('status', 'berjalan')
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Tidak ada sesi aktif untuk anak ini.'], 404);
        }

        // Simpan data sensor
        $reading = SensorReading::create([
            'session_id' => $session->id,
            'device_id' => $device->id,
            'bpm' => $request->bpm,
            'spo2' => $request->spo2,
            'reed_status' => $request->reed_status,
            'recorded_at' => now(),
        ]);

        // Cek ambang batas & kirim notifikasi
        if ($reading->isCritical()) {
            $pesan = $this->buildAlertMessage($reading);

            AppNotification::kirim(
                $session->parent_id,
                '🚨 PERINGATAN Kondisi Anak!',
                $pesan,
                'sensor_alert',
                $session->id
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Data sensor berhasil disimpan.',
            'is_critical' => $reading->isCritical(),
        ]);
    }

    private function buildAlertMessage(SensorReading $reading): string
    {
        $alerts = [];

        if ($reading->bpm < 60) $alerts[] = "BPM rendah ({$reading->bpm} bpm)";
        if ($reading->bpm > 140) $alerts[] = "BPM tinggi ({$reading->bpm} bpm)";
        if ($reading->spo2 !== null && $reading->spo2 < 95) $alerts[] = "SpO2 rendah ({$reading->spo2}%)";
        if ($reading->reed_status) $alerts[] = "Perangkat dilepas dari anak!";

        return 'Terdeteksi kondisi tidak normal: ' . implode(', ', $alerts) . '. Segera periksa kondisi anak Anda.';
    }

    public function getActiveSession(Request $request)
    {
        $device = Device::where('device_code', $request->device_code)
            ->where('device_token', $request->device_token)
            ->where('aktif', true)
            ->first();

        if (!$device) {
            return response()->json(['error' => 'Perangkat tidak valid.'], 401);
        }

        $session = CareSession::where('child_id', $device->child_id)
            ->where('status', 'berjalan')
            ->first();

        return response()->json([
            'active' => $session !== null,
            'session_id' => $session?->id,
        ]);
    }
}
