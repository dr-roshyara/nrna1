<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EloquentCommitteeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeRepositoryInterface $repository;
    private TenantId $tenant1;
    private TenantId $tenant2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(CommitteeRepositoryInterface::class);

        // Use realistic UUIDs — same format as Organisation::factory() produces.
        // No session() here: the repository uses explicit TenantId, not session state.
        $this->tenant1 = TenantId::fromOrganisationId('11111111-0000-0000-0000-000000000001');
        $this->tenant2 = TenantId::fromOrganisationId('22222222-0000-0000-0000-000000000002');
    }

    public function test_save_persists_committee_for_current_tenant(): void
    {
        $committee = Committee::createCentral(
            CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            $this->tenant1,
            'Central Committee',
            'CENTRAL-001'
        );

        $this->repository->saveForTenant($committee);

        $retrieved = $this->repository->findForTenant(
            CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            $this->tenant1
        );

        $this->assertNotNull($retrieved);
        $this->assertSame('CENTRAL-001', $retrieved->code());
    }

    public function test_find_returns_null_for_different_tenant(): void
    {
        $committee = Committee::createCentral(
            CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            $this->tenant1,
            'Central Committee',
            'CENTRAL-001'
        );

        $this->repository->saveForTenant($committee);

        $retrieved = $this->repository->findForTenant(
            CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            $this->tenant2
        );

        $this->assertNull($retrieved);
    }

    public function test_find_by_geography_returns_only_own_tenant_committees(): void
    {
        $geoRef = GeoReference::fromString('np.3.15');

        $committee1 = Committee::createForGeography(
            CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            $this->tenant1,
            'District Committee',
            'DIST-315',
            CommitteeType::district(),
            $geoRef,
            'wp'
        );

        $committee2 = Committee::createForGeography(
            CommitteeId::fromString('02ARZ3NDEKTSV4RRFFQ69G5FBW'),
            $this->tenant2,
            'District Committee',
            'DIST-316',
            CommitteeType::district(),
            GeoReference::fromString('np.3.16'),
            'wp'
        );

        $this->repository->saveForTenant($committee1);
        $this->repository->saveForTenant($committee2);

        $results = $this->repository->findByGeographyForTenant($geoRef, $this->tenant1);

        $this->assertCount(1, $results);
        $this->assertSame('DIST-315', $results[0]->code());
    }

    public function test_repository_prevents_cross_tenant_leak(): void
    {
        $committee1 = Committee::createCentral(
            CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            $this->tenant1,
            'Tenant 1 Committee',
            'T1-CENTRAL'
        );

        $committee2 = Committee::createCentral(
            CommitteeId::fromString('02ARZ3NDEKTSV4RRFFQ69G5FBW'),
            $this->tenant2,
            'Tenant 2 Committee',
            'T2-CENTRAL'
        );

        $this->repository->saveForTenant($committee1);
        $this->repository->saveForTenant($committee2);

        // Tenant 1 asking for tenant 2's committee ID must get null
        $found = $this->repository->findForTenant(
            CommitteeId::fromString('02ARZ3NDEKTSV4RRFFQ69G5FBW'),
            $this->tenant1
        );

        $this->assertNull($found, 'Cross-tenant leak detected in repository');
    }

    /**
     * Documents a known risk: raw DB::table() queries bypass ALL isolation.
     *
     * This is intentional and expected — Eloquent GlobalScope and repository
     * filtering only protect calls that go through the Eloquent model layer.
     * Any code that uses DB::table() directly will see every tenant's data.
     *
     * Rule: never use DB::table('committees') outside of migrations/seeders.
     */
    public function test_raw_db_query_bypasses_tenant_isolation(): void
    {
        $committee1 = Committee::createCentral(
            CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            $this->tenant1,
            'Tenant 1 Committee',
            'T1-CENTRAL'
        );

        $committee2 = Committee::createCentral(
            CommitteeId::fromString('02ARZ3NDEKTSV4RRFFQ69G5FBW'),
            $this->tenant2,
            'Tenant 2 Committee',
            'T2-CENTRAL'
        );

        $this->repository->saveForTenant($committee1);
        $this->repository->saveForTenant($committee2);

        // Raw query sees everything — no tenant filter applied
        $allRows = DB::table('committees')->whereNull('deleted_at')->get();
        $this->assertCount(2, $allRows, 'Raw DB::table() returns all tenants — known risk');

        // Repository correctly isolates per tenant
        $tenant1Only = $this->repository->findAllForTenant($this->tenant1);
        $this->assertCount(1, $tenant1Only);
        $this->assertSame('T1-CENTRAL', $tenant1Only[0]->code());
    }
}
