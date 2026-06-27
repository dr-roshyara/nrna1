<?php

namespace App\Domain\Election\Security\Simplified;

use App\Domain\Election\Security\DivergenceCategory;

/**
 * DivergenceRecord
 *
 * Immutable record of a divergence detected between OLD and NEW evaluation pipelines.
 * Created by ShadowDivergenceDetector when outcomes differ.
 *
 * Stores both OLD and NEW evaluation states for replay analysis.
 */
readonly class DivergenceRecord
{
    public function __construct(
        public string $evaluationHash,
        public string $oldEvaluationState,
        public string $newEvaluationState,
        public ?string $oldReason,
        public ?string $newReason,
        public array $oldPolicySequence,
        public array $newPolicySequence,
        public ?string $oldOverlaySignal,
        public ?string $newOverlaySignal,
        public DivergenceCategory $category,
        public string $divergenceDetail,
        public \DateTimeImmutable $recordedAt,
    ) {}
}
