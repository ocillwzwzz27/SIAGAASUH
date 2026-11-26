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
        $caregiver = User::with('caregiverProfile')
            ->where('role', 'pengasuh')
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

        $profile = $caregiver->caregiverProfile;
        $totalBiaya = match ($request->jenis_layanan) {
            'darurat' => $profile?->tarif_darurat ?? 200000,
            'malam' => $profile?->tarif_malam ?? 180000,
            default => $profile?->tarif_harian ?? 150000,
        };

        $session = CareSession::create([
            'parent_id' => auth()->id(),
            'caregiver_id' => $caregiver->id,
            'child_id' => $child->id,
            'jenis_layanan' => $request->jenis_layanan,
            'catatan_orang_tua' => $request->catatan_orang_tua,
            'status' => 'menunggu',
            'total_biaya' => $totalBiaya,
            'status_pembayaran' => 'belum_bayar',
        ]);

        $session->update(['order_id' => 'SGA-' . $session->id . '-' . time()]);

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
            'caregiver.caregiverProfile', 'child', 'checkins',
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

    public function rate(Request $request, $id)
    {
        $session = CareSession::with('caregiver.caregiverProfile')
            ->where('parent_id', auth()->id())
            ->where('status', 'selesai')
            ->findOrFail($id);

        if ($session->rating !== null) {
            return back()->with('error', 'Anda sudah memberikan penilaian untuk sesi ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:500',
        ]);

        $session->update([
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
        ]);

        // Hitung ulang rata-rata rating & total ulasan pada profil mitra pengasuh
        $stats = CareSession::where('caregiver_id', $session->caregiver_id)
            ->whereNotNull('rating')
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
            ->first();

        if ($session->caregiver?->caregiverProfile) {
            $session->caregiver->caregiverProfile->update([
                'rating' => round((float) ($stats->avg_rating ?? $request->rating), 2),
                'total_ulasan' => (int) ($stats->total ?? 1),
            ]);
        }

        AppNotification::kirim(
            $session->caregiver_id,
            '⭐ Penilaian Baru dari Orang Tua',
            auth()->user()->name . " memberi rating {$request->rating}/5 untuk sesi pengasuhan." . ($request->ulasan ? ' Ulasan: "' . $request->ulasan . '"' : ''),
            'layanan',
            $session->id
        );

        return back()->with('success', 'Terima kasih! Rating dan ulasan Anda berhasil disimpan.');
    }

    public function monitor($id)
    {
        $session = CareSession::with(['child.devices', 'caregiver.caregiverProfile', 'latestReading', 'checkins', 'latestCheckin'])
            ->where('parent_id', auth()->id())
            ->where('status', 'berjalan')
            ->findOrFail($id);

        return view('parent.sessions.monitor', compact('session'));
    }

    // API untuk polling data sensor real-time
    public function latestSensor($id)
    {
        $session = CareSession::with(['latestReading', 'latestCheckin'])
            ->where('parent_id', auth()->id())
            ->findOrFail($id);
        $reading = $session->latestReading;
        $checkin = $session->latestCheckin;

        return response()->json([
            'bpm' => $reading?->bpm,
            'spo2' => $reading?->spo2,
            'reed_status' => $reading?->reed_status,
            'latitude' => $reading?->latitude,
            'longitude' => $reading?->longitude,
            'location_name' => $reading?->location_name,
            'recorded_at' => $reading?->recorded_at?->diffForHumans(),
            'status_bpm' => $reading?->status_bpm,
            'checkin_count' => $session->checkins()->count(),
            'latest_checkin' => $checkin ? [
                'id' => $checkin->id,
                'foto_url' => asset('storage/' . $checkin->foto_path),
                'catatan' => $checkin->catatan,
                'checked_in_at_time' => $checkin->checked_in_at->format('H:i'),
                'checked_in_at_human' => $checkin->checked_in_at->diffForHumans(),
            ] : null,
        ]);
    }
}
