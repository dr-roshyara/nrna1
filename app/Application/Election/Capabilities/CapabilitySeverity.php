<?php

namespace App\Application\Election\Capabilities;

enum CapabilitySeverity: string
{
    case HardBlock = 'hard_block';
    case GovernanceHold = 'governance_hold';
    case Warning = 'warning';
    case Advisory = 'advisory';

    public function label(): string
    {
        return match ($this) {
            self::HardBlock => 'Hard Block',
            self::GovernanceHold => 'Governance Hold',
            self::Warning => 'Warning',
            self::Advisory => 'Advisory',
        };
    }

    public function isBlocking(): bool
    {
        return match ($this) {
            self::HardBlock, self::GovernanceHold => true,
            self::Warning, self::Advisory => false,
        };
    }
}
