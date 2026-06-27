<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $org;
    protected User $admin;
    protected User $member;
    protected Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Services\TenantContext::clear();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
        \App\Services\TenantContext::set($this->org->id);

        $this->election = Election::factory()
            ->real()
            ->forOrganisation($this->org)
            ->create();

        $this->admin = User::factory()->create([
            'organisation_id' => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::updateOrCreate(
            [
                'user_id' => $this->admin->id,
                'organisation_id' => $this->org->id,
            ],
            [
                'role' => 'admin',
            ]
        );

        // Create ElectionOfficer so admin can manage settings
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'user_id'         => $this->admin->id,
            'role'            => 'chief',
            'status'          => 'active',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);

        $this->member = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::updateOrCreate(
            [
                'user_id' => $this->member->id,
                'organisation_id' => $this->org->id,
            ],
            [
                'role' => 'member',
            ]
        );
    }

    /** @test */
    public function test_admin_can_view_settings_page()
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.settings.edit', $this->election->slug));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Elections/Settings/Index')
        );
    }

    /** @test */
    public function test_admin_can_update_settings()
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled' => true,
                'ip_restriction_max_per_ip' => 3,
                'ip_whitelist' => [],
                'no_vote_option_enabled' => true,
                'no_vote_option_label' => 'Abstain',
                'selection_constraint_type' => 'exact',
                'selection_constraint_min' => null,
                'selection_constraint_max' => 5,
                'voter_verification_mode' => 'none',
                'settings_version' => 0,
                'confirmed_active_changes' => false,
                'agreed_to_settings' => true,
            ]);

        $response->assertRedirect();

        $this->election->refresh();
        $this->assertTrue($this->election->ip_restriction_enabled);
        $this->assertEquals(3, $this->election->ip_restriction_max_per_ip);
        $this->assertTrue($this->election->no_vote_option_enabled);
        $this->assertEquals('Abstain', $this->election->no_vote_option_label);
        $this->assertEquals('exact', $this->election->selection_constraint_type);
        $this->assertEquals(5, $this->election->selection_constraint_max);
        $this->assertEquals(1, $this->election->settings_version);
        $this->assertEquals($this->admin->id, $this->election->settings_updated_by);
        $this->assertNotNull($this->election->settings_updated_at);
    }

    /** @test */
    public function test_settings_version_increments_on_each_update()
    {
        $this->election->update(['settings_version' => 5]);

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled' => false,
                'ip_restriction_max_per_ip' => 4,
                'ip_whitelist' => [],
                'no_vote_option_enabled' => false,
                'no_vote_option_label' => 'No vote / Abstain',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_min' => null,
                'selection_constraint_max' => null,
                'voter_verification_mode' => 'none',
                'settings_version' => 5,
                'confirmed_active_changes' => false,
                'agreed_to_settings' => true,
            ]);

        $response->assertRedirect();
        $this->election->refresh();
        $this->assertEquals(6, $this->election->settings_version);
    }

    /** @test */
    public function test_non_admin_cannot_update_settings()
    {
        $response = $this->actingAs($this->member)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled' => true,
                'ip_restriction_max_per_ip' => 3,
                'no_vote_option_enabled' => false,
                'no_vote_option_label' => 'No vote / Abstain',
                'selection_constraint_type' => 'maximum',
                'settings_version' => 0,
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_optimistic_lock_rejects_stale_version()
    {
        $this->election->update(['settings_version' => 3]);

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled' => true,
                'ip_restriction_max_per_ip' => 5,
                'ip_whitelist' => [],
                'no_vote_option_enabled' => false,
                'no_vote_option_label' => 'No vote / Abstain',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_min' => null,
                'selection_constraint_max' => null,
                'voter_verification_mode' => 'none',
                'settings_version' => 1,  // stale version
                'confirmed_active_changes' => false,
                'agreed_to_settings' => true,
            ]);

        $response->assertSessionHasErrors('settings_version');
        $this->election->refresh();
        $this->assertEquals(3, $this->election->settings_version);
    }

    /** @test */
    public function test_ip_restriction_blocks_excess_votes_from_same_ip()
    {
        $this->election->update([
            'ip_restriction_enabled' => true,
            'ip_restriction_max_per_ip' => 1,
        ]);

        // Create voter1 with organisation_id set
        $voter1 = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::updateOrCreate(
            [
                'user_id' => $voter1->id,
                'organisation_id' => $this->org->id,
            ],
            [
                'role' => 'member',
            ]
        );

        VoterSlug::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'user_id' => $voter1->id,
            'step_1_ip' => '10.0.0.1',
            'has_voted' => true,
        ]);

        // Create voter2 with organisation_id set
        $voter2 = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::updateOrCreate(
            [
                'user_id' => $voter2->id,
                'organisation_id' => $this->org->id,
            ],
            [
                'role' => 'member',
            ]
        );

        \App\Models\ElectionMembership::create([
            'id' => (string) Str::uuid(),
            'user_id' => $voter2->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $response = $this->actingAs($voter2)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
            ->post(route('elections.start', $this->election->slug));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_whitelisted_ip_bypasses_restriction()
    {
        $this->election->update([
            'ip_restriction_enabled' => true,
            'ip_restriction_max_per_ip' => 1,
            'ip_whitelist' => ['10.0.0.0/8'],
        ]);

        // Create voter1 with organisation_id set
        $voter1 = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::updateOrCreate(
            [
                'user_id' => $voter1->id,
                'organisation_id' => $this->org->id,
            ],
            [
                'role' => 'member',
            ]
        );

        VoterSlug::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'user_id' => $voter1->id,
            'step_1_ip' => '10.0.0.1',
            'has_voted' => true,
        ]);

        // Create voter2 with organisation_id set
        $voter2 = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::updateOrCreate(
            [
                'user_id' => $voter2->id,
                'organisation_id' => $this->org->id,
            ],
            [
                'role' => 'member',
            ]
        );

        \App\Models\ElectionMembership::create([
            'id' => (string) Str::uuid(),
            'user_id' => $voter2->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $response = $this->actingAs($voter2)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
            ->post(route('elections.start', $this->election->slug));

        $response->assertRedirect();
    }

    /** @test */
    public function test_no_vote_option_setting_persists_correctly()
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled' => '0',
                'ip_restriction_max_per_ip' => '4',
                'ip_whitelist' => [],
                'no_vote_option_enabled' => '1',
                'no_vote_option_label' => 'Abstain',
                'selection_constraint_type' => 'maximum',
                'selection_constraint_min' => null,
                'selection_constraint_max' => null,
                'voter_verification_mode' => 'none',
                'settings_version' => 0,
                'confirmed_active_changes' => false,
                'agreed_to_settings' => true,
            ]);

        $response->assertRedirect();
        $this->election->refresh();
        $this->assertTrue($this->election->no_vote_option_enabled);
        $this->assertEquals('Abstain', $this->election->no_vote_option_label);
    }

    /** @test */
    public function test_selection_constraint_persists_correctly()
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->patch(route('elections.settings.update', $this->election->slug), [
                'ip_restriction_enabled' => '0',
                'ip_restriction_max_per_ip' => '4',
                'ip_whitelist' => [],
                'no_vote_option_enabled' => '0',
                'no_vote_option_label' => 'No vote / Abstain',
                'selection_constraint_type' => 'exact',
                'selection_constraint_min' => null,
                'selection_constraint_max' => '3',
                'voter_verification_mode' => 'none',
                'settings_version' => 0,
                'confirmed_active_changes' => false,
                'agreed_to_settings' => true,
            ]);

        $response->assertRedirect();
        $this->election->refresh();
        $this->assertEquals('exact', $this->election->selection_constraint_type);
        $this->assertEquals(3, $this->election->selection_constraint_max);
    }
}
