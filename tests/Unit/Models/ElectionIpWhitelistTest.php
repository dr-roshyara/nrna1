<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Domain\Election\Security\Ipv4Whitelist;
use App\Http\Requests\Election\UpdateElectionSettingsRequest;
use App\Models\Election;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-285 (database-free): an election's IP whitelist never exempts an address it does not list.
 *
 * Election::isIpWhitelisted() used ip2long(), which returns false for IPv6 or malformed input, so `false & mask`
 * compared 0 === 0 and an IPv6 / malformed entry exempted EVERY IPv6 client from the per-IP vote limit. The one
 * matcher is now the trust policy's Ipv4Whitelist (scope IPv4 only, PO 2026-10-05): an entry or address it cannot
 * read matches nothing. Entries are validated server-side with the same parser.
 * `pgsql` points at a non-existent socket; no query may run.
 */
final class ElectionIpWhitelistTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => '/nonexistent-285-test-socket-dir',
            'database.connections.pgsql.url' => null,
        ]);
        DB::purge('pgsql');
        DB::listen(function () {
            $this->queries++;
        });
    }

    protected function tearDown(): void
    {
        $this->assertSame(0, $this->queries, 'database-free');
        parent::tearDown();
    }

    private function election(array $entries): Election
    {
        $election = (new \ReflectionClass(Election::class))->newInstanceWithoutConstructor();
        $election->setRawAttributes(['ip_whitelist' => json_encode($entries)]);

        return $election;
    }

    /** @return iterable<string, array{list<string>, string, bool}> */
    public static function cases(): iterable
    {
        // The defect: an entry the matcher cannot read must never match.
        yield 'IPv6 range vs unrelated IPv6 client' => [['2001:db8::/32'], '2a00:1450:4001::1', false];
        yield 'IPv6 range vs IPv6 client inside it (IPv4-only scope)' => [['2001:db8::/32'], '2001:db8::1', false];
        yield 'malformed range vs IPv6 client' => [['garbage/24'], '2a00:1450:4001::1', false];
        yield 'malformed range vs IPv4 client' => [['garbage/24'], '203.0.113.5', false];
        yield 'prefix out of range' => [['203.0.113.0/40'], '198.51.100.7', false];
        yield 'empty prefix' => [['203.0.113.0/'], '198.51.100.7', false];
        // Unchanged valid behaviour.
        yield 'IPv4 range, address inside' => [['203.0.113.0/24'], '203.0.113.5', true];
        yield 'IPv4 range, address outside' => [['203.0.113.0/24'], '198.51.100.7', false];
        yield 'exact IPv4 address' => [['198.51.100.7'], '198.51.100.7', true];
        yield 'entry with spaces' => [[' 198.51.100.7 '], '198.51.100.7', true];
        yield 'IPv6 client, IPv4 entry' => [['203.0.113.0/24'], '2001:db8::1', false];
    }

    /** @dataProvider cases */
    public function test_the_whitelist_matches_only_what_it_lists(array $entries, string $ip, bool $expected): void
    {
        $this->assertSame($expected, $this->election($entries)->isIpWhitelisted($ip));
    }

    /** @dataProvider cases */
    public function test_the_election_and_the_trust_policy_use_the_same_matcher(array $entries, string $ip, bool $expected): void
    {
        $this->assertSame(Ipv4Whitelist::fromEntries($entries)->contains($ip), $this->election($entries)->isIpWhitelisted($ip));
    }

    /** @return iterable<string, array{string, bool}> */
    public static function entries(): iterable
    {
        yield 'IPv4 address' => ['203.0.113.7', true];
        yield 'IPv4 range' => ['10.0.0.0/8', true];
        yield 'IPv4 /32' => ['198.51.100.7/32', true];
        yield 'IPv4 /0' => ['0.0.0.0/0', true];
        yield 'IPv6 address' => ['2001:db8::1', false];
        yield 'IPv6 range' => ['2001:db8::/32', false];
        yield 'garbage' => ['abc', false];
        yield 'garbage range' => ['garbage/24', false];
        yield 'prefix too long' => ['10.0.0.0/33', false];
        yield 'empty prefix' => ['10.0.0.0/', false];
        yield 'non-digit prefix' => ['10.0.0.0/8a', false];
    }

    /** @dataProvider entries */
    public function test_entries_are_validated_on_the_server(string $entry, bool $valid): void
    {
        $this->assertSame($valid, Ipv4Whitelist::isValidEntry($entry));

        $rules = (new UpdateElectionSettingsRequest())->rules();
        $validator = Validator::make(['ip_whitelist' => [$entry]], ['ip_whitelist' => $rules['ip_whitelist'], 'ip_whitelist.*' => $rules['ip_whitelist.*']]);
        $this->assertSame(! $valid, $validator->fails(), "settings request for '{$entry}'");
    }
}
