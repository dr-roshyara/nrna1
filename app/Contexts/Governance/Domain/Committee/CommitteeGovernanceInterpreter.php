<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee;

use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationStatus;
use App\Contexts\Governance\Domain\Committee\Policies\ConstitutionalLegitimacyPolicy;
use App\Contexts\Governance\Domain\Committee\Policies\OperationalStatePolicy;
use App\Contexts\Governance\Domain\Committee\Policies\TemporalGovernancePolicy;
use App\Contexts\Governance\Domain\Committee\ViewModels\CommitteeGovernanceProjection;
use App\Contexts\Governance\Domain\ValueObjects\AuthorityChain;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use DateTimeImmutable;

final class CommitteeGovernanceInterpreter
{
    public function __construct(
        private OperationalStatePolicy $operationalPolicy,
        private TemporalGovernancePolicy $temporalPolicy,
        private ConstitutionalLegitimacyPolicy $legitimacyPolicy,
    ) {}

    public function interpret(
        CommitteeFacts $facts,
        DateTimeImmutable $now,
        ?AuthorityChain $authorityChain = null,
        DelegationStatus $delegationStatus = DelegationStatus::ACTIVE,
    ): CommitteeGovernanceProjection {
        $operational = $this->operationalPolicy->evaluate($facts);
        $temporal = $this->temporalPolicy->evaluate($facts, $now);
        $legitimacy = $this->legitimacyPolicy->evaluate($facts, $authorityChain, $delegationStatus);

        return new CommitteeGovernanceProjection(
            committeeId: $facts->id,
            operationalState: $operational,
            temporalState: $temporal,
            legitimacy: $legitimacy,
            evaluatedAt: $now,
        );
    }
}
