<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calander extends Model
{
    use HasFactory;
    protected $fillable  =['google_id','name', 'timezone'];
    //
    public function gooogleAccount(){
        return $this->belongsTo(GoogleAccount::class);

    }
    public function events (){
        return $this->hasMany(Event::class);
    }
}
