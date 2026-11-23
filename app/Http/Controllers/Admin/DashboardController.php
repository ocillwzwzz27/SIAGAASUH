<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CareSession;
use App\Models\CaregiverProfile;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pengguna' => User::count(),
            'total_orang_tua' => User::where('role', 'orang_tua')->count(),
            'total_pengasuh' => User::where('role', 'pengasuh')->count(),
            'pengasuh_menunggu' => CaregiverProfile::where('status_verifikasi', 'menunggu')->count(),
            'pengasuh_terverifikasi' => CaregiverProfile::where('status_verifikasi', 'terverifikasi')->count(),
            'sesi_aktif' => CareSession::where('status', 'berjalan')->count(),
            'total_sesi' => CareSession::count(),
        ];

        $pengasuh_menunggu = CaregiverProfile::with('user')
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        $sesi_terbaru = CareSession::with(['parent', 'caregiver', 'child'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pengasuh_menunggu', 'sesi_terbaru'));
    }
}
