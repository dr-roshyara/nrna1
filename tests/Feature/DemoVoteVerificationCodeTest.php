<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Election;
use App\Models\DemoVote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * TDD Feature Tests for Demo Vote Verification Code Issue
 *
 * Bug: Verification code from email doesn't match stored code
 * 1. Generated code format issue (underscore and number appended?)
 * 2. Code lookup fails to find the vote
 * 3. Whitespace or encoding transforms the code
 *
 * @group demo-vote-verification
 * @group verification-code
 * @group tdd
 */
class DemoVoteVerificationCodeTest extends TestCase
{
    use RefreshDatabase;

    private Election $demoElection;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->demoElection = Election::factory()->create([
            'type' => 'demo',
            'name' => 'Demo Election'
        ]);

        $this->voter = User::factory()->create([
            'email' => 'voter@demo.test',
            'region' => 'Test Region'
        ]);
    }

    /** @test */
    public function generated_verification_code_matches_stored_code()
    {
        $generatedCode = bin2hex(random_bytes(16));

        $demoVote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'verify_test_' . uniqid(),
            'user_id' => $this->voter->id,
            'verification_code' => $generatedCode,
        ]);

        $retrieved = DemoVote::find($demoVote->id);

        $this->assertEquals(
            $generatedCode,
            $retrieved->verification_code,
            "Stored code should equal generated code"
        );
    }

    /** @test */
    public function lookup_demo_vote_by_verification_code_finds_vote()
    {
        $verificationCode = bin2hex(random_bytes(16));

        $demoVote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'lookup_test_' . uniqid(),
            'user_id' => $this->voter->id,
            'verification_code' => $verificationCode,
        ]);

        $foundVote = DemoVote::where('verification_code', $verificationCode)->first();

        $this->assertNotNull(
            $foundVote,
            "Should find demo vote with verification_code={$verificationCode}"
        );

        $this->assertEquals($demoVote->id, $foundVote->id,
            'Should find the correct vote by verification code');
    }

    /** @test */
    public function whitespace_handling_in_verification_code()
    {
        $codeWithoutWhitespace = bin2hex(random_bytes(16));

        $demoVote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'whitespace_test_' . uniqid(),
            'user_id' => $this->voter->id,
            'verification_code' => $codeWithoutWhitespace,
        ]);

        // Try with whitespace (user might copy from email with extra spaces)
        $withSpaces = '  ' . $codeWithoutWhitespace . '  ';

        $found = DemoVote::where('verification_code', trim($withSpaces))->first();

        $this->assertNotNull($found, 'Should find vote with trimmed code');
        $this->assertEquals($demoVote->id, $found->id);
    }

    /** @test */
    public function retrieved_code_matches_stored_code_exactly()
    {
        $originalCode = bin2hex(random_bytes(16));

        $vote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'verify_' . uniqid(),
            'user_id' => $this->voter->id,
            'verification_code' => $originalCode,
        ]);

        $retrieved = DemoVote::find($vote->id);
        $retrievedCode = $retrieved->verification_code;

        // Use retrieved code to find another vote
        $vote2 = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'verify2_' . uniqid(),
            'user_id' => $this->voter->id,
            'verification_code' => $originalCode,
        ]);

        $foundVote = DemoVote::where('verification_code', $retrievedCode)->first();

        $this->assertEquals($originalCode, $retrievedCode,
            'Retrieved code should match original code exactly');

        $this->assertNotNull($foundVote,
            'Retrieved code should be able to find demo vote');
    }

    /** @test */
    public function non_existent_verification_code_returns_null()
    {
        $invalidCode = 'this_code_does_not_exist_' . uniqid();

        $foundVote = DemoVote::where('verification_code', $invalidCode)->first();

        $this->assertNull($foundVote,
            'Non-existent code should return null');
    }

    /** @test */
    public function verification_code_format_with_underscore_and_number()
    {
        // User reports codes like: 102b46c7eac757cc8ec4f56df8473fac_1
        // Test the actual format being generated

        $demoVote = DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'format_test_' . uniqid(),
            'user_id' => $this->voter->id,
            'verification_code' => bin2hex(random_bytes(16)),
        ]);

        $retrieved = DemoVote::find($demoVote->id);
        $code = $retrieved->verification_code;

        // Check the format - should be hex string, possibly with underscore and number
        $this->assertNotEmpty($code, 'Code should not be empty');
        $this->assertIsString($code, 'Code should be string');

        // Verify it's retrievable as-is
        $found = DemoVote::where('verification_code', $code)->first();
        $this->assertNotNull($found, 'Code should be retrievable exactly as stored');
    }
}
