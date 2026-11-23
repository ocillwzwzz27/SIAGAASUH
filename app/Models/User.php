<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helpers
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isOrangTua(): bool { return $this->role === 'orang_tua'; }
    public function isPengasuh(): bool { return $this->role === 'pengasuh'; }

    // Relationships
    public function caregiverProfile()
    {
        return $this->hasOne(CaregiverProfile::class);
    }

    public function children()
    {
        return $this->hasMany(Child::class, 'parent_id');
    }

    public function sessionsAsParent()
    {
        return $this->hasMany(CareSession::class, 'parent_id');
    }

    public function sessionsAsCaregiver()
    {
        return $this->hasMany(CareSession::class, 'caregiver_id');
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class);
    }
}
