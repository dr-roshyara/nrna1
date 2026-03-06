<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use  Illuminate\Support\Facades\DB;
use App\Traits\BelongsToTenant;

class Code extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    use BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;
     protected $fillable = [
        'organisation_id',
        'user_id',
        'election_id', // ✅ CRITICAL: Election scoping for multi-election support
        'code1',
        'code2',
        'code3',
        'code4',
        'vote_show_code',
        'is_code1_usable',
        'is_code2_usable',
        'is_code3_usable',
        'is_code4_usable',
        'code1_sent_at',
        'code2_sent_at',
        'code3_sent_at',
        'code4_sent_at',
        'can_vote_now',
        'has_voted',
        'voting_time_in_minutes',
        'vote_last_seen',
        'code1_used_at',
        'code2_used_at',
        'code3_used_at',
        'code4_used_at',
        'code_for_vote',
        'vote_submitted',
        'vote_submitted_at',
        'has_code1_sent',
        'has_code2_sent',
        'client_ip',
        'has_agreed_to_vote',
        'has_used_code1',
        'has_used_code2',
        'session_name',
        'has_agreed_to_vote_at',
        'voting_started_at',
        'is_codemodel_valid' // ✅ Added for verification tracking
    ];

    protected $casts = [
        'has_code1_sent' => 'boolean',
        'is_code1_usable' => 'boolean',
        'is_code2_usable' => 'boolean',
        'is_code3_usable' => 'boolean',
        'is_code4_usable' => 'boolean',
        'can_vote_now' => 'boolean',
        'has_voted' => 'boolean',
        'vote_submitted' => 'boolean',
        'has_agreed_to_vote' => 'boolean',
        'has_used_code1' => 'boolean',
        'has_used_code2' => 'boolean',
        'is_codemodel_valid' => 'boolean',
        'code1_sent_at' => 'datetime',
        'code2_sent_at' => 'datetime',
        'code3_sent_at' => 'datetime',
        'code4_sent_at' => 'datetime',
        'code1_used_at' => 'datetime',
        'code2_used_at' => 'datetime',
        'code3_used_at' => 'datetime',
        'code4_used_at' => 'datetime',
        'vote_submitted_at' => 'datetime',
        'voting_started_at' => 'datetime',
        'has_agreed_to_vote_at' => 'datetime',
    ]; 
    /**
     * Get the organisation this code belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class)
                    ->withoutGlobalScopes();
    }

    /**
     * Get the user this code belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)
                    ->withoutGlobalScopes();
    }

    /**
     * Get the election this code is for
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function election()
    {
        return $this->belongsTo(Election::class)
                    ->withoutGlobalScopes();
    }

    /**
     * Scope: Get codes for a specific organisation
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $organisationId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->withoutGlobalScopes()
                     ->where('organisation_id', $organisationId);
    }

    /**
     * Scope: Get codes for a specific election
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|\App\Models\Election $election
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForElection($query, $election)
    {
        $electionId = is_string($election) ? $election : $election->id;
        return $query->withoutGlobalScopes()
                     ->where('election_id', $electionId);
    }

    /**
     * Scope: Get codes for demo election
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDemoElection($query)
    {
        // CRITICAL: Use withoutGlobalScopes() on the election query because demo elections
        // are accessible to ALL users regardless of organisation context
        return $query->whereHas('election', fn($q) => $q->withoutGlobalScopes()->where('type', 'demo'));
    }

    /**
     * Scope: Get codes for real election
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForRealElection($query)
    {
        return $query->whereHas('election', fn($q) => $q->where('type', 'real'));
    }

    /**
     * Scope: Get verified codes (can_vote_now = 1)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('can_vote_now', 1);
    }

    /**
     * Scope: Get unverified codes (can_vote_now = 0)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnverified($query)
    {
        return $query->where('can_vote_now', 0);
    }

    /**
     * Check if this code is verified
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return (bool) $this->can_vote_now;
    }

    /**
     * Scope: Get unused codes (both codes still usable)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnused($query)
    {
        return $query->withoutGlobalScopes()
                     ->where('is_code1_usable', true)
                     ->where('is_code2_usable', false);
    }

    /**
     * Mark code1 as used
     *
     * @return bool
     */
    public function useCode1(): bool
    {
        return $this->update([
            'is_code1_usable' => false,
            'code1_used_at' => now(),
        ]);
    }

    /**
     * Mark code2 as used
     *
     * @return bool
     */
    public function useCode2(): bool
    {
        return $this->update([
            'is_code2_usable' => false,
            'code2_used_at' => now(),
        ]);
    }

    /**
     * Check if code is still usable
     *
     * @return bool
     */
    public function isUsable(): bool
    {
        return $this->is_code1_usable || $this->is_code2_usable;
    }
}
