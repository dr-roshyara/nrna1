<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** The Objection lifecycle (strategic baseline S1.1; v3.11 §16). Corrected, Disputed and Rejected are final. */
enum ObjectionState: string
{
    case Submitted = 'submitted';
    case Assessed = 'assessed';
    case Corrected = 'corrected';
    case Disputed = 'disputed';
    case Rejected = 'rejected';

    public function isDecided(): bool
    {
        return in_array($this, [self::Corrected, self::Disputed, self::Rejected], true);
    }
}
