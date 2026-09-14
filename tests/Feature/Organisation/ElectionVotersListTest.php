<?php

namespace Tests\Feature\Organisation;

use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * OrganisationController::voters() — /organisations/{org}/elections/{election}/voters.
 *
 * has_voted is backed by Code.has_voted (the sole, transactionally-consistent
 * writer: VoteController::markUserAsVoted(), same transaction as the vote
 * save — see .claude/plans/encapsulated-hopping-hamster.md for the full
 * invariant evidence). ElectionMembership.has_voted is dead (no production
 * writer) and must NOT be used.
 */
class ElectionVotersListTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        $this->election = Election::factory()->create([
            'organisation_id' => $this->organisation->id,
            'type' => 'real',
        ]);

        $this->admin = User::factory()->forOrganisation($this->organisation)->create();
        // UserFactory::afterCreating() already creates a default role row for
        // this user+organisation pair — update it rather than inserting a
        // second one (would violate the unique(user_id, organisation_id)).
        UserOrganisationRole::updateOrCreate(
            ['user_id' => $this->admin->id, 'organisation_id' => $this->organisation->id],
            ['role' => 'admin']
        );
    }

    private function voter(bool $hasVotedInCode, ?\Carbon\Carbon $lastLoginAt = null, array $membershipOverrides = []): User
    {
        $user = User::factory()->forOrganisation($this->organisation)->create([
            'last_login_at' => $lastLoginAt,
        ]);

        ElectionMembership::factory()->create(array_merge([
            'organisation_id' => $this->organisation->id,
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'role' => 'voter',
            'status' => 'active',
            // Deliberately the OPPOSITE of $hasVotedInCode in some tests —
            // this dead column must never be what the page reads from.
            'has_voted' => false,
        ], $membershipOverrides));

        Code::factory()->create([
            'organisation_id' => $this->organisation->id,
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'has_voted' => $hasVotedInCode,
        ]);

        return $user;
    }

    private function votersUrl(array $query = []): string
    {
        return route('organisations.elections.voters', array_merge([
            'organisation' => $this->organisation->slug,
            'election' => $this->election->slug,
        ], $query));
    }

    // ── has_voted source ─────────────────────────────────────────────────

    public function test_has_voted_reflects_code_not_the_dead_election_membership_column(): void
    {
        // Code says voted; ElectionMembership (dead column) says not — the
        // page must show voted.
        $voted = $this->voter(hasVotedInCode: true, membershipOverrides: ['has_voted' => false]);
        // Code says not voted; ElectionMembership (dead column) says voted —
        // the page must NOT trust that dead flag.
        $notVoted = $this->voter(hasVotedInCode: false, membershipOverrides: ['has_voted' => true]);

        $rows = $this->getVoterRows();
        $byName = collect($rows)->keyBy('name');

        $this->assertTrue($byName[$voted->name]['has_voted']);
        $this->assertFalse($byName[$notVoted->name]['has_voted']);
    }

    // ── stats ─────────────────────────────────────────────────────────────

    public function test_stats_report_correct_totals_and_percentage(): void
    {
        $this->voter(hasVotedInCode: true);
        $this->voter(hasVotedInCode: true);
        $this->voter(hasVotedInCode: false);
        $this->voter(hasVotedInCode: false);

        $stats = $this->getStats();

        $this->assertSame(4, $stats['total']);
        $this->assertSame(2, $stats['voted']);
        $this->assertSame(2, $stats['not_voted']);
        // assertEquals, not assertSame: PHP's json_encode(50.0) round-trips
        // as plain int 50 through the Inertia test response — a JSON
        // serialization artifact, not a production behavior difference.
        $this->assertEquals(50.0, $stats['participation_percentage']);
    }

    public function test_stats_are_zero_percent_for_zero_voters(): void
    {
        $stats = $this->getStats();

        $this->assertSame(0, $stats['total']);
        $this->assertSame(0, $stats['voted']);
        $this->assertSame(0, $stats['not_voted']);
        $this->assertEquals(0.0, $stats['participation_percentage']);
    }

    public function test_stats_are_unaffected_by_pagination(): void
    {
        // 60 voters, page size is 50 — stats must still describe all 60.
        for ($i = 0; $i < 60; $i++) {
            $this->voter(hasVotedInCode: $i < 12);
        }

        $stats = $this->getStats();

        $this->assertSame(60, $stats['total']);
        $this->assertSame(12, $stats['voted']);
        $this->assertSame(48, $stats['not_voted']);
    }

    public function test_stats_are_unaffected_by_status_filter(): void
    {
        $this->voter(hasVotedInCode: true, membershipOverrides: ['status' => 'active']);
        $this->voter(hasVotedInCode: false, membershipOverrides: ['status' => 'inactive']);

        $stats = $this->getStats(['status' => 'active']);

        // Still describes the whole electorate, not just "active" ones.
        $this->assertSame(2, $stats['total']);
    }

    public function test_stats_are_unaffected_by_voted_filter(): void
    {
        $this->voter(hasVotedInCode: true);
        $this->voter(hasVotedInCode: false);

        $stats = $this->getStats(['voted' => 'voted']);

        $this->assertSame(2, $stats['total']);
        $this->assertSame(1, $stats['voted']);
    }

    public function test_stats_are_unaffected_by_sorting(): void
    {
        $this->voter(hasVotedInCode: true);
        $this->voter(hasVotedInCode: false);

        $stats = $this->getStats(['sort' => 'name', 'direction' => 'desc']);

        $this->assertSame(2, $stats['total']);
    }

    private function getStats(array $query = []): array
    {
        return $this->actingAs($this->admin)
            ->get($this->votersUrl($query))
            ->inertiaPage()['props']['stats'];
    }

    // ── voted filter ──────────────────────────────────────────────────────

    public function test_voted_filter_shows_only_voted(): void
    {
        $voted = $this->voter(hasVotedInCode: true);
        $notVoted = $this->voter(hasVotedInCode: false);

        $rows = $this->getVoterRows(['voted' => 'voted']);

        $this->assertCount(1, $rows);
        $this->assertTrue($rows[0]['has_voted']);
    }

    public function test_voted_filter_not_voted_shows_only_not_voted(): void
    {
        $voted = $this->voter(hasVotedInCode: true);
        $notVoted = $this->voter(hasVotedInCode: false);

        $rows = $this->getVoterRows(['voted' => 'not_voted']);

        $this->assertCount(1, $rows);
        $this->assertFalse($rows[0]['has_voted']);
    }

    public function test_voted_filter_composes_with_status_filter(): void
    {
        $this->voter(hasVotedInCode: true, membershipOverrides: ['status' => 'active']);
        $this->voter(hasVotedInCode: true, membershipOverrides: ['status' => 'inactive']);
        $this->voter(hasVotedInCode: false, membershipOverrides: ['status' => 'active']);

        $rows = $this->getVoterRows(['voted' => 'voted', 'status' => 'active']);

        $this->assertCount(1, $rows);
        $this->assertTrue($rows[0]['has_voted']);
    }

    private function getVoterRows(array $query = []): array
    {
        return $this->actingAs($this->admin)
            ->get($this->votersUrl($query))
            ->inertiaPage()['props']['voters']['data'];
    }

    // ── last_login_at ─────────────────────────────────────────────────────

    public function test_last_login_at_is_null_when_never_logged_in(): void
    {
        $this->voter(hasVotedInCode: false, lastLoginAt: null);

        $rows = $this->getVoterRows();

        $this->assertNull($rows[0]['last_login_at']);
    }

    public function test_last_login_at_is_populated_when_set(): void
    {
        $when = now()->subDays(2);
        $this->voter(hasVotedInCode: false, lastLoginAt: $when);

        $rows = $this->getVoterRows();

        $this->assertNotNull($rows[0]['last_login_at']);
    }

    // ── authorization (regression) ───────────────────────────────────────

    public function test_authorization_is_unchanged_non_member_is_forbidden(): void
    {
        $this->voter(hasVotedInCode: true);
        $outsider = User::factory()->forOrganisation($this->organisation)->create();

        $response = $this->actingAs($outsider)->get($this->votersUrl());

        $response->assertStatus(403);
    }

    public function test_authorization_is_unchanged_active_voter_member_can_view(): void
    {
        $member = $this->voter(hasVotedInCode: false);

        $response = $this->actingAs($member)->get($this->votersUrl());

        $response->assertOk();
    }
}
