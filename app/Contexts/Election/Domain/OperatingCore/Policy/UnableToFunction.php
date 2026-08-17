<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Policy;

use App\Contexts\Election\Domain\OperatingCore\Gate\RequiredVotes;

/**
 * P-3 `UnableToFunction` (stateless): "unable to function" is ARITHMETIC —
 * non-vacant seats < required Committee Votes. No declaration, no determiner,
 * no classifier (EM-GOV-065; the Option-C move validated by the EM-OPEN-109
 * analysis; EM-ARCH-001 §2e).
 */
final class UnableToFunction
{
    private function __construct()
    {
    }

    public static function evaluate(int $nonVacantSeats, RequiredVotes $required): bool
    {
        return $nonVacantSeats < $required->count();
    }
}
