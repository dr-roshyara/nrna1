<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee\Policies;

use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationStatus;
use App\Contexts\Governance\Domain\ValueObjects\AuthorityChain;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use App\Contexts\Membership\Domain\ValueObjects\ConstitutionalLegitimacy;

final class ConstitutionalLegitimacyPolicy
{
    public function evaluate(
        CommitteeFacts $facts,
        ?AuthorityChain $authorityChain,
        DelegationStatus $delegationStatus = DelegationStatus::ACTIVE,
    ): ConstitutionalLegitimacy {
        if ($facts->operationalState === 'DISSOLVED' || $facts->operationalState === 'SUSPENDED') {
            return ConstitutionalLegitimacy::UNAUTHORIZED;
        }

        if ($authorityChain === null) {
            return ConstitutionalLegitimacy::UNAUTHORIZED;
        }

        if ($delegationStatus === DelegationStatus::REVOKED) {
            return ConstitutionalLegitimacy::REVOKED;
        }

        if ($authorityChain->delegationPath()->length() < 2) {
            return ConstitutionalLegitimacy::UNAUTHORIZED;
        }

        return ConstitutionalLegitimacy::LEGITIMATE;
    }
}
