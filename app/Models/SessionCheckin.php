<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SessionCheckin extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'caregiver_id',
        'foto_path',
        'catatan',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(CareSession::class, 'session_id');
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }
}
