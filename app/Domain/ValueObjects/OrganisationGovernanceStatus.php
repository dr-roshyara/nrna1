<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

enum OrganisationGovernanceStatus: string
{
    case PENDING_SETUP = 'pending_setup';
    case GOVERNANCE_CONFIGURED = 'governance_configured';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';

    public function allowCommitteeCreation(): bool
    {
        return $this === self::ACTIVE;
    }

    public function allowGovernanceSetup(): bool
    {
        return in_array($this, [self::PENDING_SETUP, self::GOVERNANCE_CONFIGURED]);
    }
}
