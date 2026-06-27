<?php

declare(strict_types=1);

namespace App\Contexts\Committee\Application\ReadModel;

final readonly class CommitteeMemberView
{
    public function __construct(
        public string $memberId,
        public string $displayName,
        public string $statusKey,
        public string $roleKey,
        public ?\DateTimeImmutable $joinedAt = null,
        public ?\DateTimeImmutable $lastTransitionAt = null,
    ) {}
}
