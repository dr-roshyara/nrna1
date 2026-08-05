<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
use App\Contexts\Adjudication\Infrastructure\Outbox\DeterminationIssuedHydrator;
use PHPUnit\Framework\TestCase;

/**
 * Context-owned hydrator for DeterminationIssued — the Adjudication side of the
 * Event Registry. Reconstructs the domain event from the exact payload shape
 * written by OutboxEventAdapter::writeDeterminationIssued().
 *
 * VERSION WINDOW (WP-1, ADR-T22): vCurrent = 3 (adds the fixed considered
 * evidence set), vPrevious = 2 (contested outcome, no evidence set — hydrates
 * null). v1 is RETIRED per the versioning rule (Event_Registry.md: vCurrent +
 * vPrevious ONLY) — a v1 payload (schema_version absent or 1) is rejected
 * loudly, never silently tolerated. Pre-deploy check rides the slice: zero
 * pending v1 rows (verified, not assumed).
 *
 * Traceability:
 *   Blueprint: Push B §6, §16 step 4
 *   ADR:       ADR-T3, ADR-T5, ADR-T22 (v3 succession) · ADR-PL-01 (v2 history)
 *   Matrix:    Push B — Event Registry · roadmap WP-1 keystone tests
 *   Context:   Adjudication (Infrastructure)
 */
final class DeterminationIssuedHydratorTest extends TestCase
{
    /** Exact v3 shape written by OutboxEventAdapter — the current wire contract. */
    private function payloadV3(): array
    {
        return [
            'schema_version' => 3,
            'determinationId' => '9f1b6a2e-3c4d-4e5f-8a9b-0c1d2e3f4a5b',
            'challengeRef' => 'challenge-7c8d9e0f',
            'outcome' => 'upheld',
            'legitimacy' => 'legitimate',
            'reason' => 'Evidence envelope hash mismatch confirmed by audit.',
            'evidenceEnvelopeRef' => 'envelope-sha256-abc123',
            'issuedByAuthority' => 'authority-cab-01',
            'jurisdiction' => 'election-2026-eu',
            'contestedOutcome' => [
                'electionId' => 'election-1',
                'type' => 'election_result',
                'targetId' => 'result-1',
            ],
            'evidenceSet' => ['envelope-sha256-abc123', 'envelope-sha256-def456'],
            'occurredAt' => '2026-07-06T10:15:30+00:00',
        ];
    }

    /** vPrevious: the v2 shape (ADR-PL-01) — no evidenceSet. Still accepted. */
    private function payloadV2(): array
    {
        $payload = $this->payloadV3();
        $payload['schema_version'] = 2;
        unset($payload['evidenceSet']);

        return $payload;
    }

    public function test_event_type_matches_canonical_catalog_name(): void
    {
        $this->assertSame('DeterminationIssued', (new DeterminationIssuedHydrator())->eventType());
    }

    // ── KEYSTONE (WP-1): v3 round-trip — payload ↔ domain event, set intact ──

    public function test_hydrates_domain_event_with_full_v3_payload_fidelity(): void
    {
        $event = (new DeterminationIssuedHydrator())->hydrate($this->payloadV3());

        $this->assertInstanceOf(DeterminationIssued::class, $event);
        $this->assertSame('9f1b6a2e-3c4d-4e5f-8a9b-0c1d2e3f4a5b', $event->determinationId->toString());
        $this->assertSame('challenge-7c8d9e0f', $event->challengeRef->toString());
        $this->assertSame(DeterminationOutcome::Upheld, $event->outcome);
        $this->assertSame(Legitimacy::Legitimate, $event->legitimacy);
        $this->assertSame('Evidence envelope hash mismatch confirmed by audit.', $event->reason->toString());
        $this->assertSame('envelope-sha256-abc123', $event->evidenceEnvelopeRef->toString());
        $this->assertSame('authority-cab-01', $event->issuedByAuthority->toString());
        $this->assertSame('election-2026-eu', $event->jurisdiction->toString());
        $this->assertSame('2026-07-06T10:15:30+00:00', $event->occurredAt->format(DATE_ATOM));

        // The fixed considered set survives the wire intact (ADR-T22).
        $this->assertNotNull($event->evidenceSet);
        $this->assertSame(
            ['envelope-sha256-abc123', 'envelope-sha256-def456'],
            $event->evidenceSet->toArray(),
        );

        // v2 content is unchanged by the additive evolution.
        $this->assertNotNull($event->contestedOutcome);
        $this->assertSame('election-1', $event->contestedOutcome->electionId->toString());
        $this->assertSame(TargetType::ElectionResult, $event->contestedOutcome->type);
        $this->assertSame('result-1', $event->contestedOutcome->targetId->toString());
    }

    public function test_hydrates_dismissed_outcome(): void
    {
        $payload = $this->payloadV3();
        $payload['outcome'] = 'dismissed';
        $payload['legitimacy'] = 'illegitimate';

        $event = (new DeterminationIssuedHydrator())->hydrate($payload);

        $this->assertSame(DeterminationOutcome::Dismissed, $event->outcome);
        $this->assertSame(Legitimacy::Illegitimate, $event->legitimacy);
    }

    public function test_v3_payload_missing_evidence_set_is_rejected(): void
    {
        // v3 fixes the set at issuance — a v3 payload without it is malformed,
        // not tolerable (the wire contract and the aggregate evolve together).
        $payload = $this->payloadV3();
        unset($payload['evidenceSet']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('evidenceSet');

        (new DeterminationIssuedHydrator())->hydrate($payload);
    }

    // ── KEYSTONE (WP-1): v2 tolerance — vPrevious accepted, evidenceSet null ──

    public function test_v2_payload_hydrates_with_null_evidence_set(): void
    {
        $event = (new DeterminationIssuedHydrator())->hydrate($this->payloadV2());

        $this->assertInstanceOf(DeterminationIssued::class, $event);
        $this->assertNull($event->evidenceSet);

        // v2 semantics preserved: contested outcome still hydrated.
        $this->assertNotNull($event->contestedOutcome);
        $this->assertSame('election-1', $event->contestedOutcome->electionId->toString());
    }

    // ── KEYSTONE (WP-1): v1 rejection — the retired version is refused loudly ──

    public function test_v1_schema_version_is_rejected(): void
    {
        // ADR-T22 succession + versioning rule (vCurrent+vPrevious ONLY):
        // v1 leaves the window when v3 enters. Supersedes the former
        // v1-tolerance tests — succession, not history editing.
        $payload = $this->payloadV2();
        $payload['schema_version'] = 1;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('schema_version');

        (new DeterminationIssuedHydrator())->hydrate($payload);
    }

    public function test_missing_schema_version_is_treated_as_v1_and_rejected(): void
    {
        $payload = $this->payloadV2();
        unset($payload['schema_version']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('schema_version');

        (new DeterminationIssuedHydrator())->hydrate($payload);
    }

    public function test_missing_required_field_fails_loudly(): void
    {
        $payload = $this->payloadV3();
        unset($payload['determinationId']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('determinationId');

        (new DeterminationIssuedHydrator())->hydrate($payload);
    }
}
