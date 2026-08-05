<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Service;

use App\Contexts\Contestation\Application\Command\RaiseChallengeCommand;
use App\Contexts\Contestation\Application\Port\TransactionManager;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;

/**
 * Transactional decorator for {@see ContestationService} (ADR-T1: one transaction =
 * one aggregate root + its outbox row(s)).
 *
 * It matters most for `route()`, which saves the `Challenge` AND writes its outbox
 * row: a refused route, or a failure between the two, must leave neither a mutated
 * challenge nor an orphan message. The decorator owns the boundary; the coordinator
 * owns the orchestration; the aggregate owns the rules.
 *
 * House precedent: `TransactionalAdjudicationService` (the pattern is mirrored, not
 * imported — contract R-1/R-2).
 */
final class TransactionalContestationService implements ContestationService
{
    public function __construct(
        private readonly ContestationService $inner,
        private readonly TransactionManager $transactions,
    ) {
    }

    public function raise(RaiseChallengeCommand $command): ChallengeId
    {
        return $this->transactions->transactional(fn (): ChallengeId => $this->inner->raise($command));
    }

    public function admit(ChallengeId $id): void
    {
        $this->transactions->transactional(function () use ($id): void {
            $this->inner->admit($id);
        });
    }

    public function route(ChallengeId $id, string $routedTo): void
    {
        $this->transactions->transactional(function () use ($id, $routedTo): void {
            $this->inner->route($id, $routedTo);
        });
    }
}
