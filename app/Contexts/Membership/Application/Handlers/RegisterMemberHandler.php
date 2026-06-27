<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Handlers;

use App\Contexts\Membership\Application\Commands\RegisterMemberCommand;
use App\Contexts\Membership\Domain\Models\Member;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Services\IdentityVerificationInterface;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\PersonalInfo;
use InvalidArgumentException;

/**
 * Register Member Handler
 *
 * Application service that orchestrates member registration workflow.
 *
 * Responsibilities:
 * 1. Validate digital identity exists (business rule)
 * 2. Check member ID uniqueness (business rule)
 * 3. Create domain value objects
 * 4. Delegate to Member aggregate for business logic
 * 5. Persist via repository (infrastructure)
 *
 * Architectural Note (ADR-001):
 * - NO static Member::where() calls (use repository)
 * - Business logic in Member aggregate, orchestration here
 * - Domain events recorded in aggregate, dispatched on save
 */
class RegisterMemberHandler
{
    public function __construct(
        private readonly MemberRepositoryInterface $repository,
        private readonly IdentityVerificationInterface $identityVerifier
    ) {}

    /**
     * Handle member registration command
     *
     * @param RegisterMemberCommand $command The registration command
     * @return Member The registered member aggregate
     * @throws InvalidArgumentException If business rules violated
     */
    public function handle(RegisterMemberCommand $command): Member
    {
        // Business Rule 1: Digital identity must exist (digital identity first)
        if (!$this->identityVerifier->userExists($command->tenantUserId, $command->tenantId)) {
            throw new InvalidArgumentException(
                'User identity must exist before member registration. ' .
                "Tenant user ID '{$command->tenantUserId}' not found for tenant '{$command->tenantId}'."
            );
        }

        // Business Rule 2: Check if user is already a member (1:1 relationship)
        if ($this->repository->existsByTenantUserId($command->tenantId, $command->tenantUserId)) {
            throw new InvalidArgumentException(
                'This user is already registered as a member. ' .
                'One user can only be one member per tenant.'
            );
        }

        // Business Rule 3: Member ID uniqueness (if provided)
        if ($command->memberId !== null &&
            $this->repository->existsByMemberId($command->tenantId, $command->memberId)) {
            throw new InvalidArgumentException(
                "Member ID '{$command->memberId}' already exists for tenant '{$command->tenantId}'. " .
                'Each member ID must be unique within the organization.'
            );
        }

        // Create domain value objects
        $personalInfo = new PersonalInfo(
            fullName: $command->fullName,
            email: $command->email,
            phone: $command->phone
        );

        $memberId = $command->memberId !== null
            ? new MemberId($command->memberId)
            : null;

        // Delegate to Member aggregate for business logic
        // (Member::register records MemberRegistered event internally)
        $member = Member::register(
            tenantUserId: $command->tenantUserId,
            tenantId: $command->tenantId,
            personalInfo: $personalInfo,
            memberId: $memberId,
            geoReference: $command->geoReference
        );

        // Persist via repository (events dispatched here)
        $this->repository->save($member);

        return $member;
    }
}
