<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Actor;

enum ActorPosition: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case COMMISSION = 'commission';
    case VOTER = 'voter';
    case MEMBER = 'member';

    public function hasGovernanceAuthority(): bool
    {
        return in_array($this, [
            self::OWNER,
            self::ADMIN,
            self::COMMISSION,
        ], strict: true);
    }

    public static function fromRole(string $role): self
    {
        return match(strtolower($role)) {
            'owner' => self::OWNER,
            'admin' => self::ADMIN,
            'commission' => self::COMMISSION,
            'voter' => self::VOTER,
            'member' => self::MEMBER,
            default => self::MEMBER,
        };
    }
}
