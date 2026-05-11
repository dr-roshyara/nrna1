<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionKernel;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassifier;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\ConflictDetectionEngine;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\DefaultAuthorityPrecedencePolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\DefaultConflictResolutionPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\StableAuthoritySelectionPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\UuidGovernanceDecisionIdGenerator;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;
use App\Contexts\Membership\Domain\Committee\Capability\InstitutionalCapabilityPolicy;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityEvaluation;
use App\Contexts\Membership\Domain\Committee\Capability\EvaluationResult;
use App\Contexts\Membership\Domain\Committee\Capability\DecisionStep;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraph;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationEdge;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationType;
use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;
use App\Contexts\Membership\Domain\Committee\Context\ActorContext;
use App\Contexts\Membership\Domain\Committee\Context\OrganisationGovernanceContext;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeStructureEpochContext;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeLineageView;
use App\Contexts\Membership\Domain\Committee\Actor\ActorPosition;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class GovernanceDecisionKernelTest extends TestCase
{
    private GovernanceDecisionKernel $kernel;
    private AuthorityClassifier $classifier;
    private ConflictDetectionEngine $conflictEngine;
    private DefaultAuthorityPrecedencePolicy $precedencePolicy;

    protected function setUp(): void
    {
        parent::setUp();
        $mockEngine = $this->createMockEngine();

        $this->classifier = new AuthorityClassifier();
        $this->conflictEngine = new ConflictDetectionEngine();
        $this->precedencePolicy = new DefaultAuthorityPrecedencePolicy();

        $this->kernel = new GovernanceDecisionKernel(
            $mockEngine,
            $this->classifier,
            $this->conflictEngine,
            $this->precedencePolicy,
            new DefaultConflictResolutionPolicy(new StableAuthoritySelectionPolicy()),
            new UuidGovernanceDecisionIdGenerator(),
            null
        );
    }

    private function createMockEngine(): InstitutionalCapabilityPolicy
    {
        $mockEngine = $this->createMock(InstitutionalCapabilityPolicy::class);
        $mockEngine->method('evaluateCreateCommittee')
            ->willReturn(new EvaluationResult(CapabilityEvaluation::allow('Test evaluation'), []));
        $mockEngine->method('evaluateActivateStructure')
            ->willReturn(new EvaluationResult(CapabilityEvaluation::allow('Test evaluation'), []));
        $mockEngine->method('evaluateModifyCommittee')
            ->willReturn(new EvaluationResult(CapabilityEvaluation::allow('Test evaluation'), []));
        $mockEngine->method('evaluateDeprecateStructure')
            ->willReturn(new EvaluationResult(CapabilityEvaluation::allow('Test evaluation'), []));
        return $mockEngine;
    }

    private function createCapabilityContext(string $level = 'national', ?string $code = null): CapabilityContext
    {
        $actor = new ActorContext(
            userId: 'user-1',
            tenantId: TenantId::fromString('tenant-1'),
            position: ActorPosition::OWNER,
            geographicScope: new GeographicScope($level, $code),
            isSystemActor: false,
        );

        $organisation = new OrganisationGovernanceContext(
            organisationId: 'org-1',
            governanceStatus: 'active'
        );

        $epoch = new CommitteeStructureEpochContext(
            version: 1,
            status: 'active',
            depth: 0,
            levelCode: 'CENTRAL',
            isLatestActive: true,
        );

        $lineage = CommitteeLineageView::root('committee-1');

        return new CapabilityContext($actor, $organisation, $epoch, $lineage);
    }

    public function test_kernel_returns_governance_decision_type(): void
    {
        $ctx = $this->createCapabilityContext();
        $evaluation = CapabilityEvaluation::allow('Test evaluation');

        $decision = $this->kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, new \DateTimeImmutable());

        $this->assertInstanceOf(GovernanceDecision::class, $decision);
        $this->assertInstanceOf(GovernanceDecisionId::class, $decision->id);
        $this->assertInstanceOf(\DateTimeImmutable::class, $decision->decidedAt);
    }

    public function test_evaluation_reflects_capability_engine_result(): void
    {
        $ctx = $this->createCapabilityContext();
        $evaluation = CapabilityEvaluation::allow('Test evaluation');

        $decision = $this->kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, new \DateTimeImmutable());

        $this->assertTrue($decision->evaluation->allowed);
    }

    public function test_classification_is_empty_without_authority_graph(): void
    {
        $ctx = $this->createCapabilityContext();

        $kernel = new GovernanceDecisionKernel(
            $this->createMockEngine(),
            $this->classifier,
            $this->conflictEngine,
            $this->precedencePolicy,
            new DefaultConflictResolutionPolicy(new StableAuthoritySelectionPolicy()),
            new UuidGovernanceDecisionIdGenerator(),
            null
        );

        $decision = $kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, new \DateTimeImmutable());

        $this->assertTrue($decision->classification->isEmpty());
    }

    public function test_no_conflicts_detected_without_graph(): void
    {
        $ctx = $this->createCapabilityContext();

        $kernel = new GovernanceDecisionKernel(
            $this->createMockEngine(),
            $this->classifier,
            $this->conflictEngine,
            $this->precedencePolicy,
            new DefaultConflictResolutionPolicy(new StableAuthoritySelectionPolicy()),
            new UuidGovernanceDecisionIdGenerator(),
            null
        );

        $decision = $kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, new \DateTimeImmutable());

        $this->assertTrue($decision->detectedConflicts->isEmpty());
    }

    public function test_override_edge_classified_into_override_authority_bucket(): void
    {
        $now = new \DateTimeImmutable();
        $ctx = $this->createCapabilityContext();
        $evaluation = CapabilityEvaluation::allow('Test evaluation');

        $mockEngine = $this->createMock(InstitutionalCapabilityPolicy::class);
        $mockEngine->method('evaluateCreateCommittee')
            ->willReturn(new EvaluationResult($evaluation, []));

        $rootNode = new JurisdictionNode('node-1', 'national', null, true, false);
        $overrideNode = new JurisdictionNode('node-3', 'state', 'BY', true, false);
        $edge = new DelegationEdge('node-1', 'node-3', DelegationType::OVERRIDE, $now, null);

        $graph = new GeoAuthorityGraph(
            nodes: [$rootNode, $overrideNode],
            edges: [$edge]
        );

        $kernel = new GovernanceDecisionKernel(
            $mockEngine,
            $this->classifier,
            $this->conflictEngine,
            $this->precedencePolicy,
            new DefaultConflictResolutionPolicy(new StableAuthoritySelectionPolicy()),
            new UuidGovernanceDecisionIdGenerator(),
            $graph
        );

        $decision = $kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, $now);

        $this->assertCount(1, $decision->classification->overrides);
        $this->assertEquals('node-3', $decision->classification->overrides[0]->id);
    }

    public function test_exception_zone_node_classified_into_exceptions_bucket(): void
    {
        $now = new \DateTimeImmutable();
        $ctx = $this->createCapabilityContext();
        $evaluation = CapabilityEvaluation::allow('Test evaluation');

        $mockEngine = $this->createMock(InstitutionalCapabilityPolicy::class);
        $mockEngine->method('evaluateCreateCommittee')
            ->willReturn(new EvaluationResult($evaluation, []));

        $rootNode = new JurisdictionNode('node-1', 'national', null, true, false);
        $exceptionNode = new JurisdictionNode('node-4', 'district', 'BY-01', true, true);
        $edge = new DelegationEdge('node-1', 'node-4', DelegationType::AUTHORITY, $now, null);

        $graph = new GeoAuthorityGraph(
            nodes: [$rootNode, $exceptionNode],
            edges: [$edge]
        );

        $kernel = new GovernanceDecisionKernel(
            $mockEngine,
            $this->classifier,
            $this->conflictEngine,
            $this->precedencePolicy,
            new DefaultConflictResolutionPolicy(new StableAuthoritySelectionPolicy()),
            new UuidGovernanceDecisionIdGenerator(),
            $graph
        );

        $decision = $kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, $now);

        $this->assertCount(1, $decision->classification->exceptions);
        $this->assertEquals('node-4', $decision->classification->exceptions[0]->id);
    }

    public function test_kernel_records_governance_decision_produced_event(): void
    {
        $ctx = $this->createCapabilityContext();

        $decision = $this->kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, new \DateTimeImmutable());

        $this->assertInstanceOf(GovernanceDecisionProduced::class, $decision->producedEvent);
        $this->assertNotEmpty($decision->producedEvent->decisionId);
    }

    public function test_kernel_decision_always_has_final_authority_decision(): void
    {
        $ctx = $this->createCapabilityContext();

        $decision = $this->kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, new \DateTimeImmutable());

        $this->assertNotNull($decision->finalDecision);
    }

    public function test_empty_graph_produces_none_final_decision(): void
    {
        $ctx = $this->createCapabilityContext();

        $kernel = new GovernanceDecisionKernel(
            $this->createMockEngine(),
            $this->classifier,
            $this->conflictEngine,
            $this->precedencePolicy,
            new DefaultConflictResolutionPolicy(new StableAuthoritySelectionPolicy()),
            new UuidGovernanceDecisionIdGenerator(),
            null
        );

        $decision = $kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, new \DateTimeImmutable());

        $this->assertNull($decision->finalDecision->winningNode);
        $this->assertEquals('none', $decision->finalDecision->type->value);
    }

    public function test_exception_node_in_graph_resolves_as_exception_source(): void
    {
        $now = new \DateTimeImmutable();
        $ctx = $this->createCapabilityContext();
        $evaluation = CapabilityEvaluation::allow('Test evaluation');

        $mockEngine = $this->createMock(InstitutionalCapabilityPolicy::class);
        $mockEngine->method('evaluateCreateCommittee')
            ->willReturn(new EvaluationResult($evaluation, []));

        $rootNode = new JurisdictionNode('node-1', 'national', null, true, false);
        $exceptionNode = new JurisdictionNode('node-4', 'district', 'BY-01', true, true);
        $edge = new DelegationEdge('node-1', 'node-4', DelegationType::AUTHORITY, $now, null);

        $graph = new GeoAuthorityGraph(
            nodes: [$rootNode, $exceptionNode],
            edges: [$edge]
        );

        $kernel = new GovernanceDecisionKernel(
            $mockEngine,
            $this->classifier,
            $this->conflictEngine,
            $this->precedencePolicy,
            new DefaultConflictResolutionPolicy(new StableAuthoritySelectionPolicy()),
            new UuidGovernanceDecisionIdGenerator(),
            $graph
        );

        $decision = $kernel->decide($ctx, CapabilityType::COMMITTEE_CREATION, $now);

        $this->assertEquals('node-4', $decision->finalDecision->winningNode->id);
        $this->assertEquals('exception', $decision->finalDecision->type->value);
    }
}
