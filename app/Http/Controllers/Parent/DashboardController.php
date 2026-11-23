<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\CareSession;
use App\Models\AppNotification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $sesi_aktif = CareSession::with(['caregiver', 'child', 'latestReading'])
            ->where('parent_id', $user->id)
            ->where('status', 'berjalan')
            ->first();

        $sesi_menunggu = CareSession::where('parent_id', $user->id)
            ->where('status', 'menunggu')
            ->count();

        $total_sesi = CareSession::where('parent_id', $user->id)->count();

        $notifikasi = AppNotification::where('user_id', $user->id)
            ->where('sudah_dibaca', false)
            ->latest()
            ->take(5)
            ->get();

        $riwayat_sesi = CareSession::with(['caregiver', 'child'])
            ->where('parent_id', $user->id)
            ->whereIn('status', ['selesai', 'ditolak', 'dibatalkan'])
            ->latest()
            ->take(5)
            ->get();

        return view('parent.dashboard', compact(
            'sesi_aktif', 'sesi_menunggu', 'total_sesi', 'notifikasi', 'riwayat_sesi'
        ));
    }
}
