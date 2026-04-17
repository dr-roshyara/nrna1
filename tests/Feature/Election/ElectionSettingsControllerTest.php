<?php

namespace Tests\Feature\Election;

use App\Models\DemoVote;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ElectionSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $admin;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        session(['current_organisation_id' => $this->organisation->id]);

        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->organisation->id,
            'role'            => 'admin',
        ]);

        $this->election = Election::factory()
            ->real()
            ->for($this->organisation)
            ->create([
                'status'            => 'active',
                'settings_version'  => 0,
                'ip_restriction_enabled' => false,
            ]);
    }

    // ── Edit (GET) ──────────────────────────────────────────────────

    /** @test */
    public function admin_can_view_election_settings_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('elections.settings.edit', $this->election->slug));

        $response->assertOk();
        $response->assertInertia(fn ($page) =>
            $page->component('Elections/Settings/Index')
                 ->has('election')
                 ->has('organisation')
                 ->has('hasVotes')
        );
    }

    /** @test */
    public function non_admin_cannot_view_election_settings(): void
    {
        $user = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $user->id,
            'organisation_id' => $this->organisation->id,
            'role'            => 'member',
        ]);

        $response = $this->actingAs($user)
            ->get(route('elections.settings.edit', $this->election->slug));

        $response->assertForbidden();
    }

    /** @test */
    public function settings_page_shows_has_votes_flag_for_demo_election(): void
    {
        $demoElection = Election::factory()
            ->isDemo()
            ->for($this->organisation)
            ->create();

        DemoVote::factory()->create(['election_id' => $demoElection->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('elections.settings.edit', $demoElection->slug));

        $response->assertInertia(fn ($page) =>
            $page->where('hasVotes', true)
        );
    }

    // ── Update (PATCH) ──────────────────────────────────────────────

    /** @test */
    public function admin_can_update_basic_settings(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => true,
                'ip_restriction_max_per_ip' => 2,
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => true,
                'no_vote_option_label'      => 'Abstain',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_min'  => null,
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'none',
                'settings_version'          => 0,
                'confirmed_active_changes'  => false,
                'agreed_to_settings'        => true,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('elections', [
            'id'                    => $this->election->id,
            'ip_restriction_enabled' => true,
            'ip_restriction_max_per_ip' => 2,
            'no_vote_option_enabled' => true,
            'no_vote_option_label'  => 'Abstain',
            'settings_version'      => 1,
        ]);
    }

    /** @test */
    public function optimistic_locking_prevents_stale_writes(): void
    {
        // Simulate another user updating first
        $this->election->increment('settings_version');

        $response = $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => true,
                'ip_restriction_max_per_ip' => 2,
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => false,
                'no_vote_option_label'      => 'No vote',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'none',
                'settings_version'          => 0,  // Stale version
                'confirmed_active_changes'  => false,
                'agreed_to_settings'        => true,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('settings_version');
    }

    /** @test */
    public function active_election_with_votes_requires_confirmation(): void
    {
        $this->election->update(['is_active' => true]);
        Vote::factory()->create(['election_id' => $this->election->id]);

        $response = $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => true,
                'ip_restriction_max_per_ip' => 2,
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => false,
                'no_vote_option_label'      => 'No vote',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'none',
                'settings_version'          => 0,
                'confirmed_active_changes'  => false,  // NOT confirmed
                'agreed_to_settings'        => true,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('warning');
    }

    /** @test */
    public function active_election_with_confirmation_allows_update(): void
    {
        $this->election->update(['is_active' => true]);
        Vote::factory()->create(['election_id' => $this->election->id]);

        $response = $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => true,
                'ip_restriction_max_per_ip' => 2,
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => false,
                'no_vote_option_label'      => 'No vote',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'none',
                'settings_version'          => 0,
                'confirmed_active_changes'  => true,  // CONFIRMED
                'agreed_to_settings'        => true,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function array_fields_are_properly_detected_for_changes(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => true,
                'ip_restriction_max_per_ip' => 1,
                'ip_whitelist'              => ['192.168.1.0/24', '10.0.0.1'],
                'no_vote_option_enabled'    => false,
                'no_vote_option_label'      => 'No vote',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'none',
                'settings_version'          => 0,
                'confirmed_active_changes'  => false,
                'agreed_to_settings'        => true,
            ]);

        $this->assertDatabaseHas('elections', [
            'id'              => $this->election->id,
            'ip_whitelist'    => json_encode(['192.168.1.0/24', '10.0.0.1']),
            'settings_version' => 1,
        ]);
    }

    /** @test */
    public function settings_changes_are_recorded_in_audit_trail(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => true,
                'ip_restriction_max_per_ip' => 5,
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => true,
                'no_vote_option_label'      => 'Abstain',
                'selection_constraint_type' => 'exact',
                'selection_constraint_min'  => null,
                'selection_constraint_max'  => 2,
                'voter_verification_mode'   => 'ip_only',
                'settings_version'          => 0,
                'confirmed_active_changes'  => false,
                'agreed_to_settings'        => true,
            ]);

        $this->election->refresh();
        $changes = $this->election->settings_changes;

        $this->assertNotNull($changes);
        $this->assertTrue(isset($changes['ip_restriction_enabled']));
        $this->assertTrue(isset($changes['voter_verification_mode']));
        $this->assertEquals(false, $changes['ip_restriction_enabled']['from']);
        $this->assertEquals(true, $changes['ip_restriction_enabled']['to']);
    }

    /** @test */
    public function settings_cache_is_invalidated_on_update(): void
    {
        Cache::put("election.{$this->election->id}", ['cached' => true], 3600);
        Cache::put("election-settings-{$this->election->id}", ['settings' => true], 3600);

        $this->assertTrue(Cache::has("election.{$this->election->id}"));
        $this->assertTrue(Cache::has("election-settings-{$this->election->id}"));

        $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => true,
                'ip_restriction_max_per_ip' => 2,
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => false,
                'no_vote_option_label'      => 'No vote',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'none',
                'settings_version'          => 0,
                'confirmed_active_changes'  => false,
                'agreed_to_settings'        => true,
            ]);

        $this->assertFalse(Cache::has("election.{$this->election->id}"));
        $this->assertFalse(Cache::has("election-settings-{$this->election->id}"));
    }

    /** @test */
    public function validation_rejects_invalid_ip_restriction_max(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => true,
                'ip_restriction_max_per_ip' => 100,  // Exceeds max of 50
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => false,
                'no_vote_option_label'      => 'No vote',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'none',
                'settings_version'          => 0,
                'confirmed_active_changes'  => false,
                'agreed_to_settings'        => true,
            ]);

        $response->assertSessionHasErrors('ip_restriction_max_per_ip');
    }

    /** @test */
    public function validation_rejects_invalid_voter_verification_mode(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => false,
                'ip_restriction_max_per_ip' => 4,
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => false,
                'no_vote_option_label'      => 'No vote',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'invalid_mode',
                'settings_version'          => 0,
                'confirmed_active_changes'  => false,
                'agreed_to_settings'        => true,
            ]);

        $response->assertSessionHasErrors('voter_verification_mode');
    }

    /** @test */
    public function no_changes_recorded_if_all_values_remain_same(): void
    {
        // First update
        $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => false,
                'ip_restriction_max_per_ip' => 4,
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => false,
                'no_vote_option_label'      => 'No vote',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'none',
                'settings_version'          => 0,
                'confirmed_active_changes'  => false,
                'agreed_to_settings'        => true,
            ]);

        $this->election->refresh();
        $firstChanges = $this->election->settings_changes;

        // Second update with same values
        $this->actingAs($this->admin)
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled'    => false,
                'ip_restriction_max_per_ip' => 4,
                'ip_whitelist'              => [],
                'no_vote_option_enabled'    => false,
                'no_vote_option_label'      => 'No vote',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_max'  => 3,
                'voter_verification_mode'   => 'none',
                'settings_version'          => 1,
                'confirmed_active_changes'  => false,
                'agreed_to_settings'        => true,
            ]);

        $this->election->refresh();
        $secondChanges = $this->election->settings_changes;

        // Second update should have empty changes
        $this->assertEmpty($secondChanges);
        $this->assertNotEmpty($firstChanges);
    }
}
