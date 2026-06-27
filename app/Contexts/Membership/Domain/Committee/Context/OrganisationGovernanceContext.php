<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Context;

final readonly class OrganisationGovernanceContext
{
    public function __construct(
        public string $organisationId,
        public string $governanceStatus,
    ) {}

    public function isActive(): bool
    {
        return strtolower($this->governanceStatus) === 'active';
    }
}
