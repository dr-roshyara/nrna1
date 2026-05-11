<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

interface InstitutionalCapabilityPolicy
{
    public function canCreateCommittee(CapabilityContext $context): CapabilityEvaluation;

    public function canActivateStructure(CapabilityContext $context): CapabilityEvaluation;

    public function canModifyCommittee(CapabilityContext $context): CapabilityEvaluation;

    public function canDeprecateStructure(CapabilityContext $context): CapabilityEvaluation;

    public function evaluateCreateCommittee(CapabilityContext $context): EvaluationResult;

    public function evaluateActivateStructure(CapabilityContext $context): EvaluationResult;

    public function evaluateModifyCommittee(CapabilityContext $context): EvaluationResult;

    public function evaluateDeprecateStructure(CapabilityContext $context): EvaluationResult;
}
