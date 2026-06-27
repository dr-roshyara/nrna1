<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

final readonly class EvaluationResult
{
    /**
     * @param DecisionStep[] $steps
     */
    public function __construct(
        public CapabilityEvaluation $evaluation,
        public array $steps,
    ) {}
}
