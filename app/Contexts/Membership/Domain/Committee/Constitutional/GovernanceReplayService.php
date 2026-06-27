<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;

final class GovernanceReplayService
{
    public function __construct(
        private readonly GovernanceDecisionStore $store,
        private readonly GovernanceClock         $clock,
    ) {}

    public function persist(
        ConstitutionalGovernanceDecision $decision,
        CapabilityType                   $capability,
        ?SnapshotMetadata                $metadata = null,
    ): GovernanceDecisionSnapshot {
        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            $capability,
            $this->clock->now(),
            $metadata,
        );
        $this->store->store($snapshot);
        return $snapshot;
    }

    public function replay(GovernanceDecisionId $id): GovernanceArchaeologyRecord
    {
        $snapshot = $this->store->findById($id);

        if ($snapshot === null) {
            throw new GovernanceDecisionNotFoundException($id->toString());
        }

        if (!$snapshot->verifyIntegrity()) {
            throw new SnapshotIntegrityViolationException($id->toString());
        }

        return new GovernanceArchaeologyRecord($snapshot, $this->clock->now());
    }
}
