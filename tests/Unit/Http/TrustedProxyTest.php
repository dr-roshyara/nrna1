<?php

declare(strict_types=1);

namespace Tests\Unit\Http;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-287 (Human PO 2026-10-13: production runs behind a single reverse proxy; trust only that proxy, from
 * configuration, never '*'): the client IP every IP protection sees ($request->ip()) is the forwarded client only
 * when the request comes from a configured proxy; otherwise it is the connecting address, whatever headers say.
 *
 * Configuration: trustedproxy.proxies (env TRUSTED_PROXIES: comma-separated addresses or CIDR ranges). Not set →
 * no proxy is trusted. A wildcard ('*', '**', or Laravel's 'REMOTE_ADDR') is refused with a warning and trusts no
 * proxy.
 *
 * Over the real HTTP kernel (global middleware) on a test-only route. `pgsql` points at a non-existent socket; no
 * query may run.
 */
final class TrustedProxyTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;

    private const PROXY = '10.0.0.5';
    private const CLIENT = '203.0.113.7';
    private const SPOOFED = '1.2.3.4';
    private const DIRECT = '198.51.100.9';

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => '/nonexistent-287-test-socket-dir',
            'database.connections.pgsql.url' => null,
            'trustedproxy.proxies' => null,
        ]);
        DB::purge('pgsql');
        DB::listen(function () {
            $this->queries++;
        });
        Route::get('/__pbdigit287/client-ip', fn (Request $request) => response((string) $request->ip()));
    }

    protected function tearDown(): void
    {
        $this->assertSame(0, $this->queries, 'database-free');
        parent::tearDown();
    }

    private function ipSeen(string $remoteAddr, array $headers = []): string
    {
        $server = ['REMOTE_ADDR' => $remoteAddr];
        foreach ($headers as $name => $value) {
            $server['HTTP_'.strtoupper(str_replace('-', '_', $name))] = $value;
        }

        return $this->withServerVariables($server)->get('/__pbdigit287/client-ip')->assertOk()->getContent();
    }

    // ── no proxy configured: headers are never believed ──────────────────────────────────────────────────

    public function test_without_a_configured_proxy_a_spoofed_forwarded_for_is_ignored(): void
    {
        $this->assertSame(self::DIRECT, $this->ipSeen(self::DIRECT, ['X-Forwarded-For' => self::SPOOFED]));
    }

    public function test_without_a_configured_proxy_the_connecting_address_is_the_client(): void
    {
        $this->assertSame(self::DIRECT, $this->ipSeen(self::DIRECT));
    }

    // ── a configured proxy ───────────────────────────────────────────────────────────────────────────────

    public function test_from_the_configured_proxy_the_forwarded_client_is_used(): void
    {
        config(['trustedproxy.proxies' => self::PROXY]);

        $this->assertSame(self::CLIENT, $this->ipSeen(self::PROXY, ['X-Forwarded-For' => self::CLIENT]));
    }

    public function test_from_any_other_address_a_spoofed_forwarded_for_is_ignored(): void
    {
        config(['trustedproxy.proxies' => self::PROXY]);

        $this->assertSame(self::DIRECT, $this->ipSeen(self::DIRECT, ['X-Forwarded-For' => self::SPOOFED]));
    }

    public function test_a_value_the_client_prepended_before_the_proxy_appended_the_real_address_is_ignored(): void
    {
        config(['trustedproxy.proxies' => self::PROXY]);

        // The client sent "X-Forwarded-For: 1.2.3.4"; the proxy appended the address it saw.
        $this->assertSame(self::CLIENT, $this->ipSeen(self::PROXY, ['X-Forwarded-For' => self::SPOOFED.', '.self::CLIENT]));
    }

    public function test_proxies_may_be_listed_as_addresses_or_ranges(): void
    {
        config(['trustedproxy.proxies' => '192.0.2.1, 10.0.0.0/8']);

        $this->assertSame(self::CLIENT, $this->ipSeen('10.20.30.40', ['X-Forwarded-For' => self::CLIENT]), 'within the range');
        $this->assertSame(self::CLIENT, $this->ipSeen('192.0.2.1', ['X-Forwarded-For' => self::CLIENT]), 'the listed address');
        $this->assertSame(self::DIRECT, $this->ipSeen(self::DIRECT, ['X-Forwarded-For' => self::SPOOFED]), 'outside both');
    }

    public function test_the_standard_forwarded_header_is_not_believed_even_from_the_proxy(): void
    {
        config(['trustedproxy.proxies' => self::PROXY]);

        $this->assertSame(self::PROXY, $this->ipSeen(self::PROXY, ['Forwarded' => 'for='.self::SPOOFED]));
    }

    // ── a wildcard is refused ────────────────────────────────────────────────────────────────────────────

    /** @return iterable<string, array{string}> */
    public static function wildcards(): iterable
    {
        yield 'star' => ['*'];
        yield 'double star' => ['**'];
        yield 'star in a list' => ['10.0.0.5, *'];
        yield 'REMOTE_ADDR (Laravel: trust whoever connects)' => ['REMOTE_ADDR'];
    }

    /** @dataProvider wildcards */
    #[\PHPUnit\Framework\Attributes\DataProvider('wildcards')]
    public function test_a_wildcard_is_refused_with_a_warning_and_trusts_no_proxy(string $configured): void
    {
        Log::spy();
        config(['trustedproxy.proxies' => $configured]);

        $this->assertSame(self::DIRECT, $this->ipSeen(self::DIRECT, ['X-Forwarded-For' => self::SPOOFED]));
        $this->assertSame(self::PROXY, $this->ipSeen(self::PROXY, ['X-Forwarded-For' => self::CLIENT]), 'not even a listed proxy is trusted');
        Log::shouldHaveReceived('warning')->withArgs(fn (string $message) => $message === 'trusted_proxies_wildcard_refused');
    }
}
