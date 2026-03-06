<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Candidacy;
use App\Models\Vote;
use App\Models\Result;
use App\Traits\BelongsToTenant;

class Post extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    use BelongsToTenant;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'election_id',
        'name',
        'nepali_name',
        'is_national_wide',
        'state_name',
        'required_number',
        'position_order',
    ];

    /**
     * Scope: Get posts for a specific organisation
     */
    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->withoutGlobalScopes()
                     ->where('organisation_id', $organisationId);
    }

    /**
     * Scope: Get posts for a specific election
     */
    public function scopeForElection($query, string $electionId)
    {
        return $query->withoutGlobalScopes()
                     ->where('election_id', $electionId);
    }

    /**
     * Get the organisation that owns this post
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the election that this post belongs to
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get all candidacies for this post WITH user relationship loaded
     * Ordered by position_order for consistent display
     */
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'id')
                    ->orderBy('position_order')
                    ->withoutGlobalScopes();
    }

    /**
     * Get approved candidacies for this post
     */
    public function approvedCandidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'id')
                    ->where('status', 'approved')
                    ->orderBy('position_order')
                    ->withoutGlobalScopes();
    }

    /**
     * Get votes for this post through results table
     */
    public function votes()
    {
        return $this->belongsToMany(Vote::class, 'results', 'post_id', 'vote_id')
                    ->withPivot('candidate_id')
                    ->withTimestamps();
    }

    /**
     * Get results for this post
     */
    public function results()
    {
        return $this->hasMany(Result::class, 'post_id', 'id');
    }
     /**
     * Get candidates with complete user information
     * Use this method when you need full candidate details
     */
    public function candidatesWithFullUser()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'id')
                    ->with(['user' => function($query) {
                        $query->select(['id', 'user_id', 'name', 'first_name', 'last_name', 'region', 'email']);
                    }]);
    }

    /**
     * Get all demo candidates for this post
     * Returns DemoCandidacy models for demo election voting
     * Ordered by position_order for consistent display
     */
    public function demoCandidates()
    {
        return $this->hasMany(DemoCandidacy::class, 'post_id', 'id')
                    ->with('user')
                    ->orderBy('position_order')
                    ->select([
                        'id',
                        'candidacy_id',
                        'user_id',
                        'post_id',
                        'user_name',
                        'candidacy_name',
                        'proposer_name',
                        'supporter_name',
                        'image_path_1',
                        'image_path_2',
                        'image_path_3',
                        'position_order'
                    ]);
    }

}
