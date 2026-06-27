<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final class DefaultTemporalLegitimacyPolicy implements LegitimacyPolicy
{
    public function __construct(private readonly LegitimacyEvaluator $evaluator) {}

    public function evaluate(TemporalAuthorityWindow $window, \DateTimeImmutable $at): GovernanceLegitimacy
    {
        return $this->evaluator->evaluate($window, $at);
    }
}
