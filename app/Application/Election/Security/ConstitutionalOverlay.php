<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\OverlaySignal;

interface ConstitutionalOverlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal;

    public function identifier(): string;
}
