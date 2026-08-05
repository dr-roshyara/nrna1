<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Contestation\Infrastructure\Outbox;

use App\Contexts\Contestation\Domain\Events\ChallengeRouted;
use App\Contexts\Contestation\Infrastructure\Outbox\ChallengeRoutedHydrator;
use PHPUnit\Framework\TestCase;

/**
 * WP-3A — `ChallengeRouted` becomes PUBLISHED LANGUAGE (ADR-T21).
 *
 * Until now `ChallengeRouted` was an INTERNAL Contestation domain event: recorded
 * by `Challenge::route()`, never announced. ADR-T21 makes it the correction
 * loop's head trigger, consumed by the Adjudication Process Manager — and
 * publication is a REGISTRY act, not merely a code act: a published event must be
 * reconstructable, or the Event Registry is incomplete.
 *
 * Contestation owns the Challenge and therefore owns and publishes this event
 * (TP-2: Contestation *requests*, never creates an adjudication). The crossing
 * carries identity as strings only — the consumer reconstructs its own local VOs
 * (ADR-T16).
 *
 * NOT in this slice: the correlation-origin relocation (WP-3B — it depends on the
 * existence of a routing application service, which does not yet exist) and
 * consumer registration (WP-4). Nothing here mints provenance.
 *
 * Traceability: ADR-T21 · ADR-T5 + `Event_Registry.md` (versioning) ·
 * `Canonical_Event_Catalog_v1.0.md` · ADR-T20 (lifecycle: …→Routed→…) · ADR-T11.
 */
final class ChallengeRoutedHydratorTest extends TestCase
{
    /** The exact shape written by ChallengeOutboxAdapter — the wire contract. */
    private function payload(): array
    {
        return [
            'schema_version' => 1,
            'challengeId' => '7c8d9e0f-1a2b-3c4d-5e6f-708192a3b4c5',
            'routedTo' => 'constitutional-council',
            'occurredAt' => '2026-07-30T10:15:30+00:00',
        ];
    }

    public function test_event_type_matches_the_canonical_catalog_name(): void
    {
        $this->assertSame('ChallengeRouted', (new ChallengeRoutedHydrator())->eventType());
    }

    // ── KEYSTONE: round-trip fidelity ───────────────────────────────────────

    public function test_hydrates_the_domain_event_with_full_payload_fidelity(): void
    {
        $event = (new ChallengeRoutedHydrator())->hydrate($this->payload());

        $this->assertInstanceOf(ChallengeRouted::class, $event);
        $this->assertSame('7c8d9e0f-1a2b-3c4d-5e6f-708192a3b4c5', $event->challengeId->toString());
        $this->assertSame('constitutional-council', $event->routedTo);
        $this->assertSame('2026-07-30T10:15:30+00:00', $event->occurredAt->format(DATE_ATOM));
    }

    public function test_the_domain_event_stays_minimal_after_hydration(): void
    {
        // The PB-005 F-2 ruling: the domain event stays minimal; publication-time
        // enrichment (if ever needed) is the Application layer's, never the event's.
        // Reconstruction must therefore add nothing beyond the three recorded facts.
        $event = (new ChallengeRoutedHydrator())->hydrate($this->payload());

        $this->assertSame(
            ['challengeId', 'routedTo', 'occurredAt'],
            array_keys(get_object_vars($event)),
        );
    }

    // ── Versioning (ADR-T5 · Event Registry) ────────────────────────────────

    public function test_unsupported_schema_version_is_rejected(): void
    {
        // A newly published event starts at v1; there is no vPrevious to tolerate.
        $payload = $this->payload();
        $payload['schema_version'] = 2;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('schema_version');

        (new ChallengeRoutedHydrator())->hydrate($payload);
    }

    public function test_absent_schema_version_is_treated_as_v1(): void
    {
        // Consistent with the house convention: absent marker = version 1.
        $payload = $this->payload();
        unset($payload['schema_version']);

        $this->assertInstanceOf(ChallengeRouted::class, (new ChallengeRoutedHydrator())->hydrate($payload));
    }

    public function test_missing_required_field_fails_loudly(): void
    {
        $payload = $this->payload();
        unset($payload['challengeId']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('challengeId');

        (new ChallengeRoutedHydrator())->hydrate($payload);
    }

    // ── Anonymity (ADR-T11 / AT-Q7) ─────────────────────────────────────────

    public function test_the_wire_contract_carries_no_voter_or_vote_identifier(): void
    {
        // The published payload announces that a challenge was routed — never who
        // raised it, and never anything that could link a voter to a vote.
        $keys = array_keys($this->payload());

        foreach (['userId', 'user_id', 'voterId', 'voter_id', 'voteId', 'vote_id'] as $forbidden) {
            $this->assertNotContains($forbidden, $keys);
        }
    }
}
