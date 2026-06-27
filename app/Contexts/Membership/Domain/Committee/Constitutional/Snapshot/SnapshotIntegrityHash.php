<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot;

use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;

final readonly class SnapshotIntegrityHash
{
    private function __construct(private string $hash) {}

    public static function fromSnapshot(GovernanceDecisionSnapshot $snapshot): self
    {
        $hash = hash('sha256', implode('|', [
            $snapshot->decisionId,
            $snapshot->decidedAt->format(\DateTimeInterface::ATOM),
            $snapshot->capabilityType,
            $snapshot->winningAuthorityId ?? 'null',
            $snapshot->legitimacy,
            $snapshot->constitutionalReasonJson,
            $snapshot->arbitrationTraceJson,
            $snapshot->metadata->schemaVersion,
            $snapshot->metadata->doctrineVersion,
            $snapshot->metadata->legitimacyPolicyVersion,
            $snapshot->metadata->arbitrationPolicyVersion,
        ]));

        return new self($hash);
    }

    public function verify(GovernanceDecisionSnapshot $snapshot): bool
    {
        $recomputed = self::fromSnapshot($snapshot);
        return hash_equals($this->hash, $recomputed->hash);
    }

    public function equals(self $other): bool
    {
        return hash_equals($this->hash, $other->hash);
    }

    public function toString(): string
    {
        return $this->hash;
    }
}
