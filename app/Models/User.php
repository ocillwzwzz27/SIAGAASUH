<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'avatar',
        'address', 'village', 'city', 'latitude', 'longitude',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isCaregiver(): bool { return $this->role === 'caregiver'; }
    public function isParent(): bool { return $this->role === 'parent'; }

    public function caregiverProfile(): HasOne
    {
        return $this->hasOne(CaregiverProfile::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Child::class, 'parent_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'parent_id');
    }
}
