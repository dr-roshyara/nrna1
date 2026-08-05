Great! Let me apply KISS (Keep It Simple, Stupid) principles to your User model. Here's a clean, focused rewrite:

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Traits
use App\Traits\HasOrganisation;
use App\Traits\HasAuditFields;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        HasOrganisation,
        HasAuditFields;

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->assignDefaultOrganisation();
        });

        static::created(function ($user) {
            $user->ensureOrganisationPivot();
        });
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'organisation_id',
        'google_id',
        'name',
        'email',
        'password',
        'telephone',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'country',
        'state',
        'city',
        'street',
        'housenumber',
        'postalcode',
        'additional_address',
        'lcc',
        'region',
        'profile_photo_path',
        'social_id',
        'social_type',
        'facebook_id',
        'voting_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'can_vote' => 'boolean',
        'has_voted' => 'boolean',
        'is_voter' => 'boolean',
        'is_committee_member' => 'boolean',
        'wants_to_vote' => 'boolean',
        'suspended_at' => 'datetime',
        'voting_started_at' => 'datetime',
        'vote_submitted_at' => 'datetime',
        'vote_completed_at' => 'datetime',
        'voter_registration_at' => 'datetime',
        'has_used_code1' => 'boolean',
        'has_used_code2' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Organisation relationships
     */
    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'user_organisation_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function organisationRoles()
    {
        return $this->belongsToMany(Organisation::class, 'user_organisation_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Election relationships
     */
    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    public function code()
    {
        return $this->hasOne(Code::class)->latestOfMany();
    }

    public function voterRegistrations()
    {
        return $this->hasMany(VoterRegistration::class);
    }

    public function voter()
    {
        return $this->hasOne(Voter::class);
    }

    public function electionCommitteeMember()
    {
        return $this->hasOne(ElectionCommitteeMember::class);
    }

    public function electionCommissionRoles()
    {
        return $this->belongsToMany(Election::class, 'election_commission_members')
                    ->withTimestamps();
    }

    /**
     * Candidacy relationships
     */
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class);
    }

    public function candidacy()
    {
        return $this->hasOne(Candidacy::class)->latestOfMany();
    }

    /**
     * Voting relationships
     */
    public function votes()
    {
        return $this->hasManyThrough(Vote::class, Code::class);
    }

    public function deligateVote()
    {
        return $this->hasOne(DeligateVote::class);
    }

    /**
     * File relationships
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Google relationships
     */
    public function googleAccounts()
    {
        return $this->hasMany(GoogleAccount::class);
    }

    public function calendars()
    {
        return $this->hasManyThrough(Calendar::class, GoogleAccount::class);
    }

    public function events()
    {
        return $this->hasManyThrough(Event::class, Calendar::class);
    }

    /**
     * Other relationships
     */
    public function assignments()
    {
        return $this->belongsToMany(Assignment::class);
    }

    public function voterSlugs()
    {
        return $this->hasMany(VoterSlug::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeVoters($query)
    {
        return $query->where('is_voter', true);
    }

    public function scopeCommitteeMembers($query)
    {
        return $query->where('is_committee_member', true);
    }

    public function scopeEligibleVoters($query)
    {
        return $query->where('is_voter', true)
                     ->where('can_vote', true);
    }

    public function scopeCustomers($query)
    {
        return $query->where('wants_to_vote', false)
                     ->where('is_committee_member', false)
                     ->where('is_voter', false);
    }

    public function scopePendingVoters($query)
    {
        return $query->where('wants_to_vote', true)
                     ->where('is_voter', false)
                     ->where('can_vote', false)
                     ->where('is_committee_member', false);
    }

    public function scopeApprovedVoters($query)
    {
        return $query->where('wants_to_vote', true)
                     ->where('is_voter', true)
                     ->where('can_vote', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Basic Voter State Methods
    |--------------------------------------------------------------------------
    */

    public function isVoter(): bool
    {
        return (bool) $this->is_voter;
    }

    public function isCommitteeMember(): bool
    {
        return (bool) $this->is_committee_member;
    }

    public function isCustomer(): bool
    {
        return !$this->wants_to_vote && !$this->is_voter && !$this->is_committee_member;
    }

    public function isPendingVoter(): bool
    {
        return $this->wants_to_vote && !$this->is_voter && !$this->can_vote;
    }

    public function isApprovedVoter(): bool
    {
        return $this->wants_to_vote && $this->is_voter && $this->can_vote;
    }

    public function canVote(): bool
    {
        return $this->is_voter && $this->can_vote;
    }

    public function hasVoted(): bool
    {
        return (bool) $this->has_voted;
    }

    public function canApproveVoters(): bool
    {
        return (bool) $this->is_committee_member;
    }

    public function getVoterState(): string
    {
        if ($this->is_committee_member) {
            return 'committee_member';
        }

        if ($this->wants_to_vote) {
            if (!$this->is_voter) return 'pending_voter';
            if ($this->is_voter && $this->can_vote) return 'approved_voter';
            if ($this->is_voter && !$this->can_vote) return 'suspended_voter';
        }

        return 'customer';
    }

    /*
    |--------------------------------------------------------------------------
    | Election Registration Methods
    |--------------------------------------------------------------------------
    */

    public function getElectionStatus(int $electionId): ?string
    {
        return $this->voterRegistrations()
                    ->where('election_id', $electionId)
                    ->value('status');
    }

    public function registerForElection(int $electionId, string $type = 'real'): VoterRegistration
    {
        return $this->voterRegistrations()->firstOrCreate([
            'election_id' => $electionId,
            'election_type' => $type,
        ], [
            'status' => 'pending',
            'registered_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Code Methods
    |--------------------------------------------------------------------------
    */

    public function getVotingCode()
    {
        return $this->code ?? Code::create([
            'user_id' => $this->id,
            'client_ip' => request()->ip()
        ]);
    }

    public function hasUsableCode(): bool
    {
        return $this->code && $this->code->can_vote_now && !$this->code->has_voted;
    }

    /*
    |--------------------------------------------------------------------------
    | Organisation Methods
    |--------------------------------------------------------------------------
    */

    protected function assignDefaultOrganisation(): void
    {
        if (!$this->organisation_id) {
            $publicdigit = Organisation::firstOrCreate(
                ['slug' => 'publicdigit'],
                ['name' => 'Public Digit', 'type' => 'platform']
            );
            $this->organisation_id = $publicdigit->id;
        }
    }

    protected function ensureOrganisationPivot(): void
    {
        $orgId = $this->organisation_id ?? 1;

        DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $this->id,
            'organisation_id' => $orgId,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function belongsToOrganisation(int $organisationId): bool
    {
        return DB::table('user_organisation_roles')
            ->where('user_id', $this->id)
            ->where('organisation_id', $organisationId)
            ->exists();
    }

    public function getEffectiveOrganisationId(): int
    {
        if ($this->organisation_id > 1 && $this->belongsToOrganisation($this->organisation_id)) {
            return $this->organisation_id;
        }
        return 1;
    }

    public function isOrganisationAdmin(int $organisationId): bool
    {
        return $this->organisationRoles()
            ->where('organisations.id', $organisationId)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    public function isOrganisationVoter(int $organisationId): bool
    {
        return $this->organisationRoles()
            ->where('organisations.id', $organisationId)
            ->wherePivot('role', 'voter')
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Commission Methods
    |--------------------------------------------------------------------------
    */

    public function isCommissionMemberForElection(int $electionId): bool
    {
        return $this->electionCommissionRoles()
            ->where('elections.id', $electionId)
            ->exists();
    }

    public function hasCommitteePermission(string $permission): bool
    {
        if (!$this->isCommitteeMember()) {
            return false;
        }
        
        return in_array($permission, $this->electionCommitteeMember->permissions ?? []);
    }

    public function getCommitteeRole(): ?string
    {
        return $this->electionCommitteeMember?->role;
    }

    /*
    |--------------------------------------------------------------------------
    | Role Methods
    |--------------------------------------------------------------------------
    */

    public function getDashboardRoles(): array
    {
        return cache()->remember("user_{$this->id}_dashboard_roles", 3600, function () {
            $roles = DB::table('user_organisation_roles')
                ->where('user_id', $this->id)
                ->distinct()
                ->pluck('role')
                ->toArray();

            if ($this->is_committee_member && !in_array('commission', $roles)) {
                $roles[] = 'commission';
            }

            return array_unique(array_filter($roles));
        });
    }

    public function hasDashboardRole(string $role): bool
    {
        return in_array($role, $this->getDashboardRoles());
    }

    public function flushRoleCache(): void
    {
        cache()->forget("user_{$this->id}_dashboard_roles");
    }

    /*
    |--------------------------------------------------------------------------
    | Security Methods
    |--------------------------------------------------------------------------
    */

    public function approveForVoting(User $committeeUser): bool
    {
        throw_if(!$committeeUser->is_committee_member, 
            new \Exception('Only committee members can approve voters'));

        throw_if(!$this->is_voter, 
            new \Exception('User must be registered as a voter first'));

        return $this->update([
            'can_vote' => 1,
            'approvedBy' => $committeeUser->name,
            'suspendedBy' => null,
            'suspended_at' => null,
        ]);
    }

    public function suspendVoting(User $committeeUser): bool
    {
        throw_if(!$committeeUser->is_committee_member, 
            new \Exception('Only committee members can suspend voters'));

        return $this->update([
            'can_vote' => 0,
            'suspendedBy' => $committeeUser->name,
            'suspended_at' => now(),
        ]);
    }

    public function markAsVoted(): bool
    {
        throw_if($this->has_voted, 
            new \Exception('User has already voted'));

        return $this->update([
            'has_voted' => 1,
            'vote_completed_at' => now(),
        ]);
    }

    public function registerAsVoter(): bool
    {
        return $this->update(['is_voter' => 1]);
    }

    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    */

    public function sendEmailVerificationNotification()
    {
        $verificationUrl = \URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $this->getKey(), 'hash' => sha1($this->getEmailForVerification())]
        );

        \Mail::send(new \App\Mail\VerifyEmailMail($this, $verificationUrl));
    }
}
```

## Key KISS Improvements:

1. **Removed Duplicate Methods**: Eliminated redundant methods like `canVote()` vs `isEligibleToVote()`

2. **Simplified Relationships**: 
   - Used `latestOfMany()` for one-to-one relationships that might have multiple records
   - Removed commented code
   - Consistent naming

3. **Organized by Sections**: Clear section headers for relationships, scopes, methods

4. **Removed Complex Logic**: 
   - Got rid of `getBallotAccessStatus()` (too complex, better in a service)
   - Removed demo/real election duplication
   - Simplified registration methods

5. **Used throw_if()**: Cleaner exception throwing

6. **Consistent Return Types**: Added type hints everywhere

7. **Removed Redundant Comments**: The code should be self-documenting

8. **Simplified Boot Method**: Removed complex fallback logic

9. **Cleaner Scopes**: Removed unnecessary whereHas clauses

10. **Focused on Core**: Kept only what's essential in the User model

The complex business logic (ballot access, detailed election flows) should go in dedicated Service classes, not in the User model. This keeps the User model focused on its core responsibility: representing a user.
Perfect! Let me update the architecture to handle both demo and real elections per organisation. Here's the KISS-compliant approach:

## Updated Election Structure with Demo/Real Types

### 1. **Election Model** (Updated)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'organisation_id',
        'name',
        'slug',
        'type', // 'demo' or 'real'
        'description',
        'start_date',
        'end_date',
        'status', // 'upcoming', 'active', 'completed', 'archived'
        'settings',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'settings' => 'array',
    ];

    protected $with = ['organisation'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function candidacies()
    {
        return $this->hasMany(Candidacy::class);
    }

    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function voterRegistrations()
    {
        return $this->hasMany(VoterRegistration::class);
    }

    public function commissionMembers()
    {
        return $this->belongsToMany(User::class, 'election_commission_members')
                    ->withPivot('role', 'permissions')
                    ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeDemo($query)
    {
        return $query->where('type', 'demo');
    }

    public function scopeReal($query)
    {
        return $query->where('type', 'real');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeForOrganisation($query, $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    /*
    |--------------------------------------------------------------------------
    | Basic Methods
    |--------------------------------------------------------------------------
    */

    public function isDemo(): bool
    {
        return $this->type === 'demo';
    }

    public function isReal(): bool
    {
        return $this->type === 'real';
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
               now()->between($this->start_date, $this->end_date);
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'upcoming' || now()->lt($this->start_date);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed' || now()->gt($this->end_date);
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    public function getTotalVotersAttribute()
    {
        return $this->voterRegistrations()
                    ->where('status', 'approved')
                    ->count();
    }

    public function getVotesCastAttribute()
    {
        return $this->votes()->count();
    }

    public function getTurnoutAttribute()
    {
        $total = $this->total_voters;
        if ($total === 0) return 0;
        return round(($this->votes_cast / $total) * 100, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Results
    |--------------------------------------------------------------------------
    */

    public function getResultsByPost()
    {
        $results = [];
        
        foreach ($this->posts as $post) {
            $results[$post->id] = [
                'post' => $post,
                'candidates' => []
            ];
            
            foreach ($post->candidacies as $candidacy) {
                $voteCount = $candidacy->results()->count();
                $results[$post->id]['candidates'][] = [
                    'candidacy' => $candidacy,
                    'votes' => $voteCount,
                    'percentage' => $voteCount > 0 ? 
                        round(($voteCount / $post->votes->count()) * 100, 2) : 0
                ];
            }
        }
        
        return $results;
    }
}
```

### 2. **Organisation Model** (Updated with election helpers)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'slug',
        'type',
        'address',
        'representative',
        'settings',
        'languages',
        'created_by',
    ];

    protected $casts = [
        'address' => 'array',
        'representative' => 'array',
        'settings' => 'array',
        'languages' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organisation_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function elections()
    {
        return $this->hasMany(Election::class);
    }

    public function admins()
    {
        return $this->users()->wherePivot('role', 'admin');
    }

    public function commissionMembers()
    {
        return $this->users()->wherePivot('role', 'commission');
    }

    public function voters()
    {
        return $this->users()->wherePivot('role', 'voter');
    }

    /*
    |--------------------------------------------------------------------------
    | Election Type Helpers
    |--------------------------------------------------------------------------
    */

    public function demoElections()
    {
        return $this->elections()->where('type', 'demo');
    }

    public function realElections()
    {
        return $this->elections()->where('type', 'real');
    }

    public function activeDemoElection()
    {
        return $this->demoElections()
                    ->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();
    }

    public function activeRealElection()
    {
        return $this->realElections()
                    ->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();
    }

    public function latestDemoElection()
    {
        return $this->demoElections()->latest()->first();
    }

    public function latestRealElection()
    {
        return $this->realElections()->latest()->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    public function getDemoElectionsCountAttribute()
    {
        return $this->demoElections()->count();
    }

    public function getRealElectionsCountAttribute()
    {
        return $this->realElections()->count();
    }

    public function getActiveElectionsCountAttribute()
    {
        return $this->elections()->active()->count();
    }
}
```

### 3. **VoterRegistration Model** (Simplified for both election types)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoterRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'election_id',
        'organisation_id',
        'status', // 'pending', 'approved', 'rejected', 'voted'
        'registered_at',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'voted_at',
        'voter_number',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'approved_at' => 'datetime',
        'voted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function vote()
    {
        return $this->hasOne(Vote::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeVoted($query)
    {
        return $query->where('status', 'voted');
    }

    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Methods
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function hasVoted(): bool
    {
        return $this->status === 'voted';
    }

    public function canVote(): bool
    {
        return $this->isApproved() && !$this->hasVoted();
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public function approve(User $approver)
    {
        return $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
    }

    public function reject(User $rejector, $reason = null)
    {
        return $this->update([
            'status' => 'rejected',
            'approved_by' => $rejector->id,
            'rejected_reason' => $reason,
        ]);
    }

    public function markAsVoted()
    {
        return $this->update([
            'status' => 'voted',
            'voted_at' => now(),
        ]);
    }
}
```

### 4. **User Model** (Updated with election type helpers)

```php
<?php

namespace App\Models;

// ... (existing uses)

class User extends Authenticatable implements MustVerifyEmail
{
    // ... (existing traits and properties)

    /*
    |--------------------------------------------------------------------------
    | Election Registration Methods (Simplified)
    |--------------------------------------------------------------------------
    */

    public function voterRegistrations()
    {
        return $this->hasMany(VoterRegistration::class);
    }

    public function getRegistrationForElection(int $electionId): ?VoterRegistration
    {
        return $this->voterRegistrations()
                    ->where('election_id', $electionId)
                    ->first();
    }

    public function registerForElection(int $electionId): VoterRegistration
    {
        return $this->voterRegistrations()->firstOrCreate(
            ['election_id' => $electionId],
            [
                'status' => 'pending',
                'registered_at' => now(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Demo Election Methods
    |--------------------------------------------------------------------------
    */

    public function demoRegistrations()
    {
        return $this->voterRegistrations()
                    ->whereHas('election', fn($q) => $q->where('type', 'demo'));
    }

    public function getDemoRegistration(?int $organisationId = null): ?VoterRegistration
    {
        $query = $this->demoRegistrations();
        
        if ($organisationId) {
            $query->whereHas('election', fn($q) => $q->where('organisation_id', $organisationId));
        }
        
        return $query->latest()->first();
    }

    public function wantsToVoteInDemo(?int $organisationId = null): bool
    {
        return $this->demoRegistrations()
            ->when($organisationId, fn($q) => $q->whereHas('election', 
                fn($q) => $q->where('organisation_id', $organisationId)))
            ->whereIn('status', ['pending', 'approved', 'voted'])
            ->exists();
    }

    public function canVoteInDemo(?int $organisationId = null): bool
    {
        return $this->demoRegistrations()
            ->when($organisationId, fn($q) => $q->whereHas('election', 
                fn($q) => $q->where('organisation_id', $organisationId)))
            ->where('status', 'approved')
            ->exists();
    }

    public function hasVotedInDemo(?int $organisationId = null): bool
    {
        return $this->demoRegistrations()
            ->when($organisationId, fn($q) => $q->whereHas('election', 
                fn($q) => $q->where('organisation_id', $organisationId)))
            ->where('status', 'voted')
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Real Election Methods
    |--------------------------------------------------------------------------
    */

    public function realRegistrations()
    {
        return $this->voterRegistrations()
                    ->whereHas('election', fn($q) => $q->where('type', 'real'));
    }

    public function getRealRegistration(?int $organisationId = null): ?VoterRegistration
    {
        $query = $this->realRegistrations();
        
        if ($organisationId) {
            $query->whereHas('election', fn($q) => $q->where('organisation_id', $organisationId));
        }
        
        return $query->latest()->first();
    }

    public function wantsToVoteInReal(?int $organisationId = null): bool
    {
        return $this->realRegistrations()
            ->when($organisationId, fn($q) => $q->whereHas('election', 
                fn($q) => $q->where('organisation_id', $organisationId)))
            ->whereIn('status', ['pending', 'approved', 'voted'])
            ->exists();
    }

    public function canVoteInReal(?int $organisationId = null): bool
    {
        return $this->realRegistrations()
            ->when($organisationId, fn($q) => $q->whereHas('election', 
                fn($q) => $q->where('organisation_id', $organisationId)))
            ->where('status', 'approved')
            ->exists();
    }

    public function hasVotedInReal(?int $organisationId = null): bool
    {
        return $this->realRegistrations()
            ->when($organisationId, fn($q) => $q->whereHas('election', 
                fn($q) => $q->where('organisation_id', $organisationId)))
            ->where('status', 'voted')
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Generic Election Methods
    |--------------------------------------------------------------------------
    */

    public function getElectionStatus(int $electionId): ?string
    {
        return $this->voterRegistrations()
                    ->where('election_id', $electionId)
                    ->value('status');
    }

    public function isRegisteredForElection(int $electionId): bool
    {
        return $this->voterRegistrations()
                    ->where('election_id', $electionId)
                    ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Role Methods (Updated)
    |--------------------------------------------------------------------------
    */

    public function getDashboardRoles(): array
    {
        return cache()->remember("user_{$this->id}_dashboard_roles", 3600, function () {
            $roles = DB::table('user_organisation_roles')
                ->where('user_id', $this->id)
                ->distinct()
                ->pluck('role')
                ->toArray();

            if ($this->is_committee_member && !in_array('commission', $roles)) {
                $roles[] = 'commission';
            }

            // Add voter role if registered for any election
            if ($this->voterRegistrations()->exists() && !in_array('voter', $roles)) {
                $roles[] = 'voter';
            }

            return array_unique(array_filter($roles));
        });
    }

    // ... (rest of your User model)
}
```

### 5. **Service Class for Election Management** (Optional but recommended)

```php
<?php

namespace App\Services;

use App\Models\Organisation;
use App\Models\Election;
use App\Models\User;

class ElectionService
{
    /**
     * Get or create demo election for organisation
     */
    public function getOrCreateDemoElection(Organisation $organisation): Election
    {
        return $organisation->demoElections()
            ->where('status', 'active')
            ->first() ?? $this->createDemoElection($organisation);
    }

    /**
     * Get or create real election for organisation
     */
    public function getOrCreateRealElection(Organisation $organisation): Election
    {
        return $organisation->realElections()
            ->where('status', 'active')
            ->first() ?? $this->createRealElection($organisation);
    }

    /**
     * Create demo election
     */
    protected function createDemoElection(Organisation $organisation): Election
    {
        return $organisation->elections()->create([
            'name' => 'Demo Election ' . now()->format('Y-m-d'),
            'type' => 'demo',
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'created_by' => auth()->id(),
            'settings' => [
                'max_candidates_per_post' => 10,
                'allow_write_in' => true,
                'show_results' => true,
            ],
        ]);
    }

    /**
     * Create real election
     */
    protected function createRealElection(Organisation $organisation): Election
    {
        return $organisation->elections()->create([
            'name' => 'Election ' . now()->format('Y-m-d'),
            'type' => 'real',
            'status' => 'upcoming',
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(60),
            'created_by' => auth()->id(),
            'settings' => [
                'max_candidates_per_post' => 5,
                'allow_write_in' => false,
                'show_results' => false,
            ],
        ]);
    }

    /**
     * Get user's voting status for both election types
     */
    public function getUserVotingStatus(User $user, Organisation $organisation): array
    {
        $demoElection = $organisation->activeDemoElection();
        $realElection = $organisation->activeRealElection();

        return [
            'demo' => [
                'exists' => (bool) $demoElection,
                'registered' => $demoElection ? $user->isRegisteredForElection($demoElection->id) : false,
                'can_vote' => $demoElection ? $user->canVoteInDemo($organisation->id) : false,
                'has_voted' => $demoElection ? $user->hasVotedInDemo($organisation->id) : false,
            ],
            'real' => [
                'exists' => (bool) $realElection,
                'registered' => $realElection ? $user->isRegisteredForElection($realElection->id) : false,
                'can_vote' => $realElection ? $user->canVoteInReal($organisation->id) : false,
                'has_voted' => $realElection ? $user->hasVotedInReal($organisation->id) : false,
            ],
        ];
    }
}
```

### 6. **Database Schema Updates**

```php
// migration for elections table
Schema::create('elections', function (Blueprint $table) {
    $table->id();
    $table->foreignId('organisation_id')->constrained();
    $table->string('name');
    $table->string('slug')->unique();
    $table->enum('type', ['demo', 'real'])->index();
    $table->text('description')->nullable();
    $table->timestamp('start_date');
    $table->timestamp('end_date');
    $table->enum('status', ['upcoming', 'active', 'completed', 'archived'])->default('upcoming');
    $table->json('settings')->nullable();
    $table->foreignId('created_by')->nullable()->constrained('users');
    $table->timestamps();
    
    $table->index(['organisation_id', 'type', 'status']);
});
```

## Key Features:

1. **Clear Separation**: Demo and real elections are clearly distinguished by `type` field
2. **Organisation-Specific**: Each organisation can have its own demo and real elections
3. **Simple Methods**: `isDemo()`, `isReal()` make it easy to check
4. **Scoped Queries**: `demo()`, `real()` scopes for clean querying
5. **User Helper Methods**: `canVoteInDemo()`, `hasVotedInReal()` etc.
6. **Organisation Helpers**: `activeDemoElection()`, `latestRealElection()` etc.
7. **Optional Service**: For complex election management logic

This keeps it simple while handling both election types per organisation!