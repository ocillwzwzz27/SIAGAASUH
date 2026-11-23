<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CaregiverProfile;
use App\Models\AppNotification;
use Illuminate\Http\Request;

class CaregiverVerificationController extends Controller
{
    public function index()
    {
        $pengasuh = CaregiverProfile::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.caregiver.index', compact('pengasuh'));
    }

    public function show($id)
    {
        $profile = CaregiverProfile::with('user')->findOrFail($id);
        return view('admin.caregiver.show', compact('profile'));
    }

    public function verify(Request $request, $id)
    {
        $profile = CaregiverProfile::with('user')->findOrFail($id);

        $request->validate([
            'action' => 'required|in:terverifikasi,ditolak',
            'alasan_penolakan' => 'required_if:action,ditolak|nullable|string',
        ]);

        $profile->update([
            'status_verifikasi' => $request->action,
            'alasan_penolakan' => $request->action === 'ditolak' ? $request->alasan_penolakan : null,
            'verified_by' => auth()->id(),
            'verified_at' => $request->action === 'terverifikasi' ? now() : null,
        ]);

        // Kirim notifikasi ke pengasuh
        $judul = $request->action === 'terverifikasi'
            ? '🎉 Profil Anda Telah Terverifikasi!'
            : '⚠️ Verifikasi Profil Ditolak';

        $pesan = $request->action === 'terverifikasi'
            ? 'Selamat! Profil Anda telah diverifikasi. Anda sekarang bisa menerima permintaan pengasuhan.'
            : 'Maaf, profil Anda ditolak. Alasan: ' . $request->alasan_penolakan . '. Silakan perbaiki dan kirim ulang.';

        AppNotification::kirim($profile->user_id, $judul, $pesan, 'sistem');

        $msg = $request->action === 'terverifikasi' ? 'Pengasuh berhasil diverifikasi!' : 'Pengasuh telah ditolak.';
        return redirect()->route('admin.caregiver.index')->with('success', $msg);
    }
}
