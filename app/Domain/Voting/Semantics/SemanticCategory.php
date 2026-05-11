<?php

declare(strict_types=1);

namespace App\Domain\Voting\Semantics;

/**
 * SemanticCategory — Governance Rule Classification
 *
 * Classifies the type of governance rule based on evaluation strategy.
 *
 * Categories:
 *   - ABSOLUTE: Fixed threshold count
 *   - PERCENTAGE_BASED: Relative to eligible population
 *   - WEIGHTED: Multi-factor weighted decision
 *   - COMPOSITE: Combination of multiple rules
 *
 * This categorizes the structural approach while intent defines the purpose.
 */
enum SemanticCategory: string
{
    case ABSOLUTE         = 'ABSOLUTE';
    case PERCENTAGE_BASED = 'PERCENTAGE_BASED';
    case WEIGHTED         = 'WEIGHTED';
    case COMPOSITE        = 'COMPOSITE';
}
