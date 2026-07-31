<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Adjudication;

use App\Contexts\Adjudication\Application\Port\AdjudicationDurations;
use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessManager;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Exception\LateDecisionOnExpiredAdjudication;
use App\Models\Organisation;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * WP-6 — the adjudication HORIZON and its announced fact.
 *
 * Scope, per ARB Decision C (Option A): this slice **publishes and registers**; the
 * Contestation consumer is WP-6B. Evidence-demand deadlines (§80/PM-3) and the
 * finality evaluator (§142) are NOT in scope — both are blocked on decisions recorded
 * in the plan, so no keystone here asserts either.
 *
 * The three governing rules under test:
 *  - **Q-2:** the APM *enforces* a duration it does not *own*. The horizon's cut-off is
 *    `now − MAD`, and MAD is resolved from configuration, never hardcoded.
 *  - **Constitutional Policy 4:** a timer must NEVER conclude anything. Expiry is a
 *    distinct terminal fact carrying no outcome, no legitimacy, no determination.
 *  - **EPIC-004K §197 (RULED):** expiry *announces* the failure-to-conclude, and a
 *    **post-expiry authority decision dead-letters as a conflict** — which is NOT the
 *    same as the idempotent no-op owed to a redelivered decision (ADR-T3).
 *
 * Provenance (Decision B): the announcement **begins a new conversation** — a timer
 * consumes no message, so `fromConsumed()` has nothing to derive from. Newly minted
 * correlation, null causation.
 *
 * Traceability: roadmap §WP-6 · Q-2 · Policy 4 · EPIC-004K §57/§72/§74/§81/§197 ·
 * ADR-MP-06 · ADR-T3 · plan `.claude/plans/WP-6-temporal-machinery.md`.
 */
final class AdjudicationHorizonTest extends TestCase
{
    use RefreshDatabase;

    private string $orgId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orgId = (string) Organisation::create([
            'name' => 'WP-6 horizon org',
            'slug' => 'wp6-horizon-'.Str::uuid(),
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

    private function manager(): AdjudicationProcessManager
    {
        return $this->app->make(AdjudicationProcessManager::class);
    }

    private function store(): AdjudicationProcessStore
    {
        return $this->app->make(AdjudicationProcessStore::class);
    }

    /** Open a process and backdate its `opened_at`, so the horizon has something to act on. */
    private function openAgedProcess(ChallengeRef $challenge, string $openedAgo): void
    {
        $this->manager()->openFor($challenge);

        DB::table('adjudication_processes')
            ->where('organisation_id', $this->orgId)
            ->where('challenge_ref', $challenge->toString())
            ->update(['opened_at' => now()->sub($openedAgo)]);
    }

    /** @return list<object> */
    private function outboxRows(): array
    {
        return DB::table('outbox_events')->where('organisation_id', $this->orgId)->get()->all();
    }

    // ── KEYSTONE 1: MAD is resolved, not hardcoded ──────────────────────────

    public function test_the_maximum_adjudication_duration_is_resolved_from_configuration(): void
    {
        $durations = $this->app->make(AdjudicationDurations::class);

        $mad = $durations->maximumAdjudicationDuration();

        $this->assertGreaterThan(
            0,
            $mad->days,
            'MAD must resolve to a positive duration (INTERIM value awaiting the Q-2 parameters)',
        );
    }

    // ── KEYSTONE 2: a process INSIDE the horizon does not expire (F-1) ───────

    /**
     * The WP-2/WP-6 seam. `enforceHorizon()` previously passed `now` as the cut-off to
     * `dueForHorizon($asOf)`, which selects `opened_at <= $asOf` — so EVERY non-terminal
     * process was "due". The cut-off must be `now − MAD`.
     */
    public function test_a_process_opened_within_the_horizon_does_not_expire(): void
    {
        $challenge = ChallengeRef::fromString('challenge-fresh');
        $this->openAgedProcess($challenge, '1 minute');

        $this->manager()->enforceHorizon();

        $process = $this->store()->activeForChallenge($challenge);

        $this->assertNotNull($process, 'a fresh process must remain ACTIVE');
        $this->assertSame(AdjudicationProcessStatus::Opened, $process->status());
    }

    // ── KEYSTONE 3: a process BEYOND the horizon expires ────────────────────

    public function test_a_process_opened_beyond_the_horizon_expires(): void
    {
        $challenge = ChallengeRef::fromString('challenge-stale');
        $this->openAgedProcess($challenge, '400 days');

        $this->manager()->enforceHorizon();

        $this->assertNull(
            $this->store()->activeForChallenge($challenge),
            'an expired process is terminal, so it is no longer ACTIVE',
        );
        $this->assertSame(
            AdjudicationProcessStatus::Expired->value,
            DB::table('adjudication_processes')
                ->where('challenge_ref', $challenge->toString())
                ->value('status'),
        );
    }

    // ── KEYSTONE 4: POLICY 4 — a timer concludes NOTHING ────────────────────

    /**
     * The constitutional guarantee: automated verification never determines
     * significance. An expired process carries no outcome, no legitimacy, no reason and
     * no concluding authority — expiry is a *distinct terminal fact*, never a verdict.
     */
    public function test_expiry_concludes_nothing(): void
    {
        $challenge = ChallengeRef::fromString('challenge-policy4');
        $this->openAgedProcess($challenge, '400 days');

        $this->manager()->enforceHorizon();

        $row = DB::table('adjudication_processes')->where('challenge_ref', $challenge->toString())->first();

        $this->assertNotNull($row);
        $this->assertNull($row->outcome, 'Policy 4: a timer must not produce an outcome');
        $this->assertNull($row->legitimacy, 'Policy 4: a timer must not rule on legitimacy');
        $this->assertNull($row->reason, 'Policy 4: a timer must not author a reason');
        $this->assertNull($row->concluded_by_authority, 'Policy 4: no authority concluded this');
        $this->assertSame(0, DB::table('determinations')->count(), 'a timer must never issue a determination');
    }

    // ── KEYSTONE 5: expiry ANNOUNCES the failure-to-conclude (§197) ──────────

    public function test_expiry_announces_exactly_one_fact(): void
    {
        $challenge = ChallengeRef::fromString('challenge-announce');
        $this->openAgedProcess($challenge, '400 days');

        $this->manager()->enforceHorizon();

        $rows = $this->outboxRows();
        $this->assertCount(1, $rows, 'expiry announces exactly one fact');
        $this->assertSame('AdjudicationExpired', $rows[0]->event_type);

        $payload = json_decode((string) $rows[0]->payload, true);
        $this->assertIsArray($payload);
        $this->assertSame(1, $payload['schema_version']);
        $this->assertSame($challenge->toString(), $payload['challengeRef']);
    }

    // ── KEYSTONE 6: the announcement is registered (published language) ──────

    public function test_the_expiry_announcement_is_registered_for_hydration(): void
    {
        $registry = $this->app->make(\App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry::class);

        $this->assertTrue(
            $registry->has('AdjudicationExpired'),
            'published language requires BOTH publication and registration (WP-3A rule)',
        );
    }

    // ── KEYSTONE 7: the announcement BEGINS a new conversation (Decision B) ──

    public function test_the_announcement_begins_a_new_conversation(): void
    {
        $challenge = ChallengeRef::fromString('challenge-origin');
        $this->openAgedProcess($challenge, '400 days');

        $this->manager()->enforceHorizon();

        $row = $this->outboxRows()[0];

        $this->assertNotNull($row->correlation_id, 'a clock-triggered fact mints its own correlation');
        $this->assertNull($row->causation_id, 'a chain START has no causation (ADR-MP-06)');
    }

    // ── KEYSTONE 8: repeated timer runs announce once (idempotent) ───────────

    public function test_a_second_timer_run_announces_nothing_further(): void
    {
        $challenge = ChallengeRef::fromString('challenge-idempotent');
        $this->openAgedProcess($challenge, '400 days');

        $this->manager()->enforceHorizon();
        $this->manager()->enforceHorizon();

        $this->assertCount(1, $this->outboxRows(), 'an expired process is terminal: it expires once, announces once');
    }

    // ── KEYSTONE 9: a LATE decision is a CONFLICT (§197 · F-2) ──────────────

    /**
     * §197: *"a post-expiry authority decision dead-letters as a conflict."* This is
     * distinct from the idempotent no-op owed to a REDELIVERED decision — the two
     * situations shared one branch before WP-6.
     */
    public function test_a_late_authority_decision_on_an_expired_process_is_a_conflict(): void
    {
        $challenge = ChallengeRef::fromString('challenge-late');
        $this->openAgedProcess($challenge, '400 days');
        $this->manager()->enforceHorizon();

        $this->expectException(LateDecisionOnExpiredAdjudication::class);

        $this->manager()->receiveRulingDecision(
            $challenge,
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('The authority ruled after the horizon elapsed.'),
            IssuedByAuthority::fromString('constitutional-council'),
            EvidenceSet::fromRefs('ev-1'),
        );
    }

    // ── KEYSTONE 10: a REDELIVERED decision stays a no-op (ADR-T3) ──────────

    /**
     * Guards against over-correcting keystone 9: at-least-once delivery means the same
     * decision can arrive twice, and that must remain harmless.
     */
    public function test_a_redelivered_decision_on_a_concluded_process_is_a_no_op(): void
    {
        $challenge = ChallengeRef::fromString('challenge-redelivered');
        $this->manager()->openFor($challenge);
        // §6 guards the lawful path: evidence is admitted BEFORE the basis can be put
        // before the authority. Submitting from `opened` is an illegal transition.
        $this->manager()->admitEvidence($challenge, 'ev-1');
        $this->manager()->submitToAuthority($challenge);

        $decide = fn (): mixed => $this->manager()->receiveRulingDecision(
            $challenge,
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('The challenge is upheld.'),
            IssuedByAuthority::fromString('constitutional-council'),
            EvidenceSet::fromRefs('ev-1'),
        );

        $decide();
        $decide();   // redelivery — must not throw, must not conclude twice

        $this->assertSame(
            1,
            DB::table('adjudication_processes')->where('challenge_ref', $challenge->toString())->count(),
            'redelivery must not create a second process',
        );
    }
}
