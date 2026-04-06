<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

/**
 * Test Suite: DashboardResolver 6-Priority Routing System
 *
 * Tests verify the complete dashboard resolution priority order:
 * 1. Active voting session → /v/{voter_slug}
 * 2. Active election available → /election/dashboard
 * 3. New user welcome → /dashboard/welcome
 * 4. Multiple roles → /role/selection
 * 5. Single role → role-specific dashboard
 * 6. Platform user fallback → /dashboard
 */
class DashboardResolverPriorityTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // PRIORITY 1: ACTIVE VOTING SESSION
    // ==========================================

    /** @test */
    public function priority_1_active_voting_session_redirects_to_voting_portal()
    {
        // Arrange: User with active voting session
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
        ]);

        // Add user to org
        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        // Create active voter slug (voting in progress)
        $voterSlug = DB::table('voter_slugs')->insertGetId([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'slug' => 'active-voting-slug',
            'expires_at' => now()->addDay(),
            'current_step' => 2, // In middle of voting (not started=1, not completed=5)
            'is_active' => true,
            'step_meta' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act: Navigate to dashboard
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert: Redirects to voting portal
        $response->assertRedirect();
        $this->assertStringContainsString(
            'vote.start',
            $response->headers->get('Location')
        );
    }

    /** @test */
    public function priority_1_takes_precedence_over_multiple_active_elections()
    {
        // Arrange: User with active voting AND multiple elections available
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        $org = Organisation::factory()->create();

        // Create first election (active voting)
        $election1 = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
        ]);

        // Create second election (available but not voting)
        $election2 = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
        ]);

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        // User has active voting in election 1
        DB::table('voter_slugs')->insert([
            'user_id' => $user->id,
            'election_id' => $election1->id,
            'slug' => 'active-voting',
            'expires_at' => now()->addDay(),
            'current_step' => 2, // In progress
            'is_active' => true,
            'step_meta' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert: Goes to voting, NOT election dashboard
        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringContainsString('vote.start', $location);
        $this->assertStringNotContainsString('election.dashboard', $location);
    }

    // ==========================================
    // PRIORITY 2: ACTIVE ELECTION AVAILABLE
    // ==========================================

    /** @test */
    public function priority_2_active_election_redirects_to_election_dashboard()
    {
        // Arrange: User with active election (no voting in progress)
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
            'is_voter' => true,
        ]);

        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertRedirect(route('election.dashboard', $election->slug));
    }

    /** @test */
    public function priority_2_skips_elections_where_user_already_voted()
    {
        // Arrange: User who already voted in one election, has another available
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
            'is_voter' => true,
        ]);

        $org = Organisation::factory()->create();

        // Election 1: Already voted
        $election1 = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
        ]);

        // Election 2: Available to vote
        $election2 = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
        ]);

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        // Mark election 1 as already voted
        DB::table('voter_slugs')->insert([
            'user_id' => $user->id,
            'election_id' => $election1->id,
            'slug' => 'voted-already',
            'expires_at' => now()->subDay(), // Expired
            'current_step' => 5, // Completed
            'is_active' => false,
            'step_meta' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert: Redirects to election 2 (available election)
        $response->assertRedirect(route('election.dashboard', $election2->slug));
    }

    /** @test */
    public function priority_2_ignores_elections_outside_voting_window()
    {
        // Arrange: Election dates not yet active
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
            'is_voter' => true,
        ]);

        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
            'start_date' => now()->addDay(), // Not started yet
            'end_date' => now()->addDays(5),
        ]);

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert: Does NOT redirect to election (should continue to next priority)
        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringNotContainsString('election.dashboard', $location);
    }

    // ==========================================
    // PRIORITY 3: NEW USER WELCOME
    // ==========================================

    /** @test */
    public function priority_3_new_user_verified_but_no_org_goes_to_welcome()
    {
        // Arrange: User verified but not onboarded (just verified email)
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => null, // Not yet onboarded
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertRedirect(route('dashboard.welcome'));
    }

    /** @test */
    public function priority_3_new_user_with_platform_org_only_goes_to_welcome()
    {
        // Arrange: User with only platform membership (org_id=1)
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => null,
        ]);

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => 1, // Platform org
            'role' => 'member',
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertRedirect(route('dashboard.welcome'));
    }

    /** @test */
    public function priority_3_skips_if_user_already_onboarded()
    {
        // Arrange: User verified and onboarded (no roles)
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert: NOT welcome page (continues to next priority)
        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringNotContainsString('dashboard.welcome', $location);
    }

    // ==========================================
    // PRIORITY 4: MULTIPLE ROLES
    // ==========================================

    /** @test */
    public function priority_4_user_with_multiple_roles_goes_to_role_selection()
    {
        // Arrange: User with admin in 2 different organisations
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        // Admin in both orgs
        DB::table('user_organisation_roles')->insert([
            ['user_id' => $user->id, 'organisation_id' => $org1->id, 'role' => 'admin', 'assigned_at' => now()],
            ['user_id' => $user->id, 'organisation_id' => $org2->id, 'role' => 'admin', 'assigned_at' => now()],
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertRedirect(route('role.selection'));
    }

    /** @test */
    public function priority_4_user_with_admin_and_commission_roles_goes_to_role_selection()
    {
        // Arrange: User with admin role AND commission membership
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        $org = Organisation::factory()->create();
        $election = Election::factory()->create(['organisation_id' => $org->id]);

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
            'assigned_at' => now(),
        ]);

        DB::table('election_commission_members')->insert([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'created_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertRedirect(route('role.selection'));
    }

    // ==========================================
    // PRIORITY 5: SINGLE ROLE
    // ==========================================

    /** @test */
    public function priority_5_single_admin_role_redirects_to_organisation_page()
    {
        // Arrange: User is admin in one organisation
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        $org = Organisation::factory()->create();

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
            'assigned_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertRedirect(route('organisations.show', $org->slug));
    }

    /** @test */
    public function priority_5_single_commission_role_redirects_to_commission_dashboard()
    {
        // Arrange: User is only a commission member
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        $org = Organisation::factory()->create();
        $election = Election::factory()->create(['organisation_id' => $org->id]);

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
            'assigned_at' => now(),
        ]);

        DB::table('election_commission_members')->insert([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'created_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertRedirect(route('commission.dashboard'));
    }

    // ==========================================
    // PRIORITY 6: PLATFORM USER FALLBACK
    // ==========================================

    /** @test */
    public function priority_6_user_with_no_roles_goes_to_default_dashboard()
    {
        // Arrange: User is verified and onboarded but has no roles
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertRedirect(route('dashboard'));
    }

    // ==========================================
    // PRIORITY PRECEDENCE TESTS
    // ==========================================

    /** @test */
    public function active_voting_takes_precedence_over_new_user_welcome()
    {
        // This is the most complex case: user hasn't been onboarded
        // but has an active voting session
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => null, // NOT onboarded
        ]);

        $org = Organisation::factory()->create();
        $election = Election::factory()->create(['organisation_id' => $org->id]);

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        // Has active voting session
        DB::table('voter_slugs')->insert([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'slug' => 'active-voting',
            'expires_at' => now()->addDay(),
            'current_step' => 2, // In progress
            'is_active' => true,
            'step_meta' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert: Goes to voting, NOT welcome page
        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringContainsString('vote.start', $location);
        $this->assertStringNotContainsString('dashboard.welcome', $location);
    }

    /** @test */
    public function active_election_takes_precedence_over_new_user_welcome()
    {
        // Arrange: User hasn't been onboarded but has an active election
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => null, // NOT onboarded yet
            'is_voter' => true,
        ]);

        $org = Organisation::factory()->create();
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'status' => 'active',
        ]);

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'member',
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert: Goes to election dashboard, NOT welcome
        $response->assertRedirect(route('election.dashboard', $election->slug));
    }

    /** @test */
    public function roles_take_precedence_over_welcome_when_onboarded()
    {
        // Arrange: Onboarded user with roles (should skip welcome even though onboarded)
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'onboarded_at' => now(),
        ]);

        $org = Organisation::factory()->create();

        DB::table('user_organisation_roles')->insert([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
            'assigned_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertRedirect(route('organisations.show', $org->slug));
    }
}
