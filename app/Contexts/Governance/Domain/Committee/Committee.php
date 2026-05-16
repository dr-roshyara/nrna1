<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee;

use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;
use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use App\Contexts\Governance\Domain\Committee\Events\MemberRemovedFromCommittee;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class Committee
{
    /** @var array<string, CommitteeRole> member ID => role mapping */
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

    public function addMember(MemberId $memberId, CommitteeRole $role): void
    {
        if ($this->isMemberAssigned($memberId)) {
            throw new \DomainException('Member already assigned to this committee');
        }

        $this->members[$memberId->value()] = $role;

        $this->recordEvent(new MemberAssignedToCommittee(
            $this->id,
            $memberId,
            $this->tenantId,
            $role
        ));
    }

    public function removeMember(MemberId $memberId): void
    {
        unset($this->members[$memberId->value()]);

        $this->recordEvent(new MemberRemovedFromCommittee(
            $this->id,
            $memberId,
            $this->tenantId
        ));
    }

    public function isMemberAssigned(MemberId $memberId): bool
    {
        return isset($this->members[$memberId->value()]);
    }

    public function getMemberRole(MemberId $memberId): ?CommitteeRole
    {
        return $this->members[$memberId->value()] ?? null;
    }

    /**
     * Returns array of member ID => role mappings
     */
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
