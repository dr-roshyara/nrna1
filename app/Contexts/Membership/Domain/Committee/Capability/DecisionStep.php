<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

final readonly class DecisionStep
{
    public function __construct(
        public string $ruleId,
        public bool $passed,
        public string $detail,
    ) {}
}
