<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * EvidenceSeverity
 *
 * Describes the severity of an overlay's evidence observation.
 * Replaces TrustLevel in OverlaySignal.suggestedElevatedLevel.
 *
 * Overlays describe the severity of what they found, NOT what action to take.
 * The Resolver interprets severity as part of authority derivation.
 */
enum EvidenceSeverity: string
{
    case LOW = 'low';
    case MODERATE = 'moderate';
    case HIGH = 'high';
}
