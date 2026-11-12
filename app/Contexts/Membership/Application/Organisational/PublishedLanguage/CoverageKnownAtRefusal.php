<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\PublishedLanguage;

/** C2's accepted refusals (CR-01; spec §6, K-3). A refusal is never "not a member" (K-5). */
enum CoverageKnownAtRefusal: string
{
    case BoundaryNotClosed = 'BOUNDARY_NOT_CLOSED';
    case SourceNotLedger = 'SOURCE_NOT_LEDGER';
    case CrossOrganisation = 'CROSS_ORGANISATION';
}
