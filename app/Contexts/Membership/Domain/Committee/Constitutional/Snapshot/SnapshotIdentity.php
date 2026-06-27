<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

final readonly class SnapshotIdentity
{
    public function __construct(
        public string  $decisionId,
        public string  $capabilityType,
        public ?string $constitutionalScope,
    ) {}
}
