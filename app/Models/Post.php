<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Candidacy;
class Post extends Model
{
    use HasFactory;

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
                        'post_id',
                        'name',         // Backup name field
                        'user_name',    // Backup name field
                        'proposer_name',
                        'supporter_name',
                        'image_path_1',
                        'image_path_2',
                        'image_path_3'
                    ]);
    }


    /**
     * Get all candidacies for this post WITH user relationship loaded
     */
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class, 'post_id', 'post_id')
                    ->with('user')  // ✅ LOAD USER RELATIONSHIP
                    ->select([
                        'id',
                        'candidacy_id',
                        'user_id',      // ✅ NEED THIS FOR RELATIONSHIP
                        'post_id',
                        'name',         // Backup name field
                        'user_name',    // Backup name field
                        'proposer_name',
                        'supporter_name',
                        'image_path_1',
                        'image_path_2', 
                        'image_path_3'
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

}
