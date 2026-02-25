<?php

/**
 * Election Domain Structure with Separate ElectionUser Model
 * 
 * This structure keeps the main User model for authentication while
 * creating a separate ElectionUser model for election participants.
 */

// app/Domain/Election/Models/ElectionUser.php
<?php

namespace App\Domain\Election\Models;

use App\Models\ElectionAwareModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * ElectionUser Model - Election Domain Participant
 * 
 * Represents users within the election system. These are separate from
 * the main application users and exist within election-specific databases.
 * 
 * Key Features:
 * - Lives in election-specific database
 * - Contains election-specific user data
 * - Can be linked to main User but operates independently
 * - Has election-specific roles and permissions
 */
class ElectionUser extends ElectionAwareModel
{
    use HasFactory, Notifiable, HasRoles;
    
    protected $table = 'users'; // Uses 'users' table in election database
    
    protected $fillable = [
        'user_id',              // Link to main app User (optional)
        'facebook_id',
        'name',
        'email',
        'first_name',
        'middle_name', 
        'last_name',
        'gender',
        'region',
        'country',
        'state',
        'street',
        'housenumber',
        'postalcode',
        'city',
        'additional_address',
        'nrna_id',
        'telephone',
        'is_voter',
        'name_prefex',
        'approvedBy',
        'approved_at',
        'suspendedBy',
        'suspended_at',
        'has_candidacy',
        'lcc',
        'designation',
        'google_id',
        'social_id',
        'social_type',
        'is_committee_member',
        'committee_name',
        'user_ip',
        'voting_ip'
    ];
    
    protected $casts = [
        'is_voter' => 'boolean',
        'has_candidacy' => 'boolean',
        'is_committee_member' => 'boolean',
        'approved_at' => 'datetime',
        'suspended_at' => 'datetime'
    ];
    
    protected $hidden = [
        'password', // ElectionUser doesn't handle passwords
        'remember_token'
    ];
    
    /**
     * Relationship to main application user (if linked)
     */
    public function mainUser()
    {
        // This queries the main database, not election database
        return $this->belongsTo(\App\Models\User::class, 'user_id')
                    ->setConnection(config('database.default'));
    }
    
    /**
     * Election voting codes
     */
    public function code()
    {
        return $this->hasOne(Code::class, 'user_id');
    }
    
    /**
     * Votes cast by this election user
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'user_id');
    }
    
    /**
     * Candidacies for this election user
     */
    public function candidacies()
    {
        return $this->hasMany(Candidacy::class, 'user_id');
    }
    
    /**
     * Check if user can vote
     */
    public function canVote(): bool
    {
        return $this->is_voter && 
               !$this->suspended_at &&
               $this->approved_at;
    }
    
    /**
     * Check if user has already voted
     */
    public function hasVoted(): bool
    {
        return $this->votes()->exists();
    }
    
    /**
     * Get voter status
     */
    public function getVoterStatus(): string
    {
        if ($this->suspended_at) {
            return 'suspended';
        }
        
        if (!$this->is_voter) {
            return 'not_voter';
        }
        
        if (!$this->approved_at) {
            return 'pending_approval';
        }
        
        if ($this->hasVoted()) {
            return 'voted';
        }
        
        return 'approved';
    }
    
    /**
     * Approve this user as a voter
     */
    public function approveAsVoter(string $approverName, string $votingIp): bool
    {
        return $this->update([
            'is_voter' => true,
            'approvedBy' => $approverName,
            'approved_at' => now(),
            'voting_ip' => $votingIp
        ]);
    }
    
    /**
     * Suspend this voter
     */
    public function suspend(string $suspendedBy, string $reason): bool
    {
        return $this->update([
            'suspendedBy' => $suspendedBy,
            'suspended_at' => now(),
            'suspension_reason' => $reason
        ]);
    }
    
    /**
     * Create or sync from main User
     */
    public static function createFromMainUser(\App\Models\User $mainUser, array $additionalData = []): self
    {
        return static::create(array_merge([
            'user_id' => $mainUser->id,
            'name' => $mainUser->name,
            'email' => $mainUser->email,
            'region' => $mainUser->region ?? null,
        ], $additionalData));
    }
}





// Directory Structure
/**
 * Recommended Directory Structure:
 * 
 * app/
 * ├── Models/
 * │   ├── User.php (main app user - keep unchanged)
 * │   ├── Election.php (main app election management)
 * │   └── ElectionAwareModel.php (base class)
 * ├── Domain/
 * │   └── Election/
 * │       ├── Models/
 * │       │   ├── ElectionUser.php
 * │       │   ├── Code.php  
 * │       │   ├── Vote.php
 * │       │   ├── Post.php
 * │       │   ├── Candidacy.php
 * │       │   └── Result.php
 * │       └── Services/
 * │           ├── ElectionUserService.php
 * │           ├── ElectionContextService.php
 * │           └── ElectionDatabaseService.php
 * ├── Services/
 * │   └── (other non-election services)
 * └── Http/Controllers/
 *     ├── (existing controllers - update to use ElectionUser)
 *     └── (main app controllers use regular User)
 */