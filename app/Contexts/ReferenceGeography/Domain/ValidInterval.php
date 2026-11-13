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

    public function overlaps(self $other): bool
    {
        $startsBeforeOtherEnds = $other->to === null || $this->from->isBefore($other->to);
        $otherStartsBeforeThisEnds = $this->to === null || $other->from->isBefore($this->to);

        return $startsBeforeOtherEnds && $otherStartsBeforeThisEnds;
    }
}
