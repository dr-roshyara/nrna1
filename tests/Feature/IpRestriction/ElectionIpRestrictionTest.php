<?php

namespace Tests\Feature\IpRestriction;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionIpRestrictionTest extends TestCase
{
    use RefreshDatabase;

    private User $voter;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->voter = User::factory()->create();
    }

    /**
     * Test 1: show() passes ipBlocked=true when per-election IP limit reached (Layer 3)
     */
    public function test_show_passes_ip_blocked_true_when_per_election_limit_reached(): void
    {
        $election = Election::factory()->create([
            'ip_restriction_enabled'    => true,
            'ip_restriction_max_per_ip' => 1,
            'ip_whitelist'              => null,
            'type'                      => 'real',
            'status'                    => 'active',
            'start_date'                => now()->subDay(),
            'end_date'                  => now()->addDay(),
        ]);

        // Add voter membership
        $election->memberships()->create([
            'user_id' => $this->voter->id,
            'role'    => 'voter',
            'status'  => 'active',
        ]);

        // Create 1 completed vote from IP 1.2.3.4
        VoterSlug::factory()->create([
            'election_id' => $election->id,
            'step_1_ip'   => '1.2.3.4',
            'has_voted'   => true,
        ]);

        // Try to show election from same IP
        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->get(route('elections.show', $election->slug));

        $response->assertInertia(
            fn ($page) => $page
                ->where('ipBlocked', true)
                ->where('ipBlockMessage', 'The maximum of 1 vote(s) from your network has been reached.')
        );
    }

    /**
     * Test 2: show() passes ipBlocked=false for whitelisted IP at limit
     */
    public function test_show_passes_ip_blocked_false_for_whitelisted_ip_at_limit(): void
    {
        $election = Election::factory()->create([
            'ip_restriction_enabled'    => true,
            'ip_restriction_max_per_ip' => 1,
            'ip_whitelist'              => ['1.2.3.4'],
            'type'                      => 'real',
            'status'                    => 'active',
            'start_date'                => now()->subDay(),
            'end_date'                  => now()->addDay(),
        ]);

        $election->memberships()->create([
            'user_id' => $this->voter->id,
            'role'    => 'voter',
            'status'  => 'active',
        ]);

        // Create 1 completed vote from same IP
        VoterSlug::factory()->create([
            'election_id' => $election->id,
            'step_1_ip'   => '1.2.3.4',
            'has_voted'   => true,
        ]);

        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->get(route('elections.show', $election->slug));

        $response->assertInertia(fn ($page) => $page->where('ipBlocked', false));
    }

    /**
     * Test 3: show() passes ipBlocked=false when under the per-election vote limit
     */
    public function test_show_passes_ip_blocked_false_when_under_limit(): void
    {
        $election = Election::factory()->create([
            'ip_restriction_enabled'    => true,
            'ip_restriction_max_per_ip' => 3,
            'ip_whitelist'              => null,
            'type'                      => 'real',
            'status'                    => 'active',
            'start_date'                => now()->subDay(),
            'end_date'                  => now()->addDay(),
        ]);

        $election->memberships()->create([
            'user_id' => $this->voter->id,
            'role'    => 'voter',
            'status'  => 'active',
        ]);

        // Create only 2 completed votes (under limit of 3)
        VoterSlug::factory()->count(2)->create([
            'election_id' => $election->id,
            'step_1_ip'   => '1.2.3.4',
            'has_voted'   => true,
        ]);

        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->get(route('elections.show', $election->slug));

        $response->assertInertia(
            fn ($page) => $page
                ->where('ipBlocked', false)
                ->where('remainingVotes', 1)
        );
    }

    /**
     * Test 4: start() redirects with error flash when IP limit reached (no slug created)
     */
    public function test_start_redirects_with_error_when_ip_limit_reached(): void
    {
        $election = Election::factory()->create([
            'ip_restriction_enabled'    => true,
            'ip_restriction_max_per_ip' => 1,
            'ip_whitelist'              => null,
            'type'                      => 'real',
            'status'                    => 'active',
        ]);

        $election->memberships()->create([
            'user_id' => $this->voter->id,
            'role'    => 'voter',
            'status'  => 'active',
        ]);

        // Create 1 completed vote from same IP
        VoterSlug::factory()->create([
            'election_id' => $election->id,
            'step_1_ip'   => '1.2.3.4',
            'has_voted'   => true,
        ]);

        $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->post(route('elections.start', $election->slug))
            ->assertRedirectToRoute('elections.show', $election->slug)
            ->assertSessionHas('error', 'The maximum of 1 vote(s) from your network has been reached.');

        // Verify no new slug was created
        $this->assertEquals(1, VoterSlug::where('election_id', $election->id)->count());
    }

    /**
     * Test 5: start() creates slug for whitelisted IP despite limit
     */
    public function test_start_creates_slug_for_whitelisted_ip_despite_limit(): void
    {
        $election = Election::factory()->create([
            'ip_restriction_enabled'    => true,
            'ip_restriction_max_per_ip' => 1,
            'ip_whitelist'              => ['1.2.3.4'],
            'type'                      => 'real',
            'status'                    => 'active',
        ]);

        $election->memberships()->create([
            'user_id' => $this->voter->id,
            'role'    => 'voter',
            'status'  => 'active',
        ]);

        // Create 1 completed vote from same IP
        VoterSlug::factory()->create([
            'election_id' => $election->id,
            'step_1_ip'   => '1.2.3.4',
            'has_voted'   => true,
        ]);

        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->post(route('elections.start', $election->slug));

        $response->assertRedirectToRoute('slug.code.create');

        // Verify a new slug was created (whitelisted IP bypassed the check)
        $this->assertEquals(2, VoterSlug::where('election_id', $election->id)->count());
    }

    /**
     * Test 6: show() enforces Layer 2 global limit when per-election restriction disabled
     */
    public function test_show_enforces_global_limit_when_per_election_restriction_disabled(): void
    {
        $this->app['config']->set('app.max_use_clientIP', 3);

        $election = Election::factory()->create([
            'ip_restriction_enabled' => false, // Layer 3 OFF
            'ip_whitelist'           => null,
            'type'                   => 'real',
            'status'                 => 'active',
            'start_date'             => now()->subDay(),
            'end_date'               => now()->addDay(),
        ]);

        $election->memberships()->create([
            'user_id' => $this->voter->id,
            'role'    => 'voter',
            'status'  => 'active',
        ]);

        // Create 3 completed votes from same IP (hitting global limit)
        VoterSlug::factory()->count(3)->create([
            'election_id' => $election->id,
            'step_1_ip'   => '1.2.3.4',
            'has_voted'   => true,
        ]);

        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->get(route('elections.show', $election->slug));

        $response->assertInertia(
            fn ($page) => $page
                ->where('ipBlocked', true)
                ->where('ipBlockMessage', 'The maximum of 3 vote(s) from your network has been reached.')
        );
    }

    /**
     * Test 7: Whitelist bypasses Layer 2 global limit
     */
    public function test_whitelist_bypasses_global_limit(): void
    {
        $this->app['config']->set('app.max_use_clientIP', 1);

        $election = Election::factory()->create([
            'ip_restriction_enabled' => false,
            'ip_whitelist'           => ['1.2.3.4'],
            'type'                   => 'real',
            'status'                 => 'active',
            'start_date'             => now()->subDay(),
            'end_date'               => now()->addDay(),
        ]);

        $election->memberships()->create([
            'user_id' => $this->voter->id,
            'role'    => 'voter',
            'status'  => 'active',
        ]);

        // Create 1 completed vote from whitelisted IP
        VoterSlug::factory()->create([
            'election_id' => $election->id,
            'step_1_ip'   => '1.2.3.4',
            'has_voted'   => true,
        ]);

        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->get(route('elections.show', $election->slug));

        $response->assertInertia(fn ($page) => $page->where('ipBlocked', false));
    }

    /**
     * Test 8: show() passes correct remainingVotes count
     */
    public function test_show_passes_correct_remaining_votes_count(): void
    {
        $election = Election::factory()->create([
            'ip_restriction_enabled'    => true,
            'ip_restriction_max_per_ip' => 5,
            'ip_whitelist'              => null,
            'type'                      => 'real',
            'status'                    => 'active',
            'start_date'                => now()->subDay(),
            'end_date'                  => now()->addDay(),
        ]);

        $election->memberships()->create([
            'user_id' => $this->voter->id,
            'role'    => 'voter',
            'status'  => 'active',
        ]);

        // Create 2 completed votes from same IP
        VoterSlug::factory()->count(2)->create([
            'election_id' => $election->id,
            'step_1_ip'   => '1.2.3.4',
            'has_voted'   => true,
        ]);

        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->get(route('elections.show', $election->slug));

        // 5 - 2 = 3 remaining
        $response->assertInertia(fn ($page) => $page->where('remainingVotes', 3));
    }
}
