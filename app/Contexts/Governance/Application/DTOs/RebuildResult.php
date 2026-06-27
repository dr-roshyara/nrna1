<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\DTOs;

final readonly class RebuildResult
{
    public function __construct(
        public int $rebuilt,
        public int $failed,
        public int $skipped,
        public string $status,
    ) {}
}
