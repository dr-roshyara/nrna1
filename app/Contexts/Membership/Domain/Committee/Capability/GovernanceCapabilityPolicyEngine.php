<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

final class GovernanceCapabilityPolicyEngine implements InstitutionalCapabilityPolicy
{
    public function __construct(
        private readonly CommitteeCreationPolicy $creationPolicy,
        private readonly StructureActivationPolicy $activationPolicy,
        private readonly CommitteeModificationPolicy $modificationPolicy,
        private readonly StructureDeprecationPolicy $deprecationPolicy,
    ) {}

    public function canCreateCommittee(CapabilityContext $context): CapabilityEvaluation
    {
        return $this->evaluateCreateCommittee($context)->evaluation;
    }

    public function evaluateCreateCommittee(CapabilityContext $context): EvaluationResult
    {
        return $this->creationPolicy->evaluate($context);
    }

    public function canActivateStructure(CapabilityContext $context): CapabilityEvaluation
    {
        return $this->evaluateActivateStructure($context)->evaluation;
    }

    public function evaluateActivateStructure(CapabilityContext $context): EvaluationResult
    {
        return $this->activationPolicy->evaluate($context);
    }

    public function canModifyCommittee(CapabilityContext $context): CapabilityEvaluation
    {
        return $this->evaluateModifyCommittee($context)->evaluation;
    }

    public function evaluateModifyCommittee(CapabilityContext $context): EvaluationResult
    {
        return $this->modificationPolicy->evaluate($context);
    }

    public function canDeprecateStructure(CapabilityContext $context): CapabilityEvaluation
    {
        return $this->evaluateDeprecateStructure($context)->evaluation;
    }

    public function evaluateDeprecateStructure(CapabilityContext $context): EvaluationResult
    {
        return $this->deprecationPolicy->evaluate($context);
    }
}
