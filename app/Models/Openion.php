<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
//
class Openion extends Model
{
    use HasFactory;
      /**
     *
     *
     * Each code row belongs to exactly one user. So belongs to relationship
     *
     *
     **/
    public function user(){
         return $this->belongsTo(User::Class)
         ->select(['id','name', 'region', 'user_id','profile_icon_photo_path']);
    }
}
