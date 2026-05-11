<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability\Rule;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;

final class GeoScopeValidRule implements CapabilityRule
{
    public function name(): string
    {
        return 'geographic_scope_valid';
    }

    public function evaluate(CapabilityContext $ctx): bool
    {
        if ($ctx->targetScope === null) {
            return true;
        }
        return $ctx->actor->canOperateIn($ctx->targetScope);
    }

    public function detail(CapabilityContext $ctx): string
    {
        if ($ctx->targetScope === null) {
            return 'no_target_scope';
        }
        return "actor:{$ctx->actor->geographicScope->level} target:{$ctx->targetScope->level}";
    }

    public function isApplicable(CapabilityContext $ctx): bool
    {
        return $ctx->targetScope !== null;
    }

    public function denyCode(): string
    {
        return 'geo_violation';
    }

    public function denyCheckCode(): string
    {
        return 'geographic_scope_violation';
    }
}
