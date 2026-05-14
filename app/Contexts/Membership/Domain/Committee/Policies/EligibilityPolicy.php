<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;

interface EligibilityPolicy
{
    public function isEligible(GeoPathChain $committee, GeoPathChain $member): bool;
}
