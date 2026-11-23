<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaregiverProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik',
        'foto_identitas',
        'foto_profil',
        'kompetensi',
        'pengalaman',
        'status_verifikasi',
        'alasan_penolakan',
        'verified_by',
        'verified_at',
        'tarif_harian',
        'tarif_darurat',
        'tarif_malam',
        'rating',
        'total_ulasan',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'tarif_harian' => 'decimal:2',
        'tarif_darurat' => 'decimal:2',
        'tarif_malam' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isVerified(): bool
    {
        return $this->status_verifikasi === 'terverifikasi';
    }

    public function isPending(): bool
    {
        return $this->status_verifikasi === 'menunggu';
    }

    public function isRejected(): bool
    {
        return $this->status_verifikasi === 'ditolak';
    }
}
