<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $with = ['calander'];
    protected $fillable = ['google_id', 'name', 'description', 'allday', 'started_at', 'ended_at'];
    //
    public function calander(){
        $this->belongsTo(Calander::class);
    }
    public function getStartedAttribute($start){
        return $this->asDateTime($start)->setTimezone($this->calander->timezone);

    }
    public function getEndedAttribute($end){
        return $this->asDateTime($end)->setTimezone($this->calander->timezone);

    }

    public function getDurationAttribute(){
        return $this->started_at->diffForHumans($this->ended_at,true);

    }


}
