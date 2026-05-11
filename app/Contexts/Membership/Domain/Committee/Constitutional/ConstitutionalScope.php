<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

enum ConstitutionalScope: string
{
    case NATIONAL  = 'national';
    case REGIONAL  = 'regional';
    case EMERGENCY = 'emergency';
    case CARETAKER = 'caretaker';
    case ELECTION  = 'election';
}
