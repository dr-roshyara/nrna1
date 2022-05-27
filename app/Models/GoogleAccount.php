<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Calendar;
use App\Models\User;
use App\Concerns\Synchronizable;
use App\Jobs\SynchronizeGoogleCalendars;
use App\Jobs\WatchGoogleCalendars;
use App\Services\Google;
class GoogleAccount extends Model
{
    use HasFactory;
    protected $fillable = ['google_id', 'name','token'];
    protected $casts    =['token'=>'json'];
    public function user(){
        return  $this->belongsTo(User::class);

    }
    public function calanders(){
        return $this->hasMany(Calander::class);
    }
    //
    public static function boot()
    {
        parent::boot();

        static::created(function ($googleAccount) {
            SynchronizeGoogleCalendars::dispatch($googleAccount);
        });
    }
}
