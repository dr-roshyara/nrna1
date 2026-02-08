<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Candidacy;
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'name',
        'nepali_name',
        'state_name',
        'required_number',
        'position_order'
    ];

    /**
     * Get all candidates for this post WITH user relationship loaded
     * This ensures we can access candidate names from the User table
     */
    public function candidates(){
        return $this->hasMany(Candidacy::class, 'post_id', 'post_id')
                    ->with('user')
                    ->select([
                        'id',
                        'candidacy_id',
                        'user_id',
                        'post_id'
                    ]);
    }


    /**
     * Get all candidacies for this post WITH user relationship loaded
     */
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'post_id')
                    ->with('user')
                    ->select([
                        'id',
                        'candidacy_id',
                        'user_id',
                        'post_id'
                    ]);
    }
     /**
     * Get candidates with complete user information
     * Use this method when you need full candidate details
     */
    public function candidatesWithFullUser()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'post_id')
                    ->with(['user' => function($query) {
                        $query->select(['id', 'user_id', 'name', 'first_name', 'last_name', 'region', 'email']);
                    }]);
    }

    /**
     * Get all demo candidates for this post
     * Returns DemoCandidate models for demo election voting
     */
    public function demoCandidates()
    {
        return $this->hasMany(DemoCandidate::class, 'post_id', 'post_id')
                    ->with('user')
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
                        'image_path_3'
                    ]);
    }

}
