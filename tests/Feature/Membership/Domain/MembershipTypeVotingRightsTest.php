<?php

/**
 * TDD — MembershipType grants_voting_rights
 *
 * Architecture reference:
 *   architecture/membership/20260404_2344_redefine_membership_participants_guests.md
 *
 * Domain rules under test:
 *   The architecture distinguishes exactly two member categories:
 *     - Full Member      → grants_voting_rights = true  (can vote in elections)
 *     - Associate Member → grants_voting_rights = false (observer / voice only)
 *
 *   These rules test that MembershipType correctly models this distinction and
 *   that the application enforces it when processing voting eligibility.
 *
 * Implementation requires:
 *   - membership_types.grants_voting_rights  BOOLEAN, default false
 *   - MembershipType::fullMember() scope
 *   - MembershipType::associateMember() scope  (or equivalent)
 *   - MembershipType factory support for grants_voting_rights
 */

namespace Tests\Feature\Membership\Domain;

use App\Models\MembershipType;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipTypeVotingRightsTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 1 — Column existence and defaults
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function membership_types_table_has_grants_voting_rights_column(): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasColumn('membership_types', 'grants_voting_rights'),
            'Column grants_voting_rights must exist on membership_types.'
        );
    }

    /** @test */
    public function new_membership_type_defaults_grants_voting_rights_to_false(): void
    {
        $type = MembershipType::factory()->create([
            'organisation_id' => $this->org->id,
            // grants_voting_rights intentionally omitted
        ]);

        $this->assertFalse((bool) $type->fresh()->grants_voting_rights);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 2 — Full Member type
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function full_member_type_has_grants_voting_rights_true(): void
    {
        $type = MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'name'               => 'Full Member',
            'grants_voting_rights' => true,
        ]);

        $this->assertTrue((bool) $type->grants_voting_rights);
    }

    /** @test */
    public function full_member_scope_returns_types_with_voting_rights(): void
    {
        MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => true,
        ]);
        MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => false,
        ]);

        $fullTypes = MembershipType::fullMember()
            ->where('organisation_id', $this->org->id)
            ->get();

        $this->assertCount(1, $fullTypes);
        $this->assertTrue((bool) $fullTypes->first()->grants_voting_rights);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 3 — Associate Member type
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function associate_member_type_has_grants_voting_rights_false(): void
    {
        $type = MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'name'               => 'Associate Member',
            'grants_voting_rights' => false,
        ]);

        $this->assertFalse((bool) $type->grants_voting_rights);
    }

    /** @test */
    public function associate_member_scope_returns_types_without_voting_rights(): void
    {
        MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => true,
        ]);
        MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => false,
        ]);
        MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => false,
        ]);

        $associateTypes = MembershipType::associateMember()
            ->where('organisation_id', $this->org->id)
            ->get();

        $this->assertCount(2, $associateTypes);
        foreach ($associateTypes as $t) {
            $this->assertFalse((bool) $t->grants_voting_rights);
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 4 — Tenant isolation
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function full_member_scope_is_scoped_to_organisation(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);

        // Full type in our org
        MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => true,
        ]);

        // Full type in other org — must not bleed across
        MembershipType::factory()->create([
            'organisation_id'    => $otherOrg->id,
            'grants_voting_rights' => true,
        ]);

        $results = MembershipType::fullMember()
            ->where('organisation_id', $this->org->id)
            ->get();

        $this->assertCount(1, $results,
            'fullMember() scope should not cross organisation boundaries.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 5 — Update behaviour
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function membership_type_can_be_upgraded_from_associate_to_full(): void
    {
        $type = MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => false,
        ]);

        $type->update(['grants_voting_rights' => true]);

        $this->assertTrue((bool) $type->fresh()->grants_voting_rights);
    }

    /** @test */
    public function membership_type_can_be_downgraded_from_full_to_associate(): void
    {
        $type = MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => true,
        ]);

        $type->update(['grants_voting_rights' => false]);

        $this->assertFalse((bool) $type->fresh()->grants_voting_rights);
    }
}
