<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Capability;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Capability\GovernanceCapabilityPolicyEngine;
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

final class GovernanceCapabilityPolicyEngineTest extends TestCase
{
    private GovernanceCapabilityPolicyEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new GovernanceCapabilityPolicyEngine(
            new CommitteeCreationPolicy(),
            new StructureActivationPolicy(),
            new CommitteeModificationPolicy(),
            new StructureDeprecationPolicy(),
        );
    }

    public function test_committee_creation_denies_when_organisation_not_active(): void
    {
        $ctx = $this->buildContext(
            organisationStatus: 'pending_setup',
            actorPosition: ActorPosition::OWNER,
            epochStatus: 'active'
        );

        $evaluation = $this->engine->canCreateCommittee($ctx);

        $this->assertFalse($evaluation->allowed);
        $this->assertEquals('org_inactive', $evaluation->reason);
        $this->assertContains('organisation_not_active', $evaluation->failedChecks);
    }

    public function test_committee_creation_denies_when_actor_has_no_authority(): void
    {
        $ctx = $this->buildContext(
            organisationStatus: 'active',
            actorPosition: ActorPosition::VOTER,
            epochStatus: 'active'
        );

        $evaluation = $this->engine->canCreateCommittee($ctx);

        $this->assertFalse($evaluation->allowed);
        $this->assertEquals('no_authority', $evaluation->reason);
        $this->assertContains('actor_lacks_governance_authority', $evaluation->failedChecks);
    }

    public function test_committee_creation_denies_when_epoch_not_active(): void
    {
        $ctx = $this->buildContext(
            organisationStatus: 'active',
            actorPosition: ActorPosition::ADMIN,
            epochStatus: 'draft'
        );

        $evaluation = $this->engine->canCreateCommittee($ctx);

        $this->assertFalse($evaluation->allowed);
        $this->assertEquals('epoch_inactive', $evaluation->reason);
        $this->assertContains('epoch_not_active', $evaluation->failedChecks);
    }

    public function test_committee_creation_denies_geo_scope_violation(): void
    {
        // Actor with state scope, target is national - state cannot operate at national level
        $actorScope = new GeographicScope('state', 'BY');
        $targetScope = new GeographicScope('national', null);

        $ctx = new CapabilityContext(
            actor: new ActorContext(
                userId: 'user-1',
                tenantId: TenantId::fromString('11111111-1111-1111-1111-111111111111'),
                position: ActorPosition::ADMIN,
                geographicScope: $actorScope,
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
            targetScope: $targetScope,
        );

        $evaluation = $this->engine->canCreateCommittee($ctx);

        $this->assertFalse($evaluation->allowed);
        $this->assertEquals('geo_violation', $evaluation->reason);
        $this->assertContains('geographic_scope_violation', $evaluation->failedChecks);
    }

    public function test_committee_creation_allows_when_all_checks_pass(): void
    {
        $ctx = $this->buildContext(
            organisationStatus: 'active',
            actorPosition: ActorPosition::OWNER,
            epochStatus: 'active'
        );

        $evaluation = $this->engine->canCreateCommittee($ctx);

        $this->assertTrue($evaluation->allowed);
        $this->assertEmpty($evaluation->failedChecks);
    }

    public function test_structure_activation_denies_when_actor_not_owner(): void
    {
        $ctx = $this->buildContext(
            organisationStatus: 'active',
            actorPosition: ActorPosition::ADMIN,
            epochStatus: 'draft'
        );

        $evaluation = $this->engine->canActivateStructure($ctx);

        $this->assertFalse($evaluation->allowed);
    }

    public function test_structure_activation_allows_owner(): void
    {
        $ctx = $this->buildContext(
            organisationStatus: 'active',
            actorPosition: ActorPosition::OWNER,
            epochStatus: 'draft'
        );

        $evaluation = $this->engine->canActivateStructure($ctx);

        $this->assertTrue($evaluation->allowed);
    }

    public function test_committee_modification_allows_chair_managing_own_committee(): void
    {
        $committeeId = 'committee-123';
        // Chair is a user assigned to a committee who can manage it (not a position role)
        $ctx = new CapabilityContext(
            actor: new ActorContext(
                userId: 'user-1',
                tenantId: TenantId::fromString('11111111-1111-1111-1111-111111111111'),
                position: ActorPosition::MEMBER,  // Regular member but assigned to this committee
                geographicScope: new GeographicScope('national', null),
                committeeId: $committeeId,  // This makes them a chair of this committee
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
            lineage: new CommitteeLineageView($committeeId, null, 0),
        );

        $evaluation = $this->engine->canModifyCommittee($ctx);

        $this->assertTrue($evaluation->allowed);
    }

    public function test_structure_deprecation_denies_non_owner(): void
    {
        $ctx = $this->buildContext(
            organisationStatus: 'active',
            actorPosition: ActorPosition::ADMIN,
            epochStatus: 'active'
        );

        $evaluation = $this->engine->canDeprecateStructure($ctx);

        $this->assertFalse($evaluation->allowed);
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
