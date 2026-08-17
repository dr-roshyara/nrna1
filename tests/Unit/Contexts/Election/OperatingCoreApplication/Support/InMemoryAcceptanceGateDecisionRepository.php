<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication\Support;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Gate\AcceptanceGateDecision;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Repository\AcceptanceGateDecisionRepository;

/**
 * In-memory test double of the AG-2 repository contract (G-6). Stores the aggregate
 * only — the interval state stays DERIVED, never persisted (EM-GOV-068; DD-1).
 * EM-IMPL-002 Phase 1 (RED).
 */
final class InMemoryAcceptanceGateDecisionRepository implements AcceptanceGateDecisionRepository
{
    /** @var array<string, AcceptanceGateDecision> */
    private array $decisions = [];

    public int $saveCount = 0;

    public function find(ElectionId $electionId, GateDesignation $gate): ?AcceptanceGateDecision
    {
        return $this->decisions[$this->key($electionId, $gate)] ?? null;
    }

    public function save(AcceptanceGateDecision $decision): void
    {
        $this->decisions[$this->key($decision->electionId(), $decision->gate())] = $decision;
        $this->saveCount++;
    }

    /** Fixture seeding without counting as a handler-driven save. */
    public function seed(AcceptanceGateDecision $decision): void
    {
        $this->decisions[$this->key($decision->electionId(), $decision->gate())] = $decision;
    }

    private function key(ElectionId $electionId, GateDesignation $gate): string
    {
        return $electionId->toString() . '|' . $gate->value;
    }
}
