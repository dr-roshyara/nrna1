<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\ConstitutionalConcernLevel;
use App\Domain\Election\Security\EvidenceWeightCategory;

/**
 * PolicyFinding (D.R.3)
 *
 * Policies emit findings, not authority decisions.
 * Findings describe constitutional facts discovered during evaluation.
 * Resolver interprets findings to derive participation authority.
 *
 * Policies are NEVER authority engines. They are constitutional fact reporters.
 */
readonly class PolicyFinding
{
    public function __construct(
        public ConstitutionalConcernLevel  $concernLevel,
        public EvidenceWeightCategory      $evidenceWeight,
        public string                      $constitutionalBasis,
        public array                       $supportingFacts,      // NO raw PII — minimized/hashed
        public ?string                     $policyIdentifier = null,
    ) {}

    public static function noFinding(string $policyId): self
    {
        return new self(
            ConstitutionalConcernLevel::NONE,
            EvidenceWeightCategory::STRONG,
            'no_constitutional_concern',
            [],
            $policyId,
        );
    }

    public function hasConstitutionalConcern(): bool
    {
        return $this->concernLevel !== ConstitutionalConcernLevel::NONE;
    }

    public function isEvidenceWeak(): bool
    {
        return $this->evidenceWeight === EvidenceWeightCategory::WEAK;
    }
}
