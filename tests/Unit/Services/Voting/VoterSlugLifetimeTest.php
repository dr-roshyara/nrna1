<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Voting;

use App\Exceptions\Voting\VoterSlugLifetimeMisconfigured;
use App\Models\DemoVoterSlug;
use App\Models\VoterSlug;
use App\Services\Voting\VoterSlugLifetime;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\CreatesApplication;

/**
 * PBDIGIT-274 S1: the single voter-slug lifetime rule (plan .claude/plans/distributed-snuggling-swan.md,
 * L-1 … L-3; decisions O-9 = (a), O-10 = (B)). Database-free: models are built in memory and never saved.
 */
final class VoterSlugLifetimeTest extends BaseTestCase
{
    use CreatesApplication;

    private Carbon $now;

    protected function setUp(): void
    {
        parent::setUp();
        $this->now = Carbon::parse('2026-10-11 12:00:00', 'UTC');
        Carbon::setTestNow($this->now);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /** @param array<string, mixed> $attributes */
    private function slug(array $attributes = [], string $class = VoterSlug::class): VoterSlug|DemoVoterSlug
    {
        $slug = new $class();
        $slug->forceFill(array_merge([
            'is_active' => true,
            'status' => 'active',
            'has_voted' => false,
            'created_at' => $this->now->copy(),
            'expires_at' => $this->now->copy()->addMinutes(30),
        ], $attributes));

        return $slug;
    }

    // ------------------------------------------------------------ L-3: configuration input classes (O-9 a, O-10 B)

    /** @return array<string, array{mixed, int}> */
    public static function validValues(): array
    {
        return ['int 30' => [30, 30], 'digit string "30"' => ['30', 30], 'int 1' => [1, 1], 'string "120"' => ['120', 120]];
    }

    #[DataProvider('validValues')]
    public function test_valid_configured_minutes_are_accepted(mixed $raw, int $minutes): void
    {
        $this->assertSame($minutes, VoterSlugLifetime::fromConfig($raw)->minutes());
    }

    /** @return array<string, array{mixed, string}> */
    public static function invalidValues(): array
    {
        return [
            'missing (null)' => [null, 'missing'],
            'empty string' => ['', 'missing'],
            'zero' => [0, 'non_positive'],
            'string zero' => ['0', 'non_positive'],
            'negative' => [-5, 'non_positive'],
            'string negative' => ['-5', 'non_positive'],
            'malformed abc' => ['abc', 'not_integer'],
            'partial 20min' => ['20min', 'not_integer'],
            'decimal string 5.9' => ['5.9', 'not_integer'],
            'float 5.9' => [5.9, 'not_integer'],
            'boolean' => [true, 'not_integer'],
            'array' => [[30], 'not_integer'],
            'leading zero 030' => ['030', 'not_integer'],
            'whitespace " 30"' => [' 30', 'not_integer'],
        ];
    }

    #[DataProvider('invalidValues')]
    public function test_invalid_configured_minutes_fail_closed_with_a_reason_code(mixed $raw, string $reason): void
    {
        try {
            VoterSlugLifetime::fromConfig($raw);
            $this->fail('expected VoterSlugLifetimeMisconfigured');
        } catch (VoterSlugLifetimeMisconfigured $e) {
            $this->assertSame($reason, $e->reason());
            $this->assertSame(503, $e->getHttpCode());
            $this->assertStringNotContainsString('20min', $e->getUserMessage(), 'no configured value reaches the client');
        }
    }

    // ------------------------------------------------------------ L-2: boundaries (UTC instants, nulls expired)

    public function test_end_is_the_earlier_of_expires_at_and_created_at_plus_minutes(): void
    {
        $lifetime = VoterSlugLifetime::fromConfig(30);

        $extended = $this->slug(['created_at' => $this->now->copy()->subMinutes(10), 'expires_at' => $this->now->copy()->addHour()]);
        $this->assertEquals(CarbonImmutable::parse('2026-10-11 12:20:00', 'UTC'), $lifetime->endsAt($extended));

        $shorter = $this->slug(['created_at' => $this->now->copy(), 'expires_at' => $this->now->copy()->addMinutes(5)]);
        $this->assertEquals(CarbonImmutable::parse('2026-10-11 12:05:00', 'UTC'), $lifetime->endsAt($shorter));
        $this->assertInstanceOf(CarbonImmutable::class, $lifetime->endsAt($shorter));
    }

    public function test_one_second_before_either_bound_is_not_expired(): void
    {
        $lifetime = VoterSlugLifetime::fromConfig(30);

        $this->assertFalse($lifetime->isExpired($this->slug(['expires_at' => $this->now->copy()->addSecond()]), $this->now));
        $this->assertFalse($lifetime->isExpired($this->slug(['created_at' => $this->now->copy()->subMinutes(30)->addSecond(), 'expires_at' => $this->now->copy()->addHour()]), $this->now));
    }

    public function test_exactly_at_either_bound_is_expired(): void
    {
        $lifetime = VoterSlugLifetime::fromConfig(30);

        $this->assertTrue($lifetime->isExpired($this->slug(['expires_at' => $this->now->copy()]), $this->now), 'now == expires_at');
        $this->assertTrue($lifetime->isExpired($this->slug(['created_at' => $this->now->copy()->subMinutes(30), 'expires_at' => $this->now->copy()->addHour()]), $this->now), 'now == created_at + T');
    }

    public function test_after_either_bound_is_expired(): void
    {
        $lifetime = VoterSlugLifetime::fromConfig(30);

        $this->assertTrue($lifetime->isExpired($this->slug(['expires_at' => $this->now->copy()->subSecond()]), $this->now));
        $this->assertTrue($lifetime->isExpired($this->slug(['created_at' => $this->now->copy()->subMinutes(31), 'expires_at' => $this->now->copy()->addHour()]), $this->now));
    }

    public function test_missing_timestamps_count_as_expired(): void
    {
        $lifetime = VoterSlugLifetime::fromConfig(30);

        $this->assertTrue($lifetime->isExpired($this->slug(['expires_at' => null]), $this->now));
        $this->assertTrue($lifetime->isExpired($this->slug(['created_at' => null]), $this->now));
        $this->assertNull($lifetime->endsAt($this->slug(['created_at' => null])));
    }

    public function test_the_configured_minutes_drive_the_cap(): void
    {
        $twenty = VoterSlugLifetime::fromConfig(20);
        $slug = $this->slug(['created_at' => $this->now->copy()->subMinutes(20), 'expires_at' => $this->now->copy()->addMinutes(10)]);

        $this->assertTrue($twenty->isExpired($slug, $this->now));
        $this->assertFalse(VoterSlugLifetime::fromConfig(30)->isExpired($slug, $this->now));
    }

    public function test_a_non_utc_clock_is_compared_as_the_same_instant(): void
    {
        $lifetime = VoterSlugLifetime::fromConfig(30);
        $berlinNow = $this->now->copy()->setTimezone('Europe/Berlin'); // same instant, other zone

        $this->assertTrue($lifetime->isExpired($this->slug(['expires_at' => $this->now->copy()]), $berlinNow));
        $this->assertFalse($lifetime->isExpired($this->slug(['expires_at' => $this->now->copy()->addSecond()]), $berlinNow));
    }

    // ------------------------------------------------------------ L-1: reusable by the issuer

    public function test_a_fresh_active_unvoted_slug_is_reusable(): void
    {
        $this->assertTrue(VoterSlugLifetime::fromConfig(30)->isReusable($this->slug(), $this->now));
        $this->assertTrue(VoterSlugLifetime::fromConfig(30)->isReusable($this->slug([], DemoVoterSlug::class), $this->now));
    }

    /** @return array<string, array{array<string, mixed>}> */
    public static function notReusable(): array
    {
        return [
            'soft-deleted' => [['deleted_at' => '2026-10-11 11:59:00']],
            'inactive' => [['is_active' => false]],
            'voted' => [['has_voted' => true]],
            'status expired' => [['status' => 'expired']],
            'expired by expires_at' => [['expires_at' => '2026-10-11 12:00:00']],
            'expired by cap' => [['created_at' => '2026-10-11 11:30:00', 'expires_at' => '2026-10-11 13:00:00']],
        ];
    }

    /** @param array<string, mixed> $attributes */
    #[DataProvider('notReusable')]
    public function test_slugs_the_issuer_must_not_reuse(array $attributes): void
    {
        $this->assertFalse(VoterSlugLifetime::fromConfig(30)->isReusable($this->slug($attributes), $this->now));
    }
}
