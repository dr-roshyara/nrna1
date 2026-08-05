<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Process;

use App\Contexts\Adjudication\Application\Port\AdjudicationDurations;
use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use App\Contexts\Adjudication\Application\Port\RequestsDeterminationIssuance;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessManager;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessStatus;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Infrastructure\Shared\Clock\FrozenClock;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\Support\Adjudication\InMemoryAdjudicationProcessStore;
use Tests\Support\Adjudication\InMemoryEventOutbox;

/**
 * WP-2 KEYSTONE — exactly-once conclusion under redelivery, and one active
 * process per challenge (PM-1).
 *
 * Scope note, stated honestly: at this level "redelivery" means the SAME
 * decision delivered twice (the transport's at-least-once reality). The genuine
 * concurrent race is asserted at the database seam in
 * `AdjudicationProcessUniquenessTest` — the INV-B1 two-seat pattern: a guard
 * here, a unique index there.
 *
 * The process RECEIVES the authority's decision; it never computes legitimacy or
 * sufficiency (K1 / Q-1 / ADR-T23) — the decision arrives whole.
 *
 * Traceability: EPIC-004K §3 (PM-1, PM-4, PM-5, PM-6) · roadmap §WP-2 keystones ·
 * EPIC-004E INV-B1 (mirrored) · Governance PM precedent (idempotent terminal guard).
 */
final class AdjudicationProcessManagerTest extends TestCase
{
    private InMemoryAdjudicationProcessStore $store;
    private AdjudicationProcessManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->store = new InMemoryAdjudicationProcessStore();
        $this->manager = new AdjudicationProcessManager(
            $this->store,
            new FrozenClock(new DateTimeImmutable('2026-07-30T10:00:00+00:00')),
            $this->durations(),
            new InMemoryEventOutbox(),
            $this->identities(),
            $this->inertIssuance(),
        );
    }

    /**
     * WP-6 added the horizon collaborators to the constructor. These keystones exercise
     * the CONDUCT (PM-1/PM-4/PM-5/PM-6), not the horizon — so the durations and identity
     * collaborators are supplied as inert stand-ins rather than asserted on. The horizon
     * itself is covered where it belongs, in `AdjudicationHorizonTest`.
     *
     * Deliberately local anonymous classes: two trivial collaborators used by one test do
     * not justify new shared support classes, and the WP-6 remediation package authorizes
     * repairing THIS file — not widening the test-support surface.
     */
    /**
     * WP-4B added the issuance collaborator to the constructor. These keystones exercise
     * the CONDUCT (PM-1/PM-4/PM-5/PM-6), not the conclude->issue seam, so it is supplied
     * as an inert stand-in rather than asserted on -- the same treatment WP-6's horizon
     * collaborators received above. The seam has its own keystones in
     * `ConcludeToIssuanceSeamTest`.
     */
    private function inertIssuance(): RequestsDeterminationIssuance
    {
        return new class implements RequestsDeterminationIssuance {
            public function request(IssueDeterminationCommand $command): void
            {
            }
        };
    }

    private function durations(): AdjudicationDurations
    {
        return new class implements AdjudicationDurations {
            public function maximumAdjudicationDuration(
                ?string $electionType = null,
                ?string $organisationId = null,
            ): DateInterval {
                return new DateInterval('P60D');
            }
        };
    }

    private function identities(): IdentityGenerator
    {
        return new class implements IdentityGenerator {
            private int $minted = 0;

            public function next(): string
            {
                return sprintf('00000000-0000-4000-8000-%012d', ++$this->minted);
            }
        };
    }

    private function challenge(): ChallengeRef
    {
        return ChallengeRef::fromString('ch-1');
    }

    private function bringToAwaitingDecision(): void
    {
        $this->manager->openFor($this->challenge());
        $this->manager->admitEvidence($this->challenge(), 'envelope-sha256-abc');
        $this->manager->submitToAuthority($this->challenge());
    }

    private function deliverRulingDecision(): void
    {
        $this->manager->receiveRulingDecision(
            $this->challenge(),
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('Tally dispute upheld.'),
            IssuedByAuthority::fromString('authority-cab-01'),
            EvidenceSet::fromRefs('envelope-sha256-abc'),
            Jurisdiction::fromString('federal'),
        );
    }

    // ── PM-1: exactly one active process per challenge ──────────────────────

    public function test_opening_twice_for_the_same_challenge_yields_one_active_process(): void
    {
        $this->manager->openFor($this->challenge());
        $this->manager->openFor($this->challenge());

        $this->assertNotNull($this->store->activeForChallenge($this->challenge()));
        $this->assertCount(1, $this->store->writes, 'A duplicate request must not open a second process');
    }

    // ── KEYSTONE: exactly-once conclusion under redelivery ──────────────────

    public function test_a_redelivered_ruling_decision_concludes_exactly_once(): void
    {
        $this->bringToAwaitingDecision();
        $writesBeforeDecision = count($this->store->writes);

        $this->deliverRulingDecision();
        $this->deliverRulingDecision();   // at-least-once transport: the same decision again

        $concluded = $this->store->writes[count($this->store->writes) - 1];
        $this->assertSame(AdjudicationProcessStatus::ConcludedRulingRequested, $concluded->status());
        $this->assertSame(
            $writesBeforeDecision + 1,
            count($this->store->writes),
            'The duplicate decision must be an idempotent no-op, never a second conclusion',
        );
    }

    public function test_a_conflicting_second_decision_does_not_replace_the_conclusion(): void
    {
        $this->bringToAwaitingDecision();
        $this->deliverRulingDecision();

        // A late, DIFFERENT decision arrives. Forward-only: the conclusion stands.
        $this->manager->receiveInsufficiencyDecision(
            $this->challenge(),
            Reason::fromString('actually insufficient'),
            IssuedByAuthority::fromString('authority-cab-01'),
            EvidenceSet::fromRefs('envelope-sha256-abc'),
        );

        $latest = $this->store->writes[count($this->store->writes) - 1];
        $this->assertSame(AdjudicationProcessStatus::ConcludedRulingRequested, $latest->status());
    }

    // ── PM-4/K1: the process receives; it does not compute ─────────────────

    public function test_the_conclusion_records_the_authority_that_decided(): void
    {
        $this->bringToAwaitingDecision();
        $this->deliverRulingDecision();

        $concluded = $this->store->writes[count($this->store->writes) - 1];
        $this->assertSame('authority-cab-01', $concluded->concludedByAuthority()->toString());
        $this->assertNotNull($concluded->consideredEvidence());
    }
}
