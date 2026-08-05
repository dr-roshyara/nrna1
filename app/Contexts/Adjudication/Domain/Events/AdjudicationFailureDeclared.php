<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Events;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\DomainEvent;
use DateTimeImmutable;

/**
 * PM-7: the authority found the evidence insufficient — **"the evidence was insufficient; no
 * ruling can issue"** (EPIC-004K §10).
 *
 * **A distinct terminal fact, not a verdict.** No outcome and no legitimacy ride on it: nothing
 * was ruled *on the contested outcome*, only that it could not be ruled. That distinction is the
 * same one `AdjudicationExpired` preserves — but the two are not interchangeable: **expiry is a
 * failure of TIME, this is a failure of EVIDENCE**, and only this one carries an authority and a
 * stated ground, because a person decided it.
 *
 * **The event CARRIES facts; it does not reconstruct them.** Every field is a value the process
 * record already holds after `concludeFailureDeclared()` — `challengeRef` · `reason` ·
 * `concludedByAuthority` · `concludedAt`. Nothing here is derived, defaulted or computed (AP-2).
 *
 * **WHY THE PARAMETERS ARE NON-NULLABLE, DELIBERATELY:** three of the four source accessors on
 * `AdjudicationProcessState` are nullable (`reason()`, `concludedByAuthority()`, `concludedAt()`),
 * and the type system cannot know that `concludeFailureDeclared()` sets all three together.
 * **The invariant belongs to the aggregate; this event only carries it** — so the absent case is
 * made UNREPRESENTABLE here rather than defended here, and the construction site fails closed
 * (AP-1). An event that could hold a null `reason` would be an event that could announce an
 * insufficiency nobody stated.
 *
 * **`consideredEvidence` IS DELIBERATELY ABSENT.** §10 states the occurrence as insufficiency,
 * not as an evidence manifest, and no consumer need has been demonstrated. Adding it would be
 * speculative payload — and under ADR-T11 every payload addition is a question about what crosses
 * a boundary, never a convenience.
 *
 * **ADR-T11:** `Reason` is the authority's stated ground. No evidence content, no voter↔vote
 * linkage. The event names an EXISTING concept (`AdjudicationProcessStatus::ConcludedFailureDeclared`)
 * and mints no new domain vocabulary.
 *
 * Traceability: EPIC-004K §10 (messages produced) · PM-7 · **R-88** (WP-4C-1's subdivision) ·
 * **R-89** (authorization) · AP-1 · AP-2 · ADR-T11 ·
 * plan `docs/plans/20260804-1900-wp4c1-adjudicationfailuredeclared-plan.md` §3, §11.
 */
final readonly class AdjudicationFailureDeclared implements DomainEvent
{
    public function __construct(
        public ChallengeRef $challengeRef,
        public Reason $reason,
        public IssuedByAuthority $declaredByAuthority,
        public DateTimeImmutable $declaredAt,
    ) {
    }
}
