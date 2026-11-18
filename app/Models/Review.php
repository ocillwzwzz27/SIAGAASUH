<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['booking_id', 'parent_id', 'caregiver_profile_id', 'rating', 'comment'];

    protected static function booted(): void
    {
        static::saved(fn (Review $r) => $r->caregiver->refreshRating());
        static::deleted(fn (Review $r) => $r->caregiver->refreshRating());
    }

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function parent(): BelongsTo { return $this->belongsTo(User::class, 'parent_id'); }
    public function caregiver(): BelongsTo { return $this->belongsTo(CaregiverProfile::class, 'caregiver_profile_id'); }
}
