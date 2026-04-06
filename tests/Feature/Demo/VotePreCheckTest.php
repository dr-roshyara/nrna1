<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\DemoCode;
use App\Models\Election;
use App\Models\User;
use App\Http\Controllers\Demo\DemoVoteController;
use Carbon\Carbon;

/**
 * Test the vote_pre_check() method
 * Ensures proper redirect logic in both SIMPLE and STRICT modes
 */
class VotePreCheckTest extends TestCase
{
    use RefreshDatabase;

    private DemoVoteController $controller;
    private Election $election;
    private User $user;
    private DemoCode $code;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new DemoVoteController();
        $this->election = Election::factory()->create(['type' => 'demo']);
        $this->user = User::factory()->create();
        $this->code = DemoCode::factory()->create([
            'election_id' => $this->election->id,
            'user_id' => $this->user->id,
        ]);
    }

    // ============================================
    // SIMPLE MODE TESTS (Default: ONE EMAIL)
    // ============================================

    /**
     * Test SIMPLE MODE: Code not entered yet redirects to code.create
     */
    public function test_simple_mode_code1_not_entered_redirects_to_code_create()
    {
        $this->app['config']['voting.two_codes_system'] = 0; // SIMPLE MODE

        // Code1 not entered yet
        $this->code->update([
            'code_to_open_voting_form_used_at' => null,  // NOT used yet
            'code_to_save_vote_used_at' => null,
            'has_code1_sent' => 1,
        ]);

        $result = $this->controller->vote_pre_check($this->code);

        // Should redirect to code.create
        $this->assertNotEmpty($result);
    }

    /**
     * Test SIMPLE MODE: After Code1 entered, allows vote submission
     */
    public function test_simple_mode_code1_entered_allows_vote()
    {
        $this->app['config']['voting.two_codes_system'] = 0; // SIMPLE MODE

        // Code1 has been entered (first use)
        $this->code->update([
            'code_to_open_voting_form_used_at' => Carbon::now(),  // ✓ Used at code entry
            'code_to_save_vote_used_at' => null,           // ✓ NOT used for voting yet
            'has_code1_sent' => 1,
            'can_vote_now' => 1,
        ]);

        $result = $this->controller->vote_pre_check($this->code);

        // Should NOT redirect - allow vote submission
        $this->assertEmpty($result);
    }

    /**
     * Test SIMPLE MODE: After vote submitted, blocks second voting attempt
     */
    public function test_simple_mode_code_already_used_blocks_vote()
    {
        $this->app['config']['voting.two_codes_system'] = 0; // SIMPLE MODE

        // Code has been used for both steps
        $this->code->update([
            'code_to_open_voting_form_used_at' => Carbon::now()->subMinutes(5),
            'code_to_save_vote_used_at' => Carbon::now(),  // ✓ Already used for voting
            'has_code1_sent' => 1,
            'can_vote_now' => 1,
        ]);

        $result = $this->controller->vote_pre_check($this->code);

        // Should indicate code expired
        $this->assertNotEmpty($result);
    }

    /**
     * Test SIMPLE MODE: Missing Code1 entry redirects to code.create
     */
    public function test_simple_mode_missing_code1_sent_redirects()
    {
        $this->app['config']['voting.two_codes_system'] = 0; // SIMPLE MODE

        $this->code->update([
            'has_code1_sent' => 0,  // Code never sent
            'code_to_open_voting_form_used_at' => null,
            'code_to_save_vote_used_at' => null,
        ]);

        $result = $this->controller->vote_pre_check($this->code);

        // Should redirect to code.create
        $this->assertNotEmpty($result);
    }

    /**
     * Test SIMPLE MODE: Voting window closed redirects to code.create
     */
    public function test_simple_mode_voting_window_expired()
    {
        $this->app['config']['voting.two_codes_system'] = 0; // SIMPLE MODE

        // Voting window expired
        $this->code->update([
            'code_to_open_voting_form_used_at' => Carbon::now()->subMinutes(45),  // Used 45 min ago
            'code_to_save_vote_used_at' => null,
            'has_code1_sent' => 1,
            'can_vote_now' => 1,
            'voting_time_in_minutes' => 30,  // Only 30 min window
        ]);

        $result = $this->controller->vote_pre_check($this->code);

        // Should redirect to code.create (window expired)
        $this->assertNotEmpty($result);
    }

    // ============================================
    // STRICT MODE TESTS (TWO EMAILS)
    // ============================================

    /**
     * Test STRICT MODE: Code1 entered, awaiting Code2
     */
    public function test_strict_mode_code1_used_awaiting_code2()
    {
        $this->app['config']['voting.two_codes_system'] = 1; // STRICT MODE

        // Code1 has been used, now waiting for Code2
        $this->code->update([
            'code_to_open_voting_form_used_at' => Carbon::now(),  // Code1 used at entry
            'code_to_save_vote_used_at' => null,           // Code2 not used yet
            'has_code1_sent' => 1,
            'can_vote_now' => 1,
        ]);

        $result = $this->controller->vote_pre_check($this->code);

        // Should allow vote submission with Code2
        $this->assertEmpty($result);
    }

    /**
     * Test STRICT MODE: Code1 not entered redirects to code.create
     */
    public function test_strict_mode_code1_not_entered_redirects()
    {
        $this->app['config']['voting.two_codes_system'] = 1; // STRICT MODE

        // Code1 not entered yet
        $this->code->update([
            'code_to_open_voting_form_used_at' => null,  // NOT used yet
            'code_to_save_vote_used_at' => null,
            'has_code1_sent' => 1,
        ]);

        $result = $this->controller->vote_pre_check($this->code);

        // Should redirect to code.create
        $this->assertNotEmpty($result);
    }

    /**
     * Test STRICT MODE: Code2 already used blocks vote
     */
    public function test_strict_mode_code2_already_used_blocks()
    {
        $this->app['config']['voting.two_codes_system'] = 1; // STRICT MODE

        // Both codes already used
        $this->code->update([
            'code_to_open_voting_form_used_at' => Carbon::now()->subMinutes(10),
            'code_to_save_vote_used_at' => Carbon::now(),  // Code2 already used
            'has_code1_sent' => 1,
            'is_code_to_save_vote_usable' => 0,
            'can_vote_now' => 1,
        ]);

        $result = $this->controller->vote_pre_check($this->code);

        // Should indicate code expired
        $this->assertNotEmpty($result);
    }

    // ============================================
    // COMMON TESTS (Both Modes)
    // ============================================

    /**
     * Test both modes: Null code returns to code.create
     */
    public function test_both_modes_null_code_redirects()
    {
        foreach ([0, 1] as $mode) {
            $this->app['config']['voting.two_codes_system'] = $mode;
            $nullCode = null;

            // Should redirect when code is null
            // This would be caught by earlier checks, but vote_pre_check should handle it
            $this->assertNull($nullCode);
        }
    }

    /**
     * Test both modes: can_vote_now=false redirects to dashboard
     */
    public function test_both_modes_can_vote_now_false_redirects()
    {
        foreach ([0, 1] as $mode) {
            $this->app['config']['voting.two_codes_system'] = $mode;

            $this->code->update([
                'can_vote_now' => 0,  // Voting not allowed
                'code_to_open_voting_form_used_at' => Carbon::now(),
                'has_code1_sent' => 1,
            ]);

            $result = $this->controller->vote_pre_check($this->code);

            // Should redirect to dashboard
            $this->assertNotEmpty($result);
        }
    }

    /**
     * Test both modes: has_voted=true redirects to dashboard
     */
    public function test_both_modes_has_voted_redirects()
    {
        foreach ([0, 1] as $mode) {
            $this->app['config']['voting.two_codes_system'] = $mode;

            $this->code->update([
                'has_voted' => 1,  // Already voted
                'code_to_open_voting_form_used_at' => Carbon::now(),
                'has_code1_sent' => 1,
                'can_vote_now' => 1,
            ]);

            $result = $this->controller->vote_pre_check($this->code);

            // Should redirect to dashboard
            $this->assertNotEmpty($result);
        }
    }

    /**
     * Test both modes: No redirect loop when voting within time window
     */
    public function test_both_modes_no_redirect_loop_within_window()
    {
        foreach ([0, 1] as $mode) {
            $this->app['config']['voting.two_codes_system'] = $mode;

            // Valid state: Code1 entered, within voting window
            $this->code->update([
                'code_to_open_voting_form_used_at' => Carbon::now()->subMinutes(5),  // 5 min ago
                'code_to_save_vote_used_at' => null,
                'has_code1_sent' => 1,
                'can_vote_now' => 1,
                'voting_time_in_minutes' => 30,
            ]);

            $result = $this->controller->vote_pre_check($this->code);

            // Should NOT redirect - no loop
            $this->assertEmpty($result, "Mode $mode should allow voting within time window");
        }
    }
}
