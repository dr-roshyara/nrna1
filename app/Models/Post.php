<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Candidacy;
use App\Models\Vote;
use App\Models\Result;
use App\Traits\BelongsToTenant;

class Post extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'election_id',
        'post_id',
        'name',
        'nepali_name',
        'state_name',
        'required_number',
        'position_order',
        'organisation_id'
    ];

    /**
     * Get the election that this post belongs to
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get all candidates for this post WITH user relationship loaded
     * This ensures we can access candidate names from the User table
     * Ordered by position_order for consistent display
     */
    public function candidates(){
        return $this->hasMany(Candidacy::class, 'post_id', 'id')
                    ->with('user')
                    ->orderBy('position_order')
                    ->select([
                        'id',
                        'candidacy_id',
                        'user_id',
                        'post_id',
                        'position_order'
                    ]);
    }


    /**
     * Get all candidacies for this post WITH user relationship loaded
     * Ordered by position_order for consistent display
     */
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'id')
                    ->with('user')
                    ->orderBy('position_order')
                    ->select([
                        'id',
                        'candidacy_id',
                        'user_id',
                        'post_id',
                        'position_order'
                    ]);
    }

    /**
     * Get approved candidacies for this post
     */
    public function approvedCandidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'id')
                    ->where('status', 'approved')
                    ->with('user')
                    ->orderBy('position_order');
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
