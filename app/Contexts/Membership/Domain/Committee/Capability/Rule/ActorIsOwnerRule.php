<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability\Rule;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;

final class ActorIsOwnerRule implements CapabilityRule
{
    public function name(): string
    {
        return 'actor_is_owner';
    }

    public function evaluate(CapabilityContext $ctx): bool
    {
        return $ctx->actor->isOwner();
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
        return 'not_owner';
    }

    public function denyCheckCode(): string
    {
        return 'actor_is_not_owner';
    }
}
