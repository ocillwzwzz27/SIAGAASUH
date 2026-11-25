<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\CaregiverProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = auth()->user()->caregiverProfile ?? new CaregiverProfile();
        return view('caregiver.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'kompetensi' => 'required|string',
            'pengalaman' => 'required|string',
            'tarif_harian' => 'nullable|numeric|min:0',
            'tarif_darurat' => 'nullable|numeric|min:0',
            'tarif_malam' => 'nullable|numeric|min:0',
            'foto_identitas' => 'nullable|image|max:2048',
            'foto_profil' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nik', 'kompetensi', 'pengalaman', 'tarif_harian', 'tarif_darurat', 'tarif_malam']);

        if ($request->hasFile('foto_identitas')) {
            $data['foto_identitas'] = $request->file('foto_identitas')->store('identitas', 'public');
        }
        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')->store('profil', 'public');
        }

        $profile = auth()->user()->caregiverProfile;
        $pesan = 'Profil berhasil diperbarui!';

        if ($profile) {
            if ($profile->status_verifikasi === 'ditolak') {
                $data['status_verifikasi'] = 'menunggu';
                $data['alasan_penolakan'] = null;
                $pesan = 'Data berhasil diperbaiki dan diajukan ulang untuk verifikasi admin.';
            } elseif ($profile->status_verifikasi === 'menunggu') {
                $pesan = 'Profil berhasil diperbarui! Menunggu verifikasi admin.';
            }
            $profile->update($data);
        } else {
            $data['user_id'] = auth()->id();
            $data['status_verifikasi'] = 'menunggu';
            CaregiverProfile::create($data);
            $pesan = 'Profil berhasil disimpan! Menunggu verifikasi admin.';
        }

        return back()->with('success', $pesan);
    }
}
