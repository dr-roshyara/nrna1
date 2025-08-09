<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ResultAuthorization extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'publisher_id',
        'authorization_session_id',
        'agreed',
        'agreed_at',
        'password_verified',
        'ip_address',
        'user_agent',
        'concerns',
        'is_valid',
        'expires_at',
        'verification_data'
    ];

    protected $casts = [
        'agreed' => 'boolean',
        'password_verified' => 'boolean',
        'is_valid' => 'boolean',
        'agreed_at' => 'datetime',
        'expires_at' => 'datetime',
        'verification_data' => 'array'
    ];

    /**
     * Generate unique authorization session ID
     */
    public static function generateSessionId(): string
    {
        return 'AUTH_' . date('Y') . '_' . Str::random(12);
    }

    /**
     * Relationship: Authorization belongs to an election
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Relationship: Authorization belongs to a publisher
     */
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * Scope: Only agreed authorizations
     */
    public function scopeAgreed($query)
    {
        return $query->where('agreed', true);
    }

    /**
     * Scope: Only valid authorizations
     */
    public function scopeValid($query)
    {
        return $query->where('is_valid', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope: For specific election and session
     */
    public function scopeForSession($query, $electionId, $sessionId)
    {
        return $query->where('election_id', $electionId)
                    ->where('authorization_session_id', $sessionId);
    }

    /**
     * Scope: Completed authorizations (agreed and valid)
     */
    public function scopeCompleted($query)
    {
        return $query->agreed()->valid();
    }

    /**
     * Check if this authorization is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if this authorization is complete and valid
     */
    public function isComplete(): bool
    {
        return $this->agreed && 
               $this->password_verified && 
               $this->is_valid && 
               !$this->isExpired();
    }

    /**
     * Invalidate this authorization
     */
    public function invalidate($reason = null): bool
    {
        return $this->update([
            'is_valid' => false,
            'concerns' => $reason
        ]);
    }

    /**
     * Get time remaining for authorization
     */
    public function getTimeRemainingAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }

        $diff = now()->diff($this->expires_at);
        
        if ($this->isExpired()) {
            return 'Expired';
        }

        if ($diff->d > 0) {
            return $diff->d . ' days, ' . $diff->h . ' hours';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hours, ' . $diff->i . ' minutes';
        } else {
            return $diff->i . ' minutes';
        }
    }

    /**
     * Get authorization status text
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_valid) {
            return 'Invalid';
        }

        if ($this->isExpired()) {
            return 'Expired';
        }

        if ($this->agreed && $this->password_verified) {
            return 'Authorized';
        }

        if ($this->concerns) {
            return 'Concerns Raised';
        }

        return 'Pending';
    }

    /**
     * Get authorization details for display
     */
    public function getDetailsAttribute(): array
    {
        return [
            'publisher' => $this->publisher->full_name,
            'status' => $this->status,
            'authorized_at' => $this->agreed_at?->format('M j, Y g:i A'),
            'time_remaining' => $this->time_remaining,
            'ip_address' => $this->ip_address,
            'has_concerns' => !empty($this->concerns),
            'concerns' => $this->concerns
        ];
    }

    /**
     * Static method: Get authorization progress for election session
     */
    public static function getAuthorizationProgress($electionId, $sessionId)
    {
        $requiredPublishers = Publisher::required()->count();
        $completedAuthorizations = self::forSession($electionId, $sessionId)
            ->completed()
            ->count();

        return [
            'required' => $requiredPublishers,
            'completed' => $completedAuthorizations,
            'remaining' => $requiredPublishers - $completedAuthorizations,
            'percentage' => $requiredPublishers > 0 ? 
                round(($completedAuthorizations / $requiredPublishers) * 100, 2) : 0,
            'is_complete' => $completedAuthorizations >= $requiredPublishers
        ];
    }

    /**
     * Static method: Check if all required authorizations are complete
     */
    public static function areAllAuthorizationsComplete($electionId, $sessionId): bool
    {
        $requiredCount = Publisher::required()->count();
        $completedCount = self::forSession($electionId, $sessionId)
            ->completed()
            ->count();

        return $completedCount >= $requiredCount && $requiredCount > 0;
    }

    /**
     * Static method: Get pending publishers for session
     */
    public static function getPendingPublishers($electionId, $sessionId)
    {
        $authorizedPublisherIds = self::forSession($electionId, $sessionId)
            ->completed()
            ->pluck('publisher_id')
            ->toArray();

        return Publisher::required()
            ->whereNotIn('id', $authorizedPublisherIds)
            ->byPriority()
            ->get();
    }

    /**
     * Static method: Get completed authorizations for session
     */
    public static function getCompletedAuthorizations($electionId, $sessionId)
    {
        return self::forSession($electionId, $sessionId)
            ->completed()
            ->with('publisher')
            ->orderBy('agreed_at')
            ->get();
    }

    /**
     * Static method: Create authorization session for all required publishers
     */
    public static function createAuthorizationSession($electionId, $sessionId = null)
    {
        if (!$sessionId) {
            $sessionId = self::generateSessionId();
        }

        $requiredPublishers = Publisher::required()->get();
        $authorizations = [];

        foreach ($requiredPublishers as $publisher) {
            $authorizations[] = $publisher->createAuthorization($electionId, $sessionId);
        }

        return [
            'session_id' => $sessionId,
            'authorizations_created' => count($authorizations),
            'required_publishers' => $requiredPublishers->count(),
            'authorizations' => $authorizations
        ];
    }
}