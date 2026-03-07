<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Calendar;
use App\Traits\BelongsToTenant;

class Event extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $with = ['calendar'];

    protected $fillable = [
        'organisation_id',
        'calendar_id',
        'google_id',
        'name',
        'description',
        'allday',
        'started_at',
        'ended_at',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class)->withoutGlobalScopes();
    }

    public function getStartedAtAttribute($start)
    {
        return $this->asDateTime($start)->setTimezone($this->calendar->timezone);
    }

    public function getEndedAtAttribute($end)
    {
        return $this->asDateTime($end)->setTimezone($this->calendar->timezone);
    }

    public function getDurationAttribute()
    {
        return $this->started_at->diffForHumans($this->ended_at, true);
    }
}
