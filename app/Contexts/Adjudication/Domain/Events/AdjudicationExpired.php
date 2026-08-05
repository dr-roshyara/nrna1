<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Events;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\DomainEvent;
use DateTimeImmutable;

/**
 * The adjudication horizon elapsed without a conclusion — a **distinct terminal
 * fact**, never a verdict (EPIC-004K §57 · Constitutional Policy 4).
 *
 * PUBLISHED LANGUAGE (ARB Decision A, 2026-07-31). It crosses the boundary because
 * §197 assigns Contestation the challenge's disposition, and Contestation cannot
 * discharge that responsibility without learning the fact. It carries **no outcome, no
 * legitimacy and no reason** — there is nothing to report but the failure to conclude.
 *
 * The event names an EXISTING concept (`AdjudicationProcessStatus::Expired`); it mints
 * no new domain vocabulary.
 */
final readonly class AdjudicationExpired implements DomainEvent
{
    public function __construct(
        public ChallengeRef $challengeRef,
        public DateTimeImmutable $expiredAt,
    ) {
    }
}
