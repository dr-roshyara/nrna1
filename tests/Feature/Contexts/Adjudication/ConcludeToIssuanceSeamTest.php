<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Adjudication;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Port\RequestsDeterminationIssuance;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessManager;
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
use DateTimeImmutable;
use Tests\TestCase;

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->challenge = ChallengeRef::fromString('ch-4b-seam-1');
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
            EvidenceSet::of(['envelope-sha256-abc']),
            Jurisdiction::fromString('federal'),
        );
    }

    private function manager(RequestsDeterminationIssuance $issuance): AdjudicationProcessManager
    {
        $this->app->instance(RequestsDeterminationIssuance::class, $issuance);

        return $this->app->make(AdjudicationProcessManager::class);
    }

    // ── K1: concluding requests issuance, exactly once ───────────────────────

    public function test_k1_a_concluded_process_requests_issuance_exactly_once(): void
    {
        $spy = $this->issuanceSpy();

        $this->concludeFor($this->manager($spy));

        $this->assertCount(1, $spy->requests, 'concluding must request issuance exactly once');
    }

    // ── K2: crash between the transactions ⇒ redrive issues exactly one ──────

    /**
     * The keystone. The conclusion transaction committed and the issuance transaction
     * did not — the state EPIC-004K §11 makes possible by design. Redrive must find
     * the process and complete it, and the result must be ONE determination, never two.
     */
    public function test_k2_a_concluded_but_unissued_process_is_completed_by_redrive(): void
    {
        $crashed = $this->issuanceSpy();
        $manager = $this->manager($crashed);
        $this->concludeFor($manager);

        // Simulate the crash: the request never reached issuance.
        $crashed->requests = [];

        $redriven = $this->issuanceSpy();
        $this->manager($redriven)->redriveIssuance();

        $this->assertCount(1, $redriven->requests, 'redrive must re-request issuance for a concluded-but-unissued process');
        $this->assertSame(
            $this->challenge->toString(),
            $redriven->requests[0]->challengeRef->toString(),
            'the redriven request must be for the concluded challenge',
        );
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
