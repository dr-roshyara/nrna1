<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Semantics;

enum GovernanceSemanticCategory: string
{
    case AUTHORITY = 'authority';
    case LEGITIMACY = 'legitimacy';
    case DELEGATION = 'delegation';
    case PARTICIPATION = 'participation';
    case CERTIFICATION = 'certification';
    case TEMPORALITY = 'temporality';
    case JURISDICTION = 'jurisdiction';
    case MANDATE = 'mandate';
    case CONFLICT = 'conflict';
    case PROVENANCE = 'provenance';
}
