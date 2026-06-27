<?php

namespace App\Domain\Election\Security;

enum BallotAuthorizationProtocol: string
{
    case UnifiedTokenProtocol = 'single_code';
    case SplitAuthorizationProtocol = 'dual_code';

    public function authorizationSteps(): int
    {
        return match ($this) {
            self::UnifiedTokenProtocol => 1,
            self::SplitAuthorizationProtocol => 2,
        };
    }

    public function requiresSeparateCommit(): bool
    {
        return $this === self::SplitAuthorizationProtocol;
    }

    public function requiresViewToken(): bool
    {
        return $this === self::SplitAuthorizationProtocol;
    }
}
