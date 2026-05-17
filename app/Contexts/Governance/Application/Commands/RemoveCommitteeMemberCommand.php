<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Commands;

// Pure PHP — No Laravel dependencies
final readonly class RemoveCommitteeMemberCommand
{
    public function __construct(
        public string $tenantId,
        public string $committeeId,
        public string $memberId
    ) {}
}
