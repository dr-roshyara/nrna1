<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
use App\Contexts\Adjudication\Infrastructure\Outbox\DeterminationIssuedHydrator;
use PHPUnit\Framework\TestCase;

/**
 * Context-owned hydrator for DeterminationIssued — the Adjudication side of the
 * Event Registry. Reconstructs the domain event from the exact payload shape
 * written by OutboxEventAdapter::writeDeterminationIssued().
 *
 * Traceability:
 *   Blueprint: Push B §6 (Relay Registry — hydrators per context), §16 step 4
 *   ADR:       ADR-T3, ADR-T5 (schema_version tolerated; v1 payloads)
 *   Matrix:    Push B — Event Registry
 *   Context:   Adjudication (Infrastructure)
 */
final class DeterminationIssuedHydratorTest extends TestCase
{
    /** Exact shape written by OutboxEventAdapter (Push A) — the wire contract. */
    private function payload(): array
    {
        return [
            'determinationId' => '9f1b6a2e-3c4d-4e5f-8a9b-0c1d2e3f4a5b',
            'challengeRef' => 'challenge-7c8d9e0f',
            'outcome' => 'upheld',
            'legitimacy' => 'legitimate',
            'reason' => 'Evidence envelope hash mismatch confirmed by audit.',
            'evidenceEnvelopeRef' => 'envelope-sha256-abc123',
            'issuedByAuthority' => 'authority-cab-01',
            'jurisdiction' => 'election-2026-eu',
            'occurredAt' => '2026-07-06T10:15:30+00:00',
        ];
    }

    public function test_event_type_matches_canonical_catalog_name(): void
    {
        $this->assertSame('DeterminationIssued', (new DeterminationIssuedHydrator())->eventType());
    }

    public function test_hydrates_domain_event_with_full_payload_fidelity(): void
    {
        $event = (new DeterminationIssuedHydrator())->hydrate($this->payload());

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
    }

    public function test_hydrates_dismissed_outcome(): void
    {
        $payload = $this->payload();
        $payload['outcome'] = 'dismissed';
        $payload['legitimacy'] = 'illegitimate';

        $event = (new DeterminationIssuedHydrator())->hydrate($payload);

        $this->assertSame(DeterminationOutcome::Dismissed, $event->outcome);
        $this->assertSame(Legitimacy::Illegitimate, $event->legitimacy);
    }

    public function test_tolerates_v1_schema_version_marker(): void
    {
        // ADR-T5: additive fields (schema_version) must not break v1 hydration.
        $payload = $this->payload();
        $payload['schema_version'] = 1;

        $event = (new DeterminationIssuedHydrator())->hydrate($payload);

        $this->assertInstanceOf(DeterminationIssued::class, $event);
    }

    public function test_missing_required_field_fails_loudly(): void
    {
        $payload = $this->payload();
        unset($payload['determinationId']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('determinationId');

        (new DeterminationIssuedHydrator())->hydrate($payload);
    }
}
