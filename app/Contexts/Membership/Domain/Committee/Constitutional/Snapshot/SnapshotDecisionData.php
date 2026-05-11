<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

final readonly class SnapshotDecisionData
{
    public function __construct(
        public ?string $winningAuthorityId,
        public string  $legitimacy,
        public string  $constitutionalReasonJson,
    ) {}
}
