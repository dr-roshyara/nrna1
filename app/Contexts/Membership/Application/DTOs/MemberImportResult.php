<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\DTOs;

final readonly class MemberImportResult
{
    public function __construct(
        public int $imported,
        public int $skipped,
        public int $failed,
        public array $errors = [],
    ) {}
}
