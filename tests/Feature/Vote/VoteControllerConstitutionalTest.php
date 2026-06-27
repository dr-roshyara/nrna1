<?php

namespace Tests\Feature\Vote;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Code;
use App\Models\DemoCode;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class VoteControllerConstitutionalTest extends TestCase
{
    /**
     * Test: VoteController.create() renders is_active from ElectionLifecycle, not raw field
     *
     * DIVERGENCE TEST: When $election->is_active (DB) differs from ElectionLifecycle::canVote(),
     * the Inertia prop should show the SSOT value, not the deprecated DB value.
     */
    public function test_create_uses_election_lifecycle_not_raw_is_active_field(): void
    {
        // Setup: Create real election with intentional divergence
        $organisation = Organisation::factory()->create();
        $user = User::factory()->create();

        // Create organisation user role (required for membership)
        \App\Models\OrganisationUser::factory()
            ->for($organisation)
            ->for($user)
            ->create();

        // Election where DB says false, but voting window says true
        $election = Election::factory()
            ->for($organisation)
            ->create([
                'type' => 'real',
                'state' => 'voting',
                'is_active' => false,  // ← Deprecated DB field says NO
                'voting_starts_at' => Carbon::now()->subHour(),
                'voting_ends_at' => Carbon::now()->addHour(),  // ← Window open
            ]);

        // Setup: Create voter membership and code
        ElectionMembership::factory()
            ->for($election)
            ->for($user)
            ->create([
                'status' => 'active',
                'organisation_id' => $organisation->id,
            ]);

        Code::factory()
            ->for($election)
            ->for($user)
            ->create(['has_agreed_to_vote' => true]);

        // Verify divergence: DB field vs lifecycle
        $lifecycle = ElectionLifecycle::of($election);
        $this->assertFalse($election->is_active, 'DB field should be false (divergence test setup)');
        $this->assertTrue($lifecycle->canVote(), 'Lifecycle should show true (voting window open)');

        // Act: Access create page
        $response = $this->actingAs($user)->get(route('vote.create'));

        // Assert: Response shows the SSOT value (true), not DB value (false)
        $response->assertStatus(200);
        $response->assertHasInertiaProps(['election']);

        $election_prop = $response->props()['election'];
        $this->assertIsArray($election_prop);
        $this->assertArrayHasKey('is_active', $election_prop);

        // THIS IS THE CRITICAL ASSERTION: Must match SSOT, not raw DB field
        $this->assertTrue($election_prop['is_active'],
            'Inertia prop must show ElectionLifecycle::canVote() (true), not raw is_active field (false)');
    }

    /**
     * Test: VoteController.verify_to_show() renders is_active from ElectionLifecycle
     *
     * DIVERGENCE TEST: Verify the verify endpoint also uses SSOT, not raw field
     */
    public function test_verify_to_show_uses_election_lifecycle_not_raw_is_active_field(): void
    {
        // Setup: Create election with divergence (DB=false, lifecycle=true)
        $organisation = Organisation::factory()->create();
        $user = User::factory()->create();

        \App\Models\OrganisationUser::factory()
            ->for($organisation)
            ->for($user)
            ->create();

        $election = Election::factory()
            ->for($organisation)
            ->create([
                'type' => 'real',
                'state' => 'voting',
                'is_active' => false,  // DB says NO
                'voting_starts_at' => Carbon::now()->subHour(),
                'voting_ends_at' => Carbon::now()->addHour(),  // Window says YES
            ]);

        ElectionMembership::factory()
            ->for($election)
            ->for($user)
            ->create([
                'status' => 'active',
                'organisation_id' => $organisation->id,
            ]);

        Code::factory()
            ->for($election)
            ->for($user)
            ->create(['has_agreed_to_vote' => true]);

        // Verify divergence
        $lifecycle = ElectionLifecycle::of($election);
        $this->assertFalse($election->is_active);
        $this->assertTrue($lifecycle->canVote());

        // Act: Submit verification (verify_to_show receives the code)
        $response = $this->actingAs($user)
            ->post(route('vote.verify'), [
                'voting_code' => 'test_code',
            ]);

        // Assert: Uses SSOT value
        $response->assertStatus(200);
        $response->assertHasInertiaProps(['election']);

        $election_prop = $response->props()['election'];
        $this->assertTrue($election_prop['is_active'],
            'verify_to_show must show ElectionLifecycle::canVote(), not raw is_active');
    }

    /**
     * Test: Closed voting window correctly shows is_active=false from SSOT
     *
     * REGRESSION TEST: Ensure SSOT correctly reflects closed windows
     */
    public function test_closed_voting_window_shows_is_active_false(): void
    {
        // Setup: Election where voting window has ended
        $organisation = Organisation::factory()->create();
        $user = User::factory()->create();

        \App\Models\OrganisationUser::factory()
            ->for($organisation)
            ->for($user)
            ->create();

        $election = Election::factory()
            ->for($organisation)
            ->create([
                'type' => 'real',
                'state' => 'voting',
                'is_active' => true,  // DB says YES
                'voting_starts_at' => Carbon::now()->subHours(2),
                'voting_ends_at' => Carbon::now()->subHour(),  // Already ended
            ]);

        ElectionMembership::factory()
            ->for($election)
            ->for($user)
            ->create([
                'status' => 'active',
                'organisation_id' => $organisation->id,
            ]);

        Code::factory()
            ->for($election)
            ->for($user)
            ->create(['has_agreed_to_vote' => true]);

        // Verify: Lifecycle reflects closed window even though DB says true
        $lifecycle = ElectionLifecycle::of($election);
        $this->assertTrue($election->is_active, 'DB field says true');
        $this->assertFalse($lifecycle->canVote(), 'Lifecycle should say false (window closed)');

        // Act: Try to access voting page
        $response = $this->actingAs($user)->get(route('vote.create'));

        // Assert: Either redirected (blocked) or page shows is_active=false
        if ($response->status() === 200) {
            $election_prop = $response->props()['election'] ?? null;
            if ($election_prop) {
                $this->assertFalse($election_prop['is_active'],
                    'When window is closed, is_active should be false from SSOT');
            }
        }
        // If redirected (401/403/redirect), that's also correct behavior
    }
}
