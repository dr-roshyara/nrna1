<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Kernel;

enum CapabilityType: string
{
    case COMMITTEE_CREATION = 'committee_creation';
    case STRUCTURE_ACTIVATION = 'structure_activation';
    case COMMITTEE_MODIFICATION = 'committee_modification';
    case STRUCTURE_DEPRECATION = 'structure_deprecation';
}
