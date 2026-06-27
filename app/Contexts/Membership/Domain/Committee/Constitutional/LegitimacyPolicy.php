<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

interface LegitimacyPolicy
{
    public function evaluate(TemporalAuthorityWindow $window, \DateTimeImmutable $at): GovernanceLegitimacy;
}
