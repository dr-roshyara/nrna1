<?php

declare(strict_types=1);

namespace Tests\Unit\Committee\Application;

use App\Contexts\Committee\Application\ReadModel\CommitteeMembershipReadModelAdapter;
use App\Contexts\Committee\Application\ReadModel\CommitteeMemberView;
use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Mockery\MockInterface;
use Tests\TestCase;

final class GetCommitteeDashboardTest extends TestCase
{
    private MockInterface $committeeRepository;
    private MockInterface $membershipLineageRepository;
    private MockInterface $membershipReadModel;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock all dependencies
        $this->committeeRepository = \Mockery::mock(CommitteeRepositoryInterface::class);
        $this->membershipLineageRepository = \Mockery::mock('App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort');
        $this->membershipReadModel = \Mockery::mock(CommitteeMembershipReadModelAdapter::class);
    }

    public function test_execute_returns_dto_with_members(): void
    {
        // Arrange: Create a mock committee
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('org-' . \Illuminate\Support\Str::uuid());
        $committeeModel = CommitteeModel::factory()->create();
        $nameVo = \Mockery::mock();
        $nameVo->shouldReceive('value')->andReturn($committeeModel->name);

        $committee = \Mockery::mock();
        $committee->shouldReceive('getId')->andReturn($committeeId);
        $committee->shouldReceive('getName')->andReturn($nameVo);
        $committee->shouldReceive('code')->andReturn($committeeModel->code);
        $committee->shouldReceive('getType')->andReturn($committeeModel->type);
        $committee->shouldReceive('getGeoUnitId')->andReturn((string) $committeeModel->operational_geo);
        $committee->shouldReceive('getStatus')->andReturn('active');
        $committee->shouldReceive('levelIndex')->andReturn($committeeModel->level);

        // Mock repository to return committee
        $this->committeeRepository
            ->shouldReceive('findById')
            ->with($committeeId)
            ->andReturn($committee);

        // Mock adapter to return member list
        $memberViews = [
            new CommitteeMemberView(
                memberId: 'member-1',
                displayName: 'Alice Smith',
                statusKey: 'committee.members.status.active',
                roleKey: 'committee.members.role.member',
            ),
            new CommitteeMemberView(
                memberId: 'member-2',
                displayName: 'Bob Jones',
                statusKey: 'committee.members.status.active',
                roleKey: 'committee.members.role.member',
            ),
        ];

        $this->membershipReadModel
            ->shouldReceive('getActiveMembers')
            ->with($committeeId)
            ->andReturn($memberViews);

        // Act: Execute the service
        $service = new GetCommitteeDashboard(
            $this->committeeRepository,
            $this->membershipLineageRepository,
            $this->membershipReadModel
        );

        $dashboard = $service->execute($committeeId, $tenantId);

        // Assert: DTO is returned correctly
        $this->assertNotNull($dashboard);
        $this->assertSame($committee->id, $dashboard->id);
        $this->assertCount(2, $dashboard->members);
    }

    public function test_execute_calls_adapter_not_eloquent_directly(): void
    {
        // Arrange
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('org-' . \Illuminate\Support\Str::uuid());
        $committeeModel = CommitteeModel::factory()->create();
        $nameVo = \Mockery::mock();
        $nameVo->shouldReceive('value')->andReturn($committeeModel->name);

        $committee = \Mockery::mock();
        $committee->shouldReceive('getId')->andReturn($committeeId);
        $committee->shouldReceive('getName')->andReturn($nameVo);
        $committee->shouldReceive('code')->andReturn($committeeModel->code);
        $committee->shouldReceive('getType')->andReturn($committeeModel->type);
        $committee->shouldReceive('getGeoUnitId')->andReturn((string) $committeeModel->operational_geo);
        $committee->shouldReceive('getStatus')->andReturn('active');
        $committee->shouldReceive('levelIndex')->andReturn($committeeModel->level);

        $this->committeeRepository
            ->shouldReceive('findById')
            ->with($committeeId)
            ->andReturn($committee);

        // Mock adapter returns empty members (no User::find calls)
        $this->membershipReadModel
            ->shouldReceive('getActiveMembers')
            ->with($committeeId)
            ->andReturn([]);

        // Act
        $service = new GetCommitteeDashboard(
            $this->committeeRepository,
            $this->membershipLineageRepository,
            $this->membershipReadModel
        );

        $dashboard = $service->execute($committeeId, $tenantId);

        // Assert: Adapter was called exactly once
        $this->membershipReadModel->shouldHaveReceived('getActiveMembers')->once();
        $this->assertEmpty($dashboard->members);
    }

    public function test_execute_throws_when_committee_not_found(): void
    {
        // Arrange
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('org-' . \Illuminate\Support\Str::uuid());

        $this->committeeRepository
            ->shouldReceive('findById')
            ->with($committeeId)
            ->andReturnNull();

        // Act & Assert
        $service = new GetCommitteeDashboard(
            $this->committeeRepository,
            $this->membershipLineageRepository,
            $this->membershipReadModel
        );

        $this->expectException(\App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException::class);
        $service->execute($committeeId, $tenantId);
    }

    public function test_dto_toarray_returns_correct_structure(): void
    {
        // Arrange
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('org-' . \Illuminate\Support\Str::uuid());
        $committeeModel = CommitteeModel::factory()->create();
        $nameVo = \Mockery::mock();
        $nameVo->shouldReceive('value')->andReturn($committeeModel->name);

        $committee = \Mockery::mock();
        $committee->shouldReceive('getId')->andReturn($committeeId);
        $committee->shouldReceive('getName')->andReturn($nameVo);
        $committee->shouldReceive('code')->andReturn($committeeModel->code);
        $committee->shouldReceive('getType')->andReturn($committeeModel->type);
        $committee->shouldReceive('getGeoUnitId')->andReturn((string) $committeeModel->operational_geo);
        $committee->shouldReceive('getStatus')->andReturn('active');
        $committee->shouldReceive('levelIndex')->andReturn($committeeModel->level);

        $this->committeeRepository
            ->shouldReceive('findById')
            ->with($committeeId)
            ->andReturn($committee);

        $memberViews = [
            new CommitteeMemberView(
                memberId: 'member-1',
                displayName: 'Test User',
                statusKey: 'committee.members.status.active',
                roleKey: 'committee.members.role.member',
                joinedAt: new \DateTimeImmutable('2026-05-15'),
            ),
        ];

        $this->membershipReadModel
            ->shouldReceive('getActiveMembers')
            ->with($committeeId)
            ->andReturn($memberViews);

        // Act
        $service = new GetCommitteeDashboard(
            $this->committeeRepository,
            $this->membershipLineageRepository,
            $this->membershipReadModel
        );

        $dashboard = $service->execute($committeeId, $tenantId);
        $array = $dashboard->toArray();

        // Assert: DTO structure is correct
        $this->assertArrayHasKey('committee', $array);
        $this->assertArrayHasKey('members', $array);

        // Check committee section
        $this->assertArrayHasKey('id', $array['committee']);
        $this->assertArrayHasKey('name', $array['committee']);
        $this->assertArrayHasKey('code', $array['committee']);
        $this->assertArrayHasKey('status', $array['committee']);

        // Check members section
        $this->assertIsArray($array['members']);
        $this->assertCount(1, $array['members']);

        $member = $array['members'][0];
        $this->assertArrayHasKey('member_id', $member);
        $this->assertArrayHasKey('display_name', $member);
        $this->assertArrayHasKey('status_key', $member);
        $this->assertArrayHasKey('role_key', $member);
        $this->assertArrayHasKey('joined_date', $member);
    }

    public function test_dto_serialization_has_no_eloquent_leakage(): void
    {
        // Arrange
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('org-' . \Illuminate\Support\Str::uuid());
        $committeeModel = CommitteeModel::factory()->create();
        $nameVo = \Mockery::mock();
        $nameVo->shouldReceive('value')->andReturn($committeeModel->name);

        $committee = \Mockery::mock();
        $committee->shouldReceive('getId')->andReturn($committeeId);
        $committee->shouldReceive('getName')->andReturn($nameVo);
        $committee->shouldReceive('code')->andReturn($committeeModel->code);
        $committee->shouldReceive('getType')->andReturn($committeeModel->type);
        $committee->shouldReceive('getGeoUnitId')->andReturn((string) $committeeModel->operational_geo);
        $committee->shouldReceive('getStatus')->andReturn('active');
        $committee->shouldReceive('levelIndex')->andReturn($committeeModel->level);

        $this->committeeRepository
            ->shouldReceive('findById')
            ->with($committeeId)
            ->andReturn($committee);

        $memberViews = [
            new CommitteeMemberView(
                memberId: 'member-1',
                displayName: 'Test User',
                statusKey: 'committee.members.status.active',
                roleKey: 'committee.members.role.member',
            ),
        ];

        $this->membershipReadModel
            ->shouldReceive('getActiveMembers')
            ->with($committeeId)
            ->andReturn($memberViews);

        // Act
        $service = new GetCommitteeDashboard(
            $this->committeeRepository,
            $this->membershipLineageRepository,
            $this->membershipReadModel
        );

        $dashboard = $service->execute($committeeId, $tenantId);
        $json = json_encode($dashboard->toArray());

        // Assert: No Laravel model serialization
        $this->assertStringNotContainsString('App\\', $json);
        $this->assertStringNotContainsString('\\', $json);  // No namespaces in output
        $this->assertStringNotContainsString('Model', $json);
    }
}
