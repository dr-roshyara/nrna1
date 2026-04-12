<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Election;
use App\Models\Code;
use App\Models\User;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\VoterSlug;
use Illuminate\Support\Facades\Hash;

/**
 * Double Vote Prevention - Debug Test
 *
 * Simple test to understand what happens when has_voted=true
 */
class DoubleVoteDebugTest extends TestCase
{
    use RefreshDatabase;

    protected $realElection;
    protected $voter;
    protected $code;
    protected $voterSlug;

    public function setUp(): void
    {
        parent::setUp();

        $this->realElection = Election::factory()->real()->create([
            'name' => 'Test Election',
            'type' => 'real',
            'is_active' => true,
        ]);

        $this->voter = User::factory()->voter()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false,
        ]);

        // Create posts
        for ($i = 1; $i <= 3; $i++) {
            $post = Post::factory()->create([
                'election_id' => $this->realElection->id,
                'post_id' => "POST{$i}",
                'required_number' => 1,
                'position_order' => $i,
            ]);

            for ($j = 1; $j <= 3; $j++) {
                $candidateUser = User::factory()->create();
                Candidacy::factory()->create([
                    'election_id' => $this->realElection->id,
                    'post_id' => $post->post_id,
                    'candidacy_id' => "CAND{$i}{$j}",
                    'user_id' => $candidateUser->user_id,
                    'position_order' => $j,
                    'proposer_id' => "PROPOSER{$i}{$j}",
                    'supporter_id' => "SUPPORTER{$i}{$j}",
                ]);
            }
        }

        $this->code = Code::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'code1' => '123456',
            'code2' => '654321',
            'can_vote_now' => 1,
            'has_voted' => 0,
            'is_code1_usable' => 1,
            'session_name' => 'vote_data_' . $this->voter->id,
        ]);

        $this->voterSlug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
            'slug' => 'test-slug-' . uniqid(),
            'current_step' => 3,
        ]);
    }

    /**
     * Test: What happens when code.has_voted = true?
     */
    public function test_debug_double_vote_scenario()
    {
        $this->actingAs($this->voter);

        // BEFORE: Check code state
        echo "\n\n=== BEFORE SETTING has_voted ===\n";
        echo "Code ID: " . $this->code->id . "\n";
        echo "has_voted: " . $this->code->has_voted . "\n";
        echo "can_vote_now: " . $this->code->can_vote_now . "\n";
        echo "election_type: " . $this->realElection->type . "\n";

        // SET has_voted = true (simulate user already voted)
        $this->code->has_voted = true;
        $this->code->save();

        echo "\n=== AFTER SETTING has_voted = true ===\n";
        $freshCode = Code::find($this->code->id);
        echo "has_voted (fresh): " . $freshCode->has_voted . "\n";

        // Set up session
        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        echo "\n=== ATTEMPTING SECOND VOTE ===\n";
        echo "Session name: " . $sessionName . "\n";
        echo "Route: slug.vote.submit\n";
        echo "Slug: " . $this->voterSlug->slug . "\n";

        // Attempt second vote
        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        echo "\n=== RESPONSE DETAILS ===\n";
        echo "Status: " . $response->status() . "\n";
        echo "Location: " . ($response->headers->get('location') ? $response->headers->get('location') : 'NO REDIRECT') . "\n";

        // If 500 error, show exception details
        if ($response->status() === 500) {
            echo "\n!!! 500 ERROR DETECTED !!!\n";
            if ($response->exception) {
                echo "Exception: " . get_class($response->exception) . "\n";
                echo "Message: " . $response->exception->getMessage() . "\n";
                echo "File: " . $response->exception->getFile() . "\n";
                echo "Line: " . $response->exception->getLine() . "\n";
            }
        }

        // Check if has_voted flag is actually being checked
        echo "\n=== CODE STATE IN FIRST_SUBMISSION ===\n";
        echo "The VoteController::first_submission() should check:\n";
        echo "  if (\$election->type === 'real' && \$code && \$code->has_voted) { redirect... }\n";
        echo "Our values: type='real', has_voted=" . $freshCode->has_voted . "\n";

        if ($freshCode->has_voted && $this->realElection->type === 'real') {
            echo "✅ SHOULD REDIRECT (conditions met for double vote check)\n";
        } else {
            echo "❌ Should NOT redirect\n";
        }

        echo "\n";
    }

    /**
     * Test: Verify has_voted flag is actually preventing vote
     */
    public function test_has_voted_flag_effect()
    {
        $this->actingAs($this->voter);

        // Check: Is has_voted being checked BEFORE other validations?
        $this->code->has_voted = true;
        $this->code->save();

        $sessionName = Hash::make($this->code->code2);
        session()->put($sessionName, [
            'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33']
        ]);

        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'national_selected_candidates' => ['CAND11', 'CAND22', 'CAND33'],
                'agree_button' => true,
            ]
        );

        // Debug output
        echo "\n\n=== HAS_VOTED EFFECT TEST ===\n";
        echo "Status code: " . $response->status() . "\n";

        if ($response->status() === 302) {
            $location = $response->headers->get('location');
            echo "Redirects to: " . $location . "\n";

            if (strpos($location, 'dashboard') !== false || strpos($location, 'code/create') !== false) {
                echo "✅ CORRECT: Redirects away from voting (double vote prevented)\n";
            } else if (strpos($location, 'vote/verify') !== false) {
                echo "❌ WRONG: Still allows voting (double vote NOT prevented)\n";
            }
        } else if ($response->status() === 500) {
            echo "❌ ERROR: Returns 500 error\n";
        } else {
            echo "? Unexpected status: " . $response->status() . "\n";
        }

        echo "\n";
    }
}
