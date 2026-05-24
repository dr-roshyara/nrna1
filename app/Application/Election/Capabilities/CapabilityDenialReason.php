<?php

namespace App\Application\Election\Capabilities;

enum CapabilityDenialReason: string
{
    case Suspended = 'suspended';
    case MissingRole = 'missing_role';
    case InvalidLifecycle = 'invalid_lifecycle';
    case UnmetPrecondition = 'unmet_precondition';

    public function label(): string
    {
        return match ($this) {
            self::Suspended => 'Election Suspended',
            self::MissingRole => 'Missing Required Role',
            self::InvalidLifecycle => 'Invalid Lifecycle State',
            self::UnmetPrecondition => 'Unmet Requirements',
        };
    }
}
