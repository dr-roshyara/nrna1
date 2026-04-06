<?php

/**
 * TDD — OrganisationParticipant Model
 *
 * Architecture reference:
 *   architecture/membership/20260404_2344_redefine_membership_participants_guests.md
 *
 * Domain definition:
 *   A Participant is a person who has an OPERATIONAL role in the organisation
 *   (staff, guest, election_committee). This is DISTINCT from:
 *     - Member    → formal paid membership record
 *     - Voter     → election-specific registration
 *     - The old "all platform roles" list shown at /participants
 *
 *   Participant types:
 *     - staff              → permanent, internal staff / administrators
 *     - guest              → temporary, event-specific, has expires_at
 *     - election_committee → can be assigned to oversee specific elections
 *
 * Implementation requires:
 *   - NEW table: organisation_participants
 *       id, organisation_id, user_id, participant_type (staff|guest|election_committee),
 *       role (nullable free text), assigned_at, expires_at (nullable), permissions (JSON)
 *   - NEW model: App\Models\OrganisationParticipant
 *   - Scopes: staff(), guests(), electionCommittee()
 *   - isExpired(): bool  (for guests)
 *   - Organisation relationships: participants(), staff(), guests(), electionCommittee()
 *
 * All tests MUST FAIL on first run (Red — model & table do not exist yet).
 */

namespace Tests\Feature\Membership\Domain;

use App\Models\Organisation;
use App\Models\OrganisationParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationParticipantTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    private function makeParticipant(string $type, array $overrides = []): OrganisationParticipant
    {
        $user = User::factory()->create();

        return OrganisationParticipant::create(array_merge([
            'organisation_id'  => $this->org->id,
            'user_id'          => $user->id,
            'participant_type' => $type,
            'assigned_at'      => now(),
        ], $overrides));
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 1 — Table & model existence
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function organisation_participants_table_exists(): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasTable('organisation_participants'),
            'Table organisation_participants must exist.'
        );
    }

    /** @test */
    public function organisation_participants_table_has_required_columns(): void
    {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('organisation_participants');

        foreach (['id', 'organisation_id', 'user_id', 'participant_type', 'assigned_at', 'expires_at', 'permissions'] as $col) {
            $this->assertContains($col, $columns,
                "Column '{$col}' must exist on organisation_participants.");
        }
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 2 — Participant type discrimination
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function can_create_staff_participant(): void
    {
        $p = $this->makeParticipant('staff', ['role' => 'coordinator']);

        $this->assertDatabaseHas('organisation_participants', [
            'id'               => $p->id,
            'participant_type' => 'staff',
            'role'             => 'coordinator',
        ]);
    }

    /** @test */
    public function can_create_guest_participant_with_expiry(): void
    {
        $expiresAt = now()->addDays(30);

        $p = $this->makeParticipant('guest', ['expires_at' => $expiresAt]);

        $this->assertDatabaseHas('organisation_participants', [
            'id'               => $p->id,
            'participant_type' => 'guest',
        ]);
        $this->assertNotNull($p->fresh()->expires_at);
    }

    /** @test */
    public function can_create_election_committee_participant(): void
    {
        $p = $this->makeParticipant('election_committee', ['role' => 'observer']);

        $this->assertDatabaseHas('organisation_participants', [
            'id'               => $p->id,
            'participant_type' => 'election_committee',
        ]);
    }

    /** @test */
    public function participant_type_must_be_one_of_allowed_values(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        $this->makeParticipant('invalid_type');
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 3 — Guest expiry logic
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function guest_with_past_expiry_date_is_expired(): void
    {
        $p = $this->makeParticipant('guest', ['expires_at' => now()->subDay()]);

        $this->assertTrue($p->isExpired());
    }

    /** @test */
    public function guest_with_future_expiry_date_is_not_expired(): void
    {
        $p = $this->makeParticipant('guest', ['expires_at' => now()->addDays(7)]);

        $this->assertFalse($p->isExpired());
    }

    /** @test */
    public function staff_participant_without_expiry_is_never_expired(): void
    {
        $p = $this->makeParticipant('staff'); // expires_at = null

        $this->assertFalse($p->isExpired());
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 4 — Model scopes
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function staff_scope_returns_only_staff_participants(): void
    {
        $this->makeParticipant('staff');
        $this->makeParticipant('guest');
        $this->makeParticipant('election_committee');

        $results = OrganisationParticipant::staff()
            ->where('organisation_id', $this->org->id)
            ->get();

        $this->assertCount(1, $results);
        $this->assertSame('staff', $results->first()->participant_type);
    }

    /** @test */
    public function guests_scope_returns_only_guest_participants(): void
    {
        $this->makeParticipant('staff');
        $this->makeParticipant('guest', ['expires_at' => now()->addDays(14)]);
        $this->makeParticipant('guest', ['expires_at' => now()->addDays(7)]);

        $results = OrganisationParticipant::guests()
            ->where('organisation_id', $this->org->id)
            ->get();

        $this->assertCount(2, $results);
    }

    /** @test */
    public function election_committee_scope_returns_only_committee_participants(): void
    {
        $this->makeParticipant('staff');
        $this->makeParticipant('election_committee');

        $results = OrganisationParticipant::electionCommittee()
            ->where('organisation_id', $this->org->id)
            ->get();

        $this->assertCount(1, $results);
        $this->assertSame('election_committee', $results->first()->participant_type);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 5 — Organisation relationship methods
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function organisation_has_participants_relationship(): void
    {
        $this->makeParticipant('staff');
        $this->makeParticipant('guest', ['expires_at' => now()->addDays(5)]);

        $this->assertCount(2, $this->org->participants);
    }

    /** @test */
    public function organisation_staff_returns_only_staff(): void
    {
        $this->makeParticipant('staff');
        $this->makeParticipant('guest', ['expires_at' => now()->addDays(5)]);
        $this->makeParticipant('election_committee');

        $this->assertCount(1, $this->org->staff);
    }

    /** @test */
    public function organisation_guests_returns_only_guests(): void
    {
        $this->makeParticipant('staff');
        $this->makeParticipant('guest', ['expires_at' => now()->addDays(5)]);
        $this->makeParticipant('guest', ['expires_at' => now()->addDays(10)]);

        $this->assertCount(2, $this->org->guests);
    }

    /** @test */
    public function organisation_election_committee_returns_only_committee_members(): void
    {
        $this->makeParticipant('staff');
        $this->makeParticipant('election_committee');

        $this->assertCount(1, $this->org->electionCommittee);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 6 — Tenant isolation
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function participants_are_scoped_to_their_organisation(): void
    {
        $otherOrg  = Organisation::factory()->create(['type' => 'tenant']);
        $otherUser = User::factory()->create();

        // Create a participant in the other org
        OrganisationParticipant::create([
            'organisation_id'  => $otherOrg->id,
            'user_id'          => $otherUser->id,
            'participant_type' => 'staff',
            'assigned_at'      => now(),
        ]);

        // Our org has no participants
        $this->assertCount(0, $this->org->participants);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 7 — Conceptual boundary: Participant ≠ "all platform roles"
    // ══════════════════════════════════════════════════════════════════════════

    /** @test */
    public function user_with_only_member_role_in_user_organisation_roles_is_not_a_participant(): void
    {
        // A user added via the old invitation flow (UserOrganisationRole only)
        // should NOT appear in organisation_participants unless explicitly added.
        $user = User::factory()->create();
        \App\Models\UserOrganisationRole::create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);

        $count = OrganisationParticipant::where('organisation_id', $this->org->id)
            ->where('user_id', $user->id)
            ->count();

        $this->assertSame(0, $count,
            'UserOrganisationRole entry must NOT auto-create an OrganisationParticipant record.');
    }

    /** @test */
    public function participant_and_member_can_coexist_for_same_user(): void
    {
        // A staff member can ALSO hold a formal paid membership — they are separate records.
        $user    = User::factory()->create();
        $orgUser = \App\Models\OrganisationUser::factory()->create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        $type = \App\Models\MembershipType::factory()->create([
            'organisation_id'    => $this->org->id,
            'grants_voting_rights' => true,
        ]);
        \App\Models\UserOrganisationRole::create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);

        // Create a Member record
        \App\Models\Member::factory()->create([
            'organisation_id'      => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'membership_type_id'   => $type->id,
            'status'               => 'active',
            'fees_status'          => 'paid',
        ]);

        // Also create a Participant record (staff role)
        OrganisationParticipant::create([
            'organisation_id'  => $this->org->id,
            'user_id'          => $user->id,
            'participant_type' => 'staff',
            'assigned_at'      => now(),
        ]);

        $this->assertDatabaseHas('members', ['organisation_user_id' => $orgUser->id]);
        $this->assertDatabaseHas('organisation_participants', [
            'organisation_id' => $this->org->id,
            'user_id'         => $user->id,
        ]);
    }
}
