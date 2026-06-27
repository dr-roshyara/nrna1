<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Service;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Exception\DeterminationAlreadyIssued;

/**
 * Application coordinator for adjudication (NOT a domain service). Orchestration
 * only: load data, verify preconditions via domain objects, create + persist the
 * Determination (the sole aggregate written — ADR-T14), enqueue its events to the
 * outbox. It does NOT evaluate legitimacy/admissibility, perform constitutional
 * reasoning, manipulate aggregate internals, or touch Eloquent/facades.
 */
interface AdjudicationService
{
    /** @throws DeterminationAlreadyIssued */
    public function issueDetermination(IssueDeterminationCommand $command): DeterminationId;
}
