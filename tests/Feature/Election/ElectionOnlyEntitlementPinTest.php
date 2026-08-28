<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * SLICE 1 — Election-Only entitlement pins (Session 3, EP-01 approved).
 *
 * Plan: docs/plans/20260812-1748-election-only-implementation-boundary-plan.md
 * Canonical rule source: Election Manifesto — see plan §5 (Manifesto rule IDs
 * pending; interim references are the adopted D-ENT-1 clause numbers).
 *
 * Rule map (ID · capability · invariant · boundary):
 *  A  @see D-ENT-1 c9/c10 · admission · no Member aggregate required · ballot gate
 *  B  @see A-1            · entitlement · election X grants nothing in Y · ballot gate
 *  C1 @see D-ENT-1 c5/A-2 · suspension workflow · suspended member cannot vote · ballot gate
 *  C2 @see A-2            · suspension state invariant · non-active state blocks · ballot gate
 *  D  @see A-2 + commission §9 · credential · possession does not override suspension · ballot gate
 *  E  @see one-vote rule  · exhaustion · consumed vote cannot repeat · ballot gate
 *
 * Not pinned (open: Q3, Q-E1, Q-E2, BR-1.12, BR-1.13, BR-1.1/1.2, BR-1.8).
 * Fixtures: ElectionMembership::create() (assignVoter() no longer exists);
 * organisation_users row mirrors import output (open question Q-S3-1).
 */
class ElectionOnlyEntitlementPinTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $voter;
    private Election $electionX;
    private Election $electionY;
    private VoterSlug $slugX;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create([
            'type'                 => 'tenant',
            'uses_full_membership' => false,
        ]);
        session(['current_organisation_id' => $this->org->id]);

        $this->voter = User::factory()->create([
            'email_verified_at' => now(),
            'organisation_id'   => $this->org->id,
        ]);
        $this->org->users()->attach($this->voter->id, [
            'id'   => (string) Str::uuid(),
            'role' => 'voter',
        ]);

        $this->electionX = Election::factory()->create([
            'organisation_id'       => $this->org->id,
            'type'                  => 'real',
            'status'                => 'active',
            'voter_source_strategy' => 'election_only',
        ]);

        $this->electionY = Election::factory()->create([
            'organisation_id'       => $this->org->id,
            'type'                  => 'real',
            'status'                => 'active',
            'voter_source_strategy' => 'election_only',
        ]);

        // Credential for election X (required by the slug middleware chain).
        $this->slugX = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->electionX->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);
    }

    private function activeMembershipInX(): ElectionMembership
    {
        return ElectionMembership::create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->electionX->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);
    }

    // =========================================================================
    // A — Clause 9/10: no Member aggregate required at the ballot gate
    // =========================================================================

    public function test_election_only_voter_without_member_aggregate_passes_the_ballot_gate(): void
    {
        $this->activeMembershipInX();

        // The adopted rule concerns the Organisation Member aggregate:
        // this voter must have NO members row and still be admitted.
        $this->assertSame(0, Member::query()->count(),
            'Precondition: no Member aggregate rows exist for this scenario');

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.code.create', ['vslug' => $this->slugX->slug]));

        // Successful admission = the code-entry step renders for this voter.
        $response->assertOk();
        $this->assertNull(session('error'),
            'An entitled Election-Only voter must be admitted without any refusal flash');
    }

    // =========================================================================
    // B — A-1: the entitlement is election-specific
    // =========================================================================

    public function test_membership_in_one_election_grants_nothing_in_another(): void
    {
        // Entitled in X only. A credential for Y exists (issuance is not the
        // subject here) — the gate must still refuse, because the entitlement
        // does not extend beyond its election.
        $this->activeMembershipInX();

        $slugY = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->electionY->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->getJson(route('slug.code.create', ['vslug' => $slugY->slug]));

        $response->assertStatus(403);
    }

    // =========================================================================
    // C — Clause 5 / A-2: a suspended ElectionMember cannot reach the ballot
    // =========================================================================

    public function test_two_actor_suspension_blocks_ballot_access(): void
    {
        $membership = $this->activeMembershipInX();

        $chief  = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Chief One']);
        $second = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Chief Two']);

        $membership->proposeSuspension($chief);
        $this->assertTrue($membership->fresh()->canConfirmSuspension($second));
        $membership->confirmSuspension($second);

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->getJson(route('slug.code.create', ['vslug' => $this->slugX->slug]));

        $response->assertStatus(403);
    }

    public function test_non_active_membership_state_blocks_ballot_access(): void
    {
        // C2 — DOMAIN-STATE INVARIANT, deliberately NOT a workflow test:
        // whatever authorised act renders the membership non-active, the ballot
        // must refuse. It does not assert who may suspend (BR-1.13 is open) nor
        // how suspension is stored (Q3 is open).
        $membership = $this->activeMembershipInX();
        $membership->update(['status' => 'inactive']);

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->getJson(route('slug.code.create', ['vslug' => $this->slugX->slug]));

        $response->assertStatus(403);
    }

    // =========================================================================
    // D — Commission §9: credential possession does not override suspension
    // =========================================================================

    public function test_credential_possession_does_not_override_suspension(): void
    {
        $membership = $this->activeMembershipInX();

        // Voter exercises the gate once while entitled and exercisable —
        // this also warms the voter-gate cache deliberately, so the test
        // additionally protects invalidation-on-suspension.
        $first = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.code.create', ['vslug' => $this->slugX->slug]));
        $this->assertNotEquals(403, $first->status(), 'Precondition: voter passes the gate while active');

        // Suspension happens AFTER a live credential was issued and used.
        $chief  = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Chief One']);
        $second = User::factory()->create(['organisation_id' => $this->org->id, 'name' => 'Chief Two']);
        $membership->proposeSuspension($chief);
        $membership->confirmSuspension($second);

        // The credential itself is still live — possession must not restore access.
        $this->assertTrue((bool) $this->slugX->fresh()->is_active, 'Precondition: credential still live');

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->getJson(route('slug.code.create', ['vslug' => $this->slugX->slug]));

        $response->assertStatus(403);

        // And the deeper voting step is refused as well (any refusal shape:
        // what is pinned is that it does NOT succeed).
        $vote = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->getJson(route('slug.vote.create', ['vslug' => $this->slugX->slug]));

        $this->assertContains($vote->status(), [302, 403],
            'A suspended member holding a live credential must not reach the ballot');
    }

    // =========================================================================
    // E — One-vote / exhaustion protection
    // =========================================================================

    public function test_exhausted_credential_blocks_repeat_voting(): void
    {
        $this->activeMembershipInX();

        // Enforcement source is the codes row (NOT election_memberships.has_voted,
        // which production never writes — recorded in the plan, not repaired here).
        DB::table('codes')->insert([
            'id'                          => (string) Str::uuid(),
            'organisation_id'             => $this->org->id,
            'user_id'                     => $this->voter->id,
            'election_id'                 => $this->electionX->id,
            'code1'                       => '000000',
            'code2'                       => '000000',
            'has_voted'                   => 1,
            'can_vote_now'                => 0,
            'is_code_to_save_vote_usable' => 0,
            'created_at'                  => now(),
            'updated_at'                  => now(),
        ]);

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.code.create', ['vslug' => $this->slugX->slug]));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
