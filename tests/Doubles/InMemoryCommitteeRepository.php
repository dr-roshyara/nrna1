<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Contexts\Membership\Application\Committee\Ports\CommitteeRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use RuntimeException;

final class InMemoryCommitteeRepository implements CommitteeRepositoryPort
{
    /** @var array<string, ConstitutionalCommittee> */
    private array $committees = [];

    public function save(ConstitutionalCommittee $committee): void
    {
        $this->committees[$committee->getId()->value()] = $committee;
    }

    public function get(CommitteeId $id): ConstitutionalCommittee
    {
        $key = $id->value();
        if (!isset($this->committees[$key])) {
            throw new RuntimeException("Committee not found: {$key}");
        }
        return $this->committees[$key];
    }
}
