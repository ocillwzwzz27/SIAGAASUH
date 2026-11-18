<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('caregiver_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_type_id')->constrained();
            $table->date('service_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('notes')->nullable(); // "Informasi Tambahan"
            $table->unsignedInteger('total_price')->default(0);
            $table->enum('status', ['pending', 'accepted', 'rejected', 'ongoing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['caregiver_profile_id', 'service_date']);
            $table->index(['parent_id', 'status']);
        });

        Schema::create('booking_children', function (Blueprint $table) {
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->primary(['booking_id', 'child_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_children');
        Schema::dropIfExists('bookings');
    }
};
