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
use Illuminate\Support\Facades\Hash;
//models
use App\Models\Vote;
use App\Models\DeligateVote;
use \App\Models\Candidacy;
use App\Models\File;
use App\Models\Upload;
use App\Models\Assignment;
use App\Models\Code;
use App\Models\Image;
use App\Models\GoogleAccount;
use App\Models\Calendar;
use App\Models\Event;
class User extends Authenticatable implements MustVerifyEmail
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
        'google_id',
        'name',
        'region',
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
        'lcc',
        'profile_photo_path',
        'social_id',
        'social_type',
        'google_id',
        'facebook_id'

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
     * The relationship that should always be loaded
     *
     */

      protected $with =[
        //   'profile',

        ];

    /**
     * Each user has one and only one Vote :
     *      */
    public function vote (){
        return $this->hasone(Vote::class, );
        // return $this->hasOne(Code::class,  'foreign_key');
        // you can also write $this->hasone('App\Vote')
    }

     /**
     * Each user has one and only one Vote :
     *      */
    public function deligatevote (){
        return $this->hasone(DeligateVote::class);
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
          return $this->hasOne(Code::class);
      }
      /**
      * Each user has extacly one code row
      */
      public function deligatecode(){
        return $this->hasOne(Code::class,  'foreign_key');
    }
    /**
     *  images
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    /***
     * google accounts
     */
       public function googleAccounts()
    {
        return $this->hasMany(GoogleAccount::class);
    }
    public function events()
    {
        return Event::whereHas('calendar', function ($calendarQuery) {
            $calendarQuery->whereHas('googleAccount', function ($accountQuery) {
                $accountQuery->whereHas('user', function ($userQuery) {
                    $userQuery->where('id', $this->id);
                });
            });
        });
    }
}
