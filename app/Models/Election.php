<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Post;
use App\Models\Candidacy;   
use App\Models\VoterRegistration;
use App\Models\Code;
use App\Models\Vote;
use App\Models\Result;  
/**
 * Election Model
 *
 * Represents an election event (demo or real).
 * Elections can be demo elections for testing or real elections for actual voting.
 */
class Election extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organisation_id', // Allow setting for tests and seeders
        'name',
        'slug',
        'description',
        'type',
        'start_date',
        'end_date',
        'is_active',
        'settings',
        'status', // Added for status field
    ];

    /**
     * The attributes that should be guarded from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the organisation this election belongs to
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Scope: Get elections for a specific organisation
     */
    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    // ============ EAGER LOADING SCOPES (OPTIMIZATION) ============

    /**
     * Load organisation relationship
     */
    public function scopeWithOrganisation($query)
    {
        return $query->with(['organisation' => function($q) {
            $q->select('id', 'name');
        }]);
    }

    /**
     * Load essential relationships for validation
     */
    public function scopeWithEssentialRelations($query)
    {
        return $query->select('id', 'name', 'organisation_id', 'type', 'status', 'end_date')
            ->with(['organisation' => function($q) {
                $q->select('id', 'name');
            }]);
    }

    /**
     * Get all posts for this election
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all candidacies for this election
     *
     * @return HasMany
     */
    public function candidacies(): HasMany
    {
        return $this->hasMany(Candidacy::class);
    }

    /**
     * Get all voter registrations for this election
     *
     * @return HasMany
     */
    public function voterRegistrations(): HasMany
    {
        return $this->hasMany(VoterRegistration::class);
    }

    /**
     * Get all verification codes for this election
     *
     * @return HasMany
     */
    public function codes(): HasMany
    {
        return $this->hasMany(Code::class);
    }

    /**
     * Get all votes for this election
     * For real elections, returns from votes table
     * For demo elections, returns from demo_votes table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes(): HasMany
    {
        // Use polymorphic approach based on election type
        if ($this->isDemo()) {
            return $this->hasManyThrough(
                DemoVote::class,
                VoterRegistration::class,
                'election_id',
                'user_id',
                'id',
                'user_id'
            );
        }

        return $this->hasManyThrough(
            Vote::class,
            VoterRegistration::class,
            'election_id',
            'user_id',
            'id',
            'user_id'
        );
    }

    /**
     * Get all results for this election
     * For real elections, returns from results table
     * For demo elections, returns from demo_results table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results(): HasMany
    {
        if ($this->isDemo()) {
            return $this->hasManyThrough(
                DemoResult::class,
                DemoVote::class,
                'election_id',
                'vote_id',
                'id',
                'id'
            );
        }

        return $this->hasManyThrough(
            Result::class,
            Vote::class,
            'election_id',
            'vote_id',
            'id',
            'id'
        );
    }

    /**
     * Get pending voters for this election
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function pendingVoters()
    {
        return $this->voterRegistrations()
            ->where('status', 'pending')
            ->with('user');
    }

    /**
     * Get approved voters for this election
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function approvedVoters()
    {
        return $this->voterRegistrations()
            ->where('status', 'approved')
            ->with('user');
    }

    /**
     * Get voters who have voted
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function votedVoters()
    {
        return $this->voterRegistrations()
            ->where('status', 'voted')
            ->with('user');
    }

    /**
     * Check if this is a demo election
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return $this->type === 'demo';
    }

    /**
     * Check if this is a real election
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return $this->type === 'real';
    }

    /**
     * Check if election is currently active
     *
     * @return bool
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        // If start_date is set and we haven't reached it, not active
        if ($this->start_date && $now < $this->start_date) {
            return false;
        }

        // If end_date is set and we've passed it, not active
        if ($this->end_date && $now > $this->end_date) {
            return false;
        }

        return true;
    }

    /**
     * Get pending voter count
     *
     * @return int
     */
    public function pendingVoterCount(): int
    {
        return $this->voterRegistrations()
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Get approved voter count
     *
     * @return int
     */
    public function approvedVoterCount(): int
    {
        return $this->voterRegistrations()
            ->where('status', 'approved')
            ->count();
    }

    /**
     * Get voted count
     *
     * @return int
     */
    public function votedCount(): int
    {
        return $this->voterRegistrations()
            ->where('status', 'voted')
            ->count();
    }

    /**
     * Get total votes cast in this election
     * Returns count from votes (real) or demo_votes (demo) table
     *
     * @return int
     */
    public function totalVotesCast(): int
    {
        if ($this->isDemo()) {
            return DemoVote::where('election_id', $this->id)->count();
        }

        return Vote::where('election_id', $this->id)->count();
    }

    /**
     * Get total verification codes for this election
     *
     * @return int
     */
    public function totalCodes(): int
    {
        return $this->codes()->count();
    }

    /**
     * Get verified codes count
     *
     * @return int
     */
    public function verifiedCodesCount(): int
    {
        return $this->codes()->verified()->count();
    }

    /**
     * Get unverified codes count
     *
     * @return int
     */
    public function unverifiedCodesCount(): int
    {
        return $this->codes()->unverified()->count();
    }

    /**
     * Get voter turnout percentage
     *
     * @return float|null
     */
    public function voterTurnout(): ?float
    {
        $approved = $this->approvedVoterCount();

        if ($approved === 0) {
            return null;
        }

        $voted = $this->votedCount();

        return ($voted / $approved) * 100;
    }

    /**
     * Get election summary statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'pending_voters' => $this->pendingVoterCount(),
            'approved_voters' => $this->approvedVoterCount(),
            'voted' => $this->votedCount(),
            'total_codes' => $this->totalCodes(),
            'verified_codes' => $this->verifiedCodesCount(),
            'total_votes_cast' => $this->totalVotesCast(),
            'turnout_percentage' => $this->voterTurnout(),
            'election_type' => $this->type,
            'is_active' => $this->isCurrentlyActive(),
        ];
    }
    // 
    
    

    
}
