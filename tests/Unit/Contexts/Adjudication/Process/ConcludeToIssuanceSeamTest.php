<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Process;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Port\RequestsDeterminationIssuance;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessId;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessManager;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessState;
use App\Contexts\Adjudication\Application\Process\Exception\ConflictingDeterminationForChallenge;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Exception\DeterminationAlreadyIssued;
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
use App\Contexts\Adjudication\Application\Port\AdjudicationDurations;
use App\Contexts\Adjudication\Application\Port\IdentityGenerator;
use App\Infrastructure\Shared\Clock\FrozenClock;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\Support\Adjudication\InMemoryAdjudicationProcessStore;
use Tests\Support\Adjudication\InMemoryEventOutbox;

/**
 * WP-4B RED — the conclude→issue seam and its crash-safe redrive.
 *
 * Authorized by R-72; scope fixed by R-76 (**request path only** — PM-6's
 * issuance-confirmation half is deliberately not asserted here).
 *
 * THE KEYSTONE, from roadmap §WP-4:
 *
 *     concluded-but-unissued → redrive → EXACTLY ONE determination
 *
 * WHAT THESE TESTS DELIBERATELY DO NOT ASSERT — and why it matters more than what
 * they do assert: **no test pins WHICH producer supplies `jurisdiction`,
 * `evidenceEnvelopeRef` or `contestedOutcome`.** The governing model already
 * decided that (R-73: the deciding authority · R-74: the Evidence context ·
 * R-75: Contestation via a promoted `ChallengeRaised`), and **a test that coupled
 * the seam to a producer's identity would silently re-decide those rulings.**
 * Doubles supply the values; the seam reads them from the concluded record and
 * must not care how they arrived.
 *
 * The three production producers do not exist yet — that is recorded, previously
 * identified, and not a stop condition. **RED is writable without them; GREEN is
 * not.** These tests fail today because the SEAM is absent, not because the
 * producers are.
 *
 * Traceability: R-72 · R-73 · R-74 · R-75 · R-76 · EPIC-004K §11 (conclude-time
 * atomicity) · §12 (INV-B1 refusal ⇒ reconcile) · ADR-T1 (one aggregate per
 * transaction — the conclusion and the issuance are SEPARATE transactions) ·
 * INV-B1 · docs/plans/20260803-1600-wp4b-conclude-to-issue-seam-delivery-plan.md.
 */
final class ConcludeToIssuanceSeamTest extends TestCase
{
    private ChallengeRef $challenge;
    private InMemoryAdjudicationProcessStore $store;

    protected function setUp(): void
    {
        parent::setUp();

        $this->challenge = ChallengeRef::fromString('ch-4b-seam-1');
        $this->store = new InMemoryAdjudicationProcessStore();
    }

    /**
     * Inert stand-ins, following the WP-2 precedent: these keystones exercise the SEAM,
     * not the horizon, so the duration and identity collaborators are supplied and not
     * asserted on.
     */
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
            private int $n = 0;

            public function next(): string
            {
                return 'id-'.++$this->n;
            }
        };
    }

    /** Records every issuance request without performing one — the seam's collaborator, doubled. */
    private function issuanceSpy(): RequestsDeterminationIssuance
    {
        return new class implements RequestsDeterminationIssuance {
            /** @var list<IssueDeterminationCommand> */
            public array $requests = [];

            public function request(IssueDeterminationCommand $command): void
            {
                $this->requests[] = $command;
            }
        };
    }

    /**
     * Drives a process to CONCLUDED with every issuance input present on the record.
     *
     * The two non-authority values arrive by paths this slice does not own; here they
     * are simply present, which is all the seam may assume.
     */
    private function concludeFor(AdjudicationProcessManager $manager): void
    {
        $manager->openFor($this->challenge);

        $manager->admitIssuanceContext(
            $this->challenge,
            ContestedOutcomeRef::of(
                ElectionId::fromString('el-1'),
                TargetType::ElectionResult,
                TargetId::fromString('res-1'),
            ),
            EvidenceEnvelopeRef::fromString('envelope-sha256-abc'),
        );

        $manager->admitEvidence($this->challenge, 'envelope-sha256-abc');
        $manager->submitToAuthority($this->challenge);

        $manager->receiveRulingDecision(
            $this->challenge,
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('the contested result is unsupported'),
            IssuedByAuthority::fromString('constitutional-authority-1'),
            EvidenceSet::fromRefs('envelope-sha256-abc'),
            Jurisdiction::fromString('federal'),
        );
    }

    /**
     * Constructed directly, not resolved from the container.
     *
     * WHY, recorded because the first RED run proved it: resolving the manager through the
     * container drags in the outbox adapter, which calls `TenantContext::require()` and
     * fails with *"Tenant context not set"*. That failure is the HARNESS, not the seam —
     * it would have masked every behavioural assertion below. Direct construction with
     * in-memory doubles keeps these keystones about the seam, which is also the WP-2
     * precedent for this manager.
     */
    private function manager(RequestsDeterminationIssuance $issuance): AdjudicationProcessManager
    {
        return new AdjudicationProcessManager(
            $this->store,
            new FrozenClock(new DateTimeImmutable('2026-08-03T10:00:00+00:00')),
            $this->durations(),
            new InMemoryEventOutbox(),
            $this->identities(),
            $issuance,
        );
    }

    // ── K1: concluding requests issuance, exactly once ───────────────────────

    /**
     * A process that CONCLUDED with every issuance fact retained and issuance NOT yet
     * requested -- crash model A, exactly as R-83 names it: transaction 1 committed and
     * transaction 2 never ran.
     *
     * **Built and saved WITHOUT traversing the seam, deliberately.** Driving the manager
     * would request issuance and write the marker, and model A is defined by that marker's
     * ABSENCE. This is the construction R-85 authorizes.
     */
    private function saveConcludedAwaiting(string $id, string $challenge): AdjudicationProcessState
    {
        $state = AdjudicationProcessState::open(
            AdjudicationProcessId::fromString($id),
            ChallengeRef::fromString($challenge),
            new DateTimeImmutable('2026-08-04T09:00:00+00:00'),
        )
            ->admitEvidence('envelope-sha256-abc', new DateTimeImmutable('2026-08-04T09:10:00+00:00'))
            ->submitToAuthority(new DateTimeImmutable('2026-08-04T09:20:00+00:00'))
            ->concludeRulingRequested(
                EvidenceSet::fromRefs('envelope-sha256-abc'),
                IssuedByAuthority::fromString('constitutional-authority-1'),
                DeterminationOutcome::Upheld,
                Legitimacy::Legitimate,
                Reason::fromString('the contested result is unsupported'),
                new DateTimeImmutable('2026-08-04T09:30:00+00:00'),
            )
            ->retainIssuanceContext(
                ContestedOutcomeRef::of(
                    ElectionId::fromString('el-1'),
                    TargetType::ElectionResult,
                    TargetId::fromString('res-1'),
                ),
                EvidenceEnvelopeRef::fromString('envelope-sha256-abc'),
            )
            ->retainJurisdiction(Jurisdiction::fromString('federal'));

        $this->store->save($state);

        return $state;
    }

    /**
     * R-81's keystone. `concludedAwaitingIssuance()` orders by `concluded_at`, so before
     * isolation the OLDEST stuck process aborted the pass and starved every newer one
     * indefinitely. **Every process must be attempted.**
     *
     * The failure is also asserted to PROPAGATE: isolation must not become silence.
     */
    public function test_r81_a_failing_process_does_not_prevent_the_others_from_being_requested(): void
    {
        $this->saveConcludedAwaiting('apm-poison', 'ch-poison');
        $this->saveConcludedAwaiting('apm-healthy', 'ch-healthy');

        $spy = new class implements RequestsDeterminationIssuance {
            /** @var list<IssueDeterminationCommand> */
            public array $requests = [];

            public function request(IssueDeterminationCommand $command): void
            {
                if ($command->challengeRef->toString() === 'ch-poison') {
                    throw new \RuntimeException('issuance refused for the poisoned process');
                }

                $this->requests[] = $command;
            }
        };

        $thrown = null;

        try {
            $this->manager($spy)->redriveIssuance();
        } catch (\Throwable $e) {
            $thrown = $e;
        }

        $requested = array_map(
            static fn (IssueDeterminationCommand $c): string => $c->challengeRef->toString(),
            $spy->requests,
        );

        $this->assertContains(
            'ch-healthy',
            $requested,
            'a process ordered after a failing one must still have its issuance requested',
        );

        $this->assertNotNull($thrown, 'isolation must not swallow the failure');
        $this->assertSame('issuance refused for the poisoned process', $thrown->getMessage());
    }

    /**
     * A collaborator that refuses every request with INV-B1's refusal, reporting the given
     * authority as the one that issued the EXISTING determination.
     */
    private function refusingIssuance(string $existingAuthority): RequestsDeterminationIssuance
    {
        return new class($existingAuthority) implements RequestsDeterminationIssuance {
            public int $attempts = 0;

            public function __construct(private readonly string $existingAuthority)
            {
            }

            public function request(IssueDeterminationCommand $command): void
            {
                ++$this->attempts;

                throw DeterminationAlreadyIssued::forExistingDetermination(
                    $command->challengeRef,
                    DeterminationId::fromString('det-existing-1'),
                    IssuedByAuthority::fromString($this->existingAuthority),
                );
            }
        };
    }

    // -- R-84 / EPIC-004K section 12, branch 1: self-redelivery => ACK ------------

    /**
     * The crash window closed by someone else's success: the determination EXISTS because
     * THIS process's earlier request succeeded, and only the marker was lost (crash model B,
     * R-83). §12 rules this a **redelivery: ack**.
     *
     * Acking means the refusal does NOT propagate AND the marker IS written — otherwise the
     * process stays in the redrive set and refuses forever, which is the poisoned pass R-81
     * was ruled against.
     */
    public function test_r84_a_self_redelivery_refusal_is_acked_and_leaves_the_redrive_set(): void
    {
        $this->saveConcludedAwaiting('apm-redeliv', 'ch-redeliv');

        // Same authority as the process concluded with -> this process's own determination.
        $refusing = $this->refusingIssuance('constitutional-authority-1');

        $this->manager($refusing)->redriveIssuance();

        $this->assertSame(1, $refusing->attempts, 'the request must have been attempted');
        $this->assertSame(
            [],
            $this->store->concludedAwaitingIssuance(),
            'an acked self-redelivery must leave the redrive set, or the pass poisons itself',
        );
    }

    // -- R-84 / section 12, branch 2: a competing writer => DEAD-LETTER + ESCALATE --

    /**
     * A determination exists that this process's conclusion did NOT produce. PM-1 + INV-B1
     * should make this impossible, which is exactly why it must be loud rather than acked.
     *
     * The marker must NOT be written: marking would bury the conflict.
     */
    public function test_r84_a_competing_determination_escalates_and_does_not_mark(): void
    {
        $this->saveConcludedAwaiting('apm-conflict', 'ch-conflict');

        // A DIFFERENT authority issued the existing determination.
        $refusing = $this->refusingIssuance('some-other-authority-99');

        $thrown = null;

        try {
            $this->manager($refusing)->redriveIssuance();
        } catch (\Throwable $e) {
            $thrown = $e;
        }

        $this->assertInstanceOf(
            ConflictingDeterminationForChallenge::class,
            $thrown,
            'a competing determination must escalate as a permanent failure, never be acked',
        );

        $this->assertCount(
            1,
            $this->store->concludedAwaitingIssuance(),
            'the marker must NOT be written for a conflict -- marking would bury it',
        );
    }

    public function test_k1_a_concluded_process_requests_issuance_exactly_once(): void
    {
        $spy = $this->issuanceSpy();

        $this->concludeFor($this->manager($spy));

        $this->assertCount(1, $spy->requests, 'concluding must request issuance exactly once');
    }

    // ── K2: crash between the transactions ⇒ redrive issues exactly one ──────

    /**
     * The keystone. The conclusion transaction committed and the issuance transaction did
     * not — crash model A, which EPIC-004K §11 makes possible by design (R-83). Redrive must
     * find the process and complete it, and the result must be ONE determination, never two.
     *
     * **AMENDED UNDER R-85.** The original setup concluded through the manager and then
     * cleared the spy's `requests` array. That mutated the SPY, not the STORE — it erased the
     * RECORD of the request while leaving its DURABLE EFFECT, the marker, in place. The
     * resulting state was *marked-but-never-requested*: **none of crash models A, B or C, and
     * one the design cannot produce.** The intent recorded in this docblock was always model
     * A; only the mechanism failed to reach it.
     *
     * **The seam was NOT changed to make this pass** (R-85). The amendment restores the
     * test's own recorded intent — it does not relax it.
     */
    public function test_k2_a_concluded_but_unissued_process_is_completed_by_redrive(): void
    {
        // Model A, reached honestly: transaction 1 committed, transaction 2 never ran.
        $this->saveConcludedAwaiting('apm-k2', $this->challenge->toString());

        $redriven = $this->issuanceSpy();
        $this->manager($redriven)->redriveIssuance();

        $this->assertCount(1, $redriven->requests, 'redrive must request issuance for a concluded-but-unissued process');
        $this->assertSame(
            $this->challenge->toString(),
            $redriven->requests[0]->challengeRef->toString(),
            'the redriven request must be for the concluded challenge',
        );

        // ONE determination, never two: the process has left the redrive set, so a second
        // pass requests nothing further.
        $this->manager($redriven)->redriveIssuance();
        $this->assertCount(1, $redriven->requests, 'a second redrive pass must request nothing further');
    }

    /**
     * R-83's model-B keystone, required by R-85: the determination EXISTS and the marker is
     * absent, because the earlier request succeeded and only the marker was lost.
     *
     * **The obligation is EXACTLY ONE DETERMINATION, NEVER TWO** — and the mechanism that
     * guarantees it is INV-B1 at the issuance boundary, not anything this seam remembers.
     * The refusal is reconciled per §12 (R-84) and the process leaves the redrive set, so no
     * later pass can produce a second.
     */
    public function test_r83_model_b_yields_exactly_one_determination_never_two(): void
    {
        $this->saveConcludedAwaiting('apm-modelb', 'ch-modelb');

        // INV-B1 refuses every request: a determination for this challenge already exists,
        // issued under the same authority this process concluded with.
        $refusing = $this->refusingIssuance('constitutional-authority-1');

        $this->manager($refusing)->redriveIssuance();
        $this->manager($refusing)->redriveIssuance();
        $this->manager($refusing)->redriveIssuance();

        $this->assertSame(
            1,
            $refusing->attempts,
            'after reconciliation the process must leave the redrive set, so repeated passes attempt nothing further',
        );
        $this->assertSame([], $this->store->concludedAwaitingIssuance());
    }

    // ── K3: redrive after issuance is inert ─────────────────────────────────

    public function test_k3_redrive_after_issuance_requests_nothing_further(): void
    {
        $spy = $this->issuanceSpy();
        $manager = $this->manager($spy);
        $this->concludeFor($manager);

        $manager->redriveIssuance();

        $this->assertCount(
            1,
            $spy->requests,
            'a process whose issuance was already requested must not be requested again',
        );
    }

    // ── K4: the command carries the record's facts and nothing else ──────────

    /**
     * AP-2 at the seam: the seam DEFINES, DEFAULTS and CLAMPS nothing. Every field is
     * the value the record holds — including the three whose producers are absent, for
     * which the record is the only legitimate source.
     */
    public function test_k4_the_command_is_built_only_from_the_concluded_record(): void
    {
        $spy = $this->issuanceSpy();

        $this->concludeFor($this->manager($spy));

        $command = $spy->requests[0];

        $this->assertSame($this->challenge->toString(), $command->challengeRef->toString());
        $this->assertSame(DeterminationOutcome::Upheld, $command->outcome);
        $this->assertSame(Legitimacy::Legitimate, $command->legitimacy);
        $this->assertSame('the contested result is unsupported', $command->reason->toString());
        $this->assertSame('constitutional-authority-1', $command->issuedByAuthority->toString());
        $this->assertSame('federal', $command->jurisdiction->toString());
        $this->assertSame('envelope-sha256-abc', $command->evidenceEnvelopeRef->toString());
        $this->assertSame('el-1', $command->contestedOutcome->electionId->toString());
        $this->assertSame(['envelope-sha256-abc'], $command->evidenceSet->toArray());
        $this->assertInstanceOf(DateTimeImmutable::class, $command->occurredAt);
    }
}
