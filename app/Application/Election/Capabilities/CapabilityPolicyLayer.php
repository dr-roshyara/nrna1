<?php

namespace App\Application\Election\Capabilities;

enum CapabilityPolicyLayer: string
{
    case Overlay = 'overlay';
    case Lifecycle = 'lifecycle';
    case Preconditions = 'preconditions';
    case Authorization = 'authorization';

    public function priority(): int
    {
        return match ($this) {
            self::Overlay => 1,
            self::Lifecycle => 2,
            self::Preconditions => 3,
            self::Authorization => 4,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Overlay => 'Operational Overlay',
            self::Lifecycle => 'Lifecycle State',
            self::Preconditions => 'Constitutional Requirements',
            self::Authorization => 'Role Authorization',
        };
    }
}
