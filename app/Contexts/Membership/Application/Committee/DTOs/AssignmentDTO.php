<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\DTOs;

use DateTimeImmutable;

final readonly class AssignmentDTO
{
    public function __construct(
        public string $id,
        public string $memberId,
        public ?string $memberName,
        public string $roleLabel,
        public string $rolePath,
        public DateTimeImmutable $joinedDate,
    ) {}
}
