<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member\Events;

use App\Contexts\Membership\Domain\Member\MemberId;
use DateTimeImmutable;

final readonly class MemberSuspended
{
    public function __construct(
        private MemberId $memberId,
        private string $reason,
        private DateTimeImmutable $occurredAt
    ) {}

    public function getMemberId(): MemberId
    {
        return $this->memberId;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
