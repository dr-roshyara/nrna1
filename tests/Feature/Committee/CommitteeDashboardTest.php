<?php

declare(strict_types=1);

namespace Tests\Feature\Committee;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommitteeDashboardTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeRepositoryInterface $repository;
    private TenantId $tenantId;
    private Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(CommitteeRepositoryInterface::class);
        $this->organisation = Organisation::factory()->create();
        $this->tenantId = TenantId::fromString($this->organisation->id);
    }

    public function test_committee_domain_persistence_and_retrieval(): void
    {
        $committeeId = CommitteeId::generate();
        $committee = Committee::createCentral(
            $committeeId,
            $this->tenantId,
            'Central Committee',
            'CENTRAL-001'
        );

        $this->repository->saveForTenant($committee);

        $this->assertDatabaseHas('committees', [
            'id' => $committeeId->value(),
            'organisation_id' => $this->tenantId->value(),
            'name' => 'Central Committee',
            'code' => 'CENTRAL-001',
            'type' => 'central',
            'status' => 'active',
        ]);
    }

    public function test_committee_domain_tenant_isolation(): void
    {
        $otherOrganisation = Organisation::factory()->create();
        $otherTenantId = TenantId::fromString($otherOrganisation->id);

        $committeeId = CommitteeId::generate();
        $committee = Committee::createCentral(
            $committeeId,
            $otherTenantId,
            'Other Tenant Committee',
            'OTHER-CENTRAL'
        );

        $this->repository->saveForTenant($committee);

        $this->assertDatabaseHas('committees', [
            'id' => $committeeId->value(),
            'organisation_id' => $otherTenantId->value(),
        ]);

        $this->assertDatabaseMissing('committees', [
            'id' => $committeeId->value(),
            'organisation_id' => $this->tenantId->value(),
        ]);
    }
}
