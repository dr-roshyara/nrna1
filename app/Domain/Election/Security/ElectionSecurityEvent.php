<?php

namespace App\Domain\Election\Security;

readonly class ElectionSecurityEvent
{
    public function __construct(
        public string              $eventType,
        public int                 $electionId,
        public ?string             $voterSlugId,
        public array               $networkEvidence,
        public array               $deviceEvidence,
        public TrustLevel          $trustLevelBefore,
        public TrustLevel          $trustLevelAfter,
        public string              $policyEvaluated,
        public ?string             $overlayApplied,
        public array               $policyEvaluationSequence,
        public array               $overlayInfluenceChain,
        public string              $trustStateTransition,
        public string              $finalConstitutionalOutcome,
        public \DateTimeImmutable  $recordedAt,
    ) {}
}
