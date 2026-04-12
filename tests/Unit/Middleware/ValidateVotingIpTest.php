<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\ValidateVotingIp;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Mockery;

class ValidateVotingIpTest extends TestCase
{
    protected $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new ValidateVotingIp();
    }

    /** @test */
    public function it_allows_request_when_ip_control_is_disabled()
    {
        Config::set('voting_security.control_ip_address', 0);

        $user = User::factory()->make([
            'voting_ip' => '192.168.1.100',
        ]);

        Auth::shouldReceive('user')->andReturn($user);

        $request = Request::create('/vote/create', 'GET');
        $request->server->set('REMOTE_ADDR', '10.0.0.50');

        $next = function ($req) {
            return response('Next called', 200);
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('Next called', $response->getContent());
    }

    /** @test */
    public function it_allows_request_when_user_has_no_ip_restriction()
    {
        Config::set('voting_security.control_ip_address', 1);

        $user = User::factory()->make([
            'voting_ip' => null, // No IP restriction
        ]);

        Auth::shouldReceive('user')->andReturn($user);

        $request = Request::create('/vote/create', 'GET');
        $request->server->set('REMOTE_ADDR', '10.0.0.50');

        $next = function ($req) {
            return response('Next called', 200);
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function it_allows_request_when_ip_matches()
    {
        Config::set('voting_security.control_ip_address', 1);

        $user = User::factory()->make([
            'id' => 1,
            'name' => 'Test User',
            'voting_ip' => '192.168.1.100',
        ]);

        Auth::shouldReceive('user')->andReturn($user);

        $request = Request::create('/vote/create', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        $next = function ($req) {
            return response('Next called', 200);
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function it_blocks_request_when_ip_does_not_match()
    {
        Config::set('voting_security.control_ip_address', 1);
        Config::set('voting_security.ip_mismatch_action', 'block');

        $user = User::factory()->make([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'voting_ip' => '192.168.1.100',
        ]);

        Auth::shouldReceive('user')->andReturn($user);

        $request = Request::create('/vote/create', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.200');

        $next = function ($req) {
            return response('Next called', 200);
        };

        $response = $this->middleware->handle($request, $next);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertTrue($response->getSession()->has('errors'));
    }

    /** @test */
    public function it_handles_unauthenticated_requests()
    {
        Config::set('voting_security.control_ip_address', 1);

        Auth::shouldReceive('user')->andReturn(null);

        $request = Request::create('/vote/create', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.200');

        $next = function ($req) {
            return response('Next called', 200);
        };

        $response = $this->middleware->handle($request, $next);

        // Should pass through (authentication middleware will handle)
        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function it_includes_bilingual_error_message_on_mismatch()
    {
        Config::set('voting_security.control_ip_address', 1);
        Config::set('voting_security.ip_mismatch_action', 'block');
        Config::set('voting_security.messages.ip_mismatch_english', 'You can only vote from your registered IP address.');
        Config::set('voting_security.messages.ip_mismatch_nepali', 'तपाईं आफ्नो दर्ता गरिएको IP ठेगानाबाट मात्र मतदान गर्न सक्नुहुन्छ।');

        $user = User::factory()->make([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'voting_ip' => '192.168.1.100',
        ]);

        Auth::shouldReceive('user')->andReturn($user);

        $request = Request::create('/vote/create', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.200');

        $next = function ($req) {
            return response('Next called', 200);
        };

        $response = $this->middleware->handle($request, $next);

        // Verify the middleware method that generates the message
        $reflectionClass = new \ReflectionClass($this->middleware);
        $method = $reflectionClass->getMethod('getIpMismatchMessage');
        $method->setAccessible(true);

        $message = $method->invokeArgs($this->middleware, ['192.168.1.100', '192.168.1.200']);

        $this->assertStringContainsString('registered IP address', $message);
        $this->assertStringContainsString('दर्ता गरिएको IP', $message);
        $this->assertStringContainsString('192.168.1.100', $message);
        $this->assertStringContainsString('192.168.1.200', $message);
    }

    /** @test */
    public function it_gets_correct_client_ip()
    {
        $request = Request::create('/vote/create', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        $reflectionClass = new \ReflectionClass($this->middleware);
        $method = $reflectionClass->getMethod('getClientIp');
        $method->setAccessible(true);

        $ip = $method->invokeArgs($this->middleware, [$request]);

        $this->assertEquals('192.168.1.100', $ip);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
