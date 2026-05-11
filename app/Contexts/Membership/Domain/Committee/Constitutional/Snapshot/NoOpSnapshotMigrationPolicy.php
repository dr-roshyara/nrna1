<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;

final class NoOpSnapshotMigrationPolicy implements SnapshotMigrationPolicy
{
    public function canMigrate(SnapshotSchemaVersion $from, SnapshotSchemaVersion $to): bool
    {
        return $from->equals($to);
    }

    public function migrate(GovernanceDecisionSnapshot $snapshot, SnapshotSchemaVersion $target): GovernanceDecisionSnapshot
    {
        return $snapshot;
    }
}
