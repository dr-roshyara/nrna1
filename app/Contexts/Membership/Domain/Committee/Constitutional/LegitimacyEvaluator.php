<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final readonly class LegitimacyEvaluator
{
    public function evaluate(
        TemporalAuthorityWindow $window,
        \DateTimeImmutable $at,
    ): GovernanceLegitimacy {
        return match ($window->stateAt($at)) {
            TemporalWindowState::PENDING  => GovernanceLegitimacy::PENDING,
            TemporalWindowState::EXPIRED  => GovernanceLegitimacy::EXPIRED,
            TemporalWindowState::ACTIVE   => GovernanceLegitimacy::LEGITIMATE,
        };
    }
}
