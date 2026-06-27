<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\InternalCreateCommitteeCommand;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\Services\CommitteePolicyResolver;
use App\Contexts\Membership\Domain\Committee\Strategies\CentralCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\GeographicCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\StudentWingStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\WomenWingStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\YouthWingStructure;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeCategory;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteePolicy;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CommitteePolicyResolverTest extends TestCase
{
    private TenantId $tenantId;
    private CommitteePolicyResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantId = TenantId::fromString('test-tenant-123');
        $this->resolver = new CommitteePolicyResolver();
    }

    // --------------------------------------------------
    // COMMITTEE STRUCTURE LEVEL MAPPING
    // --------------------------------------------------

    #[Test]
    public function central_maps_to_level_1(): void
    {
        $structure = $this->createMultiLevelStructure();
        $this->assertSame(1, $structure->getLevelIndexForCategory(CommitteeCategory::CENTRAL));
    }

    #[Test]
    public function province_maps_to_level_2(): void
    {
        $structure = $this->createMultiLevelStructure();
        $this->assertSame(2, $structure->getLevelIndexForCategory(CommitteeCategory::PROVINCE));
    }

    #[Test]
    public function district_maps_to_level_3(): void
    {
        $structure = $this->createMultiLevelStructure();
        $this->assertSame(3, $structure->getLevelIndexForCategory(CommitteeCategory::DISTRICT));
    }

    #[Test]
    public function ward_maps_to_level_4(): void
    {
        $structure = $this->createMultiLevelStructure();
        $this->assertSame(4, $structure->getLevelIndexForCategory(CommitteeCategory::WARD));
    }

    #[Test]
    public function wing_categories_map_to_level_1(): void
    {
        $structure = $this->createMultiLevelStructure();
        $this->assertSame(1, $structure->getLevelIndexForCategory(CommitteeCategory::YOUTH));
        $this->assertSame(1, $structure->getLevelIndexForCategory(CommitteeCategory::WOMEN));
        $this->assertSame(1, $structure->getLevelIndexForCategory(CommitteeCategory::STUDENT));
    }

    // --------------------------------------------------
    // CENTRAL
    // --------------------------------------------------

    #[Test]
    public function central_category_resolves_to_central_type_and_structure(): void
    {
        $structure = $this->createCentralStructure();
        $command = $this->makeCommand(CommitteeCategory::CENTRAL);

        $policy = $this->resolver->resolve($structure, $command);

        $this->assertInstanceOf(CommitteePolicy::class, $policy);
        $this->assertTrue($policy->type->isCentral());
        $this->assertInstanceOf(CentralCommitteeStructure::class, $policy->structure);
        $this->assertSame(1, $policy->level->index);
    }

    // --------------------------------------------------
    // GEOGRAPHIC
    // --------------------------------------------------

    #[Test]
    public function province_category_resolves_to_geographic_type(): void
    {
        $structure = $this->createMultiLevelStructure();
        $command = $this->makeCommand(CommitteeCategory::PROVINCE);

        $policy = $this->resolver->resolve($structure, $command);

        $this->assertTrue($policy->type->isGeographic());
        $this->assertFalse($policy->type->isCentral());
        $this->assertInstanceOf(GeographicCommitteeStructure::class, $policy->structure);
        $this->assertSame(2, $policy->level->index);
    }

    #[Test]
    public function district_category_resolves_to_geographic_type(): void
    {
        $structure = $this->createMultiLevelStructure();
        $command = $this->makeCommand(CommitteeCategory::DISTRICT);

        $policy = $this->resolver->resolve($structure, $command);

        $this->assertTrue($policy->type->isGeographic());
        $this->assertFalse($policy->type->isCentral());
        $this->assertInstanceOf(GeographicCommitteeStructure::class, $policy->structure);
        $this->assertSame(3, $policy->level->index);
    }

    #[Test]
    public function ward_category_resolves_to_geographic_type(): void
    {
        $structure = $this->createMultiLevelStructure();
        $command = $this->makeCommand(CommitteeCategory::WARD);

        $policy = $this->resolver->resolve($structure, $command);

        $this->assertTrue($policy->type->isGeographic());
        $this->assertInstanceOf(GeographicCommitteeStructure::class, $policy->structure);
        $this->assertSame(4, $policy->level->index);
    }

    // --------------------------------------------------
    // WINGS
    // --------------------------------------------------

    #[Test]
    public function youth_category_resolves_to_youth_wing(): void
    {
        $structure = $this->createCentralStructure();
        $command = $this->makeCommand(CommitteeCategory::YOUTH);

        $policy = $this->resolver->resolve($structure, $command);

        $this->assertTrue($policy->type->isYouthWing());
        $this->assertInstanceOf(YouthWingStructure::class, $policy->structure);
    }

    #[Test]
    public function women_category_resolves_to_women_wing(): void
    {
        $structure = $this->createCentralStructure();
        $command = $this->makeCommand(CommitteeCategory::WOMEN);

        $policy = $this->resolver->resolve($structure, $command);

        $this->assertTrue($policy->type->isWomenWing());
        $this->assertInstanceOf(WomenWingStructure::class, $policy->structure);
    }

    #[Test]
    public function student_category_resolves_to_student_wing(): void
    {
        $structure = $this->createCentralStructure();
        $command = $this->makeCommand(CommitteeCategory::STUDENT);

        $policy = $this->resolver->resolve($structure, $command);

        $this->assertTrue($policy->type->isStudentWing());
        $this->assertInstanceOf(StudentWingStructure::class, $policy->structure);
    }

    // --------------------------------------------------
    // REGRESSION GUARD: The original production bug
    // --------------------------------------------------

    #[Test]
    public function geographic_categories_never_resolve_to_central(): void
    {
        $structure = $this->createMultiLevelStructure();

        foreach ([CommitteeCategory::PROVINCE, CommitteeCategory::DISTRICT, CommitteeCategory::WARD] as $category) {
            $command = $this->makeCommand($category);
            $policy = $this->resolver->resolve($structure, $command);
            $this->assertFalse(
                $policy->type->isCentral(),
                "Category '{$category->value}' should never resolve to central"
            );
        }
    }

    // --------------------------------------------------
    // NEGATIVE
    // --------------------------------------------------

    #[Test]
    public function throws_when_category_level_not_in_structure(): void
    {
        $structure = $this->createCentralStructure(); // Only level 1 exists
        $command = $this->makeCommand(CommitteeCategory::PROVINCE); // Maps to level 2

        $this->expectException(\DomainException::class);
        $this->resolver->resolve($structure, $command);
    }

    // --------------------------------------------------
    // HELPERS
    // --------------------------------------------------

    private function createCentralStructure(): CommitteeStructure
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Test Central Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null),
            ]
        );

        $structure->activate('test-user', 'activated for test');
        return $structure;
    }

    private function createMultiLevelStructure(): CommitteeStructure
    {
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Test Multi-Level Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null),
                CommitteeLevel::create(2, 'province', 'Province', GeoPolicy::REQUIRED, new GeoScope('province'), [], 0, null, null),
                CommitteeLevel::create(3, 'district', 'District', GeoPolicy::REQUIRED, new GeoScope('district'), [], 0, null, null),
                CommitteeLevel::create(4, 'ward', 'Ward', GeoPolicy::REQUIRED, new GeoScope('ward'), [], 0, null, null),
            ]
        );

        $structure->activate('test-user', 'activated for test');
        return $structure;
    }

    private function makeCommand(
        CommitteeCategory $committeeCategory,
        ?string $geoReference = null,
    ): InternalCreateCommitteeCommand {
        return new InternalCreateCommitteeCommand(
            tenantId: $this->tenantId,
            committeeCode: 'TEST-' . strtoupper(substr($committeeCategory->value, 0, 4)),
            committeeName: 'Test ' . ucfirst($committeeCategory->value) . ' Committee',
            committeeCategory: $committeeCategory,
            geoReference: $geoReference,
        );
    }
}
