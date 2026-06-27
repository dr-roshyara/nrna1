<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Authority\Policies;

use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationStatus;

final class DelegationLifecyclePolicy
{
    public function canTransition(DelegationStatus $from, DelegationStatus $to): bool
    {
        return match($from) {
            DelegationStatus::ACTIVE  => $to === DelegationStatus::REVOKED,
            DelegationStatus::REVOKED => false,
        };
    }
}
