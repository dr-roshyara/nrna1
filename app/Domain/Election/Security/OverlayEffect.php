<?php

namespace App\Domain\Election\Security;

enum OverlayEffect: string
{
    case Deny = 'deny';
    case ElevateTrust = 'elevate_trust';
    case RequireReview = 'require_review';
    case RestrictContinuity = 'restrict_continuity';
    case ForceReAttestation = 'force_reattestion';
}
