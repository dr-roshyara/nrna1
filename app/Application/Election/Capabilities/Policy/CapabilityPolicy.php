<?php

namespace App\Application\Election\Capabilities\Policy;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;

interface CapabilityPolicy
{
    public function evaluate(CapabilityContext $context): ?CapabilityDecision;

    public function layer(): CapabilityPolicyLayer;
}
