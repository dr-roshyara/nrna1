<?php

namespace Tests\Feature\Middleware;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Feature tests for EnsureElectionVoter middleware.
 *
 * TDD: all tests written first (RED), then implementation added (GREEN).
 *
 * Architecture ref: architecture/election/voter/20260318_0004_EnsureElectionVoter.md
 */
class EnsureElectionVoterTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $voter;
    private Election $realElection;
    private Election $demoElection;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        // Voter with organisation_id = $this->org so TenantContext middleware
        // sets the correct session on requests.
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

        // Register a simple test route guarded by the middleware.
        // Bound via route model binding so {election} resolves to Election model.
        Route::middleware(['web', 'auth', 'ensure.election.voter'])
            ->get('/test-election-voter/{election}', function () {
                return response('OK', 200);
            });
    }

    // =========================================================================
    // TEST 1 — Unauthenticated user is redirected to login
    // =========================================================================

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/test-election-voter/' . $this->realElection->id);

        // The 'auth' middleware redirects unauthenticated users to login.
        $response->assertRedirect();
        $this->assertStringContainsString('login', $response->headers->get('Location'));
    }

    // =========================================================================
    // TEST 2 — Election not in DB → 404
    // =========================================================================

    public function test_nonexistent_election_returns_404(): void
    {
        $fakeId = (string) Str::uuid();

        $response = $this->actingAs($this->voter)
            ->get('/test-election-voter/' . $fakeId);

        $response->assertNotFound();
    }

    // =========================================================================
    // TEST 3 — Demo election bypasses the voter check (passes through)
    // =========================================================================

    public function test_demo_election_bypasses_voter_check(): void
    {
        // Voter is NOT in election_memberships for the demo election,
        // but the middleware should let the request through anyway.
        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get('/test-election-voter/' . $this->demoElection->id);

        $response->assertOk();
    }

    // =========================================================================
    // TEST 4 — Eligible voter (active membership) passes through
    // =========================================================================

    public function test_eligible_voter_passes_through(): void
    {
        ElectionMembership::assignVoter($this->voter->id, $this->realElection->id);

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get('/test-election-voter/' . $this->realElection->id);

        $response->assertOk();
    }

    // =========================================================================
    // TEST 5 — Ineligible voter (no membership) is redirected with error flash
    // =========================================================================

    public function test_ineligible_voter_is_redirected_with_error(): void
    {
        // Voter has NO election membership for $this->realElection.
        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get('/test-election-voter/' . $this->realElection->id);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    // =========================================================================
    // TEST 6 — Ineligible voter + JSON request → 403 JSON response
    // =========================================================================

    public function test_ineligible_voter_json_request_returns_403(): void
    {
        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->getJson('/test-election-voter/' . $this->realElection->id);

        $response->assertStatus(403);
        $response->assertJsonStructure(['message']);
    }

    // =========================================================================
    // TEST 7 — Eligible voter has verified_election merged into request
    // =========================================================================

    public function test_eligible_voter_has_verified_election_in_request(): void
    {
        ElectionMembership::assignVoter($this->voter->id, $this->realElection->id);

        // Override the test route to expose the merged value.
        Route::middleware(['web', 'auth', 'ensure.election.voter'])
            ->get('/test-election-voter-inspect/{election}', function () {
                $election = request()->get('verified_election');
                return response()->json(['has_verified' => $election !== null]);
            });

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->getJson('/test-election-voter-inspect/' . $this->realElection->id);

        $response->assertOk();
        $response->assertJson(['has_verified' => true]);
    }
}
