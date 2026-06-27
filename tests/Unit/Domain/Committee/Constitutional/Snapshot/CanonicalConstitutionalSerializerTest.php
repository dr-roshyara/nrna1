<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Snapshot;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\CanonicalConstitutionalSerializer;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalGovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\AuthorityConflictCollection;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\AuthorityPrecedenceRanking;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityEvaluation;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;

final class CanonicalConstitutionalSerializerTest extends TestCase
{
    private function makeDecision(): ConstitutionalGovernanceDecision
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

        return new ConstitutionalGovernanceDecision($gov, $constitutional);
    }

    public function test_serialize_reason_produces_deterministic_output(): void
    {
        $serializer = new CanonicalConstitutionalSerializer();
        $reason = ConstitutionalReason::resolved('direct', 'Direct authority');

        $json1 = $serializer->serializeReason($reason);
        $json2 = $serializer->serializeReason($reason);

        $this->assertSame($json1, $json2);
        $this->assertJson($json1);
    }

    public function test_serialize_trace_produces_deterministic_output(): void
    {
        $serializer = new CanonicalConstitutionalSerializer();
        $trace = new ConstitutionalArbitrationTrace(
            evaluatedNodeIds: ['node-1'],
            selectedNodeId: 'node-1',
            precedenceReason: 'direct',
            doctrineRulesApplied: ['direct'],
        );

        $json1 = $serializer->serializeTrace($trace);
        $json2 = $serializer->serializeTrace($trace);

        $this->assertSame($json1, $json2);
        $this->assertJson($json1);
    }

    public function test_unordered_arrays_serialize_to_same_string(): void
    {
        $serializer = new CanonicalConstitutionalSerializer();

        $traceOrdered = new ConstitutionalArbitrationTrace(
            evaluatedNodeIds: ['a', 'b', 'c'],
            selectedNodeId: 'a',
            precedenceReason: 'direct',
            doctrineRulesApplied: ['rule1', 'rule2'],
        );

        $traceUnordered = new ConstitutionalArbitrationTrace(
            evaluatedNodeIds: ['c', 'a', 'b'],
            selectedNodeId: 'a',
            precedenceReason: 'direct',
            doctrineRulesApplied: ['rule2', 'rule1'],
        );

        $this->assertSame(
            $serializer->serializeTrace($traceOrdered),
            $serializer->serializeTrace($traceUnordered),
        );
    }

    public function test_serializer_reason_output_matches_from_decision_inline_logic(): void
    {
        $serializer = new CanonicalConstitutionalSerializer();
        $decision = $this->makeDecision();
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $serializedReason = $serializer->serializeReason($decision->constitutionalDecision->reason);

        $this->assertSame($snapshot->constitutionalReasonJson, $serializedReason);
    }

    public function test_serializer_trace_output_matches_from_decision_inline_logic(): void
    {
        $serializer = new CanonicalConstitutionalSerializer();
        $decision = $this->makeDecision();
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $serializedTrace = $serializer->serializeTrace($decision->constitutionalDecision->trace);

        $this->assertSame($snapshot->arbitrationTraceJson, $serializedTrace);
    }
}
