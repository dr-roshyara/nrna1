<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

use App\Contexts\Membership\Domain\Committee\Capability\Rule\CapabilityRule;
use App\Contexts\Membership\Domain\Committee\Capability\Rule\OrgIsActiveRule;
use App\Contexts\Membership\Domain\Committee\Capability\Rule\ActorHasAuthorityRule;
use App\Contexts\Membership\Domain\Committee\Capability\Rule\GeoScopeValidRule;
use App\Contexts\Membership\Domain\Committee\Capability\Rule\EpochIsActiveRule;

final class CommitteeCreationPolicy
{
    /** @return CapabilityRule[] */
    public static function rules(): array
    {
        return [
            new OrgIsActiveRule(),
            new ActorHasAuthorityRule(),
            new GeoScopeValidRule(),
            new EpochIsActiveRule(),
        ];
    }

    public function evaluate(CapabilityContext $context): EvaluationResult
    {
        $steps = [];

        $orgActive = $context->organisation->isActive();
        $steps[] = new DecisionStep('organisation_is_active', $orgActive, $context->organisation->governanceStatus);
        if (!$orgActive) {
            return new EvaluationResult(
                CapabilityEvaluation::deny('org_inactive', ['organisation_not_active']),
                $steps
            );
        }

        $hasAuth = $context->actor->hasGovernanceAuthority();
        $steps[] = new DecisionStep('actor_has_governance_authority', $hasAuth, $context->actor->position->value);
        if (!$hasAuth) {
            return new EvaluationResult(
                CapabilityEvaluation::deny('no_authority', ['actor_lacks_governance_authority']),
                $steps
            );
        }

        if ($context->targetScope !== null) {
            $geoValid = $context->actor->canOperateIn($context->targetScope);
            $detail = "actor:{$context->actor->geographicScope->level} target:{$context->targetScope->level}";
            $steps[] = new DecisionStep('geographic_scope_valid', $geoValid, $detail);
            if (!$geoValid) {
                return new EvaluationResult(
                    CapabilityEvaluation::deny('geo_violation', ['geographic_scope_violation']),
                    $steps
                );
            }
        }

        $epochActive = $context->epoch->isActive();
        $steps[] = new DecisionStep('epoch_is_active', $epochActive, $context->epoch->status);
        if (!$epochActive) {
            return new EvaluationResult(
                CapabilityEvaluation::deny('epoch_inactive', ['epoch_not_active']),
                $steps
            );
        }

        return new EvaluationResult(
            CapabilityEvaluation::allow('all_checks_passed'),
            $steps
        );
    }
}
