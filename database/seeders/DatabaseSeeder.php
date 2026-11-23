<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CaregiverProfile;
use App\Models\Child;
use App\Models\Device;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@siagaasuh.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Orang Tua Demo
        $parent = User::create([
            'name' => 'Budi Santoso',
            'email' => 'orangtua@siagaasuh.com',
            'password' => Hash::make('password'),
            'role' => 'orang_tua',
            'phone' => '081234567891',
        ]);

        // Anak Demo
        $child = Child::create([
            'parent_id' => $parent->id,
            'nama' => 'Budi Jr',
            'tanggal_lahir' => '2020-05-15',
            'jenis_kelamin' => 'laki-laki',
            'catatan_kesehatan' => 'Sehat, tidak ada alergi',
        ]);

        // Device Demo
        Device::create([
            'child_id' => $child->id,
            'device_code' => 'WEARABLE-001',
            'device_token' => Str::random(60),
            'aktif' => true,
            'nama_perangkat' => 'Smartband Budi Jr',
        ]);

        // Pengasuh Demo (Terverifikasi)
        $caregiver1 = User::create([
            'name' => 'Trisna Putri',
            'email' => 'trisna@siagaasuh.com',
            'password' => Hash::make('password'),
            'role' => 'pengasuh',
            'phone' => '081234567892',
        ]);
        CaregiverProfile::create([
            'user_id' => $caregiver1->id,
            'nik' => '1234567890123456',
            'kompetensi' => 'Perawatan bayi, Pendidikan anak usia dini, First Aid',
            'pengalaman' => '5 tahun pengalaman merawat anak usia 0-6 tahun',
            'status_verifikasi' => 'terverifikasi',
            'tarif_harian' => 150000,
            'tarif_darurat' => 200000,
            'tarif_malam' => 180000,
            'rating' => 4.8,
            'total_ulasan' => 24,
            'verified_by' => 1,
            'verified_at' => now(),
        ]);

        // Pengasuh Demo (Menunggu Verifikasi)
        $caregiver2 = User::create([
            'name' => 'Putri Dayanti',
            'email' => 'putri@siagaasuh.com',
            'password' => Hash::make('password'),
            'role' => 'pengasuh',
            'phone' => '081234567893',
        ]);
        CaregiverProfile::create([
            'user_id' => $caregiver2->id,
            'nik' => '9876543210987654',
            'kompetensi' => 'Perawatan anak, Memasak, Mendongeng',
            'pengalaman' => '2 tahun pengalaman sebagai pengasuh',
            'status_verifikasi' => 'menunggu',
        ]);
    }
}
