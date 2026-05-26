<?php

namespace App\Domain\Election\Security;

readonly class VotingTrustResult
{
    public function __construct(
        public bool       $trusted,
        public string     $reason,
        public TrustLevel $trustLevel,
        public array      $auditContext,
        public array      $policyOutcomeSequence,
    ) {}

    public static function allow(TrustLevel $trustLevel, array $context, array $sequence): self
    {
        return new self(
            trusted: true,
            reason: '',
            trustLevel: $trustLevel,
            auditContext: $context,
            policyOutcomeSequence: $sequence,
        );
    }

    public static function deny(string $reason, array $context, array $sequence): self
    {
        return new self(
            trusted: false,
            reason: $reason,
            trustLevel: TrustLevel::Unverified,
            auditContext: $context,
            policyOutcomeSequence: $sequence,
        );
    }
}
