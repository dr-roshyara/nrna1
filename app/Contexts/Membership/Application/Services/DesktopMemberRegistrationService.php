<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\Membership\Application\DTOs\DesktopRegistrationDto;
use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\Services\GeographyResolverInterface;
use App\Contexts\Membership\Domain\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Domain\ValueObjects\Email;

/**
 * Desktop Member Registration Service
 *
 * APPLICATION LAYER - Orchestrates desktop admin member registration use case
 *
 * Responsibilities:
 * 1. Receive EXISTING tenant_user_id (admin already created user)
 * 2. Validate geography reference if provided (via GeographyResolverInterface)
 * 3. Create PersonalInfo value object
 * 4. Call Member::registerForDesktop() domain factory
 * 5. Persist member to database
 * 6. Return created member
 *
 * Key Difference from Mobile:
 * - Desktop does NOT provision user account (tenant_user_id already exists)
 * - Admin creates member on behalf of citizen
 * - Member starts at PENDING status (skips DRAFT)
 * - No email verification required
 *
 * Business Flow:
 * Admin (Desktop App) → DTO → Application Service → Domain Factory → Member Aggregate
 *
 * DDD Principles:
 * - Application service orchestrates, domain decides
 * - Domain service interface decouples Geography context
 * - Member factory method encapsulates business rules
 * - Value objects ensure data integrity
 *
 * Usage:
 * ```php
 * $dto = DesktopRegistrationDto::fromRequest($request);
 * $member = $registrationService->register($dto);
 * ```
 */
final class DesktopMemberRegistrationService
{
    public function __construct(
        private readonly GeographyResolverInterface $geographyResolver
    ) {}

    /**
     * Register a new member via desktop admin (admin-created)
     *
     * Orchestration Steps:
     * 1. Validate geography reference if provided
     * 2. Create PersonalInfo value object
     * 3. Call Member::registerForDesktop() - domain decides PENDING status
     * 4. Save member to database
     * 5. Return created member (with MemberRegistered event recorded)
     *
     * NOTE: Desktop registration assumes tenant_user_id already exists
     * (admin created user account before creating member record)
     *
     * @param DesktopRegistrationDto $dto Desktop registration data from HTTP layer
     * @return Member Created member aggregate (saved to database)
     * @throws \InvalidArgumentException If validation fails
     */
    public function register(DesktopRegistrationDto $dto): Member
    {
        // Step 1: Validate geography reference (optional)
        // Delegates to Geography context via anti-corruption layer
        $geoReference = null;
        if ($dto->geoReference !== null) {
            $validatedGeoRef = $this->geographyResolver->validate($dto->geoReference);
            $geoReference = $validatedGeoRef?->value();
        }

        // Step 2: Create PersonalInfo value object
        // Encapsulates personal data with validation
        $personalInfo = new PersonalInfo(
            fullName: $dto->fullName,
            email: new Email($dto->email),
            phone: $dto->phone
        );

        // Step 3: Call domain factory method
        // Domain decides status (PENDING for desktop channel - skips DRAFT)
        $member = Member::registerForDesktop(
            tenantUserId: $dto->tenantUserId, // Admin-provided, already exists
            tenantId: $dto->tenantId,
            personalInfo: $personalInfo,
            memberId: $dto->memberId,
            geoReference: $geoReference
        );

        // Step 4: Persist to database
        $member->save();

        // Step 5: Return created member
        // MemberRegistered event dispatched automatically on save
        return $member;
    }
}
