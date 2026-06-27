<?php

namespace Tests\Feature\Vote;

use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use ReflectionClass;
use Tests\TestCase;

/**
 * VoteControllerPropTest
 *
 * H.2 — Sovereignty Vocabulary Cleanup.
 *
 * Verifies that Inertia props flowing through VoteController call sites
 * (which delegate to validateVotingIpWithResponse()) contain no procedural
 * sovereignty IP fields. This is the controller-level integration boundary
 * complement to the unit-level helper tests.
 *
 * CONSTITUTIONAL INVARIANTS:
 * - Projection layer must NOT carry procedural sovereignty semantics
 * - current_ip must NOT be in props
 * - registered_ip must NOT be in props
 * - Component name must be Vote/VoteDenied
 */
class VoteControllerPropTest extends TestCase
{
    protected function tearDown(): void
    {
        Auth::clearResolvedInstances();
        parent::tearDown();
    }

    /** @test */
    public function denial_response_uses_vote_denied_component(): void
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

        $reflection = new ReflectionClass($result);
        $componentProperty = $reflection->getProperty('component');
        $componentProperty->setAccessible(true);
        $component = $componentProperty->getValue($result);

        $this->assertSame('Vote/VoteDenied', $component);
    }

    /** @test */
    public function denial_response_props_contain_no_ip_fields(): void
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

        $reflection = new ReflectionClass($result);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $props = $propsProperty->getValue($result);

        $this->assertArrayNotHasKey('current_ip', $props);
        $this->assertArrayNotHasKey('registered_ip', $props);
    }
}
