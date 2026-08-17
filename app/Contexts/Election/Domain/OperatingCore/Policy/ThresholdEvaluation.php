<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Policy;

use App\Contexts\Election\Domain\OperatingCore\Gate\RequiredVotes;
use App\Contexts\Election\Domain\OperatingCore\Gate\ThresholdRule;

/**
 * P-1 `ThresholdEvaluation` (stateless): required = ⌈2·constituted/3⌉; pass iff
 * accepts ≥ required — the named rule evaluated against the constituted denominator
 * (EM-GOV-036 · 038 · 057; EM-ARCH-001 §2e).
 */
final class ThresholdEvaluation
{
    private function __construct()
    {
    }

    public static function requiredVotes(ThresholdRule $rule, int $constitutedSize): RequiredVotes
    {
        return $rule->requiredVotesFor($constitutedSize);
    }

    public static function passes(int $accepts, RequiredVotes $required): bool
    {
        return $accepts >= $required->count();
    }
}
