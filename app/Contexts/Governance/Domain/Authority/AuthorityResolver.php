<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Authority;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

final class AuthorityResolver
{
    public function resolveActiveAt(
        CommitteeId $committeeId,
        DateTimeImmutable $at,
        AuthorityAssignmentRepository $repo,
    ): ?AuthorityAssignment {
        $active = $repo->findActiveByCommittee($committeeId, $at);

        if (empty($active)) {
            return null;
        }

        usort($active, function (AuthorityAssignment $a, AuthorityAssignment $b): int {
            $diff = $b->type()->priority() - $a->type()->priority();

            if ($diff !== 0) {
                return $diff;
            }

            // Lexicographic tiebreak on assignment id for determinism
            return strcmp($a->id()->value(), $b->id()->value());
        });

        return $active[0];
    }
}
