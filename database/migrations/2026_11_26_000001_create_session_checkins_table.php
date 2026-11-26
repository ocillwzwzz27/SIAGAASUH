<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('care_sessions')->onDelete('cascade');
            $table->foreignId('caregiver_id')->constrained('users')->onDelete('cascade');
            $table->string('foto_path');
            $table->string('catatan', 300)->nullable();
            $table->timestamp('checked_in_at');
            $table->timestamps();

            $table->index(['session_id', 'checked_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_checkins');
    }
};
