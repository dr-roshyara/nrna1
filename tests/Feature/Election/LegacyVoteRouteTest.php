<?php

/**
 * TDD — Legacy vote.store Route: Election-Scoped Registration Check
 *
 * Problem: The legacy POST /votes route uses VoteEligibility middleware which
 * only checks is_voter/can_vote flags on the users table — not whether the user
 * is registered as a voter for THIS specific election.
 *
 * Fix: VoteEligibility middleware must verify isVoterInElection($electionId)
 * when election_id is present in the request, before the legacy flag check.
 */

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LegacyVoteRouteTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election     $election;
    private User         $voter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['status' => 'active']);

        $this->voter = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);

        // Required by election_memberships FK
        UserOrganisationRole::firstOrCreate(
            ['user_id' => $this->voter->id, 'organisation_id' => $this->org->id],
            ['id' => (string) Str::uuid(), 'role' => 'voter']
        );
    }

    private function registerVoter(User $user, Election $election): void
    {
        ElectionMembership::create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => 'active',
            'assigned_by'     => $user->id,
            'assigned_at'     => now(),
        ]);
    }

    // ══════════════════════════════════════════════��═══════════════════════════
    //  Tests
    // ════════════════════════════════════════════════════════════��═════════════

    public function test_legacy_route_rejects_user_not_registered_for_any_election(): void
    {
        // User has is_voter=true but NO election_memberships row
        $response = $this->actingAs($this->voter)
            ->withHeader('Accept', 'application/json')
            ->post(route('vote.store'), [
                'election_id' => $this->election->id,
            ]);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'You are not registered as a voter for this election.']);
    }

    public function test_legacy_route_accepts_user_registered_for_the_election(): void
    {
        $this->registerVoter($this->voter, $this->election);

        $response = $this->actingAs($this->voter)
            ->withHeader('Accept', 'application/json')
            ->post(route('vote.store'), [
                'election_id' => $this->election->id,
            ]);

        // Must NOT be blocked by the eligibility middleware (may fail for other
        // reasons like missing vote data — that is expected and acceptable here)
        $this->assertNotEquals(403, $response->status());
    }

    public function test_legacy_route_rejects_user_registered_for_different_election(): void
    {
        $otherElection = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['status' => 'active']);

        // Registered for OTHER election, not $this->election
        $this->registerVoter($this->voter, $otherElection);

        $response = $this->actingAs($this->voter)
            ->withHeader('Accept', 'application/json')
            ->post(route('vote.store'), [
                'election_id' => $this->election->id,
            ]);

        $response->assertStatus(403);
    }
}
