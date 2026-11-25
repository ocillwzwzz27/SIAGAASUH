<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'tarif_harian' => 'required_if:action,terverifikasi|nullable|numeric|min:0',
            'tarif_darurat' => 'required_if:action,terverifikasi|nullable|numeric|min:0',
            'tarif_malam' => 'required_if:action,terverifikasi|nullable|numeric|min:0',
        ]);

        $updateData = [
            'status_verifikasi' => $request->action,
            'alasan_penolakan' => $request->action === 'ditolak' ? $request->alasan_penolakan : null,
            'verified_by' => auth()->id(),
            'verified_at' => $request->action === 'terverifikasi' ? now() : null,
        ];

        if ($request->action === 'terverifikasi') {
            $updateData['tarif_harian'] = $request->tarif_harian ?? 150000;
            $updateData['tarif_darurat'] = $request->tarif_darurat ?? 200000;
            $updateData['tarif_malam'] = $request->tarif_malam ?? 180000;
        }

        $profile->update($updateData);

        // Kirim notifikasi ke pengasuh
        $judul = $request->action === 'terverifikasi'
            ? '🎉 Profil Anda Telah Terverifikasi!'
            : '⚠️ Verifikasi Profil Ditolak';

        $pesan = $request->action === 'terverifikasi'
            ? 'Selamat! Berkas Anda telah disetujui Admin PT SiagaAsuh. Anda sekarang resmi aktif dan dapat menerima pesanan orang tua.'
            : 'Maaf, pendaftaran Anda ditolak. Alasan: ' . $request->alasan_penolakan . '. Silakan perbaiki berkas melalui menu Profil & Dokumen.';

        AppNotification::kirim($profile->user_id, $judul, $pesan, 'sistem');

        $msg = $request->action === 'terverifikasi' ? 'Mitra pengasuh berhasil diverifikasi & tarif PT ditetapkan!' : 'Pendaftaran pengasuh telah ditolak.';
        return redirect()->route('admin.caregiver.index')->with('success', $msg);
    }
}
