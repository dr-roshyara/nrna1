<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Authority;

use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

interface AuthorityAssignmentRepository
{
    public function findById(AuthorityAssignmentId $id): ?AuthorityAssignment;

    /** @return AuthorityAssignment[] */
    public function findActiveByCommittee(CommitteeId $id, DateTimeImmutable $at): array;

    /** @return AuthorityAssignment[] */
    public function findAllByFromCommittee(CommitteeId $id): array;

    public function append(AuthorityAssignment $assignment): void;

    public function save(AuthorityAssignment $assignment): void;
}
