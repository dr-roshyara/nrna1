<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * A fact as recorded: its position in the Member's log (seq) and when the
 * system of record learned it (recordedAt). Both are assigned at commit (Slice 1a).
 */
final readonly class RecordedFact
{
    private function __construct(private int $seq, private Instant $recordedAt, private Fact $fact)
    {
        if ($seq < 1) {
            throw new \InvalidArgumentException('seq starts at 1.');
        }
    }

    public static function of(int $seq, Instant $recordedAt, Fact $fact): self
    {
        return new self($seq, $recordedAt, $fact);
    }

    public function seq(): int
    {
        return $this->seq;
    }

    public function recordedAt(): Instant
    {
        return $this->recordedAt;
    }

    public function fact(): Fact
    {
        return $this->fact;
    }
}
