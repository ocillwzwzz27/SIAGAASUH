<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sensor_readings', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('reed_status');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('location_name', 150)->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('sensor_readings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'location_name']);
        });
    }
};
