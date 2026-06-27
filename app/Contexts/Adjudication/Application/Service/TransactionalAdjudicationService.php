<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Service;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Port\TransactionManager;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;

/**
 * Transaction-owning decorator over the (frozen) CoordinatesAdjudication. Runs
 * the use case inside one transaction so the Determination write and its outbox
 * enqueue commit atomically. The inner service is unchanged (freeze respected);
 * this mirrors the existing TransactionalCreateCommittee decorator convention.
 */
final class TransactionalAdjudicationService implements AdjudicationService
{
    public function __construct(
        private readonly AdjudicationService $inner,
        private readonly TransactionManager $transactions,
    ) {
    }

    public function issueDetermination(IssueDeterminationCommand $command): DeterminationId
    {
        return $this->transactions->transactional(
            fn (): DeterminationId => $this->inner->issueDetermination($command)
        );
    }
}
