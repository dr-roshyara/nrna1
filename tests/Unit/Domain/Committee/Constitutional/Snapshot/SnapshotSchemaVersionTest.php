<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Snapshot;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotSchemaVersion;

final class SnapshotSchemaVersionTest extends TestCase
{
    public function test_current_constant_resolves_to_version_string(): void
    {
        $this->assertSame('1.0', SnapshotSchemaVersion::CURRENT);
    }

    public function test_from_string_creates_comparable_version(): void
    {
        $v1 = SnapshotSchemaVersion::fromString('1.0');
        $v1b = SnapshotSchemaVersion::fromString('1.0');

        $this->assertTrue($v1->equals($v1b));
    }

    public function test_is_compatible_with_same_version_true_different_false(): void
    {
        $v1 = SnapshotSchemaVersion::fromString('1.0');
        $v2 = SnapshotSchemaVersion::fromString('2.0');

        $this->assertTrue($v1->isCompatibleWith($v1));
        $this->assertFalse($v1->isCompatibleWith($v2));
    }
}
