<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee;

use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use App\Contexts\Governance\Domain\Committee\Events\MemberRemovedFromCommittee;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class Committee
{
    private array $members = [];
    private array $events = [];

    private function __construct(
        private CommitteeId $id,
        private TenantId $tenantId
    ) {}

    public static function create(
        CommitteeId $id,
        TenantId $tenantId
    ): self
    {
        return new self($id, $tenantId);
    }

    public function addMember(MemberId $memberId): void
    {
        if ($this->isMemberAssigned($memberId)) {
            return; // idempotency: ignore duplicate
        }

        $this->members[] = $memberId;

        $this->recordEvent(new MemberAssignedToCommittee(
            $this->id,
            $memberId,
            $this->tenantId
        ));
    }

    public function removeMember(MemberId $memberId): void
    {
        $this->members = array_filter(
            $this->members,
            fn (MemberId $id) => !$id->equals($memberId)
        );

        $this->recordEvent(new MemberRemovedFromCommittee(
            $this->id,
            $memberId,
            $this->tenantId
        ));
    }

    public function isMemberAssigned(MemberId $memberId): bool
    {
        foreach ($this->members as $member) {
            if ($member->equals($memberId)) {
                return true;
            }
        }

        return false;
    }

    public function getMembers(): array
    {
        return $this->members;
    }

    public function getId(): CommitteeId
    {
        return $this->id;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    private function recordEvent(object $event): void
    {
        $this->events[] = $event;
    }

    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}
