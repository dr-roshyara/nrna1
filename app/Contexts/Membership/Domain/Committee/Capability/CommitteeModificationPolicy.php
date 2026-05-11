<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

use App\Contexts\Membership\Domain\Committee\Capability\Rule\CapabilityRule;
use App\Contexts\Membership\Domain\Committee\Capability\Rule\ActorIsOwnerRule;
use App\Contexts\Membership\Domain\Committee\Capability\Rule\ActorChairsCommitteeRule;

final class CommitteeModificationPolicy
{
    /** @return CapabilityRule[] */
    public static function rules(): array
    {
        return [
            new ActorIsOwnerRule(),
            new ActorChairsCommitteeRule(),
        ];
    }

    public function evaluate(CapabilityContext $context): EvaluationResult
    {
        $steps = [];

        $isOwner = $context->actor->isOwner();
        $steps[] = new DecisionStep('actor_is_owner', $isOwner, $context->actor->position->value);
        if ($isOwner) {
            return new EvaluationResult(
                CapabilityEvaluation::allow('owner_can_modify'),
                $steps
            );
        }

        $isChair = $context->actor->committeeId !== null
            && $context->lineage->committeeId === $context->actor->committeeId;
        $steps[] = new DecisionStep('actor_chairs_this_committee', $isChair, $context->actor->position->value);
        if ($isChair) {
            return new EvaluationResult(
                CapabilityEvaluation::allow('chair_can_modify_own_committee'),
                $steps
            );
        }

        return new EvaluationResult(
            CapabilityEvaluation::deny('no_modification_authority', [
                'actor_is_not_owner',
                'actor_not_chair_of_this_committee'
            ]),
            $steps
        );
    }
}
