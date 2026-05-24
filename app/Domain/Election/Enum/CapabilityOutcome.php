<?php

namespace App\Domain\Election\Enum;

enum CapabilityOutcome: string
{
    case Granted = 'granted';
    case Denied = 'denied';
    case Abstained = 'abstained';
    case ShortCircuit = 'short_circuit';

    public function isTerminal(): bool
    {
        return $this === self::ShortCircuit || $this === self::Denied;
    }

    public function label(): string
    {
        return match ($this) {
            self::Granted => 'Granted',
            self::Denied => 'Denied',
            self::Abstained => 'Abstained',
            self::ShortCircuit => 'Short Circuit',
        };
    }
}
