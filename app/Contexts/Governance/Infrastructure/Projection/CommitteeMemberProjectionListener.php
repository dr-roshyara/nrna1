<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Projection;

use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use App\Contexts\Governance\Domain\Committee\Events\MemberRemovedFromCommittee;
use App\Models\CommitteeMemberProjection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

/**
 * CommitteeMemberProjectionListener
 *
 * Event handler for committee membership events.
 *
 * Responsibility: Transform domain events into UI-ready projection state
 *
 * Key principle: This is CQRS write-side for the read model.
 * - No business logic
 * - No validation
 * - Only event → state transformation
 * - Idempotent via updateOrCreate
 * - Denormalizes member data for read optimization
 */
final class CommitteeMemberProjectionListener
{
    public function onMemberAssigned(MemberAssignedToCommittee $event): void
    {
        // Fetch member data from member_directories (denormalized read model)
        $member = DB::table('member_directories')
            ->where('member_id', $event->memberId->value())
            ->where('organisation_id', $event->tenantId->value())
            ->first(['display_name', 'email']);

        CommitteeMemberProjection::updateOrCreate(
            [
                'committee_id' => $event->committeeId->value(),
                'member_id' => $event->memberId->value(),
            ],
            [
                'id' => Uuid::uuid4()->toString(),
                'tenant_id' => $event->tenantId->value(),
                'member_name' => $member?->display_name ?? 'N/A',
                'member_email' => $member?->email ?? 'N/A',
                'role' => $event->role()->value,
                'assigned_at' => $event->occurredAt,
            ]
        );
    }

    public function onMemberRemoved(MemberRemovedFromCommittee $event): void
    {
        CommitteeMemberProjection::query()
            ->where('committee_id', $event->committeeId->value())
            ->where('member_id', $event->memberId->value())
            ->delete();
    }
}
