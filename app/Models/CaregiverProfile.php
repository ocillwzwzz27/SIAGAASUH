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
        'alamat_domisili',
        'pendidikan_terakhir',
        'kontak_darurat',
        'foto_identitas',
        'foto_profil',
        'dokumen_skck',
        'dokumen_surat_sehat',
        'dokumen_surat_pengalaman',
        'kompetensi',
        'pengalaman',
        'setuju_sk',
        'sk_accepted_at',
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
        'setuju_sk' => 'boolean',
        'sk_accepted_at' => 'datetime',
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
