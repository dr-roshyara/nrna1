<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Policies;

final readonly class MatrixCell
{
    public function __construct(
        public int $governanceLevel,
        public int $geoLevel,
        public bool $allowed,
    ) {}
}
