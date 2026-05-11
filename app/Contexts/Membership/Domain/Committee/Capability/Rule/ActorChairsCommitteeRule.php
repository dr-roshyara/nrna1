<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability\Rule;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;

final class ActorChairsCommitteeRule implements CapabilityRule
{
    public function name(): string
    {
        return 'actor_chairs_this_committee';
    }

    public function evaluate(CapabilityContext $ctx): bool
    {
        return $ctx->actor->committeeId !== null
            && $ctx->lineage->committeeId === $ctx->actor->committeeId;
    }

    public function detail(CapabilityContext $ctx): string
    {
        return $ctx->actor->committeeId ?? 'none';
    }

    public function isApplicable(CapabilityContext $ctx): bool
    {
        return true;
    }

    public function denyCode(): string
    {
        return 'no_modification_authority';
    }

    public function denyCheckCode(): string
    {
        return 'actor_not_chair_of_this_committee';
    }
}
