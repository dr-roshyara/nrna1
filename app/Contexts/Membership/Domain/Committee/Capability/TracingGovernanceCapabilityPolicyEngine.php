<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

final class TracingGovernanceCapabilityPolicyEngine implements InstitutionalCapabilityPolicy
{
    public function __construct(
        private readonly GovernanceCapabilityPolicyEngine $inner,
        private readonly bool $tracing = true,
    ) {}

    public function canCreateCommittee(CapabilityContext $ctx): CapabilityEvaluation
    {
        if (!$this->tracing) {
            return $this->inner->canCreateCommittee($ctx);
        }

        $result = $this->inner->evaluateCreateCommittee($ctx);
        return $this->attachTrace('canCreateCommittee', $result);
    }

    public function canActivateStructure(CapabilityContext $ctx): CapabilityEvaluation
    {
        if (!$this->tracing) {
            return $this->inner->canActivateStructure($ctx);
        }

        $result = $this->inner->evaluateActivateStructure($ctx);
        return $this->attachTrace('canActivateStructure', $result);
    }

    public function canModifyCommittee(CapabilityContext $ctx): CapabilityEvaluation
    {
        if (!$this->tracing) {
            return $this->inner->canModifyCommittee($ctx);
        }

        $result = $this->inner->evaluateModifyCommittee($ctx);
        return $this->attachTrace('canModifyCommittee', $result);
    }

    public function canDeprecateStructure(CapabilityContext $ctx): CapabilityEvaluation
    {
        if (!$this->tracing) {
            return $this->inner->canDeprecateStructure($ctx);
        }

        $result = $this->inner->evaluateDeprecateStructure($ctx);
        return $this->attachTrace('canDeprecateStructure', $result);
    }

    public function evaluateCreateCommittee(CapabilityContext $ctx): EvaluationResult
    {
        return $this->inner->evaluateCreateCommittee($ctx);
    }

    public function evaluateActivateStructure(CapabilityContext $ctx): EvaluationResult
    {
        return $this->inner->evaluateActivateStructure($ctx);
    }

    public function evaluateModifyCommittee(CapabilityContext $ctx): EvaluationResult
    {
        return $this->inner->evaluateModifyCommittee($ctx);
    }

    public function evaluateDeprecateStructure(CapabilityContext $ctx): EvaluationResult
    {
        return $this->inner->evaluateDeprecateStructure($ctx);
    }

    private function attachTrace(string $capability, EvaluationResult $result): CapabilityEvaluation
    {
        $trace = new CapabilityDecisionTrace(
            $capability,
            $result->evaluation->allowed,
            $result->steps,
            new \DateTimeImmutable()
        );

        return $result->evaluation->withTrace($trace);
    }
}
