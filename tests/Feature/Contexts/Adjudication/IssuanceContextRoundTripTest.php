<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Adjudication;

use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessId;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessState;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Models\Organisation;
use App\Services\TenantContext;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Adjudication\InMemoryAdjudicationProcessStore;
use Tests\TestCase;

/**
 * WP-4B Batch 5 — the six retained issuance facts survive a real database round trip.
 *
 * WHY THIS TEST EXISTS SEPARATELY FROM THE SEAM'S BEHAVIOUR TESTS:
 * the seam's obligation is *"request issuance once, and never twice"*. That obligation is
 * only meaningful if the facts it carries — and the marker that says "already requested" —
 * actually persist. A seam verified exclusively against an in-memory double would pass with
 * a mapper that silently dropped every new column, because an in-memory store round-trips
 * OBJECTS while a database round-trips COLUMNS. **Persistence is verified before behaviour
 * is built on top of it**, so a later failure cannot be ambiguous between the two.
 *
 * WHAT IT ASSERTS
 *   1. Every retained fact returns EQUAL IN VALUE after a real PostgreSQL write + read.
 *   2. Every reconstructed fact returns as its VALUE OBJECT TYPE, not as a string. This is
 *      the assertion a `assertSame('...', $x->toString())` check would miss: a mapper that
 *      handed back raw strings would satisfy value equality and still break every consumer.
 *   3. The Eloquent store and the in-memory double are EQUIVALENT — the double used by the
 *      unit tests is a faithful stand-in, not a more forgiving one.
 *   4. `issuanceRequestedAt() === null` survives as null (the crash window is representable).
 *
 * WHAT IT DOES NOT ASSERT: nothing about *when* the seam requests issuance, or whether it
 * requests once. That is the seam's own contract, verified by its own tests.
 *
 * Traceability: EPIC-004K §11 · R-72 · R-73 (jurisdiction ← the authority) ·
 * R-74 (envelope ← Evidence) · R-75 (contested outcome ← Contestation) · ADR-T16 (local
 * VO reconstruction) · ADR-T11 (references only, never content) ·
 * plan `docs/plans/20260803-1600-wp4b-conclude-to-issue-seam-delivery-plan.md` §3.
 */
final class IssuanceContextRoundTripTest extends TestCase
{
    use RefreshDatabase;

    private string $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        $org = Organisation::create([
            'name' => 'WP-4B Round Trip Org',
            'slug' => 'wp4b-round-trip-org',
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

    private function store(): AdjudicationProcessStore
    {
        return $this->app->make(AdjudicationProcessStore::class);
    }

    private function at(string $t): DateTimeImmutable
    {
        return new DateTimeImmutable($t);
    }

    private function contestedOutcome(): ContestedOutcomeRef
    {
        return ContestedOutcomeRef::of(
            ElectionId::fromString('elec-2026-eu'),
            TargetType::ElectionResult,
            TargetId::fromString('result-bayern-07'),
        );
    }

    /**
     * A process carried to CONCLUDED with all six facts retained but issuance NOT yet
     * requested — precisely the crash-window state the redrive query must find.
     */
    private function concludedWithContext(string $id, string $challenge): AdjudicationProcessState
    {
        return AdjudicationProcessState::open(
            AdjudicationProcessId::fromString($id),
            ChallengeRef::fromString($challenge),
            $this->at('2026-08-01T09:00:00+00:00'),
        )
            ->admitEvidence('ev-envelope-part-1', $this->at('2026-08-01T10:00:00+00:00'))
            ->submitToAuthority($this->at('2026-08-01T11:00:00+00:00'))
            ->concludeRulingRequested(
                EvidenceSet::fromRefs('ev-envelope-part-1'),
                IssuedByAuthority::fromString('constitutional-council'),
                DeterminationOutcome::Upheld,
                Legitimacy::Legitimate,
                Reason::fromString('The contested tally omitted two ballot boxes.'),
                $this->at('2026-08-02T12:00:00+00:00'),
            )
            ->retainIssuanceContext($this->contestedOutcome(), EvidenceEnvelopeRef::fromString('env-9f2c'))
            ->retainJurisdiction(Jurisdiction::fromString('EU-DE-BY'));
    }

    // ── 1 + 2 + 4: value equality AND type fidelity across a real database ──────

    public function test_every_retained_issuance_fact_survives_a_database_round_trip(): void
    {
        $this->store()->save($this->concludedWithContext('apm-rt-1', 'ch-rt-1'));

        // Read back through the redrive query, so the round trip exercises the SAME path
        // the seam's recovery will use — not a convenience lookup written for the test.
        $found = $this->store()->concludedAwaitingIssuance();

        $this->assertCount(1, $found, 'A concluded, unrequested process must be findable');
        $loaded = $found[0];

        // identity + status
        $this->assertSame('apm-rt-1', $loaded->id()->toString());
        $this->assertSame('ch-rt-1', $loaded->challengeRef()->toString());
        $this->assertSame(AdjudicationProcessStatus::ConcludedRulingRequested, $loaded->status());

        // the crash window is representable: concluded, but issuance not yet requested
        $this->assertNull(
            $loaded->issuanceRequestedAt(),
            'A concluded process that has not requested issuance must read back as null',
        );

        // ContestedOutcomeRef — all three parts, and the VO type itself
        $this->assertInstanceOf(ContestedOutcomeRef::class, $loaded->contestedOutcome());
        $this->assertTrue(
            $loaded->contestedOutcome()->equals($this->contestedOutcome()),
            'The contested outcome must return equal in value, all three parts',
        );
        $this->assertInstanceOf(ElectionId::class, $loaded->contestedOutcome()->electionId);
        $this->assertInstanceOf(TargetId::class, $loaded->contestedOutcome()->targetId);
        $this->assertSame(TargetType::ElectionResult, $loaded->contestedOutcome()->type);
        $this->assertSame('elec-2026-eu', $loaded->contestedOutcome()->electionId->toString());
        $this->assertSame('result-bayern-07', $loaded->contestedOutcome()->targetId->toString());

        // EvidenceEnvelopeRef — Evidence's fact (R-74)
        $this->assertInstanceOf(EvidenceEnvelopeRef::class, $loaded->evidenceEnvelopeRef());
        $this->assertSame('env-9f2c', $loaded->evidenceEnvelopeRef()->toString());

        // Jurisdiction — the authority's fact (R-73)
        $this->assertInstanceOf(Jurisdiction::class, $loaded->jurisdiction());
        $this->assertSame('EU-DE-BY', $loaded->jurisdiction()->toString());

        // timestamps
        $this->assertSame(
            '2026-08-01T09:00:00+00:00',
            $loaded->openedAt()->setTimezone(new \DateTimeZone('UTC'))->format('c'),
        );
        $this->assertInstanceOf(DateTimeImmutable::class, $loaded->concludedAt());
        $this->assertSame(
            '2026-08-02T12:00:00+00:00',
            $loaded->concludedAt()->setTimezone(new \DateTimeZone('UTC'))->format('c'),
        );
    }

    // ── the marker persists, and it is what removes a process from the redrive set ──

    public function test_the_issuance_marker_persists_and_closes_the_crash_window(): void
    {
        $state = $this->concludedWithContext('apm-rt-2', 'ch-rt-2');
        $this->store()->save($state);

        $requestedAt = $this->at('2026-08-02T12:00:05+00:00');
        $this->store()->save($state->markIssuanceRequested($requestedAt));

        $this->assertSame(
            [],
            $this->store()->concludedAwaitingIssuance(),
            'Once issuance is requested the process must leave the redrive set',
        );

        $loaded = $this->store()->activeForChallenge(ChallengeRef::fromString('ch-rt-2'))
            ?? $this->loadedById('apm-rt-2');

        $this->assertInstanceOf(DateTimeImmutable::class, $loaded->issuanceRequestedAt());
        $this->assertSame(
            '2026-08-02T12:00:05+00:00',
            $loaded->issuanceRequestedAt()->setTimezone(new \DateTimeZone('UTC'))->format('c'),
        );

        // The retained facts are untouched by marking — marking records Adjudication's own
        // act, it does not rewrite anybody else's fact.
        $this->assertTrue($loaded->contestedOutcome()->equals($this->contestedOutcome()));
        $this->assertSame('env-9f2c', $loaded->evidenceEnvelopeRef()->toString());
        $this->assertSame('EU-DE-BY', $loaded->jurisdiction()->toString());
    }

    // ── 3: the in-memory double is a FAITHFUL stand-in, not a more forgiving one ────

    public function test_the_in_memory_double_and_the_database_agree(): void
    {
        $state = $this->concludedWithContext('apm-rt-3', 'ch-rt-3');

        $this->store()->save($state);
        $fromDatabase = $this->store()->concludedAwaitingIssuance();

        $inMemory = new InMemoryAdjudicationProcessStore();
        $inMemory->save($state);
        $fromMemory = $inMemory->concludedAwaitingIssuance();

        $this->assertCount(1, $fromDatabase);
        $this->assertCount(1, $fromMemory);

        $a = $fromDatabase[0];
        $b = $fromMemory[0];

        $this->assertSame($b->id()->toString(), $a->id()->toString());
        $this->assertSame($b->status(), $a->status());
        $this->assertSame($b->issuanceRequestedAt(), $a->issuanceRequestedAt());
        $this->assertTrue($a->contestedOutcome()->equals($b->contestedOutcome()));
        $this->assertSame($b->evidenceEnvelopeRef()->toString(), $a->evidenceEnvelopeRef()->toString());
        $this->assertSame($b->jurisdiction()->toString(), $a->jurisdiction()->toString());
        $this->assertSame(
            $b->concludedAt()->format('c'),
            $a->concludedAt()->setTimezone(new \DateTimeZone('UTC'))->format('c'),
        );
    }

    private function loadedById(string $id): AdjudicationProcessState
    {
        foreach ($this->store()->concludedAwaitingIssuance() as $candidate) {
            if ($candidate->id()->toString() === $id) {
                return $candidate;
            }
        }

        // Not in the redrive set (correct once requested) — reload through the mapper via
        // the model, which is the only remaining read path for a terminal process.
        $model = \App\Contexts\Adjudication\Infrastructure\Models\AdjudicationProcessModel::query()
            ->findOrFail($id);

        return $this->app->make(\App\Contexts\Adjudication\Infrastructure\Persistence\AdjudicationProcessMapper::class)
            ->toState($model);
    }
}
