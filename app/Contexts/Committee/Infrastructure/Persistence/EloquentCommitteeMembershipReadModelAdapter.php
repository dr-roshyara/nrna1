<?php

declare(strict_types=1);

namespace App\Contexts\Committee\Infrastructure\Persistence;

use App\Contexts\Committee\Application\ReadModel\CommitteeMembershipReadModelAdapter;
use App\Contexts\Committee\Application\ReadModel\CommitteeMembershipStats;
use App\Contexts\Committee\Application\ReadModel\CommitteeMemberView;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Infrastructure\Models\CommitteeAssociationModel;
use App\Models\User;

final class EloquentCommitteeMembershipReadModelAdapter implements CommitteeMembershipReadModelAdapter
{
    public function getActiveMembers(CommitteeId $committeeId): array
    {
        $rows = CommitteeAssociationModel::where('committee_id', $committeeId->value())
            ->where('status', 'active')
            ->orderBy('associated_at', 'asc')
            ->get();

        return $this->hydrateViews($rows);
    }

    public function getAllMembers(CommitteeId $committeeId): array
    {
        $rows = CommitteeAssociationModel::where('committee_id', $committeeId->value())
            ->orderBy('associated_at', 'asc')
            ->get();

        return $this->hydrateViews($rows);
    }

    public function getMembershipStats(CommitteeId $committeeId): CommitteeMembershipStats
    {
        $counts = CommitteeAssociationModel::where('committee_id', $committeeId->value())
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return new CommitteeMembershipStats(
            active:     (int) ($counts['active'] ?? 0),
            suspended:  (int) ($counts['suspended'] ?? 0),
            terminated: (int) ($counts['terminated'] ?? 0),
            total:      (int) $counts->sum(),
        );
    }

    public function getMemberDetails(MemberId $memberId, CommitteeId $committeeId): ?CommitteeMemberView
    {
        $row = CommitteeAssociationModel::where('committee_id', $committeeId->value())
            ->where('member_id', $memberId->value())
            ->latest('associated_at')
            ->first();

        if (!$row) {
            return null;
        }

        $userName = User::find($row->member_id)?->name ?? 'Unknown Member';

        return new CommitteeMemberView(
            memberId:    $row->member_id,
            displayName: $userName,
            statusKey:   'committee.members.status.' . $row->status,
            roleKey:     'committee.members.role.member',
            joinedAt:    new \DateTimeImmutable($row->associated_at),
        );
    }

    private function hydrateViews(\Illuminate\Support\Collection $rows): array
    {
        if ($rows->isEmpty()) {
            return [];
        }

        // Batch-load user names to avoid N+1 query problem
        $memberIds = $rows->pluck('member_id')->unique()->values()->all();
        $userNames = User::whereIn('id', $memberIds)->pluck('name', 'id');

        return $rows->map(fn ($row) => new CommitteeMemberView(
            memberId:    $row->member_id,
            displayName: $userNames[$row->member_id] ?? 'Unknown Member',
            statusKey:   'committee.members.status.' . $row->status,
            roleKey:     'committee.members.role.member',
            joinedAt:    $row->associated_at instanceof \DateTimeImmutable
                ? $row->associated_at
                : new \DateTimeImmutable($row->associated_at->toDateTimeString()),
        ))->all();
    }
}
