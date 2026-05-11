<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Authority;

use App\Contexts\Governance\Domain\Authority\AuthorityAssignment;
use App\Contexts\Governance\Domain\Authority\AuthorityAssignmentRepository;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;

/**
 * Validates that a proposed delegation does not create a cycle in the authority graph.
 *
 * MUST be called within a SERIALIZABLE transaction boundary (AD-02). Without
 * serializable isolation, two concurrent delegations (A→B and B→A) can both
 * pass this check independently and introduce a cycle.
 */
final class AuthorityGraphValidator
{
    public function assertNoCycle(
        CommitteeId $fromId,
        CommitteeId $toId,
        AuthorityAssignmentRepository $repo,
    ): void {
        // BFS from $toId following outbound delegation edges.
        // If we reach $fromId, adding fromId→toId would form a cycle.
        $visited = [];
        $queue = [$toId];

        while (!empty($queue)) {
            $current = array_shift($queue);
            $key = $current->value();

            if (isset($visited[$key])) {
                continue;
            }

            $visited[$key] = true;

            if ($current->equals($fromId)) {
                throw new \DomainException(
                    "Delegation cycle detected: adding {$fromId->value()} → {$toId->value()} would create a circular authority graph"
                );
            }

            foreach ($repo->findAllByFromCommittee($current) as $assignment) {
                /** @var AuthorityAssignment $assignment */
                $queue[] = $assignment->toCommitteeId();
            }
        }
    }
}
