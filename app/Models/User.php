<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
//models 
use App\Models\Vote;
use \App\Models\Candidacy;
use App\Models\File;
use App\Models\Upload;
use App\Models\Assignment;
use App\Models\Code;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // public $CanResetPassword;
    public function  __construct(){
        //  $CanResetPassword =true;

    }
  
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'telephone',
        'first_name',
        'middle_name',
        'gender',        
        'last_name',
        'country',
        'state',
        'street',
        'housenumber',
        'postalcode',
        'city',
        'additional_address',
        'nrna_id',
        'can_vote_now',
        'has_voted',
        'has_candidacy', 
        'lcc'  
        
    ]; 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
    /**
     * Each user has one and only one Vote :
     *      */
    public function vote (){
        return $this->hasone(Vote::class);
        // return $this->hasOne(Code::class,  'foreign_key');
        // you can also write $this->hasone('App\Vote')
    }
    
    /**
     * Each user can have one and only candidacy 
     */
       public function candidacy(){
           return $this->hasone(candidacy::class);
       }
       /**
        * Assignments and Roles A user can be assigned to many roles 
        */
          public function assignments(){
              return $this->belongsToMany(Assignment::class);
          }
         
     /**
        * User has many files 
     */
    public function files()
    {
      return $this->hasMany(File::class);
    }

    /**
     * A user has many uploads 
     */
    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }
     /**
      * Each user has extacly one code row
      */
      public function code(){
          return $this->hasOne(Code::class,  'foreign_key');
      }
}
