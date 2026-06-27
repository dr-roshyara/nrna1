<?php

namespace App\Domain\Election\Security;

/**
 * ConstitutionalConcernLevel
 *
 * Describes the severity of constitutional concern signaled by an overlay.
 * Used for descriptive overlay influence (D.R.2).
 *
 * NONE        → No concern detected
 * LOW         → Minor concern, proceed with caution
 * MEDIUM      → Moderate concern, elevated scrutiny recommended
 * HIGH        → Significant concern, manual review advised
 * CRITICAL    → Critical governance concern, escalation required
 */
enum ConstitutionalConcernLevel: string
{
    case NONE = 'none';
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';
}
