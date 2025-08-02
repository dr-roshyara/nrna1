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
    public function candidacies()
{
    // If 'post_id' is the foreign key in Candidacy and 'post_id' is the key in Post:
    return $this->hasMany(\App\Models\Candidacy::class, 'post_id', 'post_id');
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

/**
 * Reset all voting-related state for this user and their associated voting code.
 *
 * Intended for development, QA, or testing purposes only.
 * Allows a developer to reset a voter's status and related code object for repeated testing of the voting flow.
 *
 * Usage (in tinker):
 *   $user = App\Models\User::find(1);
 *   $user->resetVotingState();
 *
 * @return $this
 */
public function resetVotingState()
{
    // Reset primary voting flags for the user
    $this->can_vote_now    = 1;   // Allow voting immediately
    $this->can_vote        = 1;   // User is eligible to vote
    $this->has_voted       = 0;   // Mark as NOT having voted
    $this->has_used_code1  = 0;   // Mark as NOT having used Code-1
    $this->has_used_code2  = 0;   // Mark as NOT having used Code-2
    $this->is_voter        = 1;   // Ensure this user is a registered voter
    $this->code1           = null; // Clear Code-1 (optional, if code is stored here)
    $this->code2           = null; // Clear Code-2 (optional, if code is stored here)
    $this->save();

    // Optionally reset any related Code model (if exists via a relationship)
    // This assumes a one-to-one relationship: User hasOne Code
    if (method_exists($this, 'code') || $this->relationLoaded('code')) {
        $code = $this->code;
        if ($code) {
            $code->vote_submitted    = 0;    // Mark vote as NOT submitted
            $code->vote_submitted_at = null; // Clear submission timestamp
            // Add any additional voting or code state resets as needed here
            $code->save();
        }
    }

    // Return the user instance for chaining or inspection
    return $this;
}

}
