<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'session_id',
        'tipe',
        'judul',
        'pesan',
        'sudah_dibaca',
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(CareSession::class);
    }

    public static function kirim(int $userId, string $judul, string $pesan, string $tipe = 'sistem', ?int $sessionId = null): self
    {
        return self::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'tipe' => $tipe,
            'judul' => $judul,
            'pesan' => $pesan,
            'sudah_dibaca' => false,
        ]);
    }
}
