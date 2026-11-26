<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CareSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'caregiver_id',
        'child_id',
        'status',
        'jenis_layanan',
        'started_at',
        'ended_at',
        'ringkasan',
        'catatan_orang_tua',
        'rating',
        'ulasan',
        'order_id',
        'total_biaya',
        'status_pembayaran',
        'metode_pembayaran',
        'snap_token',
        'paid_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'paid_at' => 'datetime',
        'rating' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class, 'session_id');
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class, 'session_id');
    }

    public function latestReading()
    {
        return $this->hasOne(SensorReading::class, 'session_id')->latestOfMany();
    }

    public function checkins()
    {
        return $this->hasMany(SessionCheckin::class, 'session_id')->orderByDesc('checked_in_at');
    }

    public function latestCheckin()
    {
        return $this->hasOne(SessionCheckin::class, 'session_id')->latestOfMany('checked_in_at');
    }

    public function isActive(): bool
    {
        return $this->status === 'berjalan';
    }

    public function isPending(): bool
    {
        return $this->status === 'menunggu';
    }

    public function markAsPaid(?string $paymentType = null): void
    {
        $wasPaid = $this->status_pembayaran === 'lunas';

        $this->update([
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => $paymentType ?? $this->metode_pembayaran ?? 'midtrans',
            'paid_at' => $this->paid_at ?? now(),
        ]);

        if ($wasPaid) {
            return;
        }

        $nominal = 'Rp ' . number_format((float) ($this->total_biaya ?? 0), 0, ',', '.');

        // 1. Notifikasi ke Orang Tua
        AppNotification::kirim(
            $this->parent_id,
            '💳 Pembayaran Sesi Lunas',
            "Pembayaran untuk sesi #{$this->id} sebesar {$nominal} telah berhasil dikonfirmasi.",
            'layanan',
            $this->id
        );

        // 2. Notifikasi ke Mitra Pengasuh
        AppNotification::kirim(
            $this->caregiver_id,
            '💰 Pembayaran Sesi Diterima',
            ($this->parent->name ?? 'Orang Tua') . " telah melunasi biaya layanan sesi #{$this->id} sebesar {$nominal}.",
            'layanan',
            $this->id
        );

        // 3. Notifikasi ke Semua Admin PT
        $admins = User::where('role', 'admin')->pluck('id');
        foreach ($admins as $adminId) {
            AppNotification::kirim(
                $adminId,
                '🧾 Pembayaran Masuk Lunas',
                "Transaksi sesi #{$this->id} ({$this->order_id}) sebesar {$nominal} telah lunas.",
                'layanan',
                $this->id
            );
        }
    }
}
