<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caregiver_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nik', 20)->nullable();
            $table->string('foto_identitas')->nullable();
            $table->string('foto_profil')->nullable();
            $table->text('kompetensi')->nullable();
            $table->text('pengalaman')->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->text('alasan_penolakan')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->decimal('tarif_harian', 10, 2)->nullable();
            $table->decimal('tarif_darurat', 10, 2)->nullable();
            $table->decimal('tarif_malam', 10, 2)->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_ulasan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caregiver_profiles');
    }
};
