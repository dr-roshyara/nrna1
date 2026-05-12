<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Ports;

use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use RuntimeException;

interface CommitteeRepositoryPort
{
    public function save(ConstitutionalCommittee $committee): void;

    /** @throws RuntimeException If not found */
    public function get(CommitteeId $id): ConstitutionalCommittee;
}
