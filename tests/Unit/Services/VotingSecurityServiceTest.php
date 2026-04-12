<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Code;
use App\Services\VotingSecurityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class VotingSecurityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'is_voter' => 1,
            'can_vote' => 1,
            'user_ip' => '192.168.1.100',
            'voting_ip' => null,
        ]);
    }

    /** @test */
    public function it_detects_when_ip_control_is_enabled()
    {
        Config::set('voting_security.control_ip_address', 1);

        $this->assertTrue(VotingSecurityService::isIpControlEnabled());
    }

    /** @test */
    public function it_detects_when_ip_control_is_disabled()
    {
        Config::set('voting_security.control_ip_address', 0);

        $this->assertFalse(VotingSecurityService::isIpControlEnabled());
    }

    /** @test */
    public function it_allows_voting_when_ip_control_is_disabled()
    {
        Config::set('voting_security.control_ip_address', 0);

        $this->user->voting_ip = '192.168.1.100';
        $this->user->save();

        $result = VotingSecurityService::detectIpChange($this->user, '10.0.0.50');

        $this->assertTrue($result['can_vote']);
        $this->assertFalse($result['is_violation']);
    }

    /** @test */
    public function it_allows_voting_when_user_has_no_ip_restriction()
    {
        Config::set('voting_security.control_ip_address', 1);

        $this->user->voting_ip = null;
        $this->user->save();

        $result = VotingSecurityService::detectIpChange($this->user, '10.0.0.50');

        $this->assertTrue($result['can_vote']);
        $this->assertFalse($result['is_violation']);
        $this->assertFalse($result['user_has_ip_restriction']);
    }

    /** @test */
    public function it_allows_voting_when_ip_matches()
    {
        Config::set('voting_security.control_ip_address', 1);

        $this->user->voting_ip = '192.168.1.100';
        $this->user->save();

        $result = VotingSecurityService::detectIpChange($this->user, '192.168.1.100');

        $this->assertTrue($result['can_vote']);
        $this->assertFalse($result['is_violation']);
        $this->assertFalse($result['ip_changed']);
    }

    /** @test */
    public function it_blocks_voting_when_ip_does_not_match()
    {
        Config::set('voting_security.control_ip_address', 1);

        $this->user->voting_ip = '192.168.1.100';
        $this->user->save();

        $result = VotingSecurityService::detectIpChange($this->user, '192.168.1.200');

        $this->assertFalse($result['can_vote']);
        $this->assertTrue($result['is_violation']);
        $this->assertTrue($result['ip_changed']);
        $this->assertStringContainsString('mismatch', $result['error_message']);
    }

    /** @test */
    public function it_checks_if_user_can_vote_from_specific_ip()
    {
        Config::set('voting_security.control_ip_address', 1);

        $this->user->voting_ip = '192.168.1.100';
        $this->user->save();

        // Can vote from registered IP
        $this->assertTrue(VotingSecurityService::canVoteFromIp($this->user, '192.168.1.100'));

        // Cannot vote from different IP
        $this->assertFalse(VotingSecurityService::canVoteFromIp($this->user, '192.168.1.200'));
    }

    /** @test */
    public function it_provides_approval_config_when_control_enabled()
    {
        Config::set('voting_security.control_ip_address', 1);

        $this->user->user_ip = '192.168.1.100';
        $this->user->save();

        $config = VotingSecurityService::getApprovalConfig($this->user);

        $this->assertTrue($config['should_set_voting_ip']);
        $this->assertEquals('192.168.1.100', $config['voting_ip_value']);
        $this->assertTrue($config['ip_control_enabled']);
    }

    /** @test */
    public function it_provides_approval_config_when_control_disabled()
    {
        Config::set('voting_security.control_ip_address', 0);

        $this->user->user_ip = '192.168.1.100';
        $this->user->save();

        $config = VotingSecurityService::getApprovalConfig($this->user);

        $this->assertFalse($config['should_set_voting_ip']);
        $this->assertNull($config['voting_ip_value']);
        $this->assertFalse($config['ip_control_enabled']);
    }

    /** @test */
    public function it_generates_complete_ip_audit_trail()
    {
        $this->user->user_ip = '192.168.1.100';
        $this->user->voting_ip = '192.168.1.100';
        $this->user->save();

        Code::factory()->create([
            'user_id' => $this->user->id,
            'client_ip' => '192.168.1.100',
        ]);

        $audit = VotingSecurityService::getIpAuditTrail($this->user);

        $this->assertEquals($this->user->id, $audit['user_id']);
        $this->assertEquals('192.168.1.100', $audit['user_ip']);
        $this->assertEquals('192.168.1.100', $audit['voting_ip']);
        $this->assertEquals('192.168.1.100', $audit['code_client_ip']);
        $this->assertArrayHasKey('current_request_ip', $audit);
        $this->assertArrayHasKey('ip_match_status', $audit);
        $this->assertArrayHasKey('can_vote_from_current_ip', $audit);
    }

    /** @test */
    public function it_validates_complete_voter_eligibility()
    {
        Config::set('voting_security.control_ip_address', 1);

        $this->user->voting_ip = '192.168.1.100';
        $this->user->save();

        // Eligible from matching IP
        $result = VotingSecurityService::validateVoterEligibility($this->user, '192.168.1.100');
        $this->assertTrue($result['eligible']);
        $this->assertEmpty($result['reasons']);

        // Not eligible from different IP
        $result = VotingSecurityService::validateVoterEligibility($this->user, '192.168.1.200');
        $this->assertFalse($result['eligible']);
        $this->assertNotEmpty($result['reasons']);
        $this->assertStringContainsString('IP address mismatch', $result['reasons'][0]);
    }

    /** @test */
    public function it_validates_voter_eligibility_checks_basic_requirements()
    {
        Config::set('voting_security.control_ip_address', 0);

        // Not a voter
        $nonVoter = User::factory()->create(['is_voter' => 0, 'can_vote' => 0]);
        $result = VotingSecurityService::validateVoterEligibility($nonVoter, '192.168.1.100');

        $this->assertFalse($result['eligible']);
        $this->assertContains('User is not a registered voter', $result['reasons']);

        // Not approved
        $unapprovedVoter = User::factory()->create(['is_voter' => 1, 'can_vote' => 0]);
        $result = VotingSecurityService::validateVoterEligibility($unapprovedVoter, '192.168.1.100');

        $this->assertFalse($result['eligible']);
        $this->assertContains('User is not approved to vote', $result['reasons']);
    }

    /** @test */
    public function it_provides_system_status()
    {
        Config::set('voting_security.control_ip_address', 1);
        Config::set('voting_security.ip_validation_mode', 'strict');
        Config::set('voting_security.ip_mismatch_action', 'block');

        $status = VotingSecurityService::getSystemStatus();

        $this->assertTrue($status['ip_control_enabled']);
        $this->assertEquals('strict', $status['validation_mode']);
        $this->assertEquals('block', $status['mismatch_action']);
        $this->assertStringContainsString('ENABLED', $status['status_message']);
    }

    /** @test */
    public function it_returns_correct_ip_match_status_when_control_disabled()
    {
        Config::set('voting_security.control_ip_address', 0);

        $this->user->voting_ip = '192.168.1.100';
        $this->user->save();

        $audit = VotingSecurityService::getIpAuditTrail($this->user);

        $this->assertEquals('IP_CONTROL_DISABLED', $audit['ip_match_status']);
    }

    /** @test */
    public function it_returns_correct_ip_match_status_when_no_restriction()
    {
        Config::set('voting_security.control_ip_address', 1);

        $this->user->voting_ip = null;
        $this->user->save();

        $audit = VotingSecurityService::getIpAuditTrail($this->user);

        $this->assertEquals('NO_RESTRICTION', $audit['ip_match_status']);
    }

    /** @test */
    public function it_returns_correct_ip_match_status_when_match()
    {
        Config::set('voting_security.control_ip_address', 1);

        $this->user->voting_ip = request()->ip();
        $this->user->save();

        $audit = VotingSecurityService::getIpAuditTrail($this->user);

        $this->assertContains($audit['ip_match_status'], ['MATCH', 'NO_RESTRICTION']);
    }
}
