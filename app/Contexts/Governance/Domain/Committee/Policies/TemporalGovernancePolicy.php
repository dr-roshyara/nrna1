<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use App\Contexts\Membership\Domain\ValueObjects\TemporalGovernanceState;
use DateTimeImmutable;

final class TemporalGovernancePolicy
{
    private const EXPIRING_WINDOW_DAYS = 30;

    public function evaluate(CommitteeFacts $facts, DateTimeImmutable $now): TemporalGovernanceState
    {
        if ($facts->term === null) {
            return TemporalGovernanceState::NO_TERM;
        }

        $termStart = $facts->term->start();
        $termEnd = $facts->term->end();

        if ($now < $termStart) {
            return TemporalGovernanceState::NOT_YET_ACTIVE;
        }

        $daysUntilExpiry = (int) $now->diff($termEnd)->format('%r%a');

        if ($now > $termEnd && $facts->operationalState === 'ACTIVE') {
            return TemporalGovernanceState::CARETAKER;
        }

        if ($now >= $termEnd) {
            return TemporalGovernanceState::EXPIRED;
        }

        if ($daysUntilExpiry <= self::EXPIRING_WINDOW_DAYS && $daysUntilExpiry >= 0) {
            return TemporalGovernanceState::EXPIRING;
        }

        return TemporalGovernanceState::VALID;
    }
}
