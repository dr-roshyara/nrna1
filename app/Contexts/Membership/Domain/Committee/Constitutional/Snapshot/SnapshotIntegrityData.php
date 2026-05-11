<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

final readonly class SnapshotIntegrityData
{
    public function __construct(
        public string $integrityHash,
        public string $schemaVersion,
        public string $replayEngineVersion,
        public string $replayCompatibilityVersion,
    ) {}
}
