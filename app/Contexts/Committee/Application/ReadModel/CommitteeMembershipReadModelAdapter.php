<?php

declare(strict_types=1);

namespace App\Contexts\Committee\Application\ReadModel;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;

interface CommitteeMembershipReadModelAdapter
{
    /**
     * Query active members only (constitutional truth).
     *
     * @return CommitteeMemberView[]
     */
    public function getActiveMembers(CommitteeId $committeeId): array;

    /**
     * Query all members across all statuses (for admin UI).
     *
     * @return CommitteeMemberView[]
     */
    public function getAllMembers(CommitteeId $committeeId): array;

    /**
     * Get membership stats for dashboard KPIs.
     */
    public function getMembershipStats(CommitteeId $committeeId): CommitteeMembershipStats;

    /**
     * Get detailed view of a single member in committee context.
     */
    public function getMemberDetails(MemberId $memberId, CommitteeId $committeeId): ?CommitteeMemberView;
}
