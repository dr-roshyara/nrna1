<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Application\Committee\Views\CommitteeDashboardView;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetCommitteeDashboardTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeRepositoryInterface $repository;
    private GetCommitteeDashboard $useCase;
    private TenantId $tenant1;
    private TenantId $tenant2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(CommitteeRepositoryInterface::class);
        $this->useCase    = new GetCommitteeDashboard($this->repository);
        $this->tenant1    = TenantId::fromOrganisationId('11111111-0000-0000-0000-000000000001');
        $this->tenant2    = TenantId::fromOrganisationId('22222222-0000-0000-0000-000000000002');
    }

    public function test_returns_dashboard_view_for_existing_committee(): void
    {
        $committeeId = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $committee   = Committee::createCentral($committeeId, $this->tenant1, 'Central HQ', 'HQ-001');
        $this->repository->saveForTenant($committee);

        $view = $this->useCase->execute($committeeId, $this->tenant1);

        $this->assertInstanceOf(CommitteeDashboardView::class, $view);
        $data = $view->toArray();
        $this->assertSame('HQ-001', $data['committee']['code']);
        $this->assertSame('Central HQ', $data['committee']['name']);
        $this->assertSame('central', $data['committee']['type']);
    }

    public function test_throws_committee_not_found_when_id_does_not_exist(): void
    {
        $this->expectException(CommitteeNotFoundException::class);
        $this->expectExceptionMessage('01ARZ3NDEKTSV4RRFFQ69G5FAV');

        $this->useCase->execute(
            CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            $this->tenant1
        );
    }

    public function test_throws_committee_not_found_for_different_tenant(): void
    {
        $committeeId = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $committee   = Committee::createCentral($committeeId, $this->tenant1, 'T1 Central', 'T1-001');
        $this->repository->saveForTenant($committee);

        $this->expectException(CommitteeNotFoundException::class);

        // tenant2 cannot see tenant1's committee
        $this->useCase->execute($committeeId, $this->tenant2);
    }

    public function test_returns_view_with_empty_sub_committees_for_central(): void
    {
        $committeeId = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $committee   = Committee::createCentral($committeeId, $this->tenant1, 'Central HQ', 'HQ-001');
        $this->repository->saveForTenant($committee);

        $view = $this->useCase->execute($committeeId, $this->tenant1);
        $data = $view->toArray();

        $this->assertArrayHasKey('sub_committees', $data);
        $this->assertEmpty($data['sub_committees']);
    }
}
