<?php

namespace Tests\Feature\Membership;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OrganisationRoleController::assignOfficer() — two distinct defects found
 * while tracing a live production crash:
 *
 * Bug 2: the existence check (`ElectionOfficer::where('user_id', ...)
 * ->where('organisation_id', ...)->first()`) is a default Eloquent query,
 * which silently excludes soft-deleted rows (ElectionOfficer uses
 * SoftDeletes). The unique constraint on (user_id, organisation_id,
 * election_id) has no `deleted_at IS NULL` qualifier, so re-assigning a
 * previously-removed officer to the same election crashes with a unique
 * constraint violation on insert.
 *
 * Bug 3: that same existence check ignores election_id entirely — it
 * matches the OLD org-scoped constraint (dropped by migration
 * 2026_05_11_213435_fix_election_officers_unique_constraint.php), not the
 * current election-scoped one. So assigning a user as an officer of a
 * second election in the same org silently moves their existing
 * assignment instead of creating a second, independent one.
 */
class OrganisationRoleControllerOfficerAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $this->assignRole($this->admin, $this->org, 'owner');
    }

    private function assignOfficer(User $target, Election $election, string $role): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.membership.roles.assign-officer', $this->org->slug), [
                'user_id'     => $target->id,
                'election_id' => $election->id,
                'role'        => $role,
            ]);
    }

    // ── Bug 2 ────────────────────────────────────────────────────────────

    public function test_reassigning_a_previously_removed_officer_to_the_same_election_succeeds(): void
    {
        $election = Election::factory()->create(['organisation_id' => $this->org->id]);
        $target   = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);

        $this->assignOfficer($target, $election, 'chief')->assertRedirect();

        // Remove — soft-deletes the row (ElectionOfficer uses SoftDeletes).
        $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.membership.roles.remove-officer', $this->org->slug), [
                'user_id'     => $target->id,
                'election_id' => $election->id,
            ])
            ->assertRedirect();

        $this->assertSoftDeleted('election_officers', [
            'user_id'     => $target->id,
            'election_id' => $election->id,
        ]);

        // Re-assign the same user to the same election — must not crash.
        $response = $this->assignOfficer($target, $election, 'deputy');

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('election_officers', [
            'user_id'     => $target->id,
            'election_id' => $election->id,
            'role'        => 'deputy',
            'status'      => 'active',
            'deleted_at'  => null,
        ]);
    }

    // ── Bug 3 ────────────────────────────────────────────────────────────

    public function test_same_user_can_be_an_officer_of_two_different_elections_in_the_same_organisation(): void
    {
        $electionA = Election::factory()->create(['organisation_id' => $this->org->id]);
        $electionB = Election::factory()->create(['organisation_id' => $this->org->id]);
        $target    = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);

        $this->assignOfficer($target, $electionA, 'chief')->assertRedirect();
        $this->assignOfficer($target, $electionB, 'deputy')->assertRedirect();

        // Both assignments must exist independently — the second must not
        // have silently moved/overwritten the first.
        $this->assertDatabaseHas('election_officers', [
            'user_id'     => $target->id,
            'election_id' => $electionA->id,
            'role'        => 'chief',
            'status'      => 'active',
        ]);
        $this->assertDatabaseHas('election_officers', [
            'user_id'     => $target->id,
            'election_id' => $electionB->id,
            'role'        => 'deputy',
            'status'      => 'active',
        ]);

        $this->assertEquals(
            2,
            ElectionOfficer::where('user_id', $target->id)->where('organisation_id', $this->org->id)->count()
        );
    }
}
