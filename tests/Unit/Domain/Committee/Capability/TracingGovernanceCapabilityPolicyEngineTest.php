<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Capability;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Capability\GovernanceCapabilityPolicyEngine;
use App\Contexts\Membership\Domain\Committee\Capability\TracingGovernanceCapabilityPolicyEngine;
use App\Contexts\Membership\Domain\Committee\Capability\CommitteeCreationPolicy;
use App\Contexts\Membership\Domain\Committee\Capability\StructureActivationPolicy;
use App\Contexts\Membership\Domain\Committee\Capability\CommitteeModificationPolicy;
use App\Contexts\Membership\Domain\Committee\Capability\StructureDeprecationPolicy;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;
use App\Contexts\Membership\Domain\Committee\Actor\ActorPosition;
use App\Contexts\Membership\Domain\Committee\Context\ActorContext;
use App\Contexts\Membership\Domain\Committee\Context\OrganisationGovernanceContext;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeStructureEpochContext;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeLineageView;
use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class TracingGovernanceCapabilityPolicyEngineTest extends TestCase
{
    private GovernanceCapabilityPolicyEngine $innerEngine;
    private TracingGovernanceCapabilityPolicyEngine $tracingEngine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->innerEngine = new GovernanceCapabilityPolicyEngine(
            new CommitteeCreationPolicy(),
            new StructureActivationPolicy(),
            new CommitteeModificationPolicy(),
            new StructureDeprecationPolicy(),
        );
        $this->tracingEngine = new TracingGovernanceCapabilityPolicyEngine($this->innerEngine);
    }

    public function test_committee_creation_all_checks_pass_includes_trace(): void
    {
        $ctx = $this->buildContext('active', ActorPosition::OWNER, 'active');

        $evaluation = $this->tracingEngine->canCreateCommittee($ctx);

        $this->assertTrue($evaluation->allowed);
        $this->assertNotNull($evaluation->trace);
        $this->assertEquals('canCreateCommittee', $evaluation->trace->capability);
        $this->assertCount(3, $evaluation->trace->steps);
        $this->assertTrue($evaluation->trace->steps[0]->passed);
        $this->assertEquals('organisation_is_active', $evaluation->trace->steps[0]->ruleId);
        $this->assertEquals('active', $evaluation->trace->steps[0]->detail);
        $this->assertTrue($evaluation->trace->steps[1]->passed);
        $this->assertEquals('actor_has_governance_authority', $evaluation->trace->steps[1]->ruleId);
        $this->assertEquals('owner', $evaluation->trace->steps[1]->detail);
        $this->assertTrue($evaluation->trace->steps[2]->passed);
        $this->assertEquals('epoch_is_active', $evaluation->trace->steps[2]->ruleId);
        $this->assertEquals('active', $evaluation->trace->steps[2]->detail);
    }

    public function test_committee_creation_denied_at_org_inactive(): void
    {
        $ctx = $this->buildContext('pending_setup', ActorPosition::OWNER, 'active');

        $evaluation = $this->tracingEngine->canCreateCommittee($ctx);

        $this->assertFalse($evaluation->allowed);
        $this->assertNotNull($evaluation->trace);
        $this->assertCount(1, $evaluation->trace->steps);
        $this->assertFalse($evaluation->trace->steps[0]->passed);
        $this->assertEquals('organisation_is_active', $evaluation->trace->steps[0]->ruleId);
        $this->assertEquals('pending_setup', $evaluation->trace->steps[0]->detail);
    }

    public function test_committee_creation_denied_at_no_authority(): void
    {
        $ctx = $this->buildContext('active', ActorPosition::VOTER, 'active');

        $evaluation = $this->tracingEngine->canCreateCommittee($ctx);

        $this->assertFalse($evaluation->allowed);
        $this->assertCount(2, $evaluation->trace->steps);
        $this->assertTrue($evaluation->trace->steps[0]->passed);
        $this->assertEquals('organisation_is_active', $evaluation->trace->steps[0]->ruleId);
        $this->assertEquals('active', $evaluation->trace->steps[0]->detail);
        $this->assertFalse($evaluation->trace->steps[1]->passed);
        $this->assertEquals('actor_has_governance_authority', $evaluation->trace->steps[1]->ruleId);
        $this->assertEquals('voter', $evaluation->trace->steps[1]->detail);
    }

    public function test_committee_creation_denied_at_geo_violation(): void
    {
        $ctx = new CapabilityContext(
            actor: new ActorContext(
                userId: 'user-1',
                tenantId: TenantId::fromString('11111111-1111-1111-1111-111111111111'),
                position: ActorPosition::ADMIN,
                geographicScope: new GeographicScope('state', 'BY'),
                isSystemActor: false,
            ),
            organisation: new OrganisationGovernanceContext(
                organisationId: '11111111-1111-1111-1111-111111111111',
                governanceStatus: 'active'
            ),
            epoch: new CommitteeStructureEpochContext(
                version: 1,
                status: 'active',
                depth: 0,
                levelCode: 'CENTRAL',
                isLatestActive: true,
            ),
            lineage: CommitteeLineageView::root(),
            targetScope: new GeographicScope('national', null),
        );

        $evaluation = $this->tracingEngine->canCreateCommittee($ctx);

        $this->assertFalse($evaluation->allowed);
        $this->assertCount(3, $evaluation->trace->steps);
        $this->assertTrue($evaluation->trace->steps[0]->passed);
        $this->assertEquals('organisation_is_active', $evaluation->trace->steps[0]->ruleId);
        $this->assertTrue($evaluation->trace->steps[1]->passed);
        $this->assertEquals('actor_has_governance_authority', $evaluation->trace->steps[1]->ruleId);
        $this->assertFalse($evaluation->trace->steps[2]->passed);
        $this->assertEquals('geographic_scope_valid', $evaluation->trace->steps[2]->ruleId);
        $this->assertEquals('actor:state target:national', $evaluation->trace->steps[2]->detail);
    }

    public function test_committee_creation_denied_at_epoch_inactive(): void
    {
        $ctx = $this->buildContext('active', ActorPosition::OWNER, 'draft');

        $evaluation = $this->tracingEngine->canCreateCommittee($ctx);

        $this->assertFalse($evaluation->allowed);
        $this->assertCount(3, $evaluation->trace->steps);
        $this->assertTrue($evaluation->trace->steps[0]->passed);
        $this->assertEquals('organisation_is_active', $evaluation->trace->steps[0]->ruleId);
        $this->assertTrue($evaluation->trace->steps[1]->passed);
        $this->assertEquals('actor_has_governance_authority', $evaluation->trace->steps[1]->ruleId);
        $this->assertFalse($evaluation->trace->steps[2]->passed);
        $this->assertEquals('epoch_is_active', $evaluation->trace->steps[2]->ruleId);
        $this->assertEquals('draft', $evaluation->trace->steps[2]->detail);
    }

    public function test_trace_carries_capability_name(): void
    {
        $ctx = $this->buildContext('active', ActorPosition::OWNER, 'active');
        $evaluation = $this->tracingEngine->canCreateCommittee($ctx);

        $this->assertNotNull($evaluation->trace);
        $this->assertEquals('canCreateCommittee', $evaluation->trace->capability);
    }

    public function test_trace_evaluated_at_is_datetime_immutable(): void
    {
        $ctx = $this->buildContext('active', ActorPosition::OWNER, 'active');
        $evaluation = $this->tracingEngine->canCreateCommittee($ctx);

        $this->assertInstanceOf(\DateTimeImmutable::class, $evaluation->trace->evaluatedAt);
    }

    public function test_structure_activation_non_owner_traced(): void
    {
        $ctx = $this->buildContext('active', ActorPosition::ADMIN, 'draft');

        $evaluation = $this->tracingEngine->canActivateStructure($ctx);

        $this->assertFalse($evaluation->allowed);
        $this->assertNotNull($evaluation->trace);
        $this->assertCount(1, $evaluation->trace->steps);
        $this->assertFalse($evaluation->trace->steps[0]->passed);
        $this->assertEquals('actor_is_owner', $evaluation->trace->steps[0]->ruleId);
        $this->assertEquals('admin', $evaluation->trace->steps[0]->detail);
    }

    public function test_tracing_can_be_disabled(): void
    {
        $noTraceEngine = new TracingGovernanceCapabilityPolicyEngine($this->innerEngine, tracing: false);
        $ctx = $this->buildContext('active', ActorPosition::OWNER, 'active');

        $evaluation = $noTraceEngine->canCreateCommittee($ctx);

        $this->assertTrue($evaluation->allowed);
        $this->assertNull($evaluation->trace);
    }

    private function buildContext(
        string $organisationStatus,
        ActorPosition $actorPosition,
        string $epochStatus
    ): CapabilityContext {
        return new CapabilityContext(
            actor: new ActorContext(
                userId: 'test-user',
                tenantId: TenantId::fromString('11111111-1111-1111-1111-111111111111'),
                position: $actorPosition,
                geographicScope: new GeographicScope('national', null),
                isSystemActor: false,
            ),
            organisation: new OrganisationGovernanceContext(
                organisationId: '11111111-1111-1111-1111-111111111111',
                governanceStatus: $organisationStatus
            ),
            epoch: new CommitteeStructureEpochContext(
                version: 1,
                status: $epochStatus,
                depth: 0,
                levelCode: 'CENTRAL',
                isLatestActive: true,
            ),
            lineage: CommitteeLineageView::root(),
            targetScope: null,
        );
    }
}
