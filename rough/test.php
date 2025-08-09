<?php
// Add this method to your Election.php model

/**
 * 🎯 MASTER METHOD: Determines if results can be viewed
 * This is your SINGLE SOURCE OF TRUTH for the seal/unseal system
 */
public function canViewResults(): bool
{
    // Emergency override (committee can force publication)
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

/**
 * Update fillable array to include phase
 */
protected $fillable = [
    'name',
    'description',
    'voting_start_time',
    'voting_end_time',
    'status',
    'phase',  // ✅ ADD THIS
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
    'publication_summary'
];

/**
 * Add phase to casts
 */
protected $casts = [
    'voting_start_time' => 'datetime',
    'voting_end_time' => 'datetime',
    'phase' => 'string',  // ✅ ADD THIS
    'results_verified' => 'boolean',
    'results_verified_at' => 'datetime',
    'authorization_started' => 'boolean',
    'authorization_started_at' => 'datetime',
    'authorization_deadline' => 'datetime',
    'authorization_complete' => 'boolean',
    'authorization_completed_at' => 'datetime',
    'results_published' => 'boolean',
    'results_published_at' => 'datetime',
    'publication_summary' => 'array'
];

// ✅ REMOVE THIS UNUSED METHOD from ElectionResultController.php:
/*
private function areResultsPublished(): bool
{
    // Delete this entire method - no longer needed
    // All logic is now in Election->canViewResults()
}
*/