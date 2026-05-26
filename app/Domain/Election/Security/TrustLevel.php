<?php

namespace App\Domain\Election\Security;

enum TrustLevel: string
{
    case Unverified = 'unverified';
    case Attested = 'attested';
    case ContinuityVerified = 'continuity_verified';
    case RegistrarAttested = 'registrar_attested';
}
