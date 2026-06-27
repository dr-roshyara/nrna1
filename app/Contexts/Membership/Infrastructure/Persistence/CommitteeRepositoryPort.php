<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Persistence;

use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;

interface CommitteeRepositoryPort
{
    public function save(ConstitutionalCommittee $committee): void;
}
