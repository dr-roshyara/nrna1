<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteePositionExpressed;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatFilled;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatVacated;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionBecameInoperative;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionCancelledOnRestorationExpiry;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionRestored;
use App\Contexts\Election\Domain\OperatingCore\Event\GateFailedByDecision;
use App\Contexts\Election\Domain\OperatingCore\Event\GateSatisfied;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodExpired;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodStarted;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptancePosition;
use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;

/**
 * EM-IMPL-002 RED — grant item (iii): THE PER-EVENT HISTORYKIND ASSIGNMENT TABLE,
 * pinned by tests, not by prose (proposal §9b) — RED-5: lifecycle history and
 * progression-decision history are never merged (P-2H; F-PROTO-1 property 7).
 *
 * The table (every appendable operating-core fact a UC-1…UC-5 flow can produce):
 *
 *   | Recorded fact                        | HistoryKind         |
 *   |--------------------------------------|---------------------|
 *   | CommitteePositionExpressed           | ProgressionDecision |
 *   | GateSatisfied                        | ProgressionDecision |
 *   | GateFailedByDecision                 | ProgressionDecision |
 *   | CommitteeSeatVacated                 | Lifecycle           |
 *   | ElectionBecameInoperative            | Lifecycle           |
 *   | RecoveryPeriodStarted                | Lifecycle           |
 *   | CommitteeSeatFilled                  | Lifecycle           |
 *   | ElectionRestored                     | Lifecycle           |
 *   | RecoveryPeriodExpired                | Lifecycle           |
 *   | ElectionCancelledOnRestorationExpiry | Lifecycle           |
 *
 * NOT in the table, deliberately: `AppointmentsAwaited` — no Increment-2 use case
 * produces it (the pre-Chief halt has no application entry point in this scope);
 * its Lifecycle standing is already exemplified by the frozen
 * `ProtocolAppendContractTest`. The UC-5 constitution fact's TYPE is a named open
 * point (see `RecordCommitteeConstitutionHandlerRedTest`); its KIND is pinned
 * there as Lifecycle.
 */
final class HistoryKindAssignmentRedTest extends OperatingCoreApplicationTestCase
{
    private const TABLE = [
        CommitteePositionExpressed::class => HistoryKind::ProgressionDecision,
        GateSatisfied::class => HistoryKind::ProgressionDecision,
        GateFailedByDecision::class => HistoryKind::ProgressionDecision,
        CommitteeSeatVacated::class => HistoryKind::Lifecycle,
        ElectionBecameInoperative::class => HistoryKind::Lifecycle,
        RecoveryPeriodStarted::class => HistoryKind::Lifecycle,
        CommitteeSeatFilled::class => HistoryKind::Lifecycle,
        ElectionRestored::class => HistoryKind::Lifecycle,
        RecoveryPeriodExpired::class => HistoryKind::Lifecycle,
        ElectionCancelledOnRestorationExpiry::class => HistoryKind::Lifecycle,
    ];

    public function test_every_fact_a_handler_appends_carries_exactly_the_pinned_history_kind(): void
    {
        $observed = [];

        // Scenario A — UC-1 pass: PositionExpressed ×2, GateSatisfied.
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->expressPositionHandler();
        $handler->handle($this->expressCommand('s1', AcceptancePosition::Accept));
        $handler->handle($this->expressCommand('s2', AcceptancePosition::Accept));
        $this->collect($observed);

        // Scenario B — UC-1 decided failure (F-2): GateFailedByDecision, RecoveryPeriodStarted (halted).
        $this->setUp();
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->expressPositionHandler();
        $handler->handle($this->expressCommand('s1', AcceptancePosition::Object));
        $handler->handle($this->expressCommand('s2', AcceptancePosition::Object));
        $this->collect($observed);

        // Scenario C — UC-2 breaching vacancies (F-5): SeatVacated, ElectionBecameInoperative, RecoveryPeriodStarted (restoration).
        $this->setUp();
        $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $handler = $this->recordVacancyHandler();
        $this->instants->setNowEpoch(1_000);
        $handler->handle($this->vacancyCommand('s1'));
        $this->instants->setNowEpoch(2_000);
        $handler->handle($this->vacancyCommand('s2'));
        $this->collect($observed);

        // Scenario D — UC-3 restoring fill (F-6): SeatFilled, ElectionRestored.
        $this->setUp();
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $committee->recordVacancy($this->seat('s1'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(10));
        $committee->recordVacancy($this->seat('s2'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(20));
        $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 20);
        $this->instants->setNowEpoch(100);
        $this->fillSeatHandler()->handle($this->fillCommand('s1'));
        $this->collect($observed);

        // Scenario E — UC-4 halted-recovery expiry (F-7): RecoveryPeriodExpired.
        $this->setUp();
        $this->seedCommittee('s1', 's2', 's3');
        $gate = $this->seedGate(3);
        $gate->expressPosition($this->seat('s1'), AcceptancePosition::Object, $this->at(10));
        $gate->expressPosition($this->seat('s2'), AcceptancePosition::Object, $this->at(20));
        $this->seedRecoveryProcess(PeriodKind::HaltedElectionRecovery, 0);
        $this->instants->setNowEpoch(4_000);
        $this->reportExpiryHandler()->handle($this->expiryReportCommand(PeriodKind::HaltedElectionRecovery));
        $this->collect($observed);

        // Scenario F — UC-4 restoration expiry (F-8): ElectionCancelledOnRestorationExpiry.
        $this->setUp();
        $committee = $this->seedCommittee('s1', 's2', 's3');
        $this->seedGate(3);
        $committee->recordVacancy($this->seat('s1'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(10));
        $committee->recordVacancy($this->seat('s2'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(20));
        $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 0);
        $this->instants->setNowEpoch(8_000);
        $this->reportExpiryHandler()->handle($this->expiryReportCommand(PeriodKind::CommitteeRestoration));
        $this->collect($observed);

        ksort($observed);
        $expected = self::TABLE;
        ksort($expected);

        $this->assertSame($expected, $observed, 'RED-5/P-2H: the per-event HistoryKind table, complete and exact — the two histories are never merged.');
    }

    /** @param array<class-string, HistoryKind> $observed */
    private function collect(array &$observed): void
    {
        foreach ($this->protocol->eventEntries() as $entry) {
            $class = get_class($entry->event);
            $this->assertArrayHasKey($class, self::TABLE, sprintf('An appended fact (%s) outside the pinned table — no unpinned history exists.', $class));
            if (isset($observed[$class])) {
                $this->assertSame($observed[$class], $entry->kind, sprintf('%s carried TWO different history kinds — the histories merged (P-2H violated).', $class));
            }
            $observed[$class] = $entry->kind;
        }
    }
}
