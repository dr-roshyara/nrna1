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
        'can_vote',
        'has_voted',
        'has_candidacy',
        'lcc',
        'profile_photo_path',
        'social_id',
        'social_type',
        'google_id',
        'facebook_id',
        'approvedBy', 

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

    // In your User model (App\Models\User.php)

    /**
     * Determine if the user is fully eligible to vote.
     * A user is eligible if both 'is_voter' and 'can_vote' are set to true (1).
     *
     * @return bool
     */
 public function isEligibleToVote()
{
    // Convert database integers to booleans properly
    return (bool) $this->is_voter && (bool) $this->can_vote;
}
    
    /**
     * Check if user can ACCESS the ballot (eligibility only)
     * This is just the first gate - actual voting is controlled by Code model
     * 
     * @return bool
     */
   /**
 * Check if user can ACCESS the ballot 
 * Handles both voting and viewing scenarios based on Code model
 * 
 * @return bool
 */
    public function canAccessBallot()
    {
        // Basic eligibility check
        if (!(bool) $this->is_voter || !(bool) $this->can_vote) {
            return false;
        }
        
        // Election must be active
        if (!config('election.is_active', true)) {
            return false;
        }
        
        // If user has can_vote = 1, they can access ballot
        // (either to vote or to view their vote)
        return true;
    }
    
        /**
     * Get ballot access status with detailed error messages
     * Now checks Code model for voting status
     * 
     * @return array
     */
    public function getBallotAccessStatus()
    {
        $status = [
            'can_access' => false,
            'error_type' => null,
            'error_title' => '',
            'error_message_nepali' => '',
            'error_message_english' => ''
        ];
        
        $isVoter = (bool) $this->is_voter;
        $canVote = (bool) $this->can_vote;
        
        // Check 1: Must be a voter
        if (!$isVoter) {
            $status['error_type'] = 'not_voter';
            $status['error_title'] = 'मतदाता नभएको | Not a Voter';
            $status['error_message_nepali'] = 'तपाईंको नाम मतदाता नामाबलीमा छैन।';
            $status['error_message_english'] = 'You are not a registered voter.';
            return $status; 
        }
        
        // Check 2: Must be approved (can_vote = 1)
        if (!$canVote) {
            $status['error_type'] = 'not_verified';
            $status['error_title'] = 'प्रमाणीकरण आवश्यक | Verification Required';
            $status['error_message_nepali'] = 'तपाईं प्रमाणित मतदाता हुनुहुन्न। निर्वाचन समितिले प्रमाणीकरण गर्नुपर्छ।';
            $status['error_message_english'] = 'You are not a verified voter. Election committee must approve you first.';
            return $status;
        }
        
        // Check 3: Election must be active
        $electionActive = config('election.is_active', true);
        if (!$electionActive) {
            $status['error_type'] = 'election_inactive';
            $status['error_title'] = 'निर्वाचन निष्क्रिय | Election Inactive';
            $status['error_message_nepali'] = 'निर्वाचन अहिले सक्रिय छैन।';
            $status['error_message_english'] = 'Election is not currently active.';
            return $status;
        }

        // ✅ NEW: Check Code model for voting status
        $code = $this->code; // Using the relationship
        
        if ($code && $code->has_voted == 1) {
            // User has voted - can access to view vote
            $status['can_access'] = true;
            $status['error_type'] = 'already_voted';
            return $status;
        }
        
        // User can vote (either no Code or Code->has_voted = 0)
        $status['can_access'] = true;
        $status['error_type'] = null;
        return $status;
    }
    

   
   /**
     * Check if user is committee member who can approve voters
     * 
     * @return bool
     */
    public function canApproveVoters()
    {
        return $this->is_committee_member == 1;
    }
    /**
     * Get or create the Code model for this user (anonymization layer)
     * 
     * @return Code
     */
    public function getVotingCode()
    {
        return $this->hasOne(Code::class)->first() ?? 
            Code::create(['user_id' => $this->id, 'client_ip' => request()->ip()]);
    }


    /**
     * Get a descriptive status of the user's voting eligibility.
     * Returns:
     * - 'not_in_list'   : User is neither marked as a voter nor approved.
     * - 'not_verified'  : User is a voter but not yet verified/approved.
     * - 'eligible'      : User is both a voter and verified/approved.
     * - 'ineligible'    : Any other case (should rarely occur, added for safety).
     *
     * @return string
     */
    public function getVoteEligibilityStatus()
    {
        // Not in voter list and not approved: not eligible to vote at all.
        if (!$this->is_voter && !$this->can_vote) {
            return 'not_in_list';
        }

        // In voter list but not yet approved/verified by committee.
        if ($this->is_voter && !$this->can_vote) {
            return 'not_verified';
        }

        // In voter list and approved: eligible to vote.
        if ($this->is_voter && $this->can_vote) {
            return 'eligible';
        }

        // Catch-all for unusual or inconsistent data.
        return 'ineligible';
    }

    /**
     * Get the voter record associated with the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function voter()
    {
        return $this->hasOne(Voter::class);
    }
    /**
     * Get the election committee member record associated with the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function electionCommitteeMember()
    {
        return $this->hasOne(ElectionCommitteeMember::class);
    }


/**
 * Check if user is a voter
 * 
 * @return bool
 */
public function isVoter()
{
    return $this->is_voter && $this->voter()->exists();
}
/**
 * Check if user is an election committee member
 * 
 * @return bool
 */
public function isCommitteeMember()
{
    return $this->is_committee_member && $this->electionCommitteeMember()->exists();
}

/**
 * Check if user can vote (is voter and approved)
 * 
 * @return bool
 */
public function canVote()
{
    return $this->isVoter() && 
           $this->voter->can_vote && 
           $this->voter->is_active;
}

/**
 * Check if user has committee permission
 * 
 * @param string $permission
 * @return bool
 */
public function hasCommitteePermission($permission)
{
    if (!$this->isCommitteeMember()) {
        return false;
    }
    
    $permissions = $this->electionCommitteeMember->permissions ?? [];
    return in_array($permission, $permissions);
}
/**
 * Get user's committee role
 * 
 * @return string|null
 */
public function getCommitteeRole()
{
    return $this->electionCommitteeMember?->role;
}

/**
 * Scope: Get only voters
 * 
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeVoters($query)
{
    return $query->where('is_voter', true)->whereHas('voter');
}

/**
 * Scope: Get only committee members
 * 
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeCommitteeMembers($query)
{
    return $query->where('is_committee_member', true)->whereHas('electionCommitteeMember');
}

/**
 * Scope: Get users who can vote
 * 
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeEligibleVoters($query)
{
    return $query->voters()->whereHas('voter', function($q) {
        $q->where('can_vote', true)->where('is_active', true);
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
