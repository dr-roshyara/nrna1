<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\SnapshotMetadata;

final class SnapshotMetadataTest extends TestCase
{
    public function test_metadata_holds_all_version_fields(): void
    {
        $now = new \DateTimeImmutable();
        $metadata = new SnapshotMetadata(
            schemaVersion: '1.0',
            doctrineVersion: '1.0',
            legitimacyPolicyVersion: '1.0',
            arbitrationPolicyVersion: '1.0',
            replayEngineVersion: '3.2',
            replayCompatibilityVersion: '3.2',
            generatedAt: $now,
        );

        $this->assertSame('1.0', $metadata->schemaVersion);
        $this->assertSame('1.0', $metadata->doctrineVersion);
        $this->assertSame('1.0', $metadata->legitimacyPolicyVersion);
        $this->assertSame('1.0', $metadata->arbitrationPolicyVersion);
        $this->assertSame('3.2', $metadata->replayEngineVersion);
        $this->assertSame('3.2', $metadata->replayCompatibilityVersion);
        $this->assertSame($now, $metadata->generatedAt);
    }

    public function test_replay_engine_version_constant_is_defined(): void
    {
        $this->assertSame('3.2', SnapshotMetadata::REPLAY_ENGINE_VERSION);
    }
}
