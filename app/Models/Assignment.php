<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//models
use \App\Models\User;
class Assignment extends Model
{
    use HasFactory;

    public function users (){
        $this->hasMany(Role::class);
    }
    
}
