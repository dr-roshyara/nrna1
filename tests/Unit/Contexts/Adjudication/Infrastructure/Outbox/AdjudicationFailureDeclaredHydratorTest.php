<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Events\AdjudicationFailureDeclared;
use App\Contexts\Adjudication\Infrastructure\Outbox\AdjudicationFailureDeclaredHydrator;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * WP-4C-1 · K2 + K3 (RED) — the REGISTRATION half of published-language status.
 *
 * **The WP-3A rule, quoted in `AdjudicationExpiredHydrator`: *"published language requires BOTH
 * publication and registration."*** A hydrator is not an optional convenience — without it the
 * event cannot be reconstructed from the outbox, and the failure is invisible until a consumer
 * cannot hydrate.
 *
 * **VERSION WINDOW: v1 only, because v1 is all that exists.** Any other version is rejected
 * loudly rather than guessed at (ADR-T5) — the `AdjudicationExpiredHydrator` precedent.
 *
 * **K3 IS TWO DISTINCT FAILURES, NOT ONE.** An unsupported schema version and an incomplete
 * payload are different defects with different causes, and a single test covering "invalid input"
 * would let either regress unnoticed.
 *
 * ⚠️ **RECORDED, NOT ACTED ON:** WP-6's `AdjudicationExpiredHydrator` has **no** dedicated unit
 * test and **no** registry-wiring assertion. This file and K4 therefore establish coverage that
 * the accepted precedent lacks. **An observation about WP-6's test coverage, NOT a defect claim
 * against accepted work** — and a reason WP-4C-1's evidence should not be described as parity.
 *
 * Traceability: R-88 · R-89 · ADR-T5 · ADR-T3 · EPIC-004K §10 · WP-3A rule ·
 * plan `docs/plans/20260804-1900-wp4c1-adjudicationfailuredeclared-plan.md` §12, §13.
 */
final class AdjudicationFailureDeclaredHydratorTest extends TestCase
{
    /** The v1 wire shape — the only version that exists. */
    private function payloadV1(): array
    {
        return [
            'schema_version' => 1,
            'challengeRef' => 'ch-4c1-1',
            'reason' => 'The admitted evidence does not establish the contested tally.',
            'declaredByAuthority' => 'constitutional-council',
            'declaredAt' => '2026-08-04T11:30:00+00:00',
        ];
    }

    private function hydrator(): AdjudicationFailureDeclaredHydrator
    {
        return new AdjudicationFailureDeclaredHydrator();
    }

    // ── K2: reconstruction ───────────────────────────────────────────────────

    public function test_k2_the_event_type_is_the_canonical_name(): void
    {
        $this->assertSame('AdjudicationFailureDeclared', $this->hydrator()->eventType());
    }

    public function test_k2_a_v1_payload_reconstructs_an_equal_event(): void
    {
        $event = $this->hydrator()->hydrate($this->payloadV1());

        $this->assertInstanceOf(AdjudicationFailureDeclared::class, $event);
        $this->assertSame('ch-4c1-1', $event->challengeRef->toString());
        $this->assertSame(
            'The admitted evidence does not establish the contested tally.',
            $event->reason->toString(),
        );
        $this->assertSame('constitutional-council', $event->declaredByAuthority->toString());
        $this->assertSame(
            '2026-08-04T11:30:00+00:00',
            $event->declaredAt->setTimezone(new \DateTimeZone('UTC'))->format('c'),
        );
    }

    /**
     * Type fidelity. A hydrator returning raw strings would satisfy value equality and break
     * every consumer — the defect `assertInstanceOf` exists to catch (WP-4B).
     */
    public function test_k2_the_hydrator_returns_value_objects_not_strings(): void
    {
        $event = $this->hydrator()->hydrate($this->payloadV1());

        $this->assertInstanceOf(ChallengeRef::class, $event->challengeRef);
        $this->assertInstanceOf(Reason::class, $event->reason);
        $this->assertInstanceOf(IssuedByAuthority::class, $event->declaredByAuthority);
        $this->assertInstanceOf(DateTimeImmutable::class, $event->declaredAt);
    }

    // ── K3a: an unsupported schema version is refused ────────────────────────

    public function test_k3_an_unsupported_schema_version_is_rejected(): void
    {
        $payload = $this->payloadV1();
        $payload['schema_version'] = 2;

        $this->expectException(InvalidArgumentException::class);

        $this->hydrator()->hydrate($payload);
    }

    // ── K3b: an incomplete payload is refused — a DIFFERENT failure ──────────

    #[DataProvider('requiredFields')]
    public function test_k3_a_payload_missing_a_required_field_is_rejected(string $field): void
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
            'reason' => ['reason'],
            'declaredByAuthority' => ['declaredByAuthority'],
            'declaredAt' => ['declaredAt'],
        ];
    }
}
