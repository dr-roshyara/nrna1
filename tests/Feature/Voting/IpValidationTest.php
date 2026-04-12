<?php

namespace Tests\Feature\Voting;

use Tests\TestCase;
use App\Models\User;
use App\Models\Code;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

class IpValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $voter;
    protected $committeeUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a committee member
        $this->committeeUser = User::factory()->create([
            'is_committee_member' => 1,
            'can_vote' => 0,
        ]);

        // Create a voter
        $this->voter = User::factory()->create([
            'is_voter' => 1,
            'can_vote' => 0,
            'has_voted' => 0,
            'user_ip' => '192.168.1.100',
            'voting_ip' => null,
        ]);
    }

    /** @test */
    public function voter_with_ip_restriction_can_vote_from_registered_ip()
    {
        // Enable IP control
        Config::set('voting_security.control_ip_address', 1);

        // Approve voter (this will set voting_ip)
        $this->voter->approveForVoting($this->committeeUser);
        $this->voter->refresh();

        // Create voter slug
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'is_active' => true,
            'expires_at' => now()->addHours(2),
        ]);

        // Create code
        Code::factory()->create([
            'user_id' => $this->voter->id,
            'code1' => '123456',
            'is_code1_usable' => 1,
            'has_agreed_to_vote' => 1,
            'client_ip' => '192.168.1.100',
        ]);

        // Simulate request from same IP
        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        // Should be allowed (200 OK or redirect to vote page)
        $this->assertNotEquals(302, $response->status());
        $this->assertFalse($response->baseResponse->isRedirect());
    }

    /** @test */
    public function voter_with_ip_restriction_cannot_vote_from_different_ip()
    {
        // Enable IP control
        Config::set('voting_security.control_ip_address', 1);

        // Approve voter with IP restriction
        $this->voter->voting_ip = '192.168.1.100';
        $this->voter->can_vote = 1;
        $this->voter->approvedBy = $this->committeeUser->name;
        $this->voter->save();

        // Create voter slug
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'is_active' => true,
            'expires_at' => now()->addHours(2),
        ]);

        // Create code
        Code::factory()->create([
            'user_id' => $this->voter->id,
            'code1' => '123456',
            'is_code1_usable' => 1,
            'has_agreed_to_vote' => 1,
            'client_ip' => '192.168.1.200',
        ]);

        // Simulate request from different IP
        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '192.168.1.200'])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        // Should be blocked with error
        $response->assertRedirect();
        $response->assertSessionHasErrors('ip_mismatch');
    }

    /** @test */
    public function voter_without_ip_restriction_can_vote_from_any_ip()
    {
        // Enable IP control globally
        Config::set('voting_security.control_ip_address', 1);

        // Approve voter WITHOUT IP restriction (voting_ip = null)
        $this->voter->voting_ip = null;
        $this->voter->can_vote = 1;
        $this->voter->approvedBy = $this->committeeUser->name;
        $this->voter->save();

        // Create voter slug
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'is_active' => true,
            'expires_at' => now()->addHours(2),
        ]);

        // Create code
        Code::factory()->create([
            'user_id' => $this->voter->id,
            'code1' => '123456',
            'is_code1_usable' => 1,
            'has_agreed_to_vote' => 1,
            'client_ip' => '192.168.1.200',
        ]);

        // Simulate request from any IP
        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '10.0.0.50'])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        // Should be allowed
        $this->assertNotEquals(302, $response->status());
    }

    /** @test */
    public function ip_control_disabled_allows_voting_from_any_ip()
    {
        // Disable IP control globally
        Config::set('voting_security.control_ip_address', 0);

        // Approve voter (even with IP set, it should be bypassed)
        $this->voter->voting_ip = '192.168.1.100';
        $this->voter->can_vote = 1;
        $this->voter->approvedBy = $this->committeeUser->name;
        $this->voter->save();

        // Create voter slug
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'is_active' => true,
            'expires_at' => now()->addHours(2),
        ]);

        // Create code
        Code::factory()->create([
            'user_id' => $this->voter->id,
            'code1' => '123456',
            'is_code1_usable' => 1,
            'has_agreed_to_vote' => 1,
            'client_ip' => '192.168.1.200',
        ]);

        // Simulate request from different IP
        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '10.0.0.99'])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        // Should be allowed (IP control is disabled)
        $this->assertNotEquals(302, $response->status());
    }

    /** @test */
    public function voter_approval_sets_ip_restriction_when_control_enabled()
    {
        // Enable IP control
        Config::set('voting_security.control_ip_address', 1);

        $this->voter->user_ip = '192.168.1.100';
        $this->voter->save();

        // Approve voter
        $this->voter->approveForVoting($this->committeeUser);
        $this->voter->refresh();

        // Check that voting_ip was set to user_ip
        $this->assertEquals('192.168.1.100', $this->voter->voting_ip);
        $this->assertTrue($this->voter->can_vote);
    }

    /** @test */
    public function voter_approval_does_not_set_ip_restriction_when_control_disabled()
    {
        // Disable IP control
        Config::set('voting_security.control_ip_address', 0);

        $this->voter->user_ip = '192.168.1.100';
        $this->voter->save();

        // Approve voter
        $this->voter->approveForVoting($this->committeeUser);
        $this->voter->refresh();

        // Check that voting_ip is NULL
        $this->assertNull($this->voter->voting_ip);
        $this->assertTrue($this->voter->can_vote);
    }

    /** @test */
    public function ip_mismatch_error_message_is_bilingual()
    {
        // Enable IP control
        Config::set('voting_security.control_ip_address', 1);

        // Approve voter with IP restriction
        $this->voter->voting_ip = '192.168.1.100';
        $this->voter->can_vote = 1;
        $this->voter->approvedBy = $this->committeeUser->name;
        $this->voter->save();

        // Create voter slug
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'is_active' => true,
            'expires_at' => now()->addHours(2),
        ]);

        // Create code
        Code::factory()->create([
            'user_id' => $this->voter->id,
            'code1' => '123456',
            'is_code1_usable' => 1,
            'has_agreed_to_vote' => 1,
            'client_ip' => '192.168.1.200',
        ]);

        // Simulate request from different IP
        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '192.168.1.200'])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        // Check error message contains both English and Nepali
        $errorMessage = session('errors')->get('ip_mismatch')[0] ?? '';

        $this->assertStringContainsString('registered IP address', $errorMessage);
        $this->assertStringContainsString('दर्ता गरिएको IP', $errorMessage); // Nepali text
        $this->assertStringContainsString('192.168.1.100', $errorMessage); // Registered IP
        $this->assertStringContainsString('192.168.1.200', $errorMessage); // Current IP
    }

    /** @test */
    public function multiple_voting_attempts_from_different_ips_are_logged()
    {
        // Enable IP control
        Config::set('voting_security.control_ip_address', 1);
        Config::set('voting_security.logging.enabled', true);
        Config::set('voting_security.logging.log_mismatches', true);

        // Approve voter with IP restriction
        $this->voter->voting_ip = '192.168.1.100';
        $this->voter->can_vote = 1;
        $this->voter->approvedBy = $this->committeeUser->name;
        $this->voter->save();

        // Create voter slug
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'is_active' => true,
            'expires_at' => now()->addHours(2),
        ]);

        // Create code
        Code::factory()->create([
            'user_id' => $this->voter->id,
            'code1' => '123456',
            'is_code1_usable' => 1,
            'has_agreed_to_vote' => 1,
            'client_ip' => '192.168.1.200',
        ]);

        \Log::spy();

        // Attempt from wrong IP
        $response = $this->actingAs($this->voter)
            ->withServerVariables(['REMOTE_ADDR' => '192.168.1.200'])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        // Verify logging was called
        \Log::shouldHaveReceived('warning')
            ->once()
            ->withArgs(function ($message, $context) {
                return str_contains($message, 'IP mismatch detected') &&
                       $context['user_id'] === $this->voter->id &&
                       $context['registered_ip'] === '192.168.1.100' &&
                       $context['current_ip'] === '192.168.1.200';
            });
    }
}
