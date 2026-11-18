<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaregiverProfile extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'experience_years', 'verification_status',
        'id_card_photo', 'verified_at', 'rating_avg', 'review_count', 'is_available',
    ];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime', 'is_available' => 'boolean'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(ServiceType::class, 'caregiver_services')
            ->withPivot('price_per_hour');
    }

    public function childCategories(): BelongsToMany
    {
        return $this->belongsToMany(ChildCategory::class, 'caregiver_child_categories');
    }

    public function schedules(): HasMany { return $this->hasMany(CaregiverSchedule::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }

    public function scopeVerified(Builder $q): Builder
    {
        return $q->where('verification_status', 'verified');
    }

    /** Pengasuh terdekat (rumus Haversine, hasil dalam km). */
    public function scopeNearest(Builder $q, float $lat, float $lng): Builder
    {
        return $q->join('users', 'users.id', '=', 'caregiver_profiles.user_id')
            ->select('caregiver_profiles.*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(users.latitude))
                * cos(radians(users.longitude) - radians(?))
                + sin(radians(?)) * sin(radians(users.latitude)))) AS distance',
                [$lat, $lng, $lat]
            )
            ->orderBy('distance');
    }

    /** Hitung ulang cache rating setelah ada ulasan baru. */
    public function refreshRating(): void
    {
        $this->update([
            'rating_avg' => round($this->reviews()->avg('rating') ?? 0, 1),
            'review_count' => $this->reviews()->count(),
        ]);
    }
}
