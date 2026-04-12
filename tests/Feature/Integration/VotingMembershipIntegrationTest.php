<?php

namespace Tests\Feature\Integration;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Integration tests: EnsureElectionVoter middleware wired into the voting engine.
 *
 * TDD — all tests written first (RED), then implementation added (GREEN).
 *
 * Covers:
 *  1. ensure.election.voter is present in the slug route middleware chain
 *  2. Unassigned voter blocked at slug.code.create
 *  3. Assigned voter passes slug.code.create
 *  4. Demo election bypasses membership check entirely
 *  5. Double-vote prevention (existing Layers 1–5) still works after Layer 0 added
 *  6. Voter removed between submission and verification is blocked at verify (fresh DB check)
 *
 * Architecture refs:
 *   architecture/election/voter/20260319_0056_implement_EnsureElectionVoter_into_voting_engine.md
 *   architecture/election/voter/20260319_0056_implement_EnsureElectionVoter_review.md
 */
class VotingMembershipIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $voter;
    private Election $realElection;
    private Election $demoElection;
    private VoterSlug $voterSlug;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        // Voter — organisation_id must match org so TenantContext sets correct session
        $this->voter = User::factory()->create([
            'email_verified_at' => now(),
            'organisation_id'   => $this->org->id,
        ]);
        $this->org->users()->attach($this->voter->id, [
            'id'   => (string) Str::uuid(),
            'role' => 'voter',
        ]);

        $this->realElection = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);

        $this->demoElection = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'demo',
            'status'          => 'active',
        ]);

        // Voter slug for real election — required by VerifyVoterSlug middleware
        $this->voterSlug = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->realElection->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);
    }

    // =========================================================================
    // TEST 1 — ensure.election.voter is in the slug route middleware chain
    // =========================================================================

    public function test_ensure_election_voter_middleware_is_in_slug_route_chain(): void
    {
        $route = app('router')->getRoutes()->getByName('slug.code.create');

        $this->assertNotNull($route, 'Route slug.code.create must exist');

        $middlewares = $route->gatherMiddleware();

        $this->assertContains(
            'ensure.election.voter',
            $middlewares,
            'ensure.election.voter middleware must be registered on slug.code.create'
        );
    }

    // =========================================================================
    // TEST 2 — unassigned voter is blocked at the entry to the voting flow
    // =========================================================================

    public function test_unassigned_voter_is_blocked_at_code_create(): void
    {
        // No ElectionMembership created — voter is NOT assigned

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    // =========================================================================
    // TEST 3 — assigned voter with active membership passes through
    // =========================================================================

    public function test_assigned_voter_can_access_code_create(): void
    {
        ElectionMembership::assignVoter($this->voter->id, $this->realElection->id);

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        // Layer 0 must not block an assigned voter.
        // Other middleware (e.g. VoteEligibility legacy checks) may redirect for unrelated reasons —
        // those are pre-existing and out of scope for this test.
        $this->assertNotEquals(403, $response->status(), 'Assigned voter should not get 403');

        // If there IS an error flash, it must NOT be from ensure.election.voter (Layer 0).
        $flashError = session('error');
        if ($flashError) {
            $this->assertStringNotContainsStringIgnoringCase(
                'not assigned',
                $flashError,
                'Layer 0 should not produce "not assigned" error for an assigned voter'
            );
            $this->assertStringNotContainsStringIgnoringCase(
                'not eligible to vote in this election',
                $flashError,
                'EnsureElectionVoter should not block an assigned voter'
            );
        }
    }

    // =========================================================================
    // TEST 4 — demo election bypasses the membership check entirely
    // =========================================================================

    public function test_demo_election_bypasses_voter_membership_check(): void
    {
        // Create a demo voter slug
        $demoVoterSlug = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->demoElection->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);

        // No ElectionMembership — but it's a demo, so middleware should bypass
        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.demo-code.create', ['vslug' => $demoVoterSlug->slug]));

        // Should not be blocked with error flash; 200 or inertia render is fine
        $this->assertNotEquals(
            403,
            $response->status(),
            'Demo election should bypass membership check'
        );

        // Should not have membership error
        $flashError = session('error');
        if ($flashError) {
            $this->assertStringNotContainsStringIgnoringCase(
                'not assigned',
                $flashError,
                'Demo election should not produce a "not assigned" error'
            );
        }
    }

    // =========================================================================
    // TEST 5 — existing double-vote prevention (Layer 1) still fires for assigned voter
    // =========================================================================

    public function test_already_voted_user_is_still_blocked_after_layer_0_added(): void
    {
        // Assign voter and also mark their code as already voted
        ElectionMembership::assignVoter($this->voter->id, $this->realElection->id);

        // Insert directly using actual schema columns (CodeFactory is outdated)
        \Illuminate\Support\Facades\DB::table('codes')->insert([
            'id'               => (string) \Illuminate\Support\Str::uuid(),
            'organisation_id'  => $this->org->id,
            'user_id'          => $this->voter->id,
            'election_id'      => $this->realElection->id,
            'code1'            => '000000',
            'code2'            => '000000',
            'has_voted'        => 1,
            'can_vote_now'     => 0,
            'is_code_to_save_vote_usable'  => 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.code.create', ['vslug' => $this->voterSlug->slug]));

        // Layer 1 in CodeController::create() must still redirect with an error
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    // =========================================================================
    // TEST 6 — voter removed between first_submission and verify is blocked
    //          (fresh DB check on verify; race condition protection)
    // =========================================================================

    public function test_voter_removed_between_submission_and_verification_is_blocked(): void
    {
        // Assign voter — they pass Layer 0 and start the flow
        $membership = ElectionMembership::assignVoter($this->voter->id, $this->realElection->id);

        // Simulate concurrent admin removal AFTER first_submission but BEFORE verify
        $membership->remove('Removed during test', null);

        // Now voter tries to access the verify page — fresh DB check should block them
        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.vote.verify', ['vslug' => $this->voterSlug->slug]));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
