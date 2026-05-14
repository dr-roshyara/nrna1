<?php

declare(strict_types=1);

namespace App\Contexts\Committee\Application\ReadModel;

final readonly class CommitteeMembershipStats
{
    public function __construct(
        public int $active,
        public int $suspended,
        public int $terminated,
        public int $total,
    ) {}
}
