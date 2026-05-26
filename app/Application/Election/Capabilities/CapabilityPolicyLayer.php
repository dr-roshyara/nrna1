<?php

namespace App\Application\Election\Capabilities;

enum CapabilityPolicyLayer: int
{
    case Overlay = 1;
    case Trust = 2;
    case Lifecycle = 3;
    case Preconditions = 4;
    case Authorization = 5;

    public function priority(): int
    {
        return $this->value;
    }

    public function label(): string
    {
        return match ($this) {
            self::Overlay => 'Operational Overlay',
            self::Trust => 'Constitutional Trust',
            self::Lifecycle => 'Lifecycle State',
            self::Preconditions => 'Constitutional Requirements',
            self::Authorization => 'Role Authorization',
        };
    }
}
