<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Snapshot;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotSchemaVersion;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\NoOpSnapshotMigrationPolicy;

final class SnapshotMigrationPolicyTest extends TestCase
{
    public function test_noop_policy_can_migrate_same_version(): void
    {
        $policy = new NoOpSnapshotMigrationPolicy();
        $v1 = SnapshotSchemaVersion::fromString('1.0');

        $this->assertTrue($policy->canMigrate($v1, $v1));
    }

    public function test_noop_policy_cannot_migrate_different_versions(): void
    {
        $policy = new NoOpSnapshotMigrationPolicy();
        $v1 = SnapshotSchemaVersion::fromString('1.0');
        $v2 = SnapshotSchemaVersion::fromString('2.0');

        $this->assertFalse($policy->canMigrate($v1, $v2));
    }
}
