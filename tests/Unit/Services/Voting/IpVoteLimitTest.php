<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Voting;

use App\Models\Election;
use App\Services\Voting\IpVoteLimit;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\CreatesApplication;

/**
 * PBDIGIT-278 item 8 (database-free): one reading of the per-IP vote limit.
 *
 * Global limit from MAX_USE_IP_ADDRESS (config app.max_use_clientIP): not configured → 10; a positive whole number →
 * exactly that (1 stays 1); anything else is invalid → ignored (default 10) with a warning naming reason and type,
 * never the value. Per election: a whitelisted IP has no limit; an enabled election restriction with a valid
 * maximum wins over the global limit. `pgsql` points at a non-existent socket; no query may run.
 */
final class IpVoteLimitTest extends BaseTestCase
{
    use CreatesApplication;

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => '/nonexistent-item8-test-socket-dir',
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

    private function election(array $attributes = []): Election
    {
        $election = (new \ReflectionClass(Election::class))->newInstanceWithoutConstructor();
        $election->setRawAttributes($attributes + ['ip_restriction_enabled' => false, 'ip_restriction_max_per_ip' => null, 'ip_whitelist' => null]);

        return $election;
    }

    // ── global limit ────────────────────────────────────────────────────────────────────────────────────

    public function test_no_setting_means_the_default_limit_of_ten(): void
    {
        $this->assertSame(10, IpVoteLimit::DEFAULT_LIMIT);
        $this->assertSame(10, IpVoteLimit::globalLimit(null));
        $this->assertSame(10, IpVoteLimit::globalLimit(''), 'an empty environment value counts as not configured');
    }

    public function test_an_explicit_limit_is_used_exactly_including_one(): void
    {
        $this->assertSame(1, IpVoteLimit::globalLimit('1'));
        $this->assertSame(1, IpVoteLimit::globalLimit(1));
        $this->assertSame(4, IpVoteLimit::globalLimit('4'));
        $this->assertSame(25, IpVoteLimit::globalLimit(25));
    }

    /** @return iterable<string, array{mixed, string}> */
    public static function invalid(): iterable
    {
        yield 'zero' => ['0', 'non_positive'];
        yield 'zero int' => [0, 'non_positive'];
        yield 'negative' => ['-3', 'non_positive'];
        yield 'fractional' => ['2.5', 'not_integer'];
        yield 'float' => [2.5, 'not_integer'];
        yield 'non-numeric' => ['abc', 'not_integer'];
        yield 'trailing junk' => ['10x', 'not_integer'];
        yield 'padded' => [' 5', 'not_integer'];
        yield 'leading zero' => ['07', 'not_integer'];
        yield 'boolean' => [true, 'not_integer'];
    }

    /** @dataProvider invalid */
    public function test_an_invalid_value_is_ignored_with_a_warning_never_used_as_a_limit(mixed $raw, string $reason): void
    {
        Log::spy();

        $this->assertSame(IpVoteLimit::DEFAULT_LIMIT, IpVoteLimit::globalLimit($raw));

        Log::shouldHaveReceived('warning')->once()->withArgs(function (string $message, array $context) use ($reason) {
            return $message === 'max_use_ip_address_invalid'
                && $context['reason'] === $reason
                && ! in_array('value', array_keys($context), true);
        });
    }

    public function test_the_configured_value_is_read_from_config(): void
    {
        config(['app.max_use_clientIP' => null]);
        $this->assertSame(10, IpVoteLimit::configuredGlobalLimit());
        config(['app.max_use_clientIP' => '1']);
        $this->assertSame(1, IpVoteLimit::configuredGlobalLimit());
    }

    // ── per election ────────────────────────────────────────────────────────────────────────────────────

    public function test_without_an_election_restriction_the_global_limit_applies(): void
    {
        config(['app.max_use_clientIP' => null]);
        $this->assertSame(10, IpVoteLimit::limitFor($this->election(), '203.0.113.5'));
        config(['app.max_use_clientIP' => '1']);
        $this->assertSame(1, IpVoteLimit::limitFor($this->election(), '203.0.113.5'));
    }

    public function test_an_enabled_election_restriction_wins_over_the_global_limit(): void
    {
        config(['app.max_use_clientIP' => '1']);
        $election = $this->election(['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => 3]);

        $this->assertSame(3, IpVoteLimit::limitFor($election, '203.0.113.5'));
    }

    public function test_a_disabled_or_invalid_election_restriction_falls_back_to_the_global_limit(): void
    {
        config(['app.max_use_clientIP' => '2']);

        $this->assertSame(2, IpVoteLimit::limitFor($this->election(['ip_restriction_enabled' => false, 'ip_restriction_max_per_ip' => 5]), '203.0.113.5'));
        $this->assertSame(2, IpVoteLimit::limitFor($this->election(['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => null]), '203.0.113.5'));
        $this->assertSame(2, IpVoteLimit::limitFor($this->election(['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => 0]), '203.0.113.5'));
    }

    public function test_a_whitelisted_ip_has_no_limit(): void
    {
        config(['app.max_use_clientIP' => '1']);
        $election = $this->election(['ip_whitelist' => json_encode(['203.0.113.0/24'])]);

        $this->assertNull(IpVoteLimit::limitFor($election, '203.0.113.5'));
        $this->assertSame(1, IpVoteLimit::limitFor($election, '198.51.100.7'), 'other addresses keep the limit');
    }

    public function test_the_refusal_message_is_readable_and_names_the_limit(): void
    {
        $this->assertSame('The maximum of 1 vote(s) from your network has been reached.', IpVoteLimit::refusalMessage(1));
    }

    // ── PBDIGIT-288 item 5: where the applied limit comes from (for the audit entry) ─────────────────────

    public function test_the_limit_source_mirrors_the_limit_rule(): void
    {
        config(['app.max_use_clientIP' => null]);
        $this->assertSame('default', IpVoteLimit::limitSource($this->election(), '198.51.100.7'));
        $this->assertSame('default', IpVoteLimit::limitSource($this->election(['ip_restriction_max_per_ip' => 4]), '198.51.100.7'),
            'a disabled election restriction is not the source');

        config(['app.max_use_clientIP' => '3']);
        $this->assertSame('env', IpVoteLimit::limitSource($this->election(), '198.51.100.7'));
        $this->assertSame('election', IpVoteLimit::limitSource($this->election(['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => 2]), '198.51.100.7'));
        $this->assertSame('whitelist', IpVoteLimit::limitSource($this->election(['ip_whitelist' => json_encode(['203.0.113.0/24'])]), '203.0.113.5'));
    }

    public function test_an_invalid_configured_value_is_reported_as_the_default_source(): void
    {
        Log::spy();
        config(['app.max_use_clientIP' => 'abc']);

        $this->assertSame('default', IpVoteLimit::limitSource($this->election(), '198.51.100.7'));
    }
}
