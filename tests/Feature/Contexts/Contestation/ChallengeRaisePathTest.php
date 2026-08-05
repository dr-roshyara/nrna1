<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Contestation;

use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Contestation\Application\Command\RaiseChallengeCommand;
use App\Contexts\Contestation\Application\Service\ContestationService;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\ChallengeState;
use App\Contexts\Contestation\Domain\Challenge\ContestedOutcomeRef;
use App\Contexts\Contestation\Domain\Challenge\ElectionId;
use App\Contexts\Contestation\Domain\Challenge\Exception\IllegalChallengeTransition;
use App\Contexts\Contestation\Domain\Challenge\RaiserStandingRef;
use App\Contexts\Contestation\Domain\Challenge\SubmittedContent;
use App\Contexts\Contestation\Domain\Challenge\TargetId;
use App\Contexts\Contestation\Domain\Challenge\TargetType;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use App\Models\Organisation;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * WP-5 — the Contestation RAISE PATH: raise → admit → route, through application
 * services, with the INTEGRATION CONVERSATION ORIGIN minted at `route`.
 *
 * **Business process origin and integration conversation origin are intentionally
 * different concepts** (ARB clarification, 2026-07-31). The business process begins at
 * `raise`; the integration conversation begins at `route`, when the first published
 * event is emitted. That is why the mint lives in the routing act and why no
 * provenance is persisted on the challenge.
 *
 * WP-3B (Correlation Origin Relocation) is this slice's final act — ARB Option B.
 *
 * Traceability: roadmap §WP-5 · TP-2 (Contestation REQUESTS, never creates an
 * adjudication) · ADR-T21 · ADR-MP-06 (one mint per conversation; allowlist changes
 * are ARB decisions) · ADR-T1 (one aggregate + its outbox rows per transaction) ·
 * frozen Cross-Context Integration Contract · plan `.claude/plans/WP-5-raise-path.md`.
 */
final class ChallengeRaisePathTest extends TestCase
{
    use RefreshDatabase;

    private string $orgId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orgId = (string) Organisation::create([
            'name' => 'WP-5 raise path org',
            'slug' => 'wp5-raise-path-'.Str::uuid(),
            'type' => 'tenant',
            'is_default' => false,
        ])->id;
        TenantContext::set($this->orgId);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    private function service(): ContestationService
    {
        return $this->app->make(ContestationService::class);
    }

    private function repo(): ChallengeRepository
    {
        return $this->app->make(ChallengeRepository::class);
    }

    private function raiseCommand(): RaiseChallengeCommand
    {
        return new RaiseChallengeCommand(
            RaiserStandingRef::fromString('standing-holder-1'),
            ContestedOutcomeRef::of(
                ElectionId::fromString('election-1'),
                TargetType::ElectionResult,
                TargetId::fromString('result-1'),
            ),
            SubmittedContent::fromString('The tally for district 4 was miscounted.'),
        );
    }

    /** @return list<object> */
    private function outboxRows(): array
    {
        return DB::table('outbox_events')->where('organisation_id', $this->orgId)->get()->all();
    }

    // ── KEYSTONE 1: the capability exists ───────────────────────────────────

    public function test_a_challenge_can_be_raised_through_the_application_service(): void
    {
        $id = $this->service()->raise($this->raiseCommand());

        $challenge = $this->repo()->find($id);

        $this->assertNotNull($challenge, 'the raise must persist the challenge');
        $this->assertSame(ChallengeState::Raised, $challenge->state());
    }

    // ── KEYSTONE 2: admission ───────────────────────────────────────────────

    public function test_a_raised_challenge_can_be_admitted(): void
    {
        $id = $this->service()->raise($this->raiseCommand());

        $this->service()->admit($id);

        $this->assertSame(ChallengeState::Admitted, $this->repo()->get($id)->state());
    }

    // ── KEYSTONE 3: the aggregate stays sovereign ───────────────────────────

    public function test_admitting_an_unknown_state_throws_without_mutating(): void
    {
        $id = $this->service()->raise($this->raiseCommand());
        $this->service()->admit($id);

        // Admitting twice is not a legal transition — the guard must refuse it and
        // leave the state untouched. The application service adds no guard of its own.
        $this->expectException(IllegalChallengeTransition::class);

        try {
            $this->service()->admit($id);
        } finally {
            $this->assertSame(ChallengeState::Admitted, $this->repo()->get($id)->state());
        }
    }

    // ── KEYSTONE 4: routing publishes the loop-head trigger ─────────────────

    public function test_routing_an_admitted_challenge_publishes_exactly_one_routed_event(): void
    {
        $id = $this->service()->raise($this->raiseCommand());
        $this->service()->admit($id);

        $this->service()->route($id, 'constitutional-council');

        $this->assertSame(ChallengeState::Routed, $this->repo()->get($id)->state());

        $rows = $this->outboxRows();
        $this->assertCount(1, $rows, 'routing must write exactly one outbox row');
        $this->assertSame('ChallengeRouted', $rows[0]->event_type);
        $this->assertSame('Challenge', $rows[0]->aggregate_type);

        $payload = json_decode((string) $rows[0]->payload, true);
        $this->assertIsArray($payload);
        $this->assertSame(1, $payload['schema_version']);
        $this->assertSame($id->toString(), $payload['challengeId']);
        $this->assertSame('constitutional-council', $payload['routedTo']);
    }

    // ── KEYSTONE 5: atomicity — a refused route publishes nothing (ADR-T1) ──

    public function test_routing_a_non_admitted_challenge_throws_and_publishes_nothing(): void
    {
        $id = $this->service()->raise($this->raiseCommand());   // still Raised, not Admitted

        try {
            $this->service()->route($id, 'constitutional-council');
            $this->fail('routing a non-admitted challenge must be refused');
        } catch (IllegalChallengeTransition) {
            // expected
        }

        $this->assertSame(ChallengeState::Raised, $this->repo()->get($id)->state());
        $this->assertCount(0, $this->outboxRows(), 'a refused route must leave no outbox row');
    }

    // ── KEYSTONE 6: ONLY INTEGRATION EVENTS ARE PUBLISHED ───────────────────

    /**
     * The invariant, not the symptom (ARB direction): `ChallengeRaised` and
     * `ChallengeAdmitted` are **intentionally not published** — they are Contestation's
     * internal record of its own process, with no consumer and no authority to cross a
     * boundary. Only `ChallengeRouted`, the published loop-head trigger (ADR-T21), goes
     * to the outbox.
     *
     * This test is deliberately stronger than "no exception is thrown": adding an
     * outbox mapping for `ChallengeRaised` to silence an error would FAIL here, which is
     * the point — the absence is intentional, not merely "currently unmapped".
     */
    public function test_only_integration_events_are_published_to_the_outbox(): void
    {
        $id = $this->service()->raise($this->raiseCommand());
        $this->assertCount(0, $this->outboxRows(), 'raise publishes nothing — ChallengeRaised is internal');

        $this->service()->admit($id);
        $this->assertCount(0, $this->outboxRows(), 'admit publishes nothing — ChallengeAdmitted is internal');

        $this->service()->route($id, 'constitutional-council');

        $rows = $this->outboxRows();
        $this->assertCount(1, $rows, 'the whole raise path publishes exactly ONE event');
        $this->assertSame(
            'ChallengeRouted',
            $rows[0]->event_type,
            'the only published event of the raise path is the loop-head trigger',
        );
    }

    // ── KEYSTONE 7: the INTEGRATION CONVERSATION ORIGIN is minted at ROUTE ──

    public function test_routing_mints_the_integration_conversation_origin(): void
    {
        $id = $this->service()->raise($this->raiseCommand());
        $this->service()->admit($id);

        $this->service()->route($id, 'constitutional-council');

        $row = $this->outboxRows()[0];

        $this->assertNotNull($row->correlation_id, 'routing must MINT a correlation (the conversation origin)');
        $this->assertNull(
            $row->causation_id,
            'a chain START has no causation — EventProvenance::start() returns (id, null) per ADR-MP-06',
        );
    }

    // ── KEYSTONE 8: the loop head fires from a PRODUCTION mint ──────────────

    /**
     * WP-4 proved the consumption with a TEST-SEEDED provenance. This proves the same
     * loop head firing from the production raise path — the slice's real prize.
     * TP-2 is visible here: Contestation only published; Adjudication decided to open.
     */
    public function test_the_production_raise_path_opens_an_adjudication(): void
    {
        $id = $this->service()->raise($this->raiseCommand());
        $this->service()->admit($id);
        $this->service()->route($id, 'constitutional-council');

        $this->app->make(OutboxEventProcessor::class)->handle();

        $process = $this->app->make(AdjudicationProcessStore::class)
            ->activeForChallenge(ChallengeRef::fromString($id->toString()));

        $this->assertNotNull($process, 'the routed challenge must open exactly one adjudication');
        $this->assertSame($id->toString(), $process->challengeRef()->toString());
    }

    // ── KEYSTONE 9: the mint allowlist names the routing service (WP-3B) ────

    /**
     * ADR-MP-06: extending the chain-origin allowlist is an ARB decision — granted for
     * this slice. The guard must name Contestation's routing service, so that the
     * one-mint-per-conversation rule stays machine-enforced after the relocation.
     */
    public function test_the_mint_allowlist_names_the_contestation_routing_service(): void
    {
        $guard = file_get_contents(base_path('tests/Architecture/Messaging/CorrelationIdMintingTest.php'));

        $this->assertIsString($guard);
        $this->assertStringContainsString(
            'app/Contexts/Contestation/Application/Service/CoordinatesContestation.php',
            $guard,
            'the relocated chain origin must be allowlisted (ADR-MP-06)',
        );
    }
}
