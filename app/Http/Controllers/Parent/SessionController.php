<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\CareSession;
use App\Models\Child;
use App\Models\User;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function create(Request $request)
    {
        $caregiverId = $request->caregiver_id;
        $caregiver = User::with('caregiverProfile')
            ->where('role', 'pengasuh')
            ->whereHas('caregiverProfile', fn ($q) => $q->where('status_verifikasi', 'terverifikasi'))
            ->findOrFail($caregiverId);

        $children = auth()->user()->children()->with('devices')->get();

        return view('parent.sessions.create', compact('caregiver', 'children'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'caregiver_id' => 'required|exists:users,id',
            'child_id' => 'required|exists:children,id',
            'jenis_layanan' => 'required|in:harian,darurat,malam',
            'catatan_orang_tua' => 'nullable|string|max:500',
        ]);

        // Pastikan pengasuh valid & sudah terverifikasi
        $caregiver = User::where('role', 'pengasuh')
            ->whereHas('caregiverProfile', fn ($q) => $q->where('status_verifikasi', 'terverifikasi'))
            ->find($request->caregiver_id);

        if (!$caregiver) {
            return back()->with('error', 'Pengasuh yang dipilih belum terverifikasi atau tidak tersedia.');
        }

        $child = Child::where('parent_id', auth()->id())->findOrFail($request->child_id);

        // Cek sesi aktif
        $sesiAktif = CareSession::where('parent_id', auth()->id())
            ->whereIn('status', ['menunggu', 'berjalan'])
            ->exists();

        if ($sesiAktif) {
            return back()->with('error', 'Anda masih memiliki sesi yang aktif atau menunggu konfirmasi.');
        }

        $session = CareSession::create([
            'parent_id' => auth()->id(),
            'caregiver_id' => $caregiver->id,
            'child_id' => $child->id,
            'jenis_layanan' => $request->jenis_layanan,
            'catatan_orang_tua' => $request->catatan_orang_tua,
            'status' => 'menunggu',
        ]);

        // Notifikasi ke pengasuh
        AppNotification::kirim(
            $caregiver->id,
            '📋 Permintaan Sesi Pengasuhan Baru',
            auth()->user()->name . ' meminta Anda untuk mengasuh ' . $child->nama . '. Silakan konfirmasi.',
            'layanan',
            $session->id
        );

        return redirect()->route('parent.sessions.show', $session->id)
            ->with('success', 'Permintaan sesi berhasil dikirim! Menunggu konfirmasi pengasuh.');
    }

    public function show($id)
    {
        $session = CareSession::with([
            'caregiver.caregiverProfile', 'child',
            'sensorReadings' => fn ($q) => $q->latest()->take(20),
        ])->where('parent_id', auth()->id())->findOrFail($id);

        return view('parent.sessions.show', compact('session'));
    }

    public function index()
    {
        $sessions = CareSession::with(['caregiver', 'child'])
            ->where('parent_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('parent.sessions.index', compact('sessions'));
    }

    public function cancel($id)
    {
        $session = CareSession::where('parent_id', auth()->id())
            ->where('status', 'menunggu')
            ->findOrFail($id);

        $session->update(['status' => 'dibatalkan']);

        AppNotification::kirim(
            $session->caregiver_id,
            'Sesi Dibatalkan',
            auth()->user()->name . ' membatalkan permintaan sesi pengasuhan.',
            'layanan',
            $session->id
        );

        return back()->with('success', 'Sesi berhasil dibatalkan.');
    }

    public function monitor($id)
    {
        $session = CareSession::with(['child.devices', 'caregiver.caregiverProfile', 'latestReading'])
            ->where('parent_id', auth()->id())
            ->where('status', 'berjalan')
            ->findOrFail($id);

        return view('parent.sessions.monitor', compact('session'));
    }

    // API untuk polling data sensor real-time
    public function latestSensor($id)
    {
        $session = CareSession::where('parent_id', auth()->id())->findOrFail($id);
        $reading = $session->latestReading;

        return response()->json([
            'bpm' => $reading?->bpm,
            'spo2' => $reading?->spo2,
            'reed_status' => $reading?->reed_status,
            'recorded_at' => $reading?->recorded_at?->diffForHumans(),
            'status_bpm' => $reading?->status_bpm,
        ]);
    }
}
