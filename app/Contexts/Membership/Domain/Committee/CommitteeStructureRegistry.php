<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\Committee\Strategies\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\CentralCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\GeographicCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\YouthWingStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\WomenWingStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\StudentWingStructure;

final class CommitteeStructureRegistry
{
    public static function forType(CommitteeType $type): CommitteeStructure
    {
        return match ($type->value()) {
            'central' => new CentralCommitteeStructure(),
            'province' => new GeographicCommitteeStructure(),
            'district' => new GeographicCommitteeStructure(),
            'ward' => new GeographicCommitteeStructure(),
            'youth_wing' => new YouthWingStructure(),
            'women_wing' => new WomenWingStructure(),
            'student_wing' => new StudentWingStructure(),
            default => throw new \DomainException("Unknown committee type: {$type->value()}"),
        };
    }
}
