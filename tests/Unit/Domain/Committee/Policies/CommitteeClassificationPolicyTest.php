<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\Policies\CommitteeClassificationPolicy;
use App\Contexts\Membership\Domain\Committee\Projections\GeoSemanticProjection;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeCategory;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use PHPUnit\Framework\TestCase;

final class CommitteeClassificationPolicyTest extends TestCase
{
    private CommitteeClassificationPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new CommitteeClassificationPolicy();
    }

    public function test_admin_level_0_resolves_to_central(): void
    {
        $projection = new GeoSemanticProjection(1, 0, 'asia', 'NP');
        $type = $this->policy->resolve($projection);

        $this->assertTrue($type->isCentral());
    }

    public function test_admin_level_1_resolves_to_central(): void
    {
        $projection = new GeoSemanticProjection(1, 1, '', 'NP');
        $type = $this->policy->resolve($projection);

        $this->assertTrue($type->isCentral());
    }

    public function test_admin_level_2_resolves_to_geographic(): void
    {
        $projection = new GeoSemanticProjection(1, 2, '', '');
        $type = $this->policy->resolve($projection);

        $this->assertTrue($type->isGeographic());
    }

    public function test_admin_level_3_resolves_to_geographic(): void
    {
        $projection = new GeoSemanticProjection(1, 3, '', '');
        $type = $this->policy->resolve($projection);

        $this->assertTrue($type->isGeographic());
    }

    public function test_admin_level_4_resolves_to_geographic(): void
    {
        $projection = new GeoSemanticProjection(1, 4, '', '');
        $type = $this->policy->resolve($projection);

        $this->assertTrue($type->isGeographic());
    }

    public function test_admin_level_5_resolves_to_geographic(): void
    {
        $projection = new GeoSemanticProjection(1, 5, '', '');
        $type = $this->policy->resolve($projection);

        $this->assertTrue($type->isGeographic());
    }

    public function test_with_wing_youth_resolves_to_youth_wing(): void
    {
        $projection = new GeoSemanticProjection(1, 0, '', '');
        $type = $this->policy->resolve($projection, CommitteeCategory::YOUTH);

        $this->assertTrue($type->isYouthWing());
    }

    public function test_with_wing_women_resolves_to_women_wing(): void
    {
        $projection = new GeoSemanticProjection(1, 0, '', '');
        $type = $this->policy->resolve($projection, CommitteeCategory::WOMEN);

        $this->assertTrue($type->isWomenWing());
    }

    public function test_with_wing_student_resolves_to_student_wing(): void
    {
        $projection = new GeoSemanticProjection(1, 0, '', '');
        $type = $this->policy->resolve($projection, CommitteeCategory::STUDENT);

        $this->assertTrue($type->isStudentWing());
    }

    public function test_is_geographic_eligible_level_0_returns_false(): void
    {
        $projection = new GeoSemanticProjection(1, 0, '', '');
        $this->assertFalse($this->policy->isGeographicEligible($projection));
    }

    public function test_is_geographic_eligible_level_1_returns_false(): void
    {
        $projection = new GeoSemanticProjection(1, 1, '', '');
        $this->assertFalse($this->policy->isGeographicEligible($projection));
    }

    public function test_is_geographic_eligible_level_2_returns_true(): void
    {
        $projection = new GeoSemanticProjection(1, 2, '', '');
        $this->assertTrue($this->policy->isGeographicEligible($projection));
    }

    public function test_is_geographic_eligible_level_5_returns_true(): void
    {
        $projection = new GeoSemanticProjection(1, 5, '', '');
        $this->assertTrue($this->policy->isGeographicEligible($projection));
    }

    public function test_resolve_is_deterministic(): void
    {
        $projection = new GeoSemanticProjection(42, 2, 'asia', 'NP');
        $a = $this->policy->resolve($projection);
        $b = $this->policy->resolve($projection);

        $this->assertTrue($a->equals($b));
    }

    // Phase 8C.2D: mapAdminLevelToCategory tests

    public function test_admin_level_0_maps_to_central(): void
    {
        $this->assertTrue($this->policy->mapAdminLevelToCategory(0)->isCentral());
    }

    public function test_admin_level_1_maps_to_central(): void
    {
        $this->assertTrue($this->policy->mapAdminLevelToCategory(1)->isCentral());
    }

    public function test_admin_level_2_maps_to_province(): void
    {
        $this->assertTrue($this->policy->mapAdminLevelToCategory(2)->isGeographicType());
        $this->assertSame(CommitteeCategory::PROVINCE, $this->policy->mapAdminLevelToCategory(2));
    }

    public function test_admin_level_3_maps_to_district(): void
    {
        $this->assertTrue($this->policy->mapAdminLevelToCategory(3)->isGeographicType());
        $this->assertSame(CommitteeCategory::DISTRICT, $this->policy->mapAdminLevelToCategory(3));
    }

    public function test_admin_level_4_maps_to_ward(): void
    {
        $this->assertTrue($this->policy->mapAdminLevelToCategory(4)->isGeographicType());
        $this->assertSame(CommitteeCategory::WARD, $this->policy->mapAdminLevelToCategory(4));
    }

    public function test_admin_level_5_maps_to_ward(): void
    {
        $this->assertTrue($this->policy->mapAdminLevelToCategory(5)->isGeographicType());
        $this->assertSame(CommitteeCategory::WARD, $this->policy->mapAdminLevelToCategory(5));
    }
}
