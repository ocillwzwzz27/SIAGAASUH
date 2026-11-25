<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('caregiver_profiles', function (Blueprint $table) {
            $table->text('alamat_domisili')->nullable()->after('nik');
            $table->string('pendidikan_terakhir', 100)->nullable()->after('alamat_domisili');
            $table->string('kontak_darurat', 150)->nullable()->after('pendidikan_terakhir');
            $table->string('dokumen_skck')->nullable()->after('foto_profil');
            $table->string('dokumen_surat_sehat')->nullable()->after('dokumen_skck');
            $table->string('dokumen_surat_pengalaman')->nullable()->after('dokumen_surat_sehat');
            $table->boolean('setuju_sk')->default(false)->after('pengalaman');
            $table->timestamp('sk_accepted_at')->nullable()->after('setuju_sk');
        });
    }

    public function down(): void
    {
        Schema::table('caregiver_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'alamat_domisili',
                'pendidikan_terakhir',
                'kontak_darurat',
                'dokumen_skck',
                'dokumen_surat_sehat',
                'dokumen_surat_pengalaman',
                'setuju_sk',
                'sk_accepted_at',
            ]);
        });
    }
};
