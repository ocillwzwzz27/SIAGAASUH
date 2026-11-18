<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('service_types')->insert([
            ['name' => 'Pengasuh Harian',   'slug' => 'harian',    'description' => 'Pengasuh harian dengan jadwal fleksibel',   'icon' => 'sun',    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pengasuh Darurat',  'slug' => 'darurat',   'description' => 'Pengasuh untuk kebutuhan mendadak',          'icon' => 'bolt',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pengasuh Malam',    'slug' => 'malam',     'description' => 'Pengasuh untuk jaga malam hari',             'icon' => 'moon',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pengasuh Langganan','slug' => 'langganan', 'description' => 'Pengasuh rutin untuk kebutuhan jangka panjang','icon' => 'users','created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('child_categories')->insert([
            ['name' => 'Bayi',             'age_range' => '0-1 tahun',  'icon' => 'baby',    'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Balita',           'age_range' => '1-3 tahun',  'icon' => 'toddler', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Anak',             'age_range' => '4-12 tahun', 'icon' => 'child',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Anak Kebutuhan Khusus', 'age_range' => null,    'icon' => 'heart',   'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lainnya',          'age_range' => null,         'icon' => 'more',    'created_at' => $now, 'updated_at' => $now],
        ]);

        User::create([
            'name' => 'Admin SiagaAsuh',
            'email' => 'admin@siagaasuh.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
