<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Events\AdjudicationExpired;
use App\Contexts\Adjudication\Infrastructure\Outbox\AdjudicationExpiredHydrator;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Coverage for `AdjudicationExpiredHydrator` — **written after the fact, and that is the point.**
 *
 * The hydrator shipped with WP-6, is registered in `AdjudicationServiceProvider::boot()` (line 81),
 * and had **no unit test and no registry-wiring assertion**. The gap surfaced while writing
 * WP-4C-1's equivalents, and was recorded there as an observation about coverage rather than a
 * defect claim. **This closes it.**
 *
 * **No behaviour is changed and none is asserted beyond what the shipped hydrator already does.**
 * If any assertion here fails, that is implementation evidence about accepted work — to be
 * reported, not silently repaired.
 *
 * **The version window is v1-only**, because v1 is all that exists (ADR-T5): an unsupported
 * version and a missing required field are **two distinct failures**, and both must throw.
 *
 * **`AdjudicationExpired` carries no outcome, no legitimacy and no reason** — a horizon decides
 * nothing (Constitutional Policy 4), so there is nothing to report but the failure to conclude.
 * The absence of those fields is asserted, not merely unmentioned.
 *
 * Traceability: WP-6 · EPIC-004K §197 · Constitutional Policy 4 · ADR-T5 (version, never mutate) ·
 * ADR-T3 · the WP-3A rule (publication **and** registration) · coverage observation recorded in
 * `2026-08-04-wp4c1-acceptance-evidence.md` §6.
 */
final class AdjudicationExpiredHydratorTest extends TestCase
{
    /** The v1 wire shape, exactly as `OutboxEventAdapter::writeAdjudicationExpired()` writes it. */
    private function payloadV1(): array
    {
        return [
            'schema_version' => 1,
            'challengeRef' => '88888888-8888-4888-8888-888888888888',
            'expiredAt' => '2026-09-28T10:00:00+00:00',
        ];
    }

    private function hydrator(): AdjudicationExpiredHydrator
    {
        return new AdjudicationExpiredHydrator();
    }

    public function test_the_event_type_is_the_canonical_name(): void
    {
        $this->assertSame('AdjudicationExpired', $this->hydrator()->eventType());
    }

    public function test_a_v1_payload_reconstructs_an_equal_event(): void
    {
        $event = $this->hydrator()->hydrate($this->payloadV1());

        $this->assertInstanceOf(AdjudicationExpired::class, $event);
        $this->assertSame('88888888-8888-4888-8888-888888888888', $event->challengeRef->toString());
        $this->assertSame(
            '2026-09-28T10:00:00+00:00',
            $event->expiredAt->setTimezone(new \DateTimeZone('UTC'))->format('c'),
        );
    }

    /**
     * Type fidelity. A hydrator returning primitives satisfies value equality and breaks every
     * consumer — the defect `assertInstanceOf` exists to catch.
     */
    public function test_the_hydrator_returns_value_objects_not_strings(): void
    {
        $event = $this->hydrator()->hydrate($this->payloadV1());

        $this->assertInstanceOf(ChallengeRef::class, $event->challengeRef);
        $this->assertInstanceOf(DateTimeImmutable::class, $event->expiredAt);
    }

    /**
     * Constitutional Policy 4: a timer concludes nothing. The event must carry no outcome, no
     * legitimacy and no reason — asserted structurally so a later payload addition cannot slip in
     * unnoticed.
     */
    public function test_the_event_carries_no_verdict(): void
    {
        $reflection = new \ReflectionClass(AdjudicationExpired::class);

        $properties = array_map(
            static fn (\ReflectionProperty $p): string => $p->getName(),
            $reflection->getProperties(),
        );

        $this->assertSame(['challengeRef', 'expiredAt'], $properties);

        foreach (['outcome', 'legitimacy', 'reason', 'concludedByAuthority'] as $forbidden) {
            $this->assertNotContains(
                $forbidden,
                $properties,
                "AdjudicationExpired must carry no verdict: '{$forbidden}' would make expiry a ruling",
            );
        }
    }

    // ── the v1-only window: two distinct failures ────────────────────────────

    public function test_an_unsupported_schema_version_is_rejected(): void
    {
        $payload = $this->payloadV1();
        $payload['schema_version'] = 2;

        $this->expectException(InvalidArgumentException::class);

        $this->hydrator()->hydrate($payload);
    }

    /**
     * An ABSENT `schema_version` defaults to 1 and is accepted — the shipped hydrator's
     * `?? 1`. Asserted because it is a deliberate tolerance, not an oversight, and a future
     * change would otherwise silently narrow the window.
     */
    public function test_an_absent_schema_version_defaults_to_v1_and_is_accepted(): void
    {
        $payload = $this->payloadV1();
        unset($payload['schema_version']);

        $event = $this->hydrator()->hydrate($payload);

        $this->assertInstanceOf(AdjudicationExpired::class, $event);
    }

    #[DataProvider('requiredFields')]
    public function test_a_payload_missing_a_required_field_is_rejected(string $field): void
    {
        $payload = $this->payloadV1();
        unset($payload[$field]);

        $this->expectException(InvalidArgumentException::class);

        $this->hydrator()->hydrate($payload);
    }

    /** @return array<string, array{string}> */
    public static function requiredFields(): array
    {
        return [
            'challengeRef' => ['challengeRef'],
            'expiredAt' => ['expiredAt'],
        ];
    }
}
