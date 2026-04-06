<?php

namespace Tests\Feature\Demo;

use App\Models\DemoCode;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Http\Controllers\Demo\DemoCodeController;
use App\Http\Controllers\Demo\DemoVoteController;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ExpiredCodeRestartTest
 *
 * TDD: Validates that users with expired voting windows can restart the demo voting flow.
 *
 * BUG: When a user has can_vote_now=1 but code_to_open_voting_form_used_at is older than voting_time_in_minutes,
 * visiting demo-code/create redirects them to agreement (because can_vote_now=1), which
 * then redirects them to demo-vote/create (because has_agreed_to_vote=1), creating a loop.
 *
 * FIX LOCATIONS:
 *   1. DemoCodeController::create() — check voting window expiry BEFORE redirecting to agreement
 *   2. DemoVoteController::create() — redirect to demo-code/create when window is expired
 */
class ExpiredCodeRestartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Election $election;
    private VoterSlug $voterSlug;
    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();

        // Use ElectionFactory which handles organisation creation correctly
        $this->election = Election::factory()->create([
            'type'      => 'demo',
            'is_active' => true,
        ]);

        // Get the organisation that was created/used by the factory
        $this->org = Organisation::find($this->election->organisation_id);

        $this->user = User::factory()->create([
            'email'              => 'voter@example.com',
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
            'region'            => 'Bayern',
        ]);

        $this->voterSlug = VoterSlug::create([
            'user_id'         => $this->user->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'slug'            => 'test-slug-' . uniqid(),
            'expires_at'      => now()->addHours(2),
            'current_step'    => 3,
            'is_active'       => true,
        ]);

        // Set session to the correct organisation
        session(['current_organisation_id' => $this->org->id]);
    }

    // ====================================================================
    // UNIT TESTS — test controller logic directly (no middleware complexity)
    // ====================================================================

    /**
     * RED TEST 1: DemoCodeController detects expired window and bypasses agreement redirect
     *
     * When code_to_open_voting_form_used_at is 35 min ago (voting window of 30 min has expired)
     * and can_vote_now=1, the controller must NOT redirect to agreement.
     *
     * Currently FAILS: line 89 of DemoCodeController redirects to agreement
     * without checking whether the voting window has actually expired.
     */
    public function test_expired_voting_window_bypasses_agreement_redirect_on_demo_code_create()
    {
        $code = DemoCode::withoutGlobalScopes()->create([
            'user_id'                => $this->user->id,
            'election_id'            => $this->election->id,
            'organisation_id'        => $this->org->id,
            'code_to_open_voting_form'                  => 'OLDCOD',
            'code_to_open_voting_form_sent_at'          => now()->subMinutes(40),
            'has_code1_sent'         => true,
            'is_code_to_open_voting_form_usable'        => false,
            'code_to_open_voting_form_used_at'          => now()->subMinutes(35), // 35 min ago → window expired
            'can_vote_now'           => 1,                      // Still 1: expireCode never called
            'has_agreed_to_vote'     => true,
            'has_voted'              => false,
            'voting_time_in_minutes' => 30,
        ]);

        $response = $this->actingAs($this->user)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                'selected_election_type'  => 'demo',
            ])
            ->get("/v/{$this->voterSlug->slug}/demo-code/create");

        // Must NOT redirect to agreement page — that would create a restart loop
        $location = $response->headers->get('Location', '');
        $this->assertStringNotContainsString(
            'agreement',
            $location,
            "Expired code must NOT redirect to agreement — doing so creates an infinite restart loop. Got redirect to: {$location}"
        );
    }

    /**
     * TEST 2: DemoVoteController redirects to demo-code/create when window is expired
     *
     * The CheckVotingWindow middleware intercepts the request and redirects expired users
     * to the code creation page instead of showing the (useless) voting form.
     */
    public function test_expired_voting_window_on_vote_create_redirects_to_code_create()
    {
        DemoCode::withoutGlobalScopes()->create([
            'user_id'                => $this->user->id,
            'election_id'            => $this->election->id,
            'organisation_id'        => $this->org->id,
            'code_to_open_voting_form'                  => 'OLDCOD',
            'code_to_open_voting_form_sent_at'          => now()->subMinutes(40),
            'has_code1_sent'         => true,
            'is_code_to_open_voting_form_usable'        => false,
            'code_to_open_voting_form_used_at'          => now()->subMinutes(35), // 35 min ago → window expired
            'can_vote_now'           => 1,
            'has_agreed_to_vote'     => true,
            'has_voted'              => false,
            'voting_time_in_minutes' => 30,
        ]);

        $response = $this->actingAs($this->user)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                'selected_election_type'  => 'demo',
            ])
            ->get("/v/{$this->voterSlug->slug}/demo-vote/create");

        // Must redirect to demo-code/create
        $response->assertStatus(302);
        $location = $response->headers->get('Location', '');
        $this->assertStringContainsString(
            'demo-code/create',
            $location,
            "Expired voting window on demo-vote/create must redirect to demo-code/create. Got: {$location}"
        );
    }

    /**
     * TEST 3: Middleware also resets can_vote_now=0 so getOrCreateCode() generates fresh code
     */
    public function test_middleware_resets_can_vote_now_when_window_expires()
    {
        $code = DemoCode::withoutGlobalScopes()->create([
            'user_id'                => $this->user->id,
            'election_id'            => $this->election->id,
            'organisation_id'        => $this->org->id,
            'code_to_open_voting_form'                  => 'OLDCOD',
            'code_to_open_voting_form_sent_at'          => now()->subMinutes(40),
            'has_code1_sent'         => true,
            'is_code_to_open_voting_form_usable'        => false,
            'code_to_open_voting_form_used_at'          => now()->subMinutes(35),
            'can_vote_now'           => 1,   // Still 1 before middleware runs
            'has_agreed_to_vote'     => true,
            'has_voted'              => false,
            'voting_time_in_minutes' => 30,
        ]);

        $this->actingAs($this->user)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                'selected_election_type'  => 'demo',
            ])
            ->get("/v/{$this->voterSlug->slug}/demo-vote/create");

        // After middleware runs, can_vote_now must be 0
        $code->refresh();
        $this->assertEquals(0, $code->can_vote_now,
            'CheckVotingWindow middleware must reset can_vote_now=0 when window expires'
        );
    }

    /**
     * GREEN BASELINE: Valid (non-expired) code still redirects to agreement from demo-code/create
     *
     * Ensures existing behavior is preserved after the fix.
     */
    public function test_valid_code_still_redirects_to_agreement_from_demo_code_create()
    {
        DemoCode::withoutGlobalScopes()->create([
            'user_id'                => $this->user->id,
            'election_id'            => $this->election->id,
            'organisation_id'        => $this->org->id,
            'code_to_open_voting_form'                  => 'VALCOD',
            'code_to_open_voting_form_sent_at'          => now()->subMinutes(5),
            'has_code1_sent'         => true,
            'is_code_to_open_voting_form_usable'        => false,
            'code_to_open_voting_form_used_at'          => now()->subMinutes(5), // 5 min ago → still valid
            'can_vote_now'           => 1,
            'has_agreed_to_vote'     => false,
            'has_voted'              => false,
            'voting_time_in_minutes' => 30,
        ]);

        $response = $this->actingAs($this->user)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                'selected_election_type'  => 'demo',
            ])
            ->get("/v/{$this->voterSlug->slug}/demo-code/create");

        // Must redirect to agreement (existing behavior preserved)
        $response->assertStatus(302);
        $location = $response->headers->get('Location', '');
        $this->assertStringContainsString(
            'agreement',
            $location,
            "Valid (non-expired) code should still redirect to agreement from demo-code/create. Got: {$location}"
        );
    }

    // ====================================================================
    // UNIT TESTS — direct controller method testing
    // ====================================================================

    /**
     * Unit test: hasVotingWindowExpired() correctly detects expired windows
     * via vote_pre_check() returning "code.create" when window expires
     */
    public function test_vote_pre_check_detects_expired_window_and_returns_code_create()
    {
        $this->app['config']['voting.two_codes_system'] = 0; // SIMPLE MODE

        $code = DemoCode::withoutGlobalScopes()->create([
            'user_id'                => $this->user->id,
            'election_id'            => $this->election->id,
            'organisation_id'        => $this->org->id,
            'code_to_open_voting_form'                  => 'EXPIRD',
            'code_to_open_voting_form_sent_at'          => now()->subMinutes(40),
            'has_code1_sent'         => true,
            'is_code_to_open_voting_form_usable'        => false,
            'code_to_open_voting_form_used_at'          => now()->subMinutes(35), // expired
            'code_to_save_vote_used_at'          => null,
            'can_vote_now'           => 1,
            'has_voted'              => false,
            'voting_time_in_minutes' => 30,
        ]);

        $controller = new DemoVoteController();
        $result = $controller->vote_pre_check($code);

        // vote_pre_check should detect expiry and return a redirect route
        $this->assertNotEmpty(
            $result,
            'vote_pre_check() should return a redirect route when voting window is expired'
        );
    }
}
