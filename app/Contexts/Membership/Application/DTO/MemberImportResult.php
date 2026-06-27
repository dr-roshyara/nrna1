<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\DTO;

final readonly class MemberImportResult
{
    /**
     * @param int $imported Number of members successfully imported
     * @param int $skipped Number of members skipped (duplicate email, idempotent)
     * @param int $failed Number of members failed (validation error, system error)
     * @param array<array-key, array{row: int, email: string, reason: string}> $errors Error details
     */
    public function __construct(
        public int $imported,
        public int $skipped,
        public int $failed,
        public array $errors = [],
    ) {}
}
