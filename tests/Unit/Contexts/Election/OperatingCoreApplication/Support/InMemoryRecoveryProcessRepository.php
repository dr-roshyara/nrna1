<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication\Support;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\RecoveryProcess;
use App\Contexts\Election\Domain\OperatingCore\Repository\RecoveryProcessRepository;

/**
 * In-memory test double of the AG-3 repository contract (G-6): one process per
 * governed period kind per election (EM-GOV-061(b) — the allowance is per election,
 * never per failure). EM-IMPL-002 Phase 1 (RED).
 */
final class InMemoryRecoveryProcessRepository implements RecoveryProcessRepository
{
    /** @var array<string, RecoveryProcess> */
    private array $processes = [];

    public int $saveCount = 0;

    public function find(ElectionId $electionId, PeriodKind $kind): ?RecoveryProcess
    {
        return $this->processes[$this->key($electionId, $kind)] ?? null;
    }

    public function save(RecoveryProcess $process): void
    {
        $this->processes[$this->key($process->electionId(), $process->kind())] = $process;
        $this->saveCount++;
    }

    /** Fixture seeding without counting as a handler-driven save. */
    public function seed(RecoveryProcess $process): void
    {
        $this->processes[$this->key($process->electionId(), $process->kind())] = $process;
    }

    private function key(ElectionId $electionId, PeriodKind $kind): string
    {
        return $electionId->toString() . '|' . $kind->value;
    }
}
