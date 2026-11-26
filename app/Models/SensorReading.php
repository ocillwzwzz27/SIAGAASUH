<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SensorReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'device_id',
        'bpm',
        'spo2',
        'reed_status',
        'latitude',
        'longitude',
        'location_name',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'reed_status' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function session()
    {
        return $this->belongsTo(CareSession::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    // Ambang batas: BPM < 60 atau > 140, SpO2 < 95
    public function isCritical(): bool
    {
        $bpmCritical = $this->bpm < 60 || $this->bpm > 140;
        $spo2Critical = $this->spo2 !== null && $this->spo2 < 95;
        return $bpmCritical || $spo2Critical || $this->reed_status;
    }

    public function getStatusBpmAttribute(): string
    {
        if ($this->bpm < 60) return 'rendah';
        if ($this->bpm > 140) return 'tinggi';
        return 'normal';
    }
}
