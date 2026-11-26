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
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'rating' => 'decimal:2',
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
}
