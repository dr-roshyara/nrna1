<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'publisher_id',
        'user_id',
        'name',
        'title',
        'should_agree',
        'authorization_password',
        'is_active',
        'priority_order',
        'notes'
    ];

    protected $casts = [
        'should_agree' => 'boolean',
        'is_active' => 'boolean',
        'priority_order' => 'integer'
    ];

    protected $hidden = [
        'authorization_password'
    ];

    /**
     * Generate unique publisher ID
     */
    public static function generatePublisherId(): string
    {
        do {
            $id = 'PUB_' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while (self::where('publisher_id', $id)->exists());
        
        return $id;
    }

    /**
     * Set authorization password (automatically hashed)
     */
    public function setAuthorizationPasswordAttribute($value)
    {
        $this->attributes['authorization_password'] = Hash::make($value);
    }

    /**
     * Verify authorization password
     */
    public function verifyAuthorizationPassword(string $password): bool
    {
        return Hash::check($password, $this->authorization_password);
    }

    /**
     * Generate secure random authorization password
     */
    public static function generateAuthorizationPassword(): string
    {
        return Str::upper(Str::random(8));
    }

    /**
     * Relationship: Publisher belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Publisher can have many authorizations
     */
    public function authorizations()
    {
        return $this->hasMany(ResultAuthorization::class);
    }

    /**
     * Get current authorization for specific election and session
     */
    public function getCurrentAuthorization($electionId, $sessionId)
    {
        return $this->authorizations()
            ->where('election_id', $electionId)
            ->where('authorization_session_id', $sessionId)
            ->first();
    }

    /**
     * Check if publisher has authorized for specific election session
     */
    public function hasAuthorized($electionId, $sessionId): bool
    {
        return $this->authorizations()
            ->where('election_id', $electionId)
            ->where('authorization_session_id', $sessionId)
            ->where('agreed', true)
            ->where('is_valid', true)
            ->exists();
    }

    /**
     * Scope: Only active publishers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Only publishers who should agree
     */
    public function scopeShouldAgree($query)
    {
        return $query->where('should_agree', true);
    }

    /**
     * Scope: Publishers required for authorization
     */
    public function scopeRequired($query)
    {
        return $query->active()->shouldAgree();
    }

    /**
     * Scope: Ordered by priority
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority_order')->orderBy('name');
    }

    /**
     * Get display name with title
     */
    public function getFullNameAttribute(): string
    {
        return $this->name . ' (' . $this->title . ')';
    }

    /**
     * Check if this publisher is required for authorization
     */
    public function isRequired(): bool
    {
        return $this->is_active && $this->should_agree;
    }

    /**
     * Create authorization record for this publisher
     */
    public function createAuthorization($electionId, $sessionId, $ipAddress = null, $userAgent = null)
    {
        return $this->authorizations()->create([
            'election_id' => $electionId,
            'authorization_session_id' => $sessionId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'expires_at' => now()->addHours(24), // 24 hour expiry
        ]);
    }

    /**
     * Authorize results for specific election session
     */
    public function authorizeResults($electionId, $sessionId, $password, $verificationData = null, $ipAddress = null, $userAgent = null)
    {
        // Verify password first
        if (!$this->verifyAuthorizationPassword($password)) {
            return [
                'success' => false,
                'message' => 'Invalid authorization password'
            ];
        }

        // Check if already authorized
        if ($this->hasAuthorized($electionId, $sessionId)) {
            return [
                'success' => false,
                'message' => 'Already authorized for this election'
            ];
        }

        // Get or create authorization record
        $authorization = $this->getCurrentAuthorization($electionId, $sessionId);
        
        if (!$authorization) {
            $authorization = $this->createAuthorization($electionId, $sessionId, $ipAddress, $userAgent);
        }

        // Update authorization
        $authorization->update([
            'agreed' => true,
            'agreed_at' => now(),
            'password_verified' => true,
            'verification_data' => $verificationData,
            'ip_address' => $ipAddress ?: $authorization->ip_address,
            'user_agent' => $userAgent ?: $authorization->user_agent,
        ]);

        return [
            'success' => true,
            'message' => 'Authorization successful',
            'authorization' => $authorization
        ];
    }
}