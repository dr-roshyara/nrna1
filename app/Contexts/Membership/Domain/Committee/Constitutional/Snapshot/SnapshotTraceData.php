<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

final readonly class SnapshotTraceData
{
    public function __construct(
        public string $arbitrationTraceJson,
        public string $doctrineVersion,
        public string $arbitrationPolicyVersion,
    ) {}
}
