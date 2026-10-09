<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;

/** A fact that was recorded: where (Member, seq) and when the system learned it. */
final readonly class Appended
{
    public function __construct(
        private MemberId $memberId,
        private FactId $factId,
        private int $seq,
        private Instant $recordedAt,
    ) {
    }

    public function memberId(): MemberId
    {
        return $this->memberId;
    }

    public function factId(): FactId
    {
        return $this->factId;
    }

    public function seq(): int
    {
        return $this->seq;
    }

    public function recordedAt(): Instant
    {
        return $this->recordedAt;
    }
}
