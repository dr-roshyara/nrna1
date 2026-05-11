<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Snapshot;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\ConstitutionalReplayFingerprint;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Constitutional\SnapshotMetadata;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalGovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\AuthorityConflictCollection;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\AuthorityPrecedenceRanking;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityEvaluation;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;

final class ConstitutionalReplayFingerprintTest extends TestCase
{
    private function makeSnapshot(?SnapshotMetadata $metadata = null): GovernanceDecisionSnapshot
    {
        $gov = new GovernanceDecision(
            id: GovernanceDecisionId::generate(),
            decidedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z'),
            evaluation: CapabilityEvaluation::allow('test'),
            classification: new AuthorityClassification(
                exceptions: [],
                overrides: [],
                direct: new JurisdictionNode('node-1', 'national', null, true, false),
                delegated: [],
            ),
            detectedConflicts: AuthorityConflictCollection::empty(),
            precedenceRanking: AuthorityPrecedenceRanking::empty(),
            producedEvent: new GovernanceDecisionProduced(
                GovernanceDecisionId::generate()->toString(),
                'tenant-1',
                CapabilityType::COMMITTEE_CREATION->value,
                'direct',
                'node-1',
            ),
        );

        $constitutional = new ConstitutionalDecision(
            winner: $gov->classification->direct,
            legitimacy: GovernanceLegitimacy::LEGITIMATE,
            reason: ConstitutionalReason::resolved('direct', 'Direct authority'),
            evaluatedAt: $gov->decidedAt,
            trace: new ConstitutionalArbitrationTrace(
                evaluatedNodeIds: ['node-1'],
                selectedNodeId: 'node-1',
                precedenceReason: 'direct',
                doctrineRulesApplied: ['direct'],
            ),
        );

        $decision = new ConstitutionalGovernanceDecision($gov, $constitutional);

        return GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            new \DateTimeImmutable('2026-05-08T11:00:00Z'),
            $metadata,
        );
    }

    public function test_fingerprint_from_same_snapshot_is_identical(): void
    {
        $snapshot = $this->makeSnapshot();

        $fp1 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot);
        $fp2 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot);

        $this->assertTrue($fp1->equals($fp2));
    }

    public function test_different_doctrine_versions_produce_different_fingerprints(): void
    {
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $metadataV1 = new SnapshotMetadata('1.0', '1.0', '1.0', '1.0', '3.2', '3.2', $persistedAt);
        $metadataV2 = new SnapshotMetadata('1.0', '2.0', '1.0', '1.0', '3.2', '3.2', $persistedAt);

        $snapshot1 = $this->makeSnapshot($metadataV1);
        $snapshot2 = $this->makeSnapshot($metadataV2);

        $fp1 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot1);
        $fp2 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot2);

        $this->assertFalse($fp1->equals($fp2));
    }

    public function test_different_legitimacy_produces_different_fingerprint(): void
    {
        $snapshot = $this->makeSnapshot();

        // Build a snapshot with EXPIRED legitimacy by manually constructing
        $expiredSnapshot = new GovernanceDecisionSnapshot(
            decisionId:               $snapshot->decisionId,
            decidedAt:                $snapshot->decidedAt,
            capabilityType:           $snapshot->capabilityType,
            winningAuthorityId:       null,
            legitimacy:               GovernanceLegitimacy::EXPIRED->value,
            constitutionalReasonJson: $snapshot->constitutionalReasonJson,
            arbitrationTraceJson:     $snapshot->arbitrationTraceJson,
            metadata:                 $snapshot->metadata,
            constitutionalScope:      $snapshot->constitutionalScope,
            integrityHash:            $snapshot->integrityHash,
        );

        $fp1 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot);
        $fp2 = ConstitutionalReplayFingerprint::fromSnapshot($expiredSnapshot);

        $this->assertFalse($fp1->equals($fp2));
    }

    public function test_structural_only_fields_do_not_affect_fingerprint(): void
    {
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        // Same constitutional content, different replayEngineVersion (structural only)
        $metadataEngine32 = new SnapshotMetadata('1.0', '1.0', '1.0', '1.0', '3.2', '3.2', $persistedAt);
        $metadataEngine33 = new SnapshotMetadata('1.0', '1.0', '1.0', '1.0', '3.3', '3.3', $persistedAt);

        $snapshot1 = $this->makeSnapshot($metadataEngine32);
        $snapshot2 = $this->makeSnapshot($metadataEngine33);

        $fp1 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot1);
        $fp2 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot2);

        $this->assertTrue($fp1->equals($fp2));
    }
}
