<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'catatan_kesehatan',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function careSessions()
    {
        return $this->hasMany(CareSession::class);
    }

    public function getUmurAttribute(): string
    {
        return Carbon::parse($this->tanggal_lahir)->age . ' tahun';
    }

    public function getActiveDeviceAttribute()
    {
        return $this->devices()->where('aktif', true)->first();
    }
}
