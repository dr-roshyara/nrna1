<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\Simplified\OverlaySignal;

final class OverlayAggregator
{
    /** @param Overlay[] $overlays */
    public function __construct(private array $overlays) {}

    public function aggregate(TrustCapabilityContext $ctx): ConstitutionalObservationContext
    {
        $signals = [];
        foreach ($this->overlays as $overlay) {
            $signal = $overlay->evaluate($ctx);
            if ($signal instanceof OverlaySignal) {
                $signals[] = $signal;
            }
        }

        return new ConstitutionalObservationContext($signals);
    }
}
