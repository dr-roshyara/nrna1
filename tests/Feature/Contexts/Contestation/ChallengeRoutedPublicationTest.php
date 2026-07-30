<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Contestation;

use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Events\ChallengeRouted;
use App\Contexts\Contestation\Infrastructure\Outbox\ChallengeRoutedHydrator;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * WP-3A — publication of `ChallengeRouted` over the existing transactional outbox
 * (ADR-T21 · ADR-T3), and its registration in the Event Registry.
 *
 * Publication is a REGISTRY act: an event that travels the wire without a
 * registered hydrator is unreconstructable, so the registry — not just the
 * adapter — is part of this slice's acceptance.
 *
 * PROVENANCE IS SUPPLIED, NEVER MINTED HERE. WP-3A does not relocate the
 * correlation origin: that is WP-3B, which depends on the existence of a routing
 * application service (absent today). These tests therefore pass provenance in
 * explicitly, exactly as every other producer receives it.
 *
 * Traceability: ADR-T21 · ADR-T3 (transactional outbox) · ADR-MP-06 (provenance
 * supplied at publish time) · ADR-T5 (schema_version) · ADR-T11.
 */
final class ChallengeRoutedPublicationTest extends TestCase
{
    use RefreshDatabase;

    private string $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        $org = Organisation::create([
            'name' => 'ChallengeRouted Publication Test Org',
            'slug' => 'challenge-routed-publication-test-org',
            'type' => 'tenant',
            'is_default' => false,
        ]);
        $this->tenantId = (string) $org->id;
        TenantContext::set($this->tenantId);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    private function routedEvent(): ChallengeRouted
    {
        return new ChallengeRouted(
            ChallengeId::fromString('7c8d9e0f-1a2b-3c4d-5e6f-708192a3b4c5'),
            'constitutional-council',
            new DateTimeImmutable('2026-07-30T10:15:30+00:00'),
        );
    }

    private function outbox(): ChallengeEventOutbox
    {
        return $this->app->make(ChallengeEventOutbox::class);
    }

    // ── KEYSTONE: the event is published over the outbox at schema v1 ────────

    public function test_routing_is_published_to_the_outbox_as_challenge_routed(): void
    {
        $this->outbox()->enqueue(
            EventProvenance::start('conversation-1'),
            $this->routedEvent(),
        );

        $row = DB::table('outbox_events')
            ->where('organisation_id', $this->tenantId)
            ->where('event_type', 'ChallengeRouted')
            ->first();

        $this->assertNotNull($row, 'ChallengeRouted must be publishable — ADR-T21 makes it published language');
        $this->assertSame('Challenge', $row->aggregate_type);
        $this->assertSame('7c8d9e0f-1a2b-3c4d-5e6f-708192a3b4c5', $row->aggregate_id);
        $this->assertSame('pending', $row->status);

        $payload = json_decode((string) $row->payload, true);
        $this->assertSame(1, $payload['schema_version']);
        $this->assertSame('7c8d9e0f-1a2b-3c4d-5e6f-708192a3b4c5', $payload['challengeId']);
        $this->assertSame('constitutional-council', $payload['routedTo']);
        $this->assertSame('2026-07-30T10:15:30+00:00', $payload['occurredAt']);
    }

    // ── Provenance is propagated unchanged, never invented ──────────────────

    public function test_supplied_provenance_is_stamped_unchanged(): void
    {
        // ADR-MP-06: provenance is supplied explicitly at publish time. WP-3A adds
        // no mint site — the correlation origin stays where it is until WP-3B.
        $this->outbox()->enqueue(
            EventProvenance::start('conversation-42'),
            $this->routedEvent(),
        );

        $row = DB::table('outbox_events')
            ->where('organisation_id', $this->tenantId)
            ->where('event_type', 'ChallengeRouted')
            ->first();

        $this->assertSame('conversation-42', $row->correlation_id);
        $this->assertSame('conversation-42', $row->causation_id);
    }

    // ── KEYSTONE: publication is registration ───────────────────────────────

    public function test_a_registered_hydrator_reconstructs_the_published_row(): void
    {
        /** @var EventHydratorRegistry $registry */
        $registry = $this->app->make(EventHydratorRegistry::class);

        $this->outbox()->enqueue(EventProvenance::start('conversation-1'), $this->routedEvent());

        $row = DB::table('outbox_events')
            ->where('organisation_id', $this->tenantId)
            ->where('event_type', 'ChallengeRouted')
            ->first();

        // The registry must resolve the event type booted by the provider —
        // an unregistered published event is an incomplete Event Registry.
        $this->assertTrue($registry->has('ChallengeRouted'), 'a published event must be registered');
        $hydrator = $registry->hydratorFor('ChallengeRouted');
        $this->assertInstanceOf(ChallengeRoutedHydrator::class, $hydrator);

        $event = $hydrator->hydrate(json_decode((string) $row->payload, true));
        $this->assertInstanceOf(ChallengeRouted::class, $event);
        $this->assertSame('constitutional-council', $event->routedTo);
    }

    // ── Anonymity on the real wire (ADR-T11 / AT-Q7) ─────────────────────────

    public function test_the_published_row_contains_no_voter_or_vote_identifier(): void
    {
        $this->outbox()->enqueue(EventProvenance::start('conversation-1'), $this->routedEvent());

        $row = DB::table('outbox_events')
            ->where('organisation_id', $this->tenantId)
            ->where('event_type', 'ChallengeRouted')
            ->first();

        $raw = strtolower((string) $row->payload);
        foreach (['user_id', 'userid', 'voter_id', 'voterid', 'vote_id', 'voteid'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $raw);
        }
    }
}
