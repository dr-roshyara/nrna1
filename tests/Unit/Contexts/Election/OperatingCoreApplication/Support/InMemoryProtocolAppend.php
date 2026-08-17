<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication\Support;

use App\Contexts\Election\Domain\OperatingCore\Port\HistoryKind;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolAppend;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolEntry;
use App\Contexts\Election\Domain\OperatingCore\Port\RefusalRecord;

/**
 * In-memory test double of the `ProtocolAppend` driven port (G-6: test doubles are
 * this increment's ceiling; no store technology is chosen). Append-only in meaning:
 * this double exposes recording and reading of what was recorded — nothing erases.
 *
 * EM-IMPL-002 Phase 1 (RED). W-10 note: this Support namespace doubles the
 * repositories, the protocol, the instant source and the policy snapshot — and
 * deliberately contains NO double of `OrganisationalAppointmentAuthority` (D-1;
 * the wall extends to tests — RED-4).
 */
final class InMemoryProtocolAppend implements ProtocolAppend
{
    /** @var list<ProtocolEntry> */
    public array $entries = [];

    public function append(ProtocolEntry $entry): void
    {
        $this->entries[] = $entry;
    }

    /** @return list<ProtocolEntry> recorded business facts only */
    public function eventEntries(): array
    {
        return array_values(array_filter($this->entries, static fn (ProtocolEntry $e) => ! $e->isRefusal()));
    }

    /** @return list<RefusalRecord> */
    public function refusals(): array
    {
        return array_values(array_map(
            static fn (ProtocolEntry $e) => $e->refusal,
            array_filter($this->entries, static fn (ProtocolEntry $e) => $e->isRefusal()),
        ));
    }

    /** @return list<class-string> the recorded fact classes, in append order */
    public function eventClassSequence(): array
    {
        return array_values(array_map(
            static fn (ProtocolEntry $e) => get_class($e->event),
            $this->eventEntries(),
        ));
    }

    /** @return list<HistoryKind> kinds of the recorded fact entries, in append order */
    public function eventKindSequence(): array
    {
        return array_values(array_map(
            static fn (ProtocolEntry $e) => $e->kind,
            $this->eventEntries(),
        ));
    }

    /** @return list<ProtocolEntry> fact entries whose event is of the given class */
    public function entriesOfEvent(string $eventClass): array
    {
        return array_values(array_filter(
            $this->eventEntries(),
            static fn (ProtocolEntry $e) => $e->event instanceof $eventClass,
        ));
    }

    public function countEventsOf(string $eventClass): int
    {
        return count($this->entriesOfEvent($eventClass));
    }
}
