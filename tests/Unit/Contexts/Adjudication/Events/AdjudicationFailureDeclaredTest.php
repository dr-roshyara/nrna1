<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Events;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Events\AdjudicationFailureDeclared;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * WP-4C-1 · K1 (RED) — the announced fact: **"the evidence was insufficient; no ruling can
 * issue"** (EPIC-004K §10, PM-7).
 *
 * THE EVENT CARRIES A FACT; IT DOES NOT RECONSTRUCT ONE. Every field is a value the process
 * record already holds after `concludeFailureDeclared()` — `challengeRef` · `reason` ·
 * `concludedByAuthority` · `concludedAt`. **The event derives, defaults and computes nothing**
 * (AP-2), and this test asserts exactly that: what goes in comes out, as its value-object type.
 *
 * **`consideredEvidence` is DELIBERATELY ABSENT** and this test does not look for it. §10 states
 * the occurrence as insufficiency, not as an evidence manifest, and no consumer need has been
 * demonstrated — so including it would be speculative payload. Recorded in the plan (§3) as a
 * question for approval rather than a decision taken in a test.
 *
 * **ADR-T11:** `Reason` is the authority's stated ground. No evidence content, no voter↔vote
 * linkage, in the payload or in this test's fixtures.
 *
 * Traceability: R-88 · R-89 · EPIC-004K §10 · AP-2 · ADR-T11 ·
 * plan `docs/plans/20260804-1900-wp4c1-adjudicationfailuredeclared-plan.md` §3, §11, §12.
 */
final class AdjudicationFailureDeclaredTest extends TestCase
{
    private function declaredAt(): DateTimeImmutable
    {
        return new DateTimeImmutable('2026-08-04T11:30:00+00:00');
    }

    private function event(): AdjudicationFailureDeclared
    {
        return new AdjudicationFailureDeclared(
            ChallengeRef::fromString('ch-4c1-1'),
            Reason::fromString('The admitted evidence does not establish the contested tally.'),
            IssuedByAuthority::fromString('constitutional-council'),
            $this->declaredAt(),
        );
    }

    public function test_k1_the_event_carries_the_records_four_facts_unchanged(): void
    {
        $event = $this->event();

        $this->assertSame('ch-4c1-1', $event->challengeRef->toString());
        $this->assertSame(
            'The admitted evidence does not establish the contested tally.',
            $event->reason->toString(),
        );
        $this->assertSame('constitutional-council', $event->declaredByAuthority->toString());
        $this->assertSame(
            '2026-08-04T11:30:00+00:00',
            $event->declaredAt->format('c'),
        );
    }

    /**
     * Type fidelity, not merely value equality. A payload of raw strings would satisfy the
     * assertions above and still break every consumer — the WP-4B round-trip lesson applied
     * to the event itself.
     */
    public function test_k1_the_event_exposes_value_objects_not_strings(): void
    {
        $event = $this->event();

        $this->assertInstanceOf(ChallengeRef::class, $event->challengeRef);
        $this->assertInstanceOf(Reason::class, $event->reason);
        $this->assertInstanceOf(IssuedByAuthority::class, $event->declaredByAuthority);
        $this->assertInstanceOf(DateTimeImmutable::class, $event->declaredAt);
    }

    /**
     * The four constructor parameters are NON-NULLABLE by design (plan §11): three of the four
     * source accessors on `AdjudicationProcessState` are nullable, and the type system does not
     * know they are set together by `concludeFailureDeclared()`.
     *
     * **The invariant is the aggregate's; the event only carries it.** So the null case must be
     * unrepresentable here rather than defended here — the construction SITE fails closed
     * (AP-1). This test pins the contract that makes that split possible.
     */
    public function test_k1_the_event_cannot_be_constructed_with_a_missing_fact(): void
    {
        $reflection = new \ReflectionClass(AdjudicationFailureDeclared::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor, 'the event must declare a constructor');

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            $this->assertNotNull($type, "parameter \${$parameter->getName()} must be typed");
            $this->assertFalse(
                $type->allowsNull(),
                "parameter \${$parameter->getName()} must NOT be nullable — the aggregate "
                . 'guarantees the fact; the event may not represent its absence',
            );
        }
    }
}
