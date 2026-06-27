<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

use App\Contexts\Membership\Domain\Committee\Capability\Rule\CapabilityRule;
use App\Contexts\Membership\Domain\Committee\Capability\Rule\ActorIsOwnerRule;

final class StructureActivationPolicy
{
    /** @return CapabilityRule[] */
    public static function rules(): array
    {
        return [
            new ActorIsOwnerRule(),
        ];
    }

    public function evaluate(CapabilityContext $context): EvaluationResult
    {
        $steps = [];

        $isOwner = $context->actor->isOwner();
        $steps[] = new DecisionStep('actor_is_owner', $isOwner, $context->actor->position->value);
        if (!$isOwner) {
            return new EvaluationResult(
                CapabilityEvaluation::deny('not_owner', ['actor_is_not_owner']),
                $steps
            );
        }

        return new EvaluationResult(
            CapabilityEvaluation::allow('owner_can_activate'),
            $steps
        );
    }
}
