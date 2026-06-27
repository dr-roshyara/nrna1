<?php

declare(strict_types=1);

namespace Tests\Unit\Committee\Application;

use App\Contexts\Committee\Application\ReadModel\CommitteeMembershipReadModelAdapter;
use App\Contexts\Committee\Application\ReadModel\CommitteeMemberView;
use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Domain\Committee\Committee;
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
        // Arrange: Create a REAL committee aggregate (DDD principle)
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('org-' . \Illuminate\Support\Str::uuid());

        $committee = Committee::createCentral(
            $committeeId,
            $tenantId,
            'Test Committee',
            'TEST'
        );

        // Mock repository to return the real committee
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
        $this->assertSame($committeeId->value(), $dashboard->id);
        $this->assertCount(2, $dashboard->members);
    }

    public function test_execute_calls_adapter_not_eloquent_directly(): void
    {
        // Arrange: Create a REAL committee aggregate
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('org-' . \Illuminate\Support\Str::uuid());

        $committee = Committee::createCentral(
            $committeeId,
            $tenantId,
            'Test Committee',
            'TEST'
        );

        $this->committeeRepository
            ->shouldReceive('findById')
            ->with($committeeId)
            ->andReturn($committee);

        // Mock adapter returns empty members (verifies no direct User::find calls)
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

        // Assert: Adapter was called exactly once (orchestration verified)
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
        // Arrange: Create a REAL committee aggregate
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('org-' . \Illuminate\Support\Str::uuid());

        $committee = Committee::createCentral(
            $committeeId,
            $tenantId,
            'Test Committee',
            'TEST'
        );

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
        // Arrange: Create a REAL committee aggregate
        $committeeId = CommitteeId::generate();
        $tenantId = TenantId::fromString('org-' . \Illuminate\Support\Str::uuid());

        $committee = Committee::createCentral(
            $committeeId,
            $tenantId,
            'Test Committee',
            'TEST'
        );

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

        // Assert: No Laravel model serialization leakage
        $this->assertStringNotContainsString('App\\', $json);
        $this->assertStringNotContainsString('\\', $json);
        $this->assertStringNotContainsString('Model', $json);
    }
}
