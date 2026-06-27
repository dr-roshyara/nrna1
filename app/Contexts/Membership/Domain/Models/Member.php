<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Models;

use App\Contexts\Membership\Domain\Events\MemberRegistered;
use App\Contexts\Membership\Domain\Events\MemberApproved;
use App\Contexts\Membership\Domain\Events\MemberRejected;
use App\Contexts\Membership\Domain\Traits\RecordsEvents;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Domain\ValueObjects\MemberStatus;
use App\Contexts\Membership\Domain\ValueObjects\RegistrationChannel;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use App\Contexts\Membership\Infrastructure\Casts\MemberIdCast;
use App\Contexts\Membership\Infrastructure\Casts\PersonalInfoCast;
use App\Contexts\Membership\Infrastructure\Casts\MemberStatusCast;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Member Aggregate Root
 *
 * Represents a member in the political organization.
 *
 * Architecture Principles:
 * - Digital identity first: Every member MUST have a tenant_user account (1:1 required)
 * - Geography is ONLY a string reference, never IDs in Membership
 * - Contexts communicate via domain events, never direct calls
 *
 * @property string $id System ULID (internal, never shown to users)
 * @property string|null $member_id Party-defined member ID (e.g., "UML-2024-0001")
 * @property string $tenant_user_id REQUIRED, UNIQUE - Digital identity
 * @property string $tenant_id Party/organization identifier
 * @property PersonalInfo $personal_info Personal information (name, email, phone)
 * @property MemberStatus $status Member lifecycle status
 * @property string|null $residence_geo_reference Optional geography reference (string path)
 * @property string $membership_type Type of membership (regular, honorary, etc.)
 * @property string|null $registration_channel Channel used for registration (mobile, desktop, import)
 * @property array $metadata JSON for extensions
 */
class Member extends Model
{
    use RecordsEvents;

    protected $table = 'members';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Constructor - Set connection based on environment
     * Testing: tenant_test, Production: tenant
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set connection based on environment
        $this->connection = app()->environment('testing') ? 'tenant_test' : 'tenant';
    }

    protected $fillable = [
        'id',
        'member_id',
        'tenant_user_id',
        'tenant_id',
        'personal_info',
        'status',
        'residence_geo_reference',
        'residence_geo_cache',
        'geo_cache_version',
        'geo_cache_updated_at',
        'membership_type',
        'registration_channel',
        'metadata',
    ];

    protected $casts = [
        'member_id' => MemberIdCast::class,
        'personal_info' => PersonalInfoCast::class,
        'status' => MemberStatusCast::class,
        'residence_geo_cache' => 'array',
        'geo_cache_updated_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Boot the model
     * Automatically dispatch domain events after save
     */
    protected static function booted(): void
    {
        static::saved(function ($model) {
            if (method_exists($model, 'dispatchRecordedEvents')) {
                $model->dispatchRecordedEvents();
            }
        });
    }

    /**
     * Register a new member via MOBILE app (self-registration)
     *
     * Business Rules:
     * - Creates member with DRAFT status (requires verification + approval)
     * - Requires email verification
     * - tenant_user_id will be created by Application layer (TenantUserProvisioningInterface)
     *
     * Mobile Registration Flow:
     * 1. User submits form via Angular mobile app
     * 2. Application layer provisions tenant_user account
     * 3. Domain creates member with DRAFT status
     * 4. Email verification required
     * 5. Admin approval required
     *
     * @param TenantUserId $tenantUserId REQUIRED - Tenant user account ID
     * @param TenantId $tenantId REQUIRED - Party identifier
     * @param PersonalInfo $personalInfo Member's personal information
     * @param MemberId|string|null $memberId Optional party-defined member ID
     * @param string|null $geoReference Optional geography reference
     * @return self
     * @throws InvalidArgumentException
     */
    public static function registerForMobile(
        TenantUserId $tenantUserId,
        TenantId $tenantId,
        PersonalInfo $personalInfo,
        MemberId|string|null $memberId = null,
        ?string $geoReference = null
    ): self {
        return self::register(
            tenantUserId: $tenantUserId,
            tenantId: $tenantId,
            personalInfo: $personalInfo,
            memberId: $memberId,
            geoReference: $geoReference,
            registrationChannel: RegistrationChannel::MOBILE
        );
    }

    /**
     * Register a new member via DESKTOP admin (admin-created)
     *
     * Business Rules:
     * - Creates member with PENDING status (skips DRAFT)
     * - No email verification required
     * - Admin creates member on behalf of citizen
     *
     * Desktop Registration Flow:
     * 1. Admin creates tenant_user account via Vue desktop
     * 2. Admin creates member record
     * 3. Member starts at PENDING status (awaiting approval)
     * 4. No email verification needed
     *
     * @param TenantUserId $tenantUserId REQUIRED - Tenant user account ID (created by admin)
     * @param TenantId $tenantId REQUIRED - Party identifier
     * @param PersonalInfo $personalInfo Member's personal information
     * @param MemberId|string|null $memberId Optional party-defined member ID
     * @param string|null $geoReference Optional geography reference
     * @return self
     * @throws InvalidArgumentException
     */
    public static function registerForDesktop(
        TenantUserId $tenantUserId,
        TenantId $tenantId,
        PersonalInfo $personalInfo,
        MemberId|string|null $memberId = null,
        ?string $geoReference = null
    ): self {
        return self::register(
            tenantUserId: $tenantUserId,
            tenantId: $tenantId,
            personalInfo: $personalInfo,
            memberId: $memberId,
            geoReference: $geoReference,
            registrationChannel: RegistrationChannel::DESKTOP
        );
    }

    /**
     * Register a new member via CSV IMPORT (bulk import)
     *
     * Business Rules:
     * - Creates member with PENDING status (requires approval)
     * - Used for bulk imports from Excel/CSV files
     * - tenant_user_id will be created by Application layer
     * - Handles data conversion from CSV row format
     *
     * CSV Import Flow:
     * 1. Admin uploads CSV file via Desktop
     * 2. Application layer creates tenant_user accounts (or validates existing)
     * 3. Domain creates member with PENDING status
     * 4. Admin approval required for activation
     *
     * Global Platform: Supports ANY political party worldwide
     *
     * @param TenantUserId $tenantUserId REQUIRED - Tenant user account ID
     * @param TenantId $tenantId REQUIRED - Party identifier
     * @param array<string, string> $csvRow Raw CSV row data
     * @return self
     * @throws InvalidArgumentException
     */
    public static function registerFromCsv(
        TenantUserId $tenantUserId,
        TenantId $tenantId,
        array $csvRow
    ): self {
        // Convert email to Value Object (PersonalInfo expects Email VO)
        $email = new \App\Contexts\Membership\Domain\ValueObjects\Email($csvRow['email'] ?? '');

        // Convert to PersonalInfo Value Object
        $personalInfo = new PersonalInfo(
            fullName: $csvRow['full_name'] ?? '',
            email: $email,
            phone: $csvRow['phone'] ?? null
        );

        // Extract member_id if provided
        $memberId = isset($csvRow['member_id']) && !empty($csvRow['member_id'])
            ? $csvRow['member_id']
            : null;

        // Extract geography reference if provided
        $geoReference = isset($csvRow['geography']) && !empty($csvRow['geography'])
            ? $csvRow['geography']
            : null;

        return self::register(
            tenantUserId: $tenantUserId,
            tenantId: $tenantId,
            personalInfo: $personalInfo,
            memberId: $memberId,
            geoReference: $geoReference,
            registrationChannel: RegistrationChannel::IMPORT
        );
    }

    /**
     * Register a new member (PRIVATE - Domain decides status)
     *
     * Core factory method that enforces business rules:
     * - tenant_user_id is REQUIRED (digital identity first)
     * - tenant_id is REQUIRED (member belongs to a party)
     * - personal_info must be valid PersonalInfo value object
     * - member_id is optional (party-defined identifier)
     * - Domain decides status based on registration channel
     * - Records MemberRegistered domain event
     *
     * @param TenantUserId $tenantUserId REQUIRED - Cannot be empty
     * @param TenantId $tenantId REQUIRED - Party identifier
     * @param PersonalInfo $personalInfo Member's personal information
     * @param MemberId|string|null $memberId Optional party-defined member ID
     * @param string|null $geoReference Optional geography reference
     * @param RegistrationChannel $registrationChannel REQUIRED - Domain decides status based on this
     * @return self
     * @throws InvalidArgumentException
     */
    private static function register(
        TenantUserId $tenantUserId,
        TenantId $tenantId,
        PersonalInfo $personalInfo,
        MemberId|string|null $memberId = null,
        ?string $geoReference = null,
        RegistrationChannel $registrationChannel
    ): self {
        // CRITICAL: Value Objects already validated in their constructors
        // TenantUserId and TenantId are guaranteed to be valid
        // No need for empty checks - Value Objects enforce their own invariants

        // NOTE: member_id uniqueness validation moved to Application layer
        // Application Service MUST validate via Repository before calling this factory
        // Domain aggregate should NOT perform database queries (ADR-001 Rule 2)

        // Create member aggregate
        $member = new self();
        $member->id = (string) Str::ulid();
        $member->member_id = $memberId instanceof MemberId ? $memberId : ($memberId ? new MemberId($memberId) : null);
        $member->tenant_user_id = $tenantUserId->value();
        $member->tenant_id = $tenantId->toString();
        $member->personal_info = $personalInfo;
        $member->status = $registrationChannel->initialStatus(); // Domain decides based on channel
        $member->residence_geo_reference = $geoReference;
        $member->membership_type = 'regular';
        $member->registration_channel = $registrationChannel->value; // Store channel for audit
        $member->metadata = [];

        // Record domain event
        $member->recordThat(new MemberRegistered(
            memberId: $member->id,
            tenantUserId: $member->tenant_user_id,
            tenantId: $member->tenant_id,
            status: $member->status,
            personalInfo: $personalInfo->toArray()
        ));

        return $member;
    }

    /**
     * Approve this member
     *
     * Business Rules:
     * - Only PENDING members can be approved
     * - Records MemberApproved domain event
     * - Transitions status from pending → approved
     *
     * @param string $approvedByUserId Admin user ID who approved
     * @throws \DomainException if member is not pending
     */
    public function approve(string $approvedByUserId): void
    {
        if (!$this->status->isPending()) {
            throw new \DomainException('Only pending members can be approved.');
        }

        $this->status = MemberStatus::approved();

        $this->recordThat(new MemberApproved(
            memberId: $this->id,
            tenantId: $this->tenant_id,
            approvedByUserId: $approvedByUserId,
            approvedAt: new \DateTimeImmutable()
        ));
    }

    /**
     * Reject this member
     *
     * Business Rules:
     * - Only PENDING members can be rejected
     * - Rejection reason is REQUIRED (min 10 chars for compliance)
     * - Records MemberRejected domain event
     * - Transitions status from pending → rejected
     *
     * @param string $rejectedByUserId Admin user ID who rejected
     * @param string $reason Rejection reason (required, non-empty)
     * @throws \DomainException if member is not pending
     * @throws \InvalidArgumentException if reason is empty
     */
    public function reject(string $rejectedByUserId, string $reason): void
    {
        if (!$this->status->isPending()) {
            throw new \DomainException('Only pending members can be rejected.');
        }

        if (empty(trim($reason))) {
            throw new \InvalidArgumentException('Rejection reason is required.');
        }

        $this->status = MemberStatus::rejected();

        $this->recordThat(new MemberRejected(
            memberId: $this->id,
            tenantId: $this->tenant_id,
            rejectedByUserId: $rejectedByUserId,
            reason: trim($reason),
            rejectedAt: new \DateTimeImmutable()
        ));
    }

    /**
     * Activate this member
     * Transition from approved → active
     */
    public function activate(): void
    {
        $this->status = $this->status->activate();
    }

    /**
     * Suspend this member
     * Transition from active → suspended
     */
    public function suspend(): void
    {
        $this->status = $this->status->suspend();
    }

    /**
     * Check if member can vote
     */
    public function canVote(): bool
    {
        return $this->status->canVote();
    }

    /**
     * Check if member can hold committee role
     */
    public function canHoldCommitteeRole(): bool
    {
        return $this->status->canHoldCommitteeRole();
    }

    // ===================================================================
    // GEOGRAPHY CACHE METHODS (Loose Coupling with Geography Context)
    // ===================================================================

    /**
     * Update geography cache from Geography Context API response.
     *
     * Called when:
     * - Member registration with geography validation
     * - Member updates residence
     * - Geography Context publishes update events
     *
     * @param array $geoData Geography data from Geography Context API
     *   Format: [
     *     'country' => 'Nepal',
     *     'country_code' => 'NP',
     *     'province' => 'Province 1',
     *     'district' => 'Kathmandu',
     *     'local' => 'Kathmandu Metropolis',
     *     'ward' => 'Ward 15',
     *     'full_hierarchy' => 'Ward 15, Kathmandu Metropolis, Kathmandu, Province 1, Nepal',
     *     'path' => '1.12.123.1234',
     *   ]
     * @param int|null $version Geography data version for cache invalidation
     * @return void
     */
    public function updateGeographyCache(array $geoData, ?int $version = null): void
    {
        $this->residence_geo_cache = $geoData;
        $this->geo_cache_version = $version ?? ($this->geo_cache_version + 1);
        $this->geo_cache_updated_at = now();
    }

    /**
     * Get cached geography data for display.
     *
     * Benefits:
     * - No Geography Context queries needed
     * - Works even if Geography Context is unavailable
     * - Fast member location display in UI
     *
     * @return array|null Cached geography hierarchy or null if not set
     */
    public function getGeographyCache(): ?array
    {
        return $this->residence_geo_cache;
    }

    /**
     * Check if geography cache is stale and needs refresh.
     *
     * Cache considered stale if:
     * - Cache was never populated (null updated_at)
     * - Cache is older than TTL (default 24 hours)
     * - Can be customized per tenant
     *
     * @param int $ttlHours Time-to-live in hours (default 24)
     * @return bool True if cache should be refreshed
     */
    public function isGeographyCacheStale(int $ttlHours = 24): bool
    {
        if (is_null($this->geo_cache_updated_at)) {
            return true; // Never cached
        }

        return $this->geo_cache_updated_at->addHours($ttlHours)->isPast();
    }

    /**
     * Get display-friendly geography string.
     *
     * Examples:
     * - "Ward 15, Kathmandu Metropolis, Kathmandu, Province 1"
     * - "Dhankuta, Koshi Province"
     *
     * @return string|null Human-readable geography or null if not set
     */
    public function getGeographyDisplay(): ?string
    {
        if (empty($this->residence_geo_cache)) {
            return null;
        }

        return $this->residence_geo_cache['full_hierarchy'] ?? null;
    }

    /**
     * Get specific geography level from cache.
     *
     * Examples:
     * - getGeographyLevel('district') => 'Kathmandu'
     * - getGeographyLevel('province') => 'Province 1'
     *
     * @param string $level Geography level (province, district, local, ward)
     * @return string|null Value for that level or null
     */
    public function getGeographyLevel(string $level): ?string
    {
        if (empty($this->residence_geo_cache)) {
            return null;
        }

        return $this->residence_geo_cache[$level] ?? null;
    }

    /**
     * Get member's residence geography reference
     *
     * Returns the validated geography reference for committee assignment validation.
     * Used by GeographicCommitteeStructure to validate member residence is within
     * committee's operational geography.
     *
     * @return GeoReference|null Validated geography reference or null if not set
     */
    public function getResidenceGeoReference(): ?GeoReference
    {
        if (empty($this->residence_geo_reference)) {
            return null;
        }

        try {
            return new GeoReference($this->residence_geo_reference);
        } catch (\InvalidArgumentException $e) {
            // If stored reference is invalid, return null (should not happen with validation)
            return null;
        }
    }

    // ===================================================================
    // STRATEGY PATTERN VALIDATION METHODS (For CommitteeStructure)
    // ===================================================================

    /**
     * Get member's age (for Youth/Student/Women wing validation)
     *
     * MVP placeholder - TODO: Calculate from date_of_birth field
     *
     * @return int|null Age in years or null if not available
     */
    public function getAge(): ?int {
        // TODO: Calculate age from date_of_birth field
        return null; // MVP placeholder
    }

    /**
     * Get member's gender (for Women wing validation)
     *
     * MVP placeholder - TODO: Return gender from personal_info
     *
     * @return string|null Gender or null if not available
     */
    public function getGender(): ?string {
        // TODO: Return gender from personal_info
        return null; // MVP placeholder
    }

    /**
     * Get membership duration in years (for Central committee validation)
     *
     * MVP placeholder - TODO: Calculate from joined_date
     *
     * @return int Years of membership
     */
    public function getMembershipDuration(): int {
        // TODO: Calculate membership duration from joined_date
        return 0; // MVP placeholder
    }

    /**
     * Check if member is a student (for Student wing validation)
     *
     * MVP placeholder - TODO: Check student status
     *
     * @return bool True if member is a student
     */
    public function isStudent(): bool {
        // TODO: Check student status from personal_info or dedicated field
        return false; // MVP placeholder
    }





}
