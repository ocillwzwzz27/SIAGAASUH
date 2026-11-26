<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('care_sessions', function (Blueprint $table) {
            $table->string('order_id', 60)->nullable()->unique()->after('ulasan');
            $table->decimal('total_biaya', 12, 2)->nullable()->after('order_id');
            $table->enum('status_pembayaran', ['belum_bayar', 'pending', 'lunas', 'gagal'])->default('belum_bayar')->after('total_biaya');
            $table->string('metode_pembayaran', 50)->nullable()->after('status_pembayaran');
            $table->string('snap_token', 255)->nullable()->after('metode_pembayaran');
            $table->timestamp('paid_at')->nullable()->after('snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('care_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'order_id',
                'total_biaya',
                'status_pembayaran',
                'metode_pembayaran',
                'snap_token',
                'paid_at',
            ]);
        });
    }
};
