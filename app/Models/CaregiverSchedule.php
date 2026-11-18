<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaregiverSchedule extends Model
{
    public $timestamps = false;

    protected $fillable = ['caregiver_profile_id', 'day_of_week', 'start_time', 'end_time'];
}
