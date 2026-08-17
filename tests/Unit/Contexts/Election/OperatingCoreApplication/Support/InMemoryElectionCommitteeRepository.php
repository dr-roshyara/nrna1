<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication\Support;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Committee\ElectionCommittee;
use App\Contexts\Election\Domain\OperatingCore\Repository\ElectionCommitteeRepository;

/**
 * In-memory test double of the AG-1 repository contract (G-6). `saveCount` lets a
 * test pin "no aggregate mutation" flows (UC-4) and query purity (UQ-1…UQ-4).
 * EM-IMPL-002 Phase 1 (RED).
 */
final class InMemoryElectionCommitteeRepository implements ElectionCommitteeRepository
{
    /** @var array<string, ElectionCommittee> */
    private array $committees = [];

    public int $saveCount = 0;

    public function find(ElectionId $electionId): ?ElectionCommittee
    {
        return $this->committees[$electionId->toString()] ?? null;
    }

    public function save(ElectionCommittee $committee): void
    {
        $this->committees[$committee->electionId()->toString()] = $committee;
        $this->saveCount++;
    }

    /** Fixture seeding without counting as a handler-driven save. */
    public function seed(ElectionCommittee $committee): void
    {
        $this->committees[$committee->electionId()->toString()] = $committee;
    }
}
