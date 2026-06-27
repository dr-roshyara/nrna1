<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Graph;

use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;

final readonly class JurisdictionNode
{
    public function __construct(
        public string $id,
        public string $level,
        public ?string $code,
        public bool $active,
        public bool $isExceptionZone = false,
    ) {}

    public function matchesScope(GeographicScope $scope): bool
    {
        return $this->level === $scope->level && $this->code === $scope->code;
    }
}
