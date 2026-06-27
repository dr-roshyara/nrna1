<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Services;

use App\Contexts\Membership\Application\DTOs\MemberApprovalDto;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Models\Member;

/**
 * Desktop Member Approval Service
 *
 * Application layer service for approving pending members.
 * Orchestrates domain logic and repository operations.
 *
 * Business Flow:
 * 1. Retrieve member from repository
 * 2. Validate member exists
 * 3. Validate tenant ownership
 * 4. Delegate to domain approve() method
 * 5. Persist via repository
 *
 * Domain events are dispatched by repository on save.
 */
final class DesktopMemberApprovalService
{
    public function __construct(
        private readonly MemberRepositoryInterface $memberRepository
    ) {
    }

    /**
     * Approve a pending member
     *
     * @param MemberApprovalDto $dto
     * @return Member Approved member
     * @throws \DomainException if member not found or tenant mismatch
     * @throws \DomainException if member is not pending (enforced by domain)
     */
    public function approve(MemberApprovalDto $dto): Member
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

        // Delegate to domain method (enforces business rules)
        $member->approve($dto->adminUserId);

        // Persist (dispatches domain events)
        $this->memberRepository->save($member);

        return $member;
    }
}
