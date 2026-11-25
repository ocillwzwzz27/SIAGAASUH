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
        $profile = auth()->user()->caregiverProfile;

        $request->validate([
            'nik' => 'required|digits:16',
            'alamat_domisili' => 'required|string|max:500',
            'pendidikan_terakhir' => 'required|string|max:100',
            'kontak_darurat' => 'required|string|max:150',
            'kompetensi' => 'required|string|max:1000',
            'pengalaman' => 'required|string|min:20|max:2000',
            'foto_identitas' => ($profile && $profile->foto_identitas ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'dokumen_skck' => ($profile && $profile->dokumen_skck ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'dokumen_surat_sehat' => ($profile && $profile->dokumen_surat_sehat ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'dokumen_surat_pengalaman' => ($profile && $profile->dokumen_surat_pengalaman ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        $data = $request->only([
            'nik',
            'alamat_domisili',
            'pendidikan_terakhir',
            'kontak_darurat',
            'kompetensi',
            'pengalaman',
        ]);

        if ($request->hasFile('foto_identitas')) {
            $data['foto_identitas'] = $request->file('foto_identitas')->store('identitas', 'public');
        }
        if ($request->hasFile('dokumen_skck')) {
            $data['dokumen_skck'] = $request->file('dokumen_skck')->store('skck', 'public');
        }
        if ($request->hasFile('dokumen_surat_sehat')) {
            $data['dokumen_surat_sehat'] = $request->file('dokumen_surat_sehat')->store('surat-sehat', 'public');
        }
        if ($request->hasFile('dokumen_surat_pengalaman')) {
            $data['dokumen_surat_pengalaman'] = $request->file('dokumen_surat_pengalaman')->store('surat-pengalaman', 'public');
        }

        $pesan = 'Profil & berkas berhasil diperbarui!';

        if ($profile) {
            if ($profile->status_verifikasi === 'ditolak') {
                $data['status_verifikasi'] = 'menunggu';
                $data['alasan_penolakan'] = null;
                $pesan = 'Berkas berhasil diperbaiki dan diajukan ulang untuk verifikasi admin PT.';
            } elseif ($profile->status_verifikasi === 'menunggu') {
                $pesan = 'Profil & berkas berhasil diperbarui! Menunggu verifikasi admin PT.';
            }
            $profile->update($data);
        } else {
            $data['user_id'] = auth()->id();
            $data['status_verifikasi'] = 'menunggu';
            CaregiverProfile::create($data);
            $pesan = 'Profil & berkas berhasil disimpan! Menunggu verifikasi admin PT.';
        }

        return back()->with('success', $pesan);
    }
}
