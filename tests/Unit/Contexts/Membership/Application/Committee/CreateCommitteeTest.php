<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\CreateCommittee;
use App\Contexts\Membership\Application\Committee\DTOs\CreateCommitteeCommand;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Shared\Domain\Events\EventBus;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCommitteeTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeRepositoryInterface $repository;
    private CreateCommittee $useCase;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(CommitteeRepositoryInterface::class);
        $this->useCase    = new CreateCommittee($this->repository, app(EventBus::class));
        $this->tenantId   = TenantId::fromOrganisationId('11111111-0000-0000-0000-000000000001');
    }

    public function test_creates_central_committee_and_returns_id(): void
    {
        $command = new CreateCommitteeCommand(
            tenantId: $this->tenantId,
            type: CommitteeType::central(),
            name: 'Central Committee',
            code: 'CENTRAL-001'
        );

        $committeeId = $this->useCase->execute($command);

        $this->assertInstanceOf(CommitteeId::class, $committeeId);
        $this->assertDatabaseHas('committees', [
            'id'              => $committeeId->value(),
            'organisation_id' => $this->tenantId->value(),
            'name'            => 'Central Committee',
            'code'            => 'CENTRAL-001',
            'type'            => 'central',
        ]);
    }

    public function test_creates_district_committee_with_geo_reference(): void
    {
        $command = new CreateCommitteeCommand(
            tenantId: $this->tenantId,
            type: CommitteeType::district(),
            name: 'Kathmandu District Committee',
            code: 'DIST-KTM',
            geoReference: GeoReference::fromString('np.3.15')
        );

        $committeeId = $this->useCase->execute($command);

        $this->assertDatabaseHas('committees', [
            'id'                        => $committeeId->value(),
            'type'                      => 'district',
            'operational_geo_reference' => 'np.3.15',
        ]);
    }

    public function test_throws_for_geographic_committee_without_geo_reference(): void
    {
        $command = new CreateCommitteeCommand(
            tenantId: $this->tenantId,
            type: CommitteeType::district(),
            name: 'District Committee',
            code: 'DIST-001'
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('requires a geo reference');

        $this->useCase->execute($command);
    }

    public function test_each_call_generates_a_unique_committee_id(): void
    {
        $command1 = new CreateCommitteeCommand($this->tenantId, CommitteeType::central(), 'Central A', 'C-A');
        $command2 = new CreateCommitteeCommand($this->tenantId, CommitteeType::central(), 'Central B', 'C-B');

        $id1 = $this->useCase->execute($command1);
        $id2 = $this->useCase->execute($command2);

        $this->assertNotSame($id1->value(), $id2->value());
    }

    public function test_committee_is_isolated_to_its_tenant(): void
    {
        $tenant2 = TenantId::fromOrganisationId('22222222-0000-0000-0000-000000000002');
        $command = new CreateCommitteeCommand($this->tenantId, CommitteeType::central(), 'T1 Central', 'T1-001');

        $committeeId = $this->useCase->execute($command);

        $saved = $this->repository->findForTenant($committeeId, $this->tenantId);
        $this->assertNotNull($saved);

        $notFound = $this->repository->findForTenant($committeeId, $tenant2);
        $this->assertNull($notFound);
    }
}
