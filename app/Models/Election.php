<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'voting_start_time',
        'voting_end_time',
        'status',
        'results_verified',
        'results_verified_at',
        'verified_by',
        'authorization_started',
        'authorization_started_at',
        'authorization_session_id',
        'authorization_deadline',
        'authorization_complete',
        'authorization_completed_at',
        'results_published',
        'results_published_at',
        'published_by',
        'publication_summary',
        'phase',
        

    ];

    protected $casts = [
        'voting_start_time' => 'datetime',
        'voting_end_time' => 'datetime',
        'results_verified' => 'boolean',
        'results_verified_at' => 'datetime',
        'authorization_started' => 'boolean',
        'authorization_started_at' => 'datetime',
        'authorization_deadline' => 'datetime',
        'authorization_complete' => 'boolean',
        'authorization_completed_at' => 'datetime',
        'results_published' => 'boolean',
        'results_published_at' => 'datetime',
        'publication_summary' => 'array',
        'phase' => 'string', 
    ];

    // Add this method to your Election.php model

    /**
     * 🎯 MASTER METHOD: Determines if results can be viewed
     * This is your SINGLE SOURCE OF TRUTH for the seal/unseal system
     */
    public function canViewResults(): bool
    {
        
        // Emergency override (committee can force publication)
        if (Setting::isEnabled('results_published')) {
            return true;
        }

        $emergencyOverride = Setting::where('key', 'results_published')
            ->where('value', 'emergency_override')
            ->exists();
        
        if ($emergencyOverride) {
            return true;
        }
        
        // Phase-based logic (your seal/unseal system)
        switch ($this->getCurrentPhase()) {
            case 'sealed':
                return false;  // Before election: results sealed
            
            case 'voting':
                return false;  // During election: results locked
            
            case 'unsealing':
                return false;  // After election: waiting for publishers to unseal
            
            case 'published':
                // Must have proper authorization completion
                return $this->results_published && $this->authorization_complete;
            
            default:
                return false;
        }
    }

    // Add to existing Election model:

       /**
         * Enhanced startSealing method
         */
        public function startSealing(): bool
        {
            if ($this->getCurrentPhase() !== 'sealed') {
                $this->update(['phase' => 'sealed']);
            }

            $result = $this->startAuthorization();
            
            if ($result['success']) {
                Log::info('Sealing process started', [
                    'election_id' => $this->id,
                    'session_id' => $result['session_id']
                ]);
                return true;
            }
            
            return false;
        }
        /**
         * Complete sealing and enable voting
         */
        public function completeSealingProcess(): bool
        {
            $this->update([
                'phase' => 'voting',
                'authorization_complete' => true,
                'authorization_completed_at' => now(),
                'status' => 'active', // Enable voting system
            ]);
            
            Log::info('Sealing completed - Voting system activated', [
                'election_id' => $this->id,
                'phase_transition' => 'sealed → voting'
            ]);
            
            return true;
        }

        public function startVoting(): bool  
        {
            // Reset for unsealing phase
            Publisher::where('should_agree', true)->update(['agreed' => false]);
            $this->update(['phase' => 'voting']);
            return true;
        }

        public function startUnsealing(): bool
        {
            // Reuse existing authorization logic
            $this->update(['phase' => 'unsealing']);
            return $this->startAuthorization(); // Same code!
        }
        public function getCurrentPhase(): string
        {
            // Use actual phase column instead of calculated logic
            return $this->phase ?? 'sealed';
        }

  
   
    /**
     * Relationship: Election has many result authorizations
     */
    public function resultAuthorizations()
    {
        return $this->hasMany(ResultAuthorization::class);
    }

    /**
     * Relationship: Election verified by user
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Relationship: Election published by user
     */
    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Get current active election
     */
    public static function current()
    {
        return Cache::remember('current_election', 300, function () {
            return self::where('status', 'active')
                ->orWhere(function($query) {
                    $query->where('voting_start_time', '<=', now())
                          ->where('voting_end_time', '>=', now());
                })
                ->latest()
                ->first();
        });
    }

    /**
     * Check if election is currently active for voting
     */
    public function isVotingActive(): bool
    {
        $now = now();
        return $this->voting_start_time <= $now && 
               $this->voting_end_time >= $now &&
               $this->status === 'active';
    }

    /**
     * Check if voting has ended
     */
    public function hasVotingEnded(): bool
    {
        return $this->voting_end_time < now();
    }

    /**
     * Check if results are verified
     */
    public function areResultsVerified(): bool
    {
        return $this->results_verified && $this->results_verified_at;
    }

    /**
     * Check if authorization process can be started
     */
    public function canStartAuthorization(): bool
    {
        return $this->hasVotingEnded() && 
               $this->areResultsVerified() && 
               !$this->authorization_started;
    }

    /**
     * Start authorization process
     */
    public function startAuthorization($deadline = null): array
    {
        if (!$this->canStartAuthorization()) {
            return [
                'success' => false,
                'message' => 'Cannot start authorization process'
            ];
        }

        try {
            // Generate session ID
            $sessionId = ResultAuthorization::generateSessionId();
            
            // Set deadline (default 24 hours)
            $deadline = $deadline ?: now()->addHours(24);

            // Update election
            $this->update([
                'authorization_started' => true,
                'authorization_started_at' => now(),
                'authorization_session_id' => $sessionId,
                'authorization_deadline' => $deadline
            ]);

            // Create authorization records for all required publishers
            $sessionData = ResultAuthorization::createAuthorizationSession($this->id, $sessionId);

            // Clear relevant caches
            Cache::forget('current_election');
            Cache::forget('election_status');

            // Log the action
            Log::info('Authorization process started', [
                'election_id' => $this->id,
                'session_id' => $sessionId,
                'deadline' => $deadline,
                'required_publishers' => $sessionData['required_publishers']
            ]);

            return [
                'success' => true,
                'message' => 'Authorization process started successfully',
                'session_id' => $sessionId,
                'deadline' => $deadline,
                'required_publishers' => $sessionData['required_publishers']
            ];

        } catch (\Exception $e) {
            Log::error('Failed to start authorization process', [
                'election_id' => $this->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to start authorization process: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check authorization progress
     */
    public function getAuthorizationProgress(): array
    {
        if (!$this->authorization_started || !$this->authorization_session_id) {
            return [
                'required' => 0,
                'completed' => 0,
                'remaining' => 0,
                'percentage' => 0,
                'is_complete' => false,
                'status' => 'Not Started'
            ];
        }

        $progress = ResultAuthorization::getAuthorizationProgress(
            $this->id, 
            $this->authorization_session_id
        );

        // Add additional status information
        $progress['status'] = $this->getAuthorizationStatus();
        $progress['deadline'] = $this->authorization_deadline;
        $progress['time_remaining'] = $this->getAuthorizationTimeRemaining();
        $progress['is_expired'] = $this->isAuthorizationExpired();

        return $progress;
    }

    /**
     * Get authorization status text
     */
    public function getAuthorizationStatus(): string
    {
        if (!$this->authorization_started) {
            return 'Not Started';
        }

        if ($this->authorization_complete) {
            return 'Complete';
        }

        if ($this->isAuthorizationExpired()) {
            return 'Expired';
        }

        $progress = ResultAuthorization::getAuthorizationProgress(
            $this->id, 
            $this->authorization_session_id
        );

        return "In Progress ({$progress['completed']}/{$progress['required']})";
    }

    /**
     * Check if authorization deadline has passed
     */
    public function isAuthorizationExpired(): bool
    {
        return $this->authorization_deadline && $this->authorization_deadline->isPast();
    }

    /**
     * Get time remaining for authorization
     */
    public function getAuthorizationTimeRemaining(): ?string
    {
        if (!$this->authorization_deadline) {
            return null;
        }

        if ($this->isAuthorizationExpired()) {
            return 'Expired';
        }

        $diff = now()->diff($this->authorization_deadline);
        
        if ($diff->d > 0) {
            return $diff->d . ' days, ' . $diff->h . ' hours';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hours, ' . $diff->i . ' minutes';
        } else {
            return $diff->i . ' minutes';
        }
    }

    /**
     * Check if all authorizations are complete
     */
    public function areAllAuthorizationsComplete(): bool
    {
        if (!$this->authorization_started || !$this->authorization_session_id) {
            return false;
        }

        return ResultAuthorization::areAllAuthorizationsComplete(
            $this->id, 
            $this->authorization_session_id
        );
    }

    /**
     * Complete authorization process and publish results
     */
    public function completeAuthorization(): array
    {
        if (!$this->areAllAuthorizationsComplete()) {
            return [
                'success' => false,
                'message' => 'Not all required authorizations are complete'
            ];
        }

        try {
            // Update election status
            $this->update([
                'authorization_complete' => true,
                'authorization_completed_at' => now(),
                'results_published' => true,
                'results_published_at' => now(),
                'published_by' => auth()->id(),
                'publication_summary' => $this->generatePublicationSummary()
            ]);

            // Clear caches
            Cache::forget('current_election');
            Cache::forget('election_status');
            Cache::forget('election_results_data');

            // Log the action
            Log::info('Results published automatically', [
                'election_id' => $this->id,
                'published_by' => auth()->id(),
                'authorization_session' => $this->authorization_session_id
            ]);

            return [
                'success' => true,
                'message' => 'Results published successfully',
                'published_at' => $this->results_published_at
            ];

        } catch (\Exception $e) {
            Log::error('Failed to publish results', [
                'election_id' => $this->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to publish results: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate publication summary
     */
    private function generatePublicationSummary(): array
    {
        $authorizations = ResultAuthorization::getCompletedAuthorizations(
            $this->id, 
            $this->authorization_session_id
        );

        return [
            'total_authorizations' => $authorizations->count(),
            'authorization_session_id' => $this->authorization_session_id,
            'authorized_by' => $authorizations->map(function($auth) {
                return [
                    'publisher' => $auth->publisher->full_name,
                    'authorized_at' => $auth->agreed_at->toISOString(),
                    'ip_address' => $auth->ip_address
                ];
            }),
            'published_at' => now()->toISOString(),
            'verification_completed_at' => $this->results_verified_at->toISOString()
        ];
    }

    /**
     * Check if results are published and accessible
     */
    public function areResultsPublished(): bool
    {
        
        // Emergency override (highest priority)
        if (Setting::isEnabled('results_published')) {
            return true;
        }

        $election = Election::current();
        if (!$election) {
            return false;
        }
         // Need to add: Block during voting phase
            if ($this->getCurrentPhase() === 'voting') {
                return false; // YOUR KEY REQUIREMENT
            }
            

        return $this->results_published && 
               $this->results_published_at &&
               $this->authorization_complete;
    }

    /**
     * Extend authorization deadline
     */
    public function extendAuthorizationDeadline($hours = 24): bool
    {
        if (!$this->authorization_started || $this->authorization_complete) {
            return false;
        }

        $newDeadline = $this->authorization_deadline->addHours($hours);
        
        return $this->update([
            'authorization_deadline' => $newDeadline
        ]);
    }

    /**
     * Reset authorization process
     */
    public function resetAuthorization(): array
    {
        try {
            // Delete existing authorization records
            $this->resultAuthorizations()
                ->where('authorization_session_id', $this->authorization_session_id)
                ->delete();

            // Reset election authorization fields
            $this->update([
                'authorization_started' => false,
                'authorization_started_at' => null,
                'authorization_session_id' => null,
                'authorization_deadline' => null,
                'authorization_complete' => false,
                'authorization_completed_at' => null,
                'results_published' => false,
                'results_published_at' => null,
                'published_by' => null,
                'publication_summary' => null
            ]);

            // Clear caches
            Cache::forget('current_election');
            Cache::forget('election_status');

            Log::info('Authorization process reset', [
                'election_id' => $this->id,
                'reset_by' => auth()->id()
            ]);

            return [
                'success' => true,
                'message' => 'Authorization process reset successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to reset authorization process', [
                'election_id' => $this->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to reset authorization: ' . $e->getMessage()
            ];
        }
    }
}