<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;

interface SnapshotMigrationPolicy
{
    public function canMigrate(SnapshotSchemaVersion $from, SnapshotSchemaVersion $to): bool;

    public function migrate(GovernanceDecisionSnapshot $snapshot, SnapshotSchemaVersion $target): GovernanceDecisionSnapshot;
}
