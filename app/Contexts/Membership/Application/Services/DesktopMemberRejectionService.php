<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\Membership\Application\DTOs\MemberRejectionDto;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Models\Member;

/**
 * Desktop Member Rejection Service
 *
 * Application layer service for rejecting pending members.
 * Orchestrates domain logic and repository operations.
 *
 * Business Flow:
 * 1. Retrieve member from repository
 * 2. Validate member exists
 * 3. Validate tenant ownership
 * 4. Delegate to domain reject() method
 * 5. Persist via repository
 *
 * Domain events are dispatched by repository on save.
 */
final class DesktopMemberRejectionService
{
    public function __construct(
        private readonly MemberRepositoryInterface $memberRepository
    ) {
    }

    /**
     * Reject a pending member
     *
     * @param MemberRejectionDto $dto
     * @return Member Rejected member
     * @throws \DomainException if member not found or tenant mismatch
     * @throws \DomainException if member is not pending (enforced by domain)
     * @throws \InvalidArgumentException if reason is empty (enforced by domain)
     */
    public function reject(MemberRejectionDto $dto): Member
    {
        // Retrieve member
        $member = $this->memberRepository->getById($dto->memberId);

        if (!$member) {
            throw new \DomainException('Member not found.');
        }

        // Validate tenant ownership
        if ($member->tenant_id !== $dto->tenantId) {
            throw new \DomainException('Member does not belong to this tenant.');
        }

        // Delegate to domain method (enforces business rules + validates reason)
        $member->reject($dto->adminUserId, $dto->reason);

        // Persist (dispatches domain events)
        $this->memberRepository->save($member);

        return $member;
    }
}
