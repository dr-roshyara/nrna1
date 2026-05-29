<?php

namespace Tests\Unit\Helpers;

use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use ReflectionClass;
use Tests\TestCase;

/**
 * ValidateVotingIpWithResponseReturnTest
 *
 * H.2 — Sovereignty Vocabulary Cleanup.
 *
 * Verifies that validateVotingIpWithResponse() no longer exposes
 * procedural sovereignty fields (current_ip, registered_ip) in its
 * return values or Inertia props. Only constitutional evidence and
 * operational metadata should reach the frontend.
 *
 * CONSTITUTIONAL INVARIANTS:
 * - Projection layer must NOT carry procedural sovereignty semantics
 * - current_ip is hidden authority vocabulary → must NOT be in return/props
 * - registered_ip is procedural sovereignty residue → must NOT be in return/props
 * - user_name and denial_type are constitutional evidence → MUST be preserved
 */
class ValidateVotingIpWithResponseReturnTest extends TestCase
{
    protected function tearDown(): void
    {
        // Clear Auth mock expectations after each test
        Auth::clearResolvedInstances();
        parent::tearDown();
    }

    /** @test */
    public function success_array_has_valid_when_no_voting_ip(): void
    {
        $user = (object) [
            'voting_ip' => null,
            'name' => 'Test User',
            'id' => 1,
        ];
        Auth::shouldReceive('user')->andReturn($user);

        $result = validateVotingIpWithResponse();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('valid', $result);
        $this->assertTrue($result['valid']);
    }

    /** @test */
    public function success_array_has_skip_reason_when_no_voting_ip(): void
    {
        $user = (object) [
            'voting_ip' => null,
            'name' => 'Test User',
            'id' => 1,
        ];
        Auth::shouldReceive('user')->andReturn($user);

        $result = validateVotingIpWithResponse();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('skip_reason', $result);
        $this->assertSame('no_voting_ip_set', $result['skip_reason']);
    }

    /** @test */
    public function success_array_does_not_contain_current_ip_when_no_voting_ip(): void
    {
        $user = (object) [
            'voting_ip' => null,
            'name' => 'Test User',
            'id' => 1,
        ];
        Auth::shouldReceive('user')->andReturn($user);

        $result = validateVotingIpWithResponse();

        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('current_ip', $result);
    }

    /** @test */
    public function success_array_does_not_contain_registered_ip_when_no_voting_ip(): void
    {
        $user = (object) [
            'voting_ip' => null,
            'name' => 'Test User',
            'id' => 1,
        ];
        Auth::shouldReceive('user')->andReturn($user);

        $result = validateVotingIpWithResponse();

        $this->assertIsArray($result);
        $this->assertArrayNotHasKey('registered_ip', $result);
    }

    /** @test */
    public function success_array_does_not_contain_ip_fields_when_ip_matches(): void
    {
        $user = (object) [
            'voting_ip' => '192.168.1.100',
            'name' => 'Test User',
            'id' => 1,
        ];
        Auth::shouldReceive('user')->andReturn($user);

        // Set request IP to match the user's voting_ip
        request()->server->set('REMOTE_ADDR', '192.168.1.100');

        $result = validateVotingIpWithResponse();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('valid', $result);
        $this->assertTrue($result['valid']);
        $this->assertArrayNotHasKey('current_ip', $result);
        $this->assertArrayNotHasKey('registered_ip', $result);
    }

    /** @test */
    public function denial_inertia_does_not_contain_ip_props(): void
    {
        $user = (object) [
            'voting_ip' => '192.168.1.100',
            'name' => 'Test User',
            'id' => 1,
        ];
        Auth::shouldReceive('user')->andReturn($user);

        // Set request IP to a DIFFERENT value to trigger denial
        request()->server->set('REMOTE_ADDR', '10.0.0.50');

        $result = validateVotingIpWithResponse();

        $this->assertInstanceOf(Response::class, $result);

        $props = $this->getInertiaProps($result);

        $this->assertArrayNotHasKey('current_ip', $props);
        $this->assertArrayNotHasKey('registered_ip', $props);
    }

    /** @test */
    public function denial_inertia_contains_constitutional_evidence(): void
    {
        $user = (object) [
            'voting_ip' => '192.168.1.100',
            'name' => 'Test User',
            'id' => 1,
        ];
        Auth::shouldReceive('user')->andReturn($user);

        request()->server->set('REMOTE_ADDR', '10.0.0.50');

        $result = validateVotingIpWithResponse();

        $this->assertInstanceOf(Response::class, $result);

        $props = $this->getInertiaProps($result);

        $this->assertArrayHasKey('user_name', $props);
        $this->assertArrayHasKey('denial_type', $props);
        $this->assertArrayHasKey('error_message_english', $props);
    }

    /**
     * Extract props from an Inertia Response using reflection.
     */
    private function getInertiaProps(Response $response): array
    {
        $reflection = new ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);

        return $propsProperty->getValue($response);
    }
}
