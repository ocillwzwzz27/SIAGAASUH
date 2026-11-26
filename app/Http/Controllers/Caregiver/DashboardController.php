<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\CareSession;
use App\Models\AppNotification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = $user->caregiverProfile;

        $sesi_menunggu = CareSession::where('caregiver_id', $user->id)
            ->where('status', 'menunggu')
            ->with('parent', 'child')
            ->get();

        $sesi_aktif = CareSession::where('caregiver_id', $user->id)
            ->where('status', 'berjalan')
            ->with(['parent', 'child', 'checkins', 'latestCheckin'])
            ->first();

        $total_sesi_selesai = CareSession::where('caregiver_id', $user->id)
            ->where('status', 'selesai')
            ->count();

        $notifikasi = AppNotification::where('user_id', $user->id)
            ->where('sudah_dibaca', false)
            ->latest()
            ->take(5)
            ->get();

        return view('caregiver.dashboard', compact(
            'profile', 'sesi_menunggu', 'sesi_aktif', 'total_sesi_selesai', 'notifikasi'
        ));
    }
}
