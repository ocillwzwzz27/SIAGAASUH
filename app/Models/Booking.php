<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'parent_id', 'caregiver_profile_id', 'service_type_id',
        'service_date', 'start_time', 'end_time', 'address', 'latitude',
        'longitude', 'notes', 'total_price', 'status',
    ];

    protected function casts(): array
    {
        return ['service_date' => 'date'];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $b) {
            $b->booking_code ??= 'SA-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
        });
    }

    public function parent(): BelongsTo { return $this->belongsTo(User::class, 'parent_id'); }
    public function caregiver(): BelongsTo { return $this->belongsTo(CaregiverProfile::class, 'caregiver_profile_id'); }
    public function serviceType(): BelongsTo { return $this->belongsTo(ServiceType::class); }
    public function children(): BelongsToMany { return $this->belongsToMany(Child::class, 'booking_children'); }
    public function payment(): HasOne { return $this->hasOne(Payment::class); }
    public function review(): HasOne { return $this->hasOne(Review::class); }
}
