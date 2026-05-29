<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\Simplified\OverlaySignal;

interface Overlay
{
    public function evaluate(TrustCapabilityContext $ctx): OverlaySignal;

    public function identifier(): string;
}
