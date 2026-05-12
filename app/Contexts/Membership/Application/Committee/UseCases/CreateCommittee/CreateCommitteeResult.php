<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee;

use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;

final readonly class CreateCommitteeResult
{
    public function __construct(
        public ConstitutionalCommittee $committee,
    ) {}
}
