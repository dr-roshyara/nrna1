<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Constitutional\SnapshotMetadata;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalGovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\AuthorityConflictCollection;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\AuthorityPrecedenceRanking;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityEvaluation;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;

final class GovernanceDecisionSnapshotTest extends TestCase
{
    private function makeConstitutionalGovernanceDecision(): ConstitutionalGovernanceDecision
    {
        $governanceDecision = new GovernanceDecision(
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

        $constitutionalDecision = new ConstitutionalDecision(
            winner: $governanceDecision->classification->direct,
            legitimacy: GovernanceLegitimacy::LEGITIMATE,
            reason: ConstitutionalReason::resolved('direct', 'Direct authority'),
            evaluatedAt: $governanceDecision->decidedAt,
            trace: new ConstitutionalArbitrationTrace(
                evaluatedNodeIds: ['node-1'],
                selectedNodeId: 'node-1',
                precedenceReason: 'direct',
                doctrineRulesApplied: ['direct'],
            ),
        );

        return new ConstitutionalGovernanceDecision(
            $governanceDecision,
            $constitutionalDecision,
        );
    }

    public function test_from_decision_sets_all_fields(): void
    {
        $decision = $this->makeConstitutionalGovernanceDecision();
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $this->assertSame($decision->id()->toString(), $snapshot->decisionId);
        $this->assertSame($decision->decidedAt(), $snapshot->decidedAt);
        $this->assertSame(CapabilityType::COMMITTEE_CREATION->value, $snapshot->capabilityType);
        $this->assertSame($decision->winner()?->id, $snapshot->winningAuthorityId);
        $this->assertSame(GovernanceLegitimacy::LEGITIMATE->value, $snapshot->legitimacy);
    }

    public function test_integrity_hash_is_deterministic(): void
    {
        $decision = $this->makeConstitutionalGovernanceDecision();
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshot1 = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $snapshot2 = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $this->assertSame($snapshot1->integrityHash, $snapshot2->integrityHash);
    }

    public function test_verify_integrity_passes_for_intact_snapshot(): void
    {
        $decision = $this->makeConstitutionalGovernanceDecision();
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $this->assertTrue($snapshot->verifyIntegrity());
    }

    public function test_different_legitimacy_produces_different_hash(): void
    {
        $governanceDecision = new GovernanceDecision(
            id: GovernanceDecisionId::generate(),
            decidedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z'),
            evaluation: CapabilityEvaluation::allow('test'),
            classification: new AuthorityClassification(
                exceptions: [],
                overrides: [],
                direct: null,
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

        $constitutionalDecisionExpired = new ConstitutionalDecision(
            winner: null,
            legitimacy: GovernanceLegitimacy::EXPIRED,
            reason: ConstitutionalReason::noAuthority(),
            evaluatedAt: $governanceDecision->decidedAt,
            trace: new ConstitutionalArbitrationTrace(
                evaluatedNodeIds: [],
                selectedNodeId: null,
                precedenceReason: 'none',
                doctrineRulesApplied: [],
            ),
        );

        $decisionExpired = new ConstitutionalGovernanceDecision(
            $governanceDecision,
            $constitutionalDecisionExpired,
        );

        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshotExpired = GovernanceDecisionSnapshot::fromDecision(
            $decisionExpired,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $legitimateDecision = $this->makeConstitutionalGovernanceDecision();
        $snapshotLegitimate = GovernanceDecisionSnapshot::fromDecision(
            $legitimateDecision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $this->assertNotSame($snapshotExpired->integrityHash, $snapshotLegitimate->integrityHash);
    }

    public function test_unsorted_arrays_produce_same_hash_as_sorted(): void
    {
        $decision = $this->makeConstitutionalGovernanceDecision();
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshot1 = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $snapshot2 = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $this->assertSame($snapshot1->integrityHash, $snapshot2->integrityHash);
    }
}
