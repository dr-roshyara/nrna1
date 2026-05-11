<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Resolution;

use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;

final class DefaultConflictResolutionPolicy implements ConflictResolutionPolicy
{
    public function __construct(
        private readonly AuthoritySelectionPolicy $selector,
    ) {}

    public function resolve(AuthorityClassification $classification): FinalAuthorityDecision
    {
        if (!empty($classification->exceptions)) {
            return FinalAuthorityDecision::from(
                $this->selector->select($classification->exceptions),
                AuthorityResolutionType::EXCEPTION,
                'exception_precedence'
            );
        }

        if (!empty($classification->overrides)) {
            return FinalAuthorityDecision::from(
                $this->selector->select($classification->overrides),
                AuthorityResolutionType::OVERRIDE,
                'override_precedence'
            );
        }

        if ($classification->direct !== null) {
            return FinalAuthorityDecision::from(
                $classification->direct,
                AuthorityResolutionType::DIRECT,
                'direct_beats_delegated'
            );
        }

        if (!empty($classification->delegated)) {
            return FinalAuthorityDecision::from(
                $this->selector->select($classification->delegated),
                AuthorityResolutionType::DELEGATED,
                'delegated_fallback'
            );
        }

        return FinalAuthorityDecision::none();
    }
}
