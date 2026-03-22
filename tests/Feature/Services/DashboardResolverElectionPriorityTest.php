<?php

namespace Tests\Feature\Services;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Services\DashboardResolver;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardResolverElectionPriorityTest extends TestCase
{
    use DatabaseTransactions;

    // =========================================================================
    // Helpers
    // =========================================================================

    private function makeVerifiedUser(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at'      => now(),
        ]);
    }

    private function attachToOrg(User $user, Organisation $org, string $role = 'member'): void
    {
        DB::table('user_organisation_roles')->insert([
            'id'              => \Illuminate\Support\Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $org->id,
            'role'            => $role,
            'created_at'      => now(),
            'updated_at'      => now(),
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

    private function markUserAsVoted(User $user, Election $election): void
    {
        // Primary: set has_voted on ElectionMembership (single source of truth)
        ElectionMembership::updateOrCreate(
            ['user_id' => $user->id, 'election_id' => $election->id],
            [
                'organisation_id' => $election->organisation_id,
                'role'            => 'voter',
                'has_voted'       => true,
                'voted_at'        => now(),
                'status'          => 'inactive',
            ]
        );

        // Also insert VoterSlug for audit trail
        DB::table('voter_slugs')->insert([
            'id'              => Str::uuid(),
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $election->organisation_id,
            'slug'            => 'voted-slug-' . uniqid(),
            'status'          => 'voted',
            'has_voted'       => 1,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    // =========================================================================
    // countActiveElections() unit-level checks
    // =========================================================================

    /**
     * @test
     * User with no active elections → countActiveElections = 0
     */
    public function user_with_no_active_elections_counts_zero(): void
    {
        $user = $this->makeVerifiedUser();
        $org  = Organisation::factory()->create(['type' => 'tenant']);
        $this->attachToOrg($user, $org);
        // No election created

        $this->assertEquals(0, $user->countActiveElections());
    }

    /**
     * @test
     * User with one active election → countActiveElections = 1
     */
    public function user_with_one_active_election_counts_one(): void
    {
        $user = $this->makeVerifiedUser();
        $org  = Organisation::factory()->create(['type' => 'tenant']);
        $this->attachToOrg($user, $org);
        $this->makeActiveElection($org);

        $this->assertEquals(1, $user->countActiveElections());
    }

    /**
     * @test
     * User already voted → countActiveElections = 0 (VoterSlug status='voted' excludes it)
     */
    public function user_who_already_voted_counts_zero(): void
    {
        $user     = $this->makeVerifiedUser();
        $org      = Organisation::factory()->create(['type' => 'tenant']);
        $this->attachToOrg($user, $org);
        $election = $this->makeActiveElection($org);
        $this->markUserAsVoted($user, $election);

        $this->assertEquals(0, $user->countActiveElections());
    }

    /**
     * @test
     * Future election with status=active IS counted.
     * countActiveElections() is for routing only — it counts by status, not date window.
     * If an admin marks an upcoming election as 'active', the org dashboard is shown
     * so the user is not silently routed to a ballot they can't vote on yet.
     */
    public function future_active_election_is_counted(): void
    {
        $user = $this->makeVerifiedUser();
        $org  = Organisation::factory()->create(['type' => 'tenant']);
        $this->attachToOrg($user, $org);
        Election::withoutGlobalScopes()->create([
            'organisation_id' => $org->id,
            'type'            => 'real',
            'status'          => 'active',
            'name'            => 'Future Election',
            'slug'            => 'future-' . uniqid(),
            'start_date'      => now()->addDays(5),
            'end_date'        => now()->addDays(10),
        ]);

        // Counted because status=active — date range is irrelevant for routing decisions
        $this->assertEquals(1, $user->countActiveElections());
    }

    /**
     * @test
     * Demo election not counted (type='demo')
     */
    public function demo_election_not_counted(): void
    {
        $user = $this->makeVerifiedUser();
        $org  = Organisation::factory()->create(['type' => 'tenant']);
        $this->attachToOrg($user, $org);
        Election::withoutGlobalScopes()->create([
            'organisation_id' => $org->id,
            'type'            => 'demo',
            'status'          => 'active',
            'name'            => 'Demo Election',
            'slug'            => 'demo-' . uniqid(),
            'start_date'      => now()->subDay(),
            'end_date'        => now()->addDay(),
        ]);

        $this->assertEquals(0, $user->countActiveElections());
    }

    // =========================================================================
    // DashboardResolver Priority 3 integration tests
    // =========================================================================

    /**
     * @test
     * 0 eligible elections → Priority 3 skipped → Priority 5 → organisations.show
     */
    public function user_with_no_eligible_elections_skips_priority_3(): void
    {
        $user = $this->makeVerifiedUser();
        $org  = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'no-election-org-' . uniqid()]);
        $this->attachToOrg($user, $org);

        $response = app(DashboardResolver::class)->resolve($user);

        // Should reach Priority 5 and land on organisations.show, NOT election.dashboard
        $this->assertStringNotContainsString('/election', $response->getTargetUrl());
        $this->assertStringContainsString($org->slug, $response->getTargetUrl());
    }

    /**
     * @test
     * 1 eligible election → Priority 3 redirects to election.dashboard (shows ElectionPage)
     */
    public function user_with_one_eligible_election_redirects_to_election_dashboard(): void
    {
        $user = $this->makeVerifiedUser();
        $org  = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'single-org-' . uniqid()]);
        $this->attachToOrg($user, $org);
        $this->makeActiveElection($org);

        $this->assertEquals(1, $user->countActiveElections());

        $response = app(DashboardResolver::class)->resolve($user);

        $this->assertStringContainsString('/election', $response->getTargetUrl());
        $this->assertStringNotContainsString('organisations', $response->getTargetUrl());
    }

    /**
     * @test
     * 2+ eligible elections → Priority 3 redirects to organisations.show
     */
    public function user_with_two_eligible_elections_redirects_to_org_show(): void
    {
        $user = $this->makeVerifiedUser();
        $slug = 'multi-org-' . uniqid();
        $org  = Organisation::factory()->create(['type' => 'tenant', 'slug' => $slug]);
        $this->attachToOrg($user, $org);
        $this->makeActiveElection($org);
        $this->makeActiveElection($org);

        $this->assertGreaterThanOrEqual(2, $user->countActiveElections());

        $response = app(DashboardResolver::class)->resolve($user);

        $this->assertStringContainsString('organisations/' . $slug, $response->getTargetUrl());
    }

    /**
     * @test
     * User who already voted → countActiveElections = 0 → Priority 3 skipped
     */
    public function user_who_already_voted_skips_priority_3(): void
    {
        $user     = $this->makeVerifiedUser();
        $slug     = 'voted-org-' . uniqid();
        $org      = Organisation::factory()->create(['type' => 'tenant', 'slug' => $slug]);
        $this->attachToOrg($user, $org);
        $election = $this->makeActiveElection($org);
        $this->markUserAsVoted($user, $election);

        $response = app(DashboardResolver::class)->resolve($user);

        // Priority 3 skipped → Priority 5 → organisations.show (NOT /election)
        $this->assertStringNotContainsString('/election', $response->getTargetUrl());
        $this->assertStringContainsString($slug, $response->getTargetUrl());
    }
}
