<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Application\Committee\Ports\CommitteeRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use RuntimeException;

final class CommitteeRepository implements CommitteeRepositoryPort
{
    public function save(ConstitutionalCommittee $committee): void
    {
        // TODO: Implement persistence to committees table
        // For now, store in-memory or skip persistence for event-sourced aggregates
    }

    public function get(CommitteeId $id): ConstitutionalCommittee
    {
        throw new RuntimeException('Not implemented yet');
    }
}
