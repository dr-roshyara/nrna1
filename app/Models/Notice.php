<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\MakeurlController; 

class Notice extends Model
{
    use HasFactory;
    protected $fillable =['title', 'description', 'issued_at'];
}
