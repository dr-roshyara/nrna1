<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability\Rule;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;

interface CapabilityRule
{
    public function name(): string;

    public function evaluate(CapabilityContext $ctx): bool;

    public function detail(CapabilityContext $ctx): string;

    public function isApplicable(CapabilityContext $ctx): bool;

    public function denyCode(): string;

    public function denyCheckCode(): string;
}
