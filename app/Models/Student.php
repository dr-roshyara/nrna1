<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable =[
        'first_name',
        'family_name',
        'child_name',
        'child_grade',
        'birth_year',
        'child_language',
        'city',
        'country',
        'email',
        'telephone',
        'about'    

    ];
}
