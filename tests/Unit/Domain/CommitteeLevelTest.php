<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use PHPUnit\Framework\TestCase;

final class CommitteeLevelTest extends TestCase
{
    public function test_geo_policy_none_does_not_require_geo(): void
    {
        $policy = GeoPolicy::NONE;

        $this->assertFalse($policy->requiresGeo());
    }

    public function test_geo_policy_required_throws_when_geo_null(): void
    {
        $policy = GeoPolicy::REQUIRED;

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('GeoPolicy::REQUIRED requires a GeoScope');
        $policy->validate(null);
    }

    public function test_geo_policy_optional_passes_when_geo_null(): void
    {
        $policy = GeoPolicy::OPTIONAL;

        // Should not throw
        $policy->validate(null);
        $this->assertTrue(true);
    }

    public function test_geo_scope_stores_code_string(): void
    {
        $scope = new GeoScope('province');

        $this->assertEquals('province', $scope->code);
    }

    public function test_committee_level_rejects_index_below_1(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Level index must be between 1 and 10');

        CommitteeLevel::create(
            index: 0,
            name: 'Invalid Level',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            roleLimits: [],
            minMembershipYears: 0,
            ageRange: null,
            genderRequirement: null
        );
    }

    public function test_committee_level_rejects_index_above_10(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Level index must be between 1 and 10');

        CommitteeLevel::create(
            index: 11,
            name: 'Invalid Level',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            roleLimits: [],
            minMembershipYears: 0,
            ageRange: null,
            genderRequirement: null
        );
    }

    public function test_committee_level_required_policy_needs_geo_scope(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Level with REQUIRED geo policy must have a GeoScope');

        CommitteeLevel::create(
            index: 1,
            name: 'Level with geo required',
            geoPolicy: GeoPolicy::REQUIRED,
            geoScope: null,
            roleLimits: [],
            minMembershipYears: 0,
            ageRange: null,
            genderRequirement: null
        );
    }

    public function test_committee_level_none_policy_allows_null_geo_scope(): void
    {
        $level = CommitteeLevel::create(
            index: 1,
            name: 'Central Committee',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            roleLimits: ['chairperson' => 1],
            minMembershipYears: 2,
            ageRange: null,
            genderRequirement: null
        );

        $this->assertEquals(1, $level->index);
        $this->assertNull($level->geoScope);
    }

    public function test_committee_level_toArray_roundtrip(): void
    {
        $level = CommitteeLevel::create(
            index: 2,
            name: 'Province Committee',
            geoPolicy: GeoPolicy::REQUIRED,
            geoScope: new GeoScope('province'),
            roleLimits: ['chairperson' => 1, 'member' => null],
            minMembershipYears: 0,
            ageRange: [18, 65],
            genderRequirement: 'female'
        );

        $array = $level->toArray();

        $this->assertEquals(2, $array['index']);
        $this->assertEquals('Province Committee', $array['name']);
        $this->assertEquals('required', $array['geo_policy']);
        $this->assertEquals('province', $array['geo_scope']);
        $this->assertEquals(['chairperson' => 1, 'member' => null], $array['role_limits']);
        $this->assertEquals(0, $array['min_membership_years']);
        $this->assertEquals([18, 65], $array['age_range']);
        $this->assertEquals('female', $array['gender_requirement']);
    }

    public function test_committee_level_fromArray_restores_all_fields(): void
    {
        $data = [
            'index' => 3,
            'name' => 'District Committee',
            'geo_policy' => 'required',
            'geo_scope' => 'district',
            'role_limits' => ['chairperson' => 1, 'member' => null],
            'min_membership_years' => 1,
            'age_range' => [21, 60],
            'gender_requirement' => null,
        ];

        $level = CommitteeLevel::fromArray($data);

        $this->assertEquals(3, $level->index);
        $this->assertEquals('District Committee', $level->name);
        $this->assertEquals(GeoPolicy::REQUIRED, $level->geoPolicy);
        $this->assertEquals('district', $level->geoScope->code);
        $this->assertEquals(['chairperson' => 1, 'member' => null], $level->roleLimits);
        $this->assertEquals(1, $level->minMembershipYears);
        $this->assertEquals([21, 60], $level->ageRange);
        $this->assertNull($level->genderRequirement);
    }

    public function test_committee_level_role_limits_defaults_to_empty_array(): void
    {
        $level = CommitteeLevel::create(
            index: 1,
            name: 'Test Level',
            geoPolicy: GeoPolicy::NONE,
            geoScope: null,
            roleLimits: [],
            minMembershipYears: 0,
            ageRange: null,
            genderRequirement: null
        );

        $this->assertEquals([], $level->roleLimits);
    }
}
