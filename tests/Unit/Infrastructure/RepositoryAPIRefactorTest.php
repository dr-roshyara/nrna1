<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure;

use Tests\TestCase;
use Illuminate\Database\QueryException;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class RepositoryAPIRefactorTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private CommitteeStructureRepositoryInterface $repo;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = $this->app->make(CommitteeStructureRepositoryInterface::class);
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');
    }

    public function test_persist_persists_draft_structure(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Draft Structure',
            levels: [
                CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );

        // Repository has state-agnostic persist() method
        $this->repo->persist($structure);

        $found = $this->repo->findById($structure->getId());

        $this->assertNotNull($found);
        $this->assertTrue($found->isDraft());
        $this->assertEquals('Draft Structure', $found->name());
    }

    public function test_persist_active_structure_persists_all_states(): void
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure To Activate',
            levels: [
                CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );

        // First persist as draft
        $this->repo->persist($structure);

        // Then activate and persist
        $structure->activate();
        $this->repo->persist($structure);

        $found = $this->repo->findById($structure->getId());

        $this->assertNotNull($found);
        $this->assertTrue($found->isActive());
    }

    public function test_repository_does_not_auto_deprecate_existing_active(): void
    {
        // Create and activate first structure
        $active1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'First Active',
            levels: [
                CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );
        $active1->activate();
        $this->repo->persist($active1);

        // Create second structure and activate it (without deprecating first)
        $active2 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Second Active',
            levels: [
                CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );
        $active2->activate();

        // Repository does NOT handle this — DB constraint should explode
        // This proves repository has NO business logic
        $this->expectException(QueryException::class);

        $this->repo->persist($active2);
    }

    public function test_repository_interface_has_no_lifecycle_aware_methods(): void
    {
        // The interface must NOT have saveDraft() or activate() methods
        // Both are replaced by state-agnostic persist()
        $this->assertFalse(
            method_exists(CommitteeStructureRepositoryInterface::class, 'saveDraft'),
            'saveDraft() method must not exist — use persist() instead'
        );
        $this->assertFalse(
            method_exists(CommitteeStructureRepositoryInterface::class, 'activate'),
            'activate() method must not exist — use persist() instead'
        );
    }

    public function test_multiple_drafts_can_be_persisted_for_same_tenant(): void
    {
        $draft1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Draft 1',
            levels: [
                CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );

        $draft2 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Draft 2',
            levels: [
                CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );

        $this->repo->persist($draft1);
        $this->repo->persist($draft2);

        $found1 = $this->repo->findById($draft1->getId());
        $found2 = $this->repo->findById($draft2->getId());

        $this->assertNotNull($found1);
        $this->assertNotNull($found2);
        $this->assertTrue($found1->isDraft());
        $this->assertTrue($found2->isDraft());
    }
}
