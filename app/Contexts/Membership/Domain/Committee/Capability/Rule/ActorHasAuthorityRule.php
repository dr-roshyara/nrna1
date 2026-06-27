<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability\Rule;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;

final class ActorHasAuthorityRule implements CapabilityRule
{
    public function name(): string
    {
        return 'actor_has_governance_authority';
    }

    public function evaluate(CapabilityContext $ctx): bool
    {
        return $ctx->actor->hasGovernanceAuthority();
    }

    public function detail(CapabilityContext $ctx): string
    {
        return $ctx->actor->position->value;
    }

    public function isApplicable(CapabilityContext $ctx): bool
    {
        return true;
    }

    public function denyCode(): string
    {
        return 'no_authority';
    }

    public function denyCheckCode(): string
    {
        return 'actor_lacks_governance_authority';
    }
}
