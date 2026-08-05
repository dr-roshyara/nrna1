<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Adjudication;

use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Events\ChallengeRouted;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * WP-4 — the correction loop's HEAD fires: Adjudication consumes Contestation's
 * published `ChallengeRouted` and opens exactly one adjudication process (ADR-T21 ·
 * EPIC-004K §3 PM-1).
 *
 * This is the FIRST cross-context integration built after the Cross-Context
 * Integration Contract was derived, and it deliberately conforms to it rather than
 * inventing anything: the handler receives an **`InboxMessage` carrying primitives**
 * and reconstructs Adjudication's **own** `ChallengeRef`. It imports neither
 * Contestation's domain event nor its hydrator — the contract's R-1/R-2, enforced
 * by Deptrac. *(This test file may reference Contestation types because it is a
 * TEST seeding a producer; the handler under test may not.)*
 *
 * Delivery runs the REAL path — outbox → relay → `IntegrationEventDispatcher` →
 * inbox → handler — via `OutboxEventProcessor::handle()`, the established pattern
 * from `CorrectionLoopIntegrationTest`. Nothing is hand-stitched.
 *
 * PROVENANCE: nothing is minted here. WP-3B (Correlation Origin Relocation) is
 * deferred — it depends on the existence of a routing application service — so the
 * routed row is **test-seeded** with a supplied chain-start provenance, exactly as
 * the roadmap anticipated.
 *
 * G-2 (decided): no outcome translator exists, because no application-owned
 * translation responsibility exists in this scope. Duplicate delivery is the
 * platform's seat (inbox dedupe); malformed payloads fail loudly and permanently.
 *
 * Traceability: ADR-T21 · ADR-T3/T4 (at-least-once ⇒ dedupe) · ADR-T16 (local VOs) ·
 * ADR-MP-06 (audit continuity) · PB-006 (*Registration ≠ Delivery*) · roadmap §WP-4 ·
 * plan `.claude/plans/WP-4-apm-wiring.md`.
 */
final class ChallengeRoutedConsumptionTest extends TestCase
{
    use RefreshDatabase;

    private const CONVERSATION = 'c1d2e3f4-a5b6-4c7d-8e9f-0a1b2c3d4e5f';
    private const CHALLENGE = '7c8d9e0f-1a2b-3c4d-5e6f-708192a3b4c5';

    private string $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        $org = Organisation::create([
            'name' => 'WP-4 Consumption Test Org',
            'slug' => 'wp4-consumption-test-org',
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

    /** Publish a routed challenge through Contestation's real publication path (WP-3A). */
    private function publishRouted(string $challengeId = self::CHALLENGE): void
    {
        $this->app->make(ChallengeEventOutbox::class)->enqueue(
            EventProvenance::start(self::CONVERSATION),
            new ChallengeRouted(
                ChallengeId::fromString($challengeId),
                'constitutional-council',
                new DateTimeImmutable('2026-07-31T09:00:00+00:00'),
            ),
        );
    }

    private function relay(): void
    {
        $this->app->make(OutboxEventProcessor::class)->handle();
    }

    private function activeProcessCount(): int
    {
        return DB::table('adjudication_processes')->where('organisation_id', $this->tenantId)->count();
    }

    // ── KEYSTONE 1: the loop head fires ─────────────────────────────────────

    public function test_a_routed_challenge_opens_exactly_one_adjudication_process(): void
    {
        $this->publishRouted();

        $this->relay();

        $this->assertSame(1, $this->activeProcessCount(), 'the loop head must open an adjudication');

        $process = $this->app->make(AdjudicationProcessStore::class)
            ->activeForChallenge(ChallengeRef::fromString(self::CHALLENGE));

        $this->assertNotNull($process);
        $this->assertSame(AdjudicationProcessStatus::Opened, $process->status());
        $this->assertSame(self::CHALLENGE, $process->challengeRef()->toString());
    }

    // ── KEYSTONE 2: redelivery is inert (ADR-T3/T4) ─────────────────────────

    public function test_redelivery_opens_no_second_process(): void
    {
        $this->publishRouted();

        $this->relay();
        $this->relay();   // at-least-once reality: the relay runs again

        $this->assertSame(1, $this->activeProcessCount(), 'dedupe must prevent a second consumption');
    }

    public function test_a_second_routing_of_the_same_challenge_opens_no_second_process(): void
    {
        // A distinct message (its own event_id) for a challenge already under
        // adjudication: dedupe does not apply, so PM-1's own idempotency must.
        $this->publishRouted();
        $this->relay();

        $this->publishRouted();
        $this->relay();

        $this->assertSame(1, $this->activeProcessCount(), 'PM-1 must not open a second ACTIVE process');
    }

    // ── KEYSTONE 3: registration is consumer-side (PB-006) ──────────────────

    public function test_adjudication_registers_itself_as_a_consumer_of_challenge_routed(): void
    {
        /** @var InboxHandlerRegistry $registry */
        $registry = $this->app->make(InboxHandlerRegistry::class);

        $this->assertTrue(
            $registry->has('Adjudication', 'ChallengeRouted'),
            'Registration ≠ Delivery: the consumer registers itself; the producer never names it',
        );
    }

    // ── KEYSTONE 4: audit continuity (ADR-MP-06) ────────────────────────────

    public function test_the_consumption_continues_the_incoming_conversation(): void
    {
        // Observable at WP-4's boundary: the inbox record carries the incoming
        // correlation. (Adjudication publishes nothing in this slice, so "nothing
        // is newly minted" is asserted by the CorrelationIdMintingTest fitness
        // guard, not here.)
        $this->publishRouted();
        $this->relay();

        $row = DB::table('inbox_events')
            ->where('consumer_context', 'Adjudication')
            ->where('event_type', 'ChallengeRouted')
            ->first();

        $this->assertNotNull($row, 'the consumption must leave an inbox record');
        $this->assertSame(self::CONVERSATION, $row->correlation_id);
    }

    // ── KEYSTONE 5 — REMOVED, with the reason recorded ──────────────────────
    //
    // "A malformed payload must never open a process" is NOT tested here, and its
    // absence is deliberate:
    //   (a) Its business content is ALREADY proven at the hydrator level by WP-3A —
    //       `ChallengeRoutedHydratorTest::test_missing_required_field_fails_loudly`
    //       and `…::test_unsupported_schema_version_is_rejected`. A Feature-level
    //       repeat would re-prove the same rule through more machinery.
    //   (b) It is not cleanly provable under this harness. On a malformed payload the
    //       relay's defensive try/catch around its OWN status update swallows a SQL
    //       error; PostgreSQL then refuses every later statement in the same
    //       transaction ("current transaction is aborted"), so any post-relay
    //       assertion fails for a reason unrelated to the behaviour under test.
    //       In production each statement autocommits, so nothing is poisoned — this
    //       is a test-harness interaction (the recorded F-7C-6 class), not a defect.
    // Recorded rather than silently dropped.

    // ── KEYSTONE 6: only Adjudication reacts to this event ──────────────────

    public function test_routing_produces_no_contestation_side_effect(): void
    {
        $this->publishRouted();

        $this->relay();

        $this->assertSame(
            0,
            DB::table('inbox_events')
                ->where('consumer_context', 'Contestation')
                ->where('event_type', 'ChallengeRouted')
                ->count(),
            'Contestation is not a consumer of its own published routing',
        );
    }
}
