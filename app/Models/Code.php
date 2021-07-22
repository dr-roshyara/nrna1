<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Code extends Model
{
    use HasFactory;
    protected $fillable =['code1','code2','code2','used_code1','used_code2','used_code3'];
    /**
     * Each code row belongs to exactly one user. So belongs to relationship  
     */
    public function user(){
        return $this->belongsTo(User::Class);
    }
}
