<?php

namespace Tests\Unit\Models;

use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MembershipTypeTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
    }

    private function makeType(array $attrs = []): MembershipType
    {
        return MembershipType::create(array_merge([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual Member',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ], $attrs));
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /** @test */
    public function it_belongs_to_an_organisation(): void
    {
        $type = $this->makeType();
        $this->assertInstanceOf(Organisation::class, $type->organisation);
        $this->assertEquals($this->org->id, $type->organisation->id);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /** @test */
    public function active_scope_filters_out_inactive_types(): void
    {
        $this->makeType(['slug' => 'active-one', 'is_active' => true]);
        $this->makeType(['slug' => 'inactive-one', 'is_active' => false]);

        $results = MembershipType::active()->get();
        $this->assertCount(1, $results);
        $this->assertEquals('active-one', $results->first()->slug);
    }

    // ── Business logic ────────────────────────────────────────────────────────

    /** @test */
    public function it_is_lifetime_when_duration_months_is_null(): void
    {
        $type = $this->makeType(['slug' => 'lifetime', 'duration_months' => null]);
        $this->assertTrue($type->isLifetime());
    }

    /** @test */
    public function it_is_not_lifetime_when_duration_months_is_set(): void
    {
        $type = $this->makeType(['duration_months' => 12]);
        $this->assertFalse($type->isLifetime());
    }

    /** @test */
    public function slug_must_be_unique_per_organisation(): void
    {
        $this->makeType(['slug' => 'annual']);

        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->makeType(['slug' => 'annual']); // duplicate slug + org
    }

    /** @test */
    public function different_organisations_can_have_same_slug(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $this->makeType(['slug' => 'annual']);

        // Should not throw
        MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $otherOrg->id,
            'name'            => 'Annual Member',
            'slug'            => 'annual',
            'fee_amount'      => 60.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);

        $this->assertDatabaseCount('membership_types', 2);
    }

    /** @test */
    public function it_soft_deletes(): void
    {
        $type = $this->makeType();
        $type->delete();

        $this->assertSoftDeleted('membership_types', ['id' => $type->id]);
        $this->assertDatabaseCount('membership_types', 1); // still in DB
    }
}
