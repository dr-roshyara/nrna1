<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Issuance;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;
use App\Contexts\Adjudication\Application\Port\RequestsDeterminationIssuance;
use App\Contexts\Adjudication\Application\Service\CoordinatesAdjudication;

/**
 * The seam's second half (WP-4B · R-72): the process manager's issuance REQUEST reaches
 * the issuance service through this adapter.
 *
 * WHY AN ADAPTER RATHER THAN A DIRECT CALL: `CoordinatesAdjudication` opens the issuance
 * transaction and writes the `Determination` aggregate. **ADR-T1 forbids one transaction
 * writing two roots**, and EPIC-004K §11 seats the PM's conclusion in its own write — so
 * the manager must not hold the issuance service, or the two writes would sit in one
 * collaborator's reach and the separation would depend on care rather than structure.
 *
 * **It adds nothing.** No retry, no pre-check, no translation: `TransactionalAdjudication
 * Service` already owns the transaction and INV-B1's guard already owns uniqueness at the
 * issuance boundary (EPIC-004E — *"deliberately NOT the aggregate's"*). **An adapter that
 * checked first would move that guard and duplicate the rule.**
 *
 * Traceability: R-72 · R-76 (the request path only) · EPIC-004K §11 · §12 · ADR-T1 ·
 * INV-B1 · `docs/plans/20260803-1600-wp4b-conclude-to-issue-seam-delivery-plan.md`.
 */
final class CoordinatorIssuanceRequest implements RequestsDeterminationIssuance
{
    public function __construct(
        private readonly CoordinatesAdjudication $coordinator,
    ) {
    }

    public function request(IssueDeterminationCommand $command): void
    {
        $this->coordinator->issueDetermination($command);
    }
}
