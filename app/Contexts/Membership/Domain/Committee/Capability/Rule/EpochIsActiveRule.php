<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability\Rule;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;

final class EpochIsActiveRule implements CapabilityRule
{
    public function name(): string
    {
        return 'epoch_is_active';
    }

    public function evaluate(CapabilityContext $ctx): bool
    {
        return $ctx->epoch->isActive();
    }

    public function detail(CapabilityContext $ctx): string
    {
        return $ctx->epoch->status;
    }

    public function isApplicable(CapabilityContext $ctx): bool
    {
        return true;
    }

    public function denyCode(): string
    {
        return 'epoch_inactive';
    }

    public function denyCheckCode(): string
    {
        return 'epoch_not_active';
    }
}
