<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;

final readonly class ConstitutionalReplayFingerprint
{
    private function __construct(private string $fingerprint) {}

    public static function fromSnapshot(GovernanceDecisionSnapshot $snapshot): self
    {
        // Semantic constitutional identity fields only — excludes structural/infrastructure fields
        // (replayEngineVersion, replayCompatibilityVersion, decidedAt, persistedAt excluded)
        $fingerprint = hash('sha256', implode('|', [
            $snapshot->metadata->doctrineVersion,
            $snapshot->metadata->arbitrationPolicyVersion,
            $snapshot->metadata->legitimacyPolicyVersion,
            $snapshot->legitimacy,
            $snapshot->constitutionalReasonJson,
            $snapshot->arbitrationTraceJson,
            $snapshot->winningAuthorityId ?? 'null',
            $snapshot->constitutionalScope ?? 'null',
        ]));

        return new self($fingerprint);
    }

    public function equals(self $other): bool
    {
        return hash_equals($this->fingerprint, $other->fingerprint);
    }

    public function toString(): string
    {
        return $this->fingerprint;
    }
}
