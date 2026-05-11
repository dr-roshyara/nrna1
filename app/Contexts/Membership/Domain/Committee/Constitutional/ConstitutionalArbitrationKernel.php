<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionKernel;

final class ConstitutionalArbitrationKernel
{
    public function __construct(
        private readonly GovernanceDecisionKernel        $kernel,
        private readonly ConstitutionalArbitrationPolicy $arbitrationPolicy,
        private readonly GovernanceClock                 $clock,
    ) {}

    public function decide(
        CapabilityContext   $ctx,
        CapabilityType      $capability,
        ?\DateTimeImmutable $at = null,
    ): ConstitutionalGovernanceDecision {
        $at = $at ?? $this->clock->now();

        $governanceDecision = $this->kernel->decide($ctx, $capability, $at);

        $constitutionalDecision = $this->arbitrationPolicy->arbitrate(
            $governanceDecision->classification,
            $at,
        );

        return new ConstitutionalGovernanceDecision($governanceDecision, $constitutionalDecision);
    }
}
