<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Fee\Events;

use App\Contexts\Membership\Domain\Fee\FeeId;
use DateTimeImmutable;

final readonly class FeeWaived
{
    public function __construct(
        private FeeId $feeId,
        private string $reason,
        private DateTimeImmutable $occurredAt
    ) {}

    public function getFeeId(): FeeId
    {
        return $this->feeId;
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
