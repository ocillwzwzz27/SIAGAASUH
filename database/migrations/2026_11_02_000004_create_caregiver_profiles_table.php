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
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('id_card_photo')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->decimal('rating_avg', 2, 1)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        Schema::create('caregiver_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('price_per_hour');
            $table->unique(['caregiver_profile_id', 'service_type_id']);
        });

        Schema::create('caregiver_child_categories', function (Blueprint $table) {
            $table->foreignId('caregiver_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('child_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['caregiver_profile_id', 'child_category_id']);
        });

        Schema::create('caregiver_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caregiver_profile_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 0 = Minggu ... 6 = Sabtu
            $table->time('start_time');
            $table->time('end_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caregiver_schedules');
        Schema::dropIfExists('caregiver_child_categories');
        Schema::dropIfExists('caregiver_services');
        Schema::dropIfExists('caregiver_profiles');
    }
};
