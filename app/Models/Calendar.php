<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Event;
use App\Models\GoogleAccount;
use App\Jobs\SynchronizeGoogleEvents;
use App\Traits\BelongsToTenant;

class Calendar extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'google_id',
        'name',
        'color',
        'timezone',
    ];

    public function googleAccount()
    {
        return $this->belongsTo(GoogleAccount::class)->withoutGlobalScopes();
    }

    public function events()
    {
        return $this->hasMany(Event::class)->withoutGlobalScopes();
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($calendar) {
            SynchronizeGoogleEvents::dispatch($calendar);
        });
    }
}
