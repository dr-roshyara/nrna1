<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability\Rule;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;

final class OrgIsActiveRule implements CapabilityRule
{
    public function name(): string
    {
        return 'organisation_is_active';
    }

    public function evaluate(CapabilityContext $ctx): bool
    {
        return $ctx->organisation->isActive();
    }

    public function detail(CapabilityContext $ctx): string
    {
        return $ctx->organisation->governanceStatus;
    }

    public function isApplicable(CapabilityContext $ctx): bool
    {
        return true;
    }

    public function denyCode(): string
    {
        return 'org_inactive';
    }

    public function denyCheckCode(): string
    {
        return 'organisation_not_active';
    }
}
