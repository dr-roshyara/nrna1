<?php

namespace App\Domain\Election\Security;

enum OverlayStratification: string
{
    case EMERGENCY_CONSTITUTIONAL = 'emergency_constitutional';
    case GOVERNANCE_LAYER         = 'governance_layer';
    case OPERATIONAL_LAYER        = 'operational_layer';
    case CONTEXTUAL_LAYER         = 'contextual_layer';

    // Purpose: ordering metadata for aggregation traversal ONLY.
    // Does NOT determine which signal "wins" — that is Resolver responsibility.
}
