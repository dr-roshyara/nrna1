<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Services\DashboardResolver;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionShowControllerTest extends TestCase
{
    use DatabaseTransactions;

    // =========================================================================
    // Helpers
    // =========================================================================

    private function makeUser(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at'      => now(),
        ]);
    }

    private function makeOrg(): Organisation
    {
        return Organisation::factory()->create([
            'type' => 'tenant',
            'slug' => 'test-org-' . uniqid(),
        ]);
    }

    private function makeActiveElection(Organisation $org): Election
    {
        return Election::withoutGlobalScopes()->create([
            'organisation_id' => $org->id,
            'type'            => 'real',
            'status'          => 'active',
            'name'            => 'Test Election ' . uniqid(),
            'slug'            => 'test-election-' . uniqid(),
            'start_date'      => now()->subDay(),
            'end_date'        => now()->addDay(),
        ]);
    }

    private function makeVoterMember(User $user, Election $election): ElectionMembership
    {
        // FK constraint: (user_id, organisation_id) must exist in user_organisation_roles
        $alreadyAttached = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $election->organisation_id)
            ->exists();

        if (! $alreadyAttached) {
            $this->attachToOrg($user, Organisation::find($election->organisation_id));
        }

        return ElectionMembership::create([
            'user_id'         => $user->id,
            'organisation_id' => $election->organisation_id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);
    }

    private function attachToOrg(User $user, Organisation $org): void
    {
        DB::table('user_organisation_roles')->insert([
            'id'              => Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $org->id,
            'role'            => 'member',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    private function markVoted(User $user, Election $election): void
    {
        DB::table('voter_slugs')->insert([
            'id'              => Str::uuid(),
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $election->organisation_id,
            'slug'            => Str::random(32),
            'status'          => 'voted',
            'has_voted'       => 1,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    // =========================================================================
    // GET /elections/{slug} — show()
    // =========================================================================

    /** @test */
    public function guest_is_redirected_to_login(): void
    {
        $org      = $this->makeOrg();
        $election = $this->makeActiveElection($org);

        $this->get(route('elections.show', $election->slug))
             ->assertRedirect(route('login'));
    }

    /** @test */
    public function unknown_slug_returns_404(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
             ->get(route('elections.show', 'slug-that-does-not-exist'))
             ->assertStatus(404);
    }

    /** @test */
    public function demo_election_returns_404(): void
    {
        $user = $this->makeUser();
        $org  = $this->makeOrg();

        $demo = Election::withoutGlobalScopes()->create([
            'organisation_id' => $org->id,
            'type'            => 'demo',
            'status'          => 'active',
            'name'            => 'Demo Election',
            'slug'            => 'demo-' . uniqid(),
            'start_date'      => now()->subDay(),
            'end_date'        => now()->addDay(),
        ]);

        $this->actingAs($user)
             ->get(route('elections.show', $demo->slug))
             ->assertStatus(404);
    }

    /** @test */
    public function eligible_voter_sees_can_vote_true_and_has_voted_false(): void
    {
        $user     = $this->makeUser();
        $org      = $this->makeOrg();
        $election = $this->makeActiveElection($org);
        $this->makeVoterMember($user, $election);

        $this->actingAs($user)
             ->get(route('elections.show', $election->slug))
             ->assertInertia(fn ($page) => $page
                 ->component('Election/Show')
                 ->where('canVote', true)
                 ->where('hasVoted', false)
                 ->where('isEligible', true)
             );
    }

    /** @test */
    public function voted_user_sees_has_voted_true_and_can_vote_false(): void
    {
        $user     = $this->makeUser();
        $org      = $this->makeOrg();
        $election = $this->makeActiveElection($org);
        $this->makeVoterMember($user, $election);
        $this->markVoted($user, $election);

        $this->actingAs($user)
             ->get(route('elections.show', $election->slug))
             ->assertInertia(fn ($page) => $page
                 ->component('Election/Show')
                 ->where('hasVoted', true)
                 ->where('canVote', false)
             );
    }

    /** @test */
    public function non_member_sees_can_vote_false(): void
    {
        $user     = $this->makeUser();
        $org      = $this->makeOrg();
        $election = $this->makeActiveElection($org);
        // No ElectionMembership created

        $this->actingAs($user)
             ->get(route('elections.show', $election->slug))
             ->assertInertia(fn ($page) => $page
                 ->component('Election/Show')
                 ->where('canVote', false)
                 ->where('isEligible', false)
             );
    }

    /** @test */
    public function renders_election_show_inertia_component(): void
    {
        $user     = $this->makeUser();
        $org      = $this->makeOrg();
        $election = $this->makeActiveElection($org);

        $this->actingAs($user)
             ->get(route('elections.show', $election->slug))
             ->assertInertia(fn ($page) => $page
                 ->component('Election/Show')
                 ->has('election')
             );
    }

    // =========================================================================
    // DashboardResolver → elections.show (Priority 3 now uses slug-based route)
    // =========================================================================

    /** @test */
    public function dashboard_resolver_redirects_to_elections_show_for_single_election(): void
    {
        $user     = $this->makeUser();
        $org      = $this->makeOrg();
        $this->attachToOrg($user, $org);
        $election = $this->makeActiveElection($org);

        $response = app(DashboardResolver::class)->resolve($user);

        $this->assertStringContainsString(
            '/elections/' . $election->slug,
            $response->getTargetUrl()
        );
    }

    // =========================================================================
    // POST /elections/{slug}/start — start()
    // =========================================================================

    /** @test */
    public function start_for_eligible_voter_creates_voter_slug_and_redirects(): void
    {
        $user     = $this->makeUser();
        $org      = $this->makeOrg();
        $election = $this->makeActiveElection($org);
        $this->makeVoterMember($user, $election);

        $response = $this->actingAs($user)
             ->post(route('elections.start', $election->slug));

        // Should redirect to slug.code.create (voter slug route)
        $response->assertRedirect();
        $this->assertStringContainsString('/v/', $response->headers->get('Location'));
        $this->assertStringContainsString('/code/create', $response->headers->get('Location'));

        // Voter slug must be created in DB
        $this->assertDatabaseHas('voter_slugs', [
            'user_id'     => $user->id,
            'election_id' => $election->id,
            'status'      => 'active',
        ]);
    }

    /** @test */
    public function start_for_non_member_redirects_with_error(): void
    {
        $user     = $this->makeUser();
        $org      = $this->makeOrg();
        $election = $this->makeActiveElection($org);
        // No membership

        $this->actingAs($user)
             ->post(route('elections.start', $election->slug))
             ->assertRedirect(route('elections.show', $election->slug))
             ->assertSessionHas('error');
    }

    /** @test */
    public function start_for_already_voted_redirects_with_info(): void
    {
        $user     = $this->makeUser();
        $org      = $this->makeOrg();
        $election = $this->makeActiveElection($org);
        $this->makeVoterMember($user, $election);
        $this->markVoted($user, $election);

        $this->actingAs($user)
             ->post(route('elections.start', $election->slug))
             ->assertRedirect(route('elections.show', $election->slug))
             ->assertSessionHas('info');
    }

    /** @test */
    public function start_reuses_existing_active_voter_slug(): void
    {
        $user     = $this->makeUser();
        $org      = $this->makeOrg();
        $election = $this->makeActiveElection($org);
        $this->makeVoterMember($user, $election);

        // Pre-existing active slug
        $existingSlugString = Str::random(32);
        DB::table('voter_slugs')->insert([
            'id'              => Str::uuid(),
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $election->organisation_id,
            'slug'            => $existingSlugString,
            'status'          => 'active',
            'expires_at'      => now()->addMinutes(20),
            'has_voted'       => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $this->actingAs($user)
             ->post(route('elections.start', $election->slug));

        // Should still be only one voter_slug (reused, not duplicated)
        $this->assertEquals(1, DB::table('voter_slugs')
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('status', 'active')
            ->count()
        );
    }
}
