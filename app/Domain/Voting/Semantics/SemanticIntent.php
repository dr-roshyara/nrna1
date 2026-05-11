<?php

declare(strict_types=1);

namespace App\Domain\Voting\Semantics;

/**
 * SemanticIntent — Constitutional Governance Classification
 *
 * Classifies the constitutional meaning and governance purpose of a decision.
 *
 * Examples:
 *   - ORDINARY_MOTION: Standard governance decision
 *   - CONSTITUTIONAL_AMENDMENT: Modification of constitutional structure
 *   - EMERGENCY_ACTION: Time-sensitive crisis governance
 *   - DISCIPLINARY_ACTION: Member accountability
 *
 * This is semantic metadata (the WHY), not structural (the HOW).
 */
enum SemanticIntent: string
{
    case ORDINARY_MOTION           = 'ORDINARY_MOTION';
    case CONSTITUTIONAL_AMENDMENT  = 'CONSTITUTIONAL_AMENDMENT';
    case EMERGENCY_ACTION          = 'EMERGENCY_ACTION';
    case DISCIPLINARY_ACTION       = 'DISCIPLINARY_ACTION';
    case TREATY_RATIFICATION       = 'TREATY_RATIFICATION';
}
