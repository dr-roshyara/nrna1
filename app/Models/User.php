<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasOrganisation;
use App\Traits\HasAuditFields;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//models
use App\Models\Vote;
use App\Models\Result;
use App\Models\DeligateVote;
use \App\Models\Candidacy;
use App\Models\File;
use App\Models\Upload;
use App\Models\Assignment;
use App\Models\Code;
use App\Models\DemoCode;
use App\Models\Image;
use App\Models\GoogleAccount;
use App\Models\Calendar;
use App\Models\Event;
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use Notifiable;
    use HasRoles;
    use HasOrganisation;
    use HasAuditFields;

    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // public $CanResetPassword;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        //  $CanResetPassword =true;
    }

    /**
     * ⚠️ SECURITY: Only non-critical user profile fields are mass-assignable.
     * Voting-related fields are PROTECTED from mass assignment to prevent manipulation.
     */
    protected $fillable = [
        'organisation_id',
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
        'lcc',
        'profile_photo_path',
        'social_id',
        'social_type',
        'facebook_id',
        'voting_ip',  // Voting IP is mass-assignable for audit trail
    ];

    /**
     * ⚠️ SECURITY: These fields are PROTECTED from mass assignment.
     * They can only be modified through explicit setter methods with authorization checks.
     */
    protected $guarded = [
        'id',
        'can_vote',          // CRITICAL: Voting eligibility
        'has_voted',         // CRITICAL: Vote status
        'is_voter',          // CRITICAL: Voter registration status
        'is_committee_member', // CRITICAL: Admin privileges
        'wants_to_vote',     // CRITICAL: Voter intent indicator
        'approvedBy',        // CRITICAL: Audit trail
        'suspendedBy',       // CRITICAL: Audit trail
        'suspended_at',      // CRITICAL: Audit trail
        'voting_ip',         // CRITICAL: Vote security
        'has_candidacy',     // CRITICAL: Candidate status
        'vote_last_seen',
        'voting_started_at',
        'vote_submitted_at',
        'vote_completed_at',
        'voter_registration_at', // CRITICAL: Voter registration tracking
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
    protected $appends = [];

    /**
     * The relationship that should always be loaded
     *
     */

      protected $with =[
        //   'profile',

        ];

    /**
     * Multi-tenancy relationships
     */
    public function currentOrganisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'user_organisation_roles')
                    ->select('organisations.*')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    public function organisationRoles()
    {
        return $this->hasMany(UserOrganisationRole::class);
    }

    /**
     * Three-tier membership hierarchy: User → OrganisationUser → Member → Voter
     */
    public function organisationUsers()
    {
        return $this->hasMany(OrganisationUser::class);
    }

    public function members()
    {
        return $this->hasManyThrough(
            Member::class,
            OrganisationUser::class,
            'user_id',          // FK on organisation_users
            'organisation_user_id', // FK on members
            'id',               // local key on users
            'id'                // local key on organisation_users
        );
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
        return $this->hasMany(Candidacy::class, 'user_id', 'id')
                    ->withoutGlobalScopes();
    }

    /**
     * Each user can have one and only candidacy
     */
       public function candidacy(){
           return $this->hasOne(Candidacy::class, 'user_id', 'id')->where('status', 'approved');
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
       * User has many voter slugs for time-expiring voting URLs
       */
      public function voterSlugs()
      {
          return $this->hasMany(VoterSlug::class);
      }

     /**
      * Each user has extacly one code row
      */
      public function code(){
          return $this->hasOne(Code::class);
      }

     /**
      * Get all codes for this user (user can have multiple codes)
      */
      public function codes(){
          return $this->hasMany(Code::class);
      }

      /**
       * Get all demo codes for this user (demo voting workflow)
       * Demo codes are used for platform-wide and organisation-specific demo elections
       */
      public function demoCodes(){
          return $this->hasMany(DemoCode::class);
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

    // ============================================================================
    // NEW: ELECTION VOTER REGISTRATION RELATIONSHIPS (Demo/Real Elections)
    // ============================================================================

    /**
     * Get all voter registrations for this user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voterRegistrations()
    {
        return $this->hasMany(VoterRegistration::class);
    }

    /**
     * Get demo election registration for this user
     *
     * @return VoterRegistration|null
     */
    public function demoRegistration()
    {
        return $this->voterRegistrations()
                    ->where('election_type', 'demo')
                    ->first();
    }

    /**
     * Get real election registration for this user
     *
     * @return VoterRegistration|null
     */
    public function realRegistration()
    {
        return $this->voterRegistrations()
                    ->where('election_type', 'real')
                    ->first();
    }

    /**
     * Check if user wants to vote in demo election
     *
     * @return bool
     */
    public function wantsToVoteInDemo(): bool
    {
        return $this->voterRegistrations()
                    ->where('election_type', 'demo')
                    ->whereIn('status', ['pending', 'approved', 'voted'])
                    ->exists();
    }

    /**
     * Check if user wants to vote in real election
     *
     * @return bool
     */
    public function wantsToVoteInReal(): bool
    {
        return $this->voterRegistrations()
                    ->where('election_type', 'real')
                    ->whereIn('status', ['pending', 'approved', 'voted'])
                    ->exists();
    }

    /**
     * Check if user is approved to vote in demo election
     *
     * @return bool
     */
    public function canVoteInDemo(): bool
    {
        $registration = $this->demoRegistration();
        return $registration && $registration->isApproved();
    }

    /**
     * Check if user is approved to vote in real election
     *
     * @return bool
     */
    public function canVoteInReal(): bool
    {
        $registration = $this->realRegistration();
        return $registration && $registration->isApproved();
    }

    /**
     * Check if user has voted in demo election
     *
     * @return bool
     */
    public function hasVotedInDemo(): bool
    {
        $registration = $this->demoRegistration();
        return $registration && $registration->hasVoted();
    }

    /**
     * Check if user has voted in real election
     *
     * @return bool
     */
    public function hasVotedInReal(): bool
    {
        $registration = $this->realRegistration();
        return $registration && $registration->hasVoted();
    }

    /**
     * Register user for demo election
     *
     * @param int $electionId
     * @return VoterRegistration
     */
    public function registerForDemoElection(int $electionId): VoterRegistration
    {
        // Check if already registered
        $existing = $this->voterRegistrations()
            ->where('election_id', $electionId)
            ->where('election_type', 'demo')
            ->first();

        if ($existing) {
            return $existing;
        }

        return VoterRegistration::create([
            'user_id' => $this->id,
            'election_id' => $electionId,
            'election_type' => 'demo',
            'status' => 'pending',
            'registered_at' => now(),
        ]);
    }

    /**
     * Register user for real election
     *
     * @param int $electionId
     * @return VoterRegistration
     */
    public function registerForRealElection(int $electionId): VoterRegistration
    {
        // Check if already registered
        $existing = $this->voterRegistrations()
            ->where('election_id', $electionId)
            ->where('election_type', 'real')
            ->first();

        if ($existing) {
            return $existing;
        }

        return VoterRegistration::create([
            'user_id' => $this->id,
            'election_id' => $electionId,
            'election_type' => 'real',
            'status' => 'pending',
            'registered_at' => now(),
        ]);
    }

    /**
     * Get user's status in a specific election
     *
     * @param int $electionId
     * @return string|null (pending, approved, rejected, voted, or null if not registered)
     */
    public function getElectionStatus(int $electionId): ?string
    {
        $registration = $this->voterRegistrations()
            ->where('election_id', $electionId)
            ->first();

        return $registration?->status;
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

// ============================================================================
// NEW: VOTER REGISTRATION STATE SCOPES & METHODS (Phase 1 Update)
// ============================================================================

/**
 * Scope: Get only customers (users who don't want to vote)
 * Used to exclude non-voters from voter-related operations
 *
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeCustomers($query)
{
    return $query->where('wants_to_vote', false)
        ->where('is_committee_member', 0)
        ->where('is_voter', 0);
}

/**
 * Scope: Get only pending voters (users requesting voter status, not yet approved)
 * Used for voter approval workflows to show only legitimate voter candidates
 *
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopePendingVoters($query)
{
    return $query->where('wants_to_vote', true)
        ->where('is_voter', 0)
        ->where('can_vote', 0)
        ->where('is_committee_member', 0);
}

/**
 * Scope: Get only approved voters (users approved to vote)
 * Used for active voting workflows
 *
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeApprovedVoters($query)
{
    return $query->where('wants_to_vote', true)
        ->where('is_voter', 1)
        ->where('can_vote', 1);
}

/**
 * Check if user is a customer (doesn't want to vote)
 *
 * @return bool
 */
public function isCustomer(): bool
{
    return !$this->wants_to_vote && !$this->is_voter && !$this->is_committee_member;
}

/**
 * Check if user is a pending voter
 *
 * @return bool
 */
public function isPendingVoter(): bool
{
    return $this->wants_to_vote && !$this->is_voter && !$this->can_vote;
}

/**
 * Check if user is an approved voter
 *
 * @return bool
 */
public function isApprovedVoter(): bool
{
    return $this->wants_to_vote && $this->is_voter && $this->can_vote;
}

/**
 * Get user's voter registration state
 * Returns one of: 'customer', 'pending_voter', 'approved_voter', 'suspended_voter', 'committee_member'
 *
 * @return string
 */
public function getVoterState(): string
{
    if ($this->is_committee_member) {
        return 'committee_member';
    }

    if ($this->wants_to_vote) {
        if (!$this->is_voter) {
            return 'pending_voter';
        }

        if ($this->is_voter && $this->can_vote) {
            return 'approved_voter';
        }

        if ($this->is_voter && !$this->can_vote) {
            return 'suspended_voter';
        }
    }

    return 'customer';
}

    // ============================================================================
    // SECURE SETTERS FOR PROTECTED VOTING FIELDS
    // ============================================================================

    /**
     * ⚠️ SECURITY: Approve voter for voting (Committee members only)
     * This method should only be called after proper authorization checks
     *
     * Note: voting_ip is set by the controller when the user accesses the voting page,
     * not during approval. This allows capturing the actual IP at voting time.
     *
     * @param User $committeeUser The committee member approving the voter
     * @return bool Success status
     */
    public function approveForVoting(User $committeeUser): bool
    {
        if (!$committeeUser->is_committee_member) {
            throw new \Exception('Only committee members can approve voters');
        }

        if (!$this->is_voter) {
            throw new \Exception('User must be registered as a voter first');
        }

        $this->can_vote = 1;
        $this->approvedBy = $committeeUser->name;
        $this->suspendedBy = null;
        $this->suspended_at = null;

        return $this->save();
    }

    /**
     * ⚠️ SECURITY: Suspend voter (Committee members only)
     * This method should only be called after proper authorization checks
     *
     * @param User $committeeUser The committee member suspending the voter
     * @return bool Success status
     */
    public function suspendVoting(User $committeeUser): bool
    {
        if (!$committeeUser->is_committee_member) {
            throw new \Exception('Only committee members can suspend voters');
        }

        $this->can_vote = 0;
        $this->suspendedBy = $committeeUser->name;
        $this->suspended_at = now();

        return $this->save();
    }

    /**
     * ⚠️ SECURITY: Mark user as having voted (System only)
     * This method should ONLY be called by the voting system after vote submission
     *
     * @return bool Success status
     */
    public function markAsVoted(): bool
    {
        if ($this->has_voted) {
            throw new \Exception('User has already voted');
        }

        $this->has_voted = 1;
        $this->vote_completed_at = now();

        return $this->save();
    }

    /**
     * ⚠️ SECURITY: Register user as voter (Admin only)
     *
     * @return bool Success status
     */
    public function registerAsVoter(): bool
    {
        $this->is_voter = 1;
        return $this->save();
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

    // ============================================================================
    // NEW: ROLE SYSTEM METHODS - OPTION C: LEGACY-AWARE HYBRID
    // These methods work ALONGSIDE existing is_committee_member and voting logic
    // ============================================================================


    /**
     * Get dashboard-accessible roles (NEW system + legacy mapping)
     * Combines new pivot roles + maps existing committee/voter status
     *
     * @return array
     */
    public function getDashboardRoles(): array
    {
        return \Illuminate\Support\Facades\Cache::remember(
            "user_{$this->id}_dashboard_roles",
            3600,
            function () {
                $roles = [];

                // Get organisation-specific roles from new system
                $orgRoles = \DB::table('user_organisation_roles')
                    ->where('user_id', $this->id)
                    ->distinct()
                    ->pluck('role')
                    ->toArray();
                $roles = array_merge($roles, $orgRoles);

                // Map existing legacy roles to new system
                // This maintains backward compatibility
                if ($this->is_committee_member) {
                    if (!in_array('commission', $roles)) {
                        $roles[] = 'commission';
                    }
                }

                // If user can vote (from VoterRegistration logic)
                if ($this->wantsToVoteInDemo() || $this->wantsToVoteInReal()) {
                    if (!in_array('voter', $roles)) {
                        $roles[] = 'voter';
                    }
                }

                return array_unique(array_filter($roles));
            }
        );
    }

    /**
     * Check if user has dashboard role (NEW system only)
     *
     * @param string $role
     * @return bool
     */
    public function hasDashboardRole(string $role): bool
    {
        return in_array($role, $this->getDashboardRoles());
    }

    /**
     * Get election-specific commission roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function electionCommissionRoles()
    {
        return $this->belongsToMany(Election::class, 'election_commission_members')
                    ->withTimestamps();
    }

    /**
     * Check if user is commission member for specific election
     *
     * @param int $electionId
     * @return bool
     */
    public function isCommissionMemberForElection(int $electionId): bool
    {
        return $this->electionCommissionRoles()
            ->where('elections.id', $electionId)
            ->exists();
    }

    /**
     * Check if user is admin of organisation
     *
     * @param int $organisationId
     * @return bool
     */
    public function isOrganisationAdmin(string $organisationId): bool
    {
        return $this->organisationRoles()
            ->where('organisation_id', $organisationId)
            ->where('role', 'admin')
            ->exists();
    }

    /**
     * Check if user is voter in organisation
     *
     * @param string $organisationId
     * @return bool
     */
    public function isOrganisationVoter(string $organisationId): bool
    {
        return $this->organisationRoles()
            ->where('organisation_id', $organisationId)
            ->where('role', 'voter')
            ->exists();
    }

    /**
     * Check if user belongs to a specific organisation.
     *
     * @param string $organisationId The organisation ID to check
     * @return bool True if user has a valid pivot record for this organisation
     */
    public function belongsToOrganisation(string $organisationId): bool
    {
        return $this->organisations()
            ->where('organisation_id', $organisationId)
            ->exists();
    }

    /**
     * Get the user's role in a specific organisation
     */
    public function getRoleInOrganisation($organisationId): ?string
    {
        $role = DB::table('user_organisation_roles')
            ->where('user_id', $this->id)
            ->where('organisation_id', $organisationId)
            ->first();

        return $role?->role;
    }

    /**
     * Check if user has their OWN organisation (tenant, not platform)
     *
     * A user "owns" an organisation if they have a pivot record
     * and the organisation type is 'tenant'
     *
     * @return bool
     */
    public function hasOwnOrganisation(): bool
    {
        return $this->organisations()
            ->where('type', 'tenant')
            ->exists();
    }

    /**
     * Get user's own organisation (the first tenant org they belong to)
     *
     * @return Organisation|null
     */
    public function getOwnOrganisation(): ?Organisation
    {
        return $this->organisations()
            ->where('type', 'tenant')
            ->first();
    }

    /**
     * Check if user is the owner of a specific organisation
     * Stricter check - user must have role='owner'
     *
     * @param string $organisationId
     * @return bool
     */
    public function isOwnerOf(string $organisationId): bool
    {
        return $this->organisationRoles()
            ->where('organisation_id', $organisationId)
            ->where('role', 'owner')
            ->exists();
    }

    /**
     * Check if user has any active election they can vote in
     *
     * Conditions:
     * 1. User belongs to an organisation (via pivot)
     * 2. That organisation has an active election
     * 3. Election is within date range (start_date <= now <= end_date)
     * 4. User hasn't already voted in that election
     *
     * @return bool
     */
    public function hasActiveElection(): bool
    {
        return $this->getActiveElection() !== null;
    }

    /**
     * Get the first active election user can vote in
     *
     * @return Election|null
     */
    public function getActiveElection(): ?Election
    {
        // Get all organisations user belongs to (excluding platform)
        $orgIds = $this->organisations()
            ->where('type', 'tenant')
            ->pluck('organisations.id')
            ->toArray();

        if (empty($orgIds)) {
            return null;
        }

        // Find active REAL elections in those orgs (exclude demo elections)
        return Election::whereIn('organisation_id', $orgIds)
            ->where('status', 'active')
            ->where('type', 'real')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereDoesntHave('voterSlugs', function ($query) {
                $query->where('user_id', $this->id)
                    ->where('status', 'voted');
            })
            ->orderBy('start_date')
            ->first();
    }

    /**
     * Get count of active elections user can vote in
     *
     * @return int
     */
    public function countActiveElections(): int
    {
        $orgIds = $this->organisations()
            ->where('type', 'tenant')
            ->pluck('organisations.id')
            ->toArray();

        if (empty($orgIds)) {
            return 0;
        }

        return Election::whereIn('organisation_id', $orgIds)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereDoesntHave('voterSlugs', function ($query) {
                $query->where('user_id', $this->id)
                    ->where('status', 'voted');
            })
            ->count();
    }

    /**
     * Flush role cache when roles change
     *
     * @return void
     */
    public function flushRoleCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget("user_{$this->id}_dashboard_roles");
    }

    /**
     * Send the email verification notification using custom branded template.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            \Illuminate\Support\Carbon::now()->addMinutes(60),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        \Illuminate\Support\Facades\Mail::send(
            new \App\Mail\VerifyEmailMail($this, $verificationUrl)
        );
    }

    /**
     * Check if user has any tenant organisation
     */
    public function hasTenantOrganisation(): bool
    {
        return $this->organisations()
            ->where('type', 'tenant')
            ->exists();
    }

    /**
     * Get the organisation where user is owner (their "real" org)
     */
    public function getOwnedOrganisation(): ?Organisation
    {
        return $this->organisations()
            ->wherePivot('role', 'owner')
            ->where('type', 'tenant')
            ->first();
    }

    /**
     * Switch user's current organisation
     */
    public function switchToOrganisation(Organisation $org): void
    {
        if (!$this->belongsToOrganisation($org->id)) {
            throw new \Exception("Cannot switch to organisation you don't belong to");
        }

        $this->update(['organisation_id' => $org->id]);
    }

}
