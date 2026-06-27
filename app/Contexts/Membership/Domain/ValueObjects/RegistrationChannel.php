<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\ValueObjects;

/**
 * Registration Channel Value Object
 *
 * Represents the channel through which a member was registered.
 * This is a business concept - different channels have different workflows.
 *
 * Business Rules:
 * - MOBILE: Self-registration via mobile app → Starts as DRAFT
 * - DESKTOP: Admin registration via desktop → Starts as PENDING
 * - IMPORT: Bulk import → Starts as PENDING
 *
 * This enum encapsulates the business logic about which channels exist
 * and prevents invalid channels from being used.
 */
enum RegistrationChannel: string
{
    case MOBILE = 'mobile';     // Citizen self-registers via Angular mobile app
    case DESKTOP = 'desktop';   // Admin registers member via Vue desktop
    case IMPORT = 'import';     // Bulk CSV import of members

    /**
     * Get the initial status for this registration channel
     *
     * Business Rule:
     * - Mobile → DRAFT (requires email verification + admin approval)
     * - Desktop → PENDING (skip DRAFT, go straight to pending approval)
     * - Import → PENDING (bulk imports skip DRAFT)
     */
    public function initialStatus(): MemberStatus
    {
        return match($this) {
            self::MOBILE => MemberStatus::draft(),
            self::DESKTOP => MemberStatus::pending(),
            self::IMPORT => MemberStatus::pending(),
        };
    }

    /**
     * Check if this channel requires email verification
     */
    public function requiresEmailVerification(): bool
    {
        return match($this) {
            self::MOBILE => true,   // Mobile users must verify email
            self::DESKTOP => false, // Admin-created users skip verification
            self::IMPORT => false,  // Imported users skip verification
        };
    }

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match($this) {
            self::MOBILE => 'Mobile App Registration',
            self::DESKTOP => 'Admin Desktop Registration',
            self::IMPORT => 'Bulk Import',
        };
    }
}
