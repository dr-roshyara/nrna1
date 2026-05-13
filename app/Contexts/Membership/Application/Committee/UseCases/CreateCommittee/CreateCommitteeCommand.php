<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;

final readonly class CreateCommitteeCommand
{
    public function __construct(
        public string $name,
        public GovernanceAssignment $assignment,
    ) {}
}
