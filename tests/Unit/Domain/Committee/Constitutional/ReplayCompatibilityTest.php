<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\ConstitutionalReplayFingerprint;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\CanonicalConstitutionalSerializer;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotIntegrityHash;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceArchaeologyRecord;
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

final class ReplayCompatibilityTest extends TestCase
{
    private function makeConstitutionalGovernanceDecision(
        array $evaluatedNodeIds = ['node-1'],
        array $doctrineRules = ['direct'],
    ): ConstitutionalGovernanceDecision {
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
                evaluatedNodeIds: $evaluatedNodeIds,
                selectedNodeId: 'node-1',
                precedenceReason: 'direct',
                doctrineRulesApplied: $doctrineRules,
            ),
        );

        return new ConstitutionalGovernanceDecision($gov, $constitutional);
    }

    public function test_snapshot_v1_replays_to_identical_archaeology_record(): void
    {
        $decision = $this->makeConstitutionalGovernanceDecision();
        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            new \DateTimeImmutable('2026-05-08T11:00:00Z'),
        );

        $record = new GovernanceArchaeologyRecord($snapshot, new \DateTimeImmutable('2028-01-01T00:00:00Z'));

        $this->assertTrue($record->wasConstitutionallyValid());
        $this->assertSame($snapshot->decisionId, $record->decisionId());
    }

    public function test_canonicalization_stability_unsorted_arrays_same_fingerprint(): void
    {
        $decision1 = $this->makeConstitutionalGovernanceDecision(['b', 'a', 'c'], ['rule2', 'rule1']);
        $decision2 = $this->makeConstitutionalGovernanceDecision(['a', 'b', 'c'], ['rule1', 'rule2']);

        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');
        $snapshot1 = GovernanceDecisionSnapshot::fromDecision($decision1, CapabilityType::COMMITTEE_CREATION, $persistedAt);
        $snapshot2 = GovernanceDecisionSnapshot::fromDecision($decision2, CapabilityType::COMMITTEE_CREATION, $persistedAt);

        $fp1 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot1);
        $fp2 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot2);

        $this->assertTrue($fp1->equals($fp2));
    }

    public function test_snapshot_mutation_resistance_single_field(): void
    {
        $decision = $this->makeConstitutionalGovernanceDecision();
        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            new \DateTimeImmutable('2026-05-08T11:00:00Z'),
        );

        $hash = SnapshotIntegrityHash::fromSnapshot($snapshot);

        $tampered = new GovernanceDecisionSnapshot(
            decisionId:               $snapshot->decisionId,
            decidedAt:                $snapshot->decidedAt,
            capabilityType:           $snapshot->capabilityType,
            winningAuthorityId:       $snapshot->winningAuthorityId,
            legitimacy:               'expired',
            constitutionalReasonJson: $snapshot->constitutionalReasonJson,
            arbitrationTraceJson:     $snapshot->arbitrationTraceJson,
            metadata:                 $snapshot->metadata,
            constitutionalScope:      $snapshot->constitutionalScope,
            integrityHash:            $snapshot->integrityHash,
        );

        $this->assertFalse($hash->verify($tampered));
    }

    public function test_serializer_cross_check_matches_inline_hashing(): void
    {
        $serializer = new CanonicalConstitutionalSerializer();
        $decision = $this->makeConstitutionalGovernanceDecision();
        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            new \DateTimeImmutable('2026-05-08T11:00:00Z'),
        );

        $this->assertSame(
            $snapshot->constitutionalReasonJson,
            $serializer->serializeReason($decision->constitutionalDecision->reason),
        );

        $this->assertSame(
            $snapshot->arbitrationTraceJson,
            $serializer->serializeTrace($decision->constitutionalDecision->trace),
        );
    }

    public function test_constitutional_drift_stored_legitimacy_used_not_live_evaluation(): void
    {
        $decision = $this->makeConstitutionalGovernanceDecision();
        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            new \DateTimeImmutable('2026-05-08T11:00:00Z'),
        );

        // Replay in 2028 — far future, temporal context irrelevant to stored result
        $record = new GovernanceArchaeologyRecord($snapshot, new \DateTimeImmutable('2028-01-01T00:00:00Z'));

        // Stored legitimacy (LEGITIMATE) drives the result — not live re-evaluation
        $this->assertTrue($record->wasConstitutionallyValid());

        // The fingerprint is stable and can be re-derived from the snapshot
        $fingerprint = ConstitutionalReplayFingerprint::fromSnapshot($snapshot);
        $this->assertNotEmpty($fingerprint->toString());
    }

    public function test_semantic_fingerprint_stability_field_order_invariance(): void
    {
        // Same decision built twice with arrays in different order
        $decision1 = $this->makeConstitutionalGovernanceDecision(['c', 'a', 'b'], ['rule3', 'rule1', 'rule2']);
        $decision2 = $this->makeConstitutionalGovernanceDecision(['a', 'b', 'c'], ['rule1', 'rule2', 'rule3']);

        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');
        $snapshot1 = GovernanceDecisionSnapshot::fromDecision($decision1, CapabilityType::COMMITTEE_CREATION, $persistedAt);
        $snapshot2 = GovernanceDecisionSnapshot::fromDecision($decision2, CapabilityType::COMMITTEE_CREATION, $persistedAt);

        $fp1 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot1);
        $fp2 = ConstitutionalReplayFingerprint::fromSnapshot($snapshot2);

        $this->assertTrue($fp1->equals($fp2));
    }
}
