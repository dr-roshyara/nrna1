<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/** L5 — the observation's interpretation string. Unchanged wording. */
final class Interpretation
{
    private function __construct()
    {
    }

    public static function of(int $value): string
    {
        return match (true) {
            $value === 0 => 'no analyzable methods',
            $value === 1 => 'single connected component (cohesive)',
            default => sprintf('%d connected components (disjoint responsibility clusters)', $value),
        };
    }
}
