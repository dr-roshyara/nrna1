<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Platform\Application\TechnicalOperationRecord;

/**
 * PBDIGIT-345 task 11 (ISO-8, §3.8: "recorded and reported to the organisation"): tell the organisation itself that a
 * technical operation was performed on its database (identifiers, kind, outcome, operator reference; never content).
 * Returns false if the organisation could not be told (its database unreachable): the central record says so.
 */
interface OrganisationOperationReport
{
    public function report(TechnicalOperationRecord $record, string $outcome, ?string $reason): bool;
}
