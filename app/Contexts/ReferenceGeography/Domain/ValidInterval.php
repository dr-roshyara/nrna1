<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A claim's valid time [from, to) (v3.11 §3): from inclusive, to exclusive; no `to` = until further notice.
 * Empty and reversed intervals are refused.
 */
final readonly class ValidInterval
{
    public function __construct(public ValidDate $from, public ?ValidDate $to = null)
    {
        if ($to !== null && !$from->isBefore($to)) {
            throw new \InvalidArgumentException('A valid interval needs from < to.');
        }
    }

    public function isOpen(): bool
    {
        return $this->to === null;
    }

    public function covers(ValidDate $t): bool
    {
        return !$t->isBefore($this->from) && ($this->to === null || $t->isBefore($this->to));
    }

    /** The common part, or null if they share no day. */
    public function intersect(self $other): ?self
    {
        if (!$this->overlaps($other)) {
            return null;
        }
        $from = $this->from->isBefore($other->from) ? $other->from : $this->from;
        $to = match (true) {
            $this->to === null => $other->to,
            $other->to === null => $this->to,
            default => $this->to->isBefore($other->to) ? $this->to : $other->to,
        };

        return new self($from, $to);
    }

    public function overlaps(self $other): bool
    {
        $startsBeforeOtherEnds = $other->to === null || $this->from->isBefore($other->to);
        $otherStartsBeforeThisEnds = $this->to === null || $other->from->isBefore($this->to);

        return $startsBeforeOtherEnds && $otherStartsBeforeThisEnds;
    }
}
