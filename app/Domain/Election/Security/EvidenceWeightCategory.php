<?php

namespace App\Domain\Election\Security;

/**
 * EvidenceWeightCategory
 *
 * Classifies the reliability and weight of evidence analyzed by an overlay.
 * Used for descriptive overlay influence (D.R.2).
 *
 * DEFINITIVE  → Strong, reliable evidence from multiple sources
 * STRONG      → Reliable evidence from authoritative sources
 * MODERATE    → Reasonable evidence with some uncertainty
 * WEAK        → Limited evidence, high uncertainty (inconclusive)
 */
enum EvidenceWeightCategory: string
{
    case DEFINITIVE = 'definitive';
    case STRONG = 'strong';
    case MODERATE = 'moderate';
    case WEAK = 'weak';
}
