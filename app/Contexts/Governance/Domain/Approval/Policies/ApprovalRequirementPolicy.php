<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Approval\Policies;

final class ApprovalRequirementPolicy
{
    public function requiresApproval(string $capabilityType): bool
    {
        return !empty($this->requiredApproverTypes($capabilityType));
    }

    /** @return string[] strategy type identifiers */
    public function requiredApproverTypes(string $capabilityType): array
    {
        return match($capabilityType) {
            'COMMITTEE_FORMATION'     => ['PARENT_COMMITTEE'],
            'CONSTITUTIONAL_AMENDMENT'=> ['ICC_BOARD'],
            'AUTHORITY_DELEGATION'    => ['SOURCE_COMMITTEE'],
            default                   => [],
        };
    }
}
