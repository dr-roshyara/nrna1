<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\CommitteeSeatId;
use App\Contexts\Election\Domain\OperatingCore\Committee\ElectionCommittee;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyReason;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptanceGateDecision;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Gate\ThresholdRule;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PolicyBinding;
use App\Contexts\Election\Domain\OperatingCore\Recovery\RecoveryProcess;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use DomainException;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Election\OperatingCoreApplication\Support\FixedInstantSource;
use Tests\Unit\Contexts\Election\OperatingCoreApplication\Support\FixedServicePolicySnapshot;
use Tests\Unit\Contexts\Election\OperatingCoreApplication\Support\InMemoryAcceptanceGateDecisionRepository;
use Tests\Unit\Contexts\Election\OperatingCoreApplication\Support\InMemoryElectionCommitteeRepository;
use Tests\Unit\Contexts\Election\OperatingCoreApplication\Support\InMemoryProtocolAppend;
use Tests\Unit\Contexts\Election\OperatingCoreApplication\Support\InMemoryRecoveryProcessRepository;

/**
 * EM-IMPL-002 Phase 1 — RED base fixture for the Increment-2 Application Layer
 * (grant signed and STARTed 2026-08-17, as amended: G-1…G-5, Q-1…Q-3).
 *
 * The production classes referenced by the factory methods below live under
 * `App\Contexts\Election\Application\OperatingCore\` and DO NOT EXIST YET —
 * every behavioural test in this suite fails by their absence. That is the
 * point: RED before GREEN, git-provable (registration §3b sequencing).
 *
 * NAME STANDING (D-7 / EM-OPEN-045): every command, handler and query name —
 * including constructor and method shapes pinned here — is an ILLUSTRATIVE
 * PLACEHOLDER with the same non-canonical standing as the domain event names.
 *
 * PORT WIRING pinned here (one place — GREEN implements to it), traceable to
 * proposal §3a ("constructor-injected ports only; no new port is introduced")
 * and the §8 flow diagrams:
 *  · UC-1/UC-2 handlers: AG-1 + AG-2 + AG-3 repositories, policy snapshot,
 *    protocol, instant source (F-2/F-5 touch all of them)
 *  · UC-3 handler: AG-1 + AG-2 + AG-3 repositories, protocol, instant source —
 *    NO policy snapshot: restoration RESUMES remaining portions, it never
 *    starts a period (EM-GOV-061(a); §8c)
 *  · UC-4 handler: AG-1 + AG-2 + AG-3 repositories, protocol, instant source —
 *    it mutates NO aggregate (§4) and never snapshots a policy
 *  · UC-5 handler: AG-1 repository, protocol, instant source
 *  · The D-1 port `OrganisationalAppointmentAuthority` is wired NOWHERE — the
 *    application layer neither calls nor implements it (W-10; RED-4).
 *
 * G-2 / Q-1 (authorization split — applies to every handler exercised here):
 * receive command = Application · authenticate/authorize the CALLER = expressly
 * NOT here (upstream Interface layer, later increment — A-2) · decide election
 * meaning = Domain (frozen policies/aggregates) · record fact/refusal =
 * Domain/Protocol port · persist = Infrastructure, later increment. Commands
 * therefore carry no actor identity and no caller-supplied instants (D-8).
 */
abstract class OperatingCoreApplicationTestCase extends TestCase
{
    protected const ELECTION = 'election-1';

    protected const HALTED_POLICY_SECONDS = 3_600;

    protected const RESTORATION_POLICY_SECONDS = 7_200;

    protected InMemoryProtocolAppend $protocol;

    protected InMemoryElectionCommitteeRepository $committees;

    protected InMemoryAcceptanceGateDecisionRepository $gates;

    protected InMemoryRecoveryProcessRepository $recoveries;

    protected FixedInstantSource $instants;

    protected FixedServicePolicySnapshot $policies;

    protected function setUp(): void
    {
        parent::setUp();
        $this->protocol = new InMemoryProtocolAppend();
        $this->committees = new InMemoryElectionCommitteeRepository();
        $this->gates = new InMemoryAcceptanceGateDecisionRepository();
        $this->recoveries = new InMemoryRecoveryProcessRepository();
        $this->instants = new FixedInstantSource(1_000);
        $this->policies = new FixedServicePolicySnapshot([
            PeriodKind::HaltedElectionRecovery->value => PolicyBinding::of('policy-v1', self::HALTED_POLICY_SECONDS),
            PeriodKind::CommitteeRestoration->value => PolicyBinding::of('policy-v1', self::RESTORATION_POLICY_SECONDS),
        ]);
    }

    // ── Domain fixture builders (construction fixtures — UC-6 is OUT of scope,
    //    A-4: aggregates are constructed directly, as EM-IMPL-001's tests do) ──

    protected function electionId(): ElectionId
    {
        return ElectionId::fromString(self::ELECTION);
    }

    protected function seat(string $id): CommitteeSeatId
    {
        return CommitteeSeatId::fromString($id);
    }

    protected function at(int $epochSeconds): RecordedInstant
    {
        return RecordedInstant::fromEpochSeconds($epochSeconds);
    }

    /** Constitutes AG-1 directly (fixture) and seeds the repository. */
    protected function seedCommittee(string ...$seatIds): ElectionCommittee
    {
        $committee = ElectionCommittee::constitute(
            $this->electionId(),
            ...array_map(fn (string $id) => $this->seat($id), $seatIds),
        );
        $this->committees->seed($committee);

        return $committee;
    }

    /** Establishes AG-2 directly (fixture — UC-6 excluded, A-4) and seeds the repository. */
    protected function seedGate(int $constitutedSize = 3, GateDesignation $gate = GateDesignation::First): AcceptanceGateDecision
    {
        $decision = AcceptanceGateDecision::establish(
            $this->electionId(),
            $gate,
            ThresholdRule::twoThirdsOfCommitteeVotes(),
            $constitutedSize,
        );
        $this->gates->seed($decision);

        return $decision;
    }

    /** Starts AG-3 directly (fixture) and seeds the repository. */
    protected function seedRecoveryProcess(PeriodKind $kind, int $onsetEpoch): RecoveryProcess
    {
        $binding = $kind === PeriodKind::HaltedElectionRecovery
            ? PolicyBinding::of('policy-v1', self::HALTED_POLICY_SECONDS)
            : PolicyBinding::of('policy-v1', self::RESTORATION_POLICY_SECONDS);

        $process = RecoveryProcess::start($this->electionId(), $kind, $binding, $this->at($onsetEpoch));
        $this->recoveries->seed($process);

        return $process;
    }

    // ── Application-layer factories: classes under the GRANTED namespace that
    //    DO NOT EXIST YET — each `new` below is a RED failure-by-absence site ──

    protected function expressPositionHandler(): object
    {
        return new \App\Contexts\Election\Application\OperatingCore\Handler\ExpressCommitteePositionHandler(
            $this->committees,
            $this->gates,
            $this->recoveries,
            $this->policies,
            $this->protocol,
            $this->instants,
        );
    }

    protected function recordVacancyHandler(): object
    {
        return new \App\Contexts\Election\Application\OperatingCore\Handler\RecordVacancyEventHandler(
            $this->committees,
            $this->gates,
            $this->recoveries,
            $this->policies,
            $this->protocol,
            $this->instants,
        );
    }

    protected function fillSeatHandler(): object
    {
        return new \App\Contexts\Election\Application\OperatingCore\Handler\FillCommitteeSeatHandler(
            $this->committees,
            $this->gates,
            $this->recoveries,
            $this->protocol,
            $this->instants,
        );
    }

    protected function reportExpiryHandler(): object
    {
        return new \App\Contexts\Election\Application\OperatingCore\Handler\ReportPeriodExpiryHandler(
            $this->committees,
            $this->gates,
            $this->recoveries,
            $this->protocol,
            $this->instants,
        );
    }

    protected function recordConstitutionHandler(): object
    {
        return new \App\Contexts\Election\Application\OperatingCore\Handler\RecordCommitteeConstitutionHandler(
            $this->committees,
            $this->protocol,
            $this->instants,
        );
    }

    protected function expressCommand(string $seatId, AcceptancePosition $position, GateDesignation $gate = GateDesignation::First): object
    {
        return new \App\Contexts\Election\Application\OperatingCore\Command\ExpressCommitteePositionCommand(
            $this->electionId(),
            $gate,
            $this->seat($seatId),
            $position,
        );
    }

    protected function vacancyCommand(
        string $seatId,
        VacancyGround $ground = VacancyGround::DeathOrPermanentIncapacity,
        ?VacancyReason $reason = null,
    ): object {
        return new \App\Contexts\Election\Application\OperatingCore\Command\RecordVacancyEventCommand(
            $this->electionId(),
            $this->seat($seatId),
            $ground,
            $reason,
        );
    }

    protected function fillCommand(string $seatId, string $appointeeReference = 'appointee-ref-1'): object
    {
        return new \App\Contexts\Election\Application\OperatingCore\Command\FillCommitteeSeatCommand(
            $this->electionId(),
            $this->seat($seatId),
            $appointeeReference,
        );
    }

    protected function expiryReportCommand(PeriodKind $kind): object
    {
        return new \App\Contexts\Election\Application\OperatingCore\Command\ReportPeriodExpiryCommand(
            $this->electionId(),
            $kind,
        );
    }

    protected function constitutionCommand(string ...$seatIds): object
    {
        return new \App\Contexts\Election\Application\OperatingCore\Command\RecordCommitteeConstitutionCommand(
            $this->electionId(),
            ...array_map(fn (string $id) => $this->seat($id), $seatIds),
        );
    }

    // ── Refusal tolerance ─────────────────────────────────────────────────────

    /**
     * Q-1/A-5 note: what IS pinned on a domain refusal is the port-observable
     * outcome (the refusal is RECORDED with the domain reason; no fact is
     * appended — Q-3). Whether the handler additionally rethrows the domain
     * exception to its caller is NOT fixed by the authorized record (repo Rule 8
     * makes domain messages user-visible either way), so tests tolerate both:
     * they run the act through this helper and then assert on the ports.
     */
    protected function toleratingDomainRefusal(callable $act): ?DomainException
    {
        try {
            $act();

            return null;
        } catch (DomainException $refused) {
            return $refused;
        }
    }
}
