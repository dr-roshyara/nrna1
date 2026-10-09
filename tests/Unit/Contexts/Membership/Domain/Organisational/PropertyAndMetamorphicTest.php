<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\End;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\ResolvedMember;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\DualHistory;

/**
 * Seeded property tests and metamorphic relations (contract §19–§20). PBDIGIT-258.
 *
 * Every history comes from a fixed seed, so any failure is reproducible from the
 * seed shown in its message.
 */
final class PropertyAndMetamorphicTest extends TestCase
{
    private const SEEDS = 300;
    private const STEPS = 30;
    private const MAX_V = 60;

    /**
     * A seeded command history over domain values 0..MAX_V.
     * 70% plausible commands (re-admission after the last end; an End after an open
     * period's start), 30% fully random ones, so that refusals stay exercised.
     */
    private static function history(int $seed, int $shift = 0, string $prefix = ''): DualHistory
    {
        mt_srand($seed);
        $h = new DualHistory($shift, $prefix);
        $periods = []; // number => [start, end|null] in domain values, accepted facts only
        for ($i = 0; $i < self::STEPS; $i++) {
            $plausible = mt_rand(0, 99) < 70;
            $open = array_keys(array_filter($periods, static fn (array $p): bool => $p[1] === null));
            if ($periods === [] || ($plausible && $open === []) || (!$plausible && mt_rand(0, 1) === 0)) {
                $ends = array_map(static fn (array $p): int => $p[1] ?? 0, $periods);
                $v = $plausible ? min(self::MAX_V, ($ends === [] ? 0 : max($ends)) + mt_rand(0, 3)) : mt_rand(0, self::MAX_V);
                $term = match (mt_rand(0, 2)) {
                    0 => null,
                    1 => 'inf',
                    default => min(self::MAX_V + 1, $v + mt_rand(1, 6)),
                };
                if ($h->apply(['A', $v, $term])[0]) {
                    $periods[$h->periodCount()] = [$v, null];
                }
            } else {
                $q = $plausible && $open !== [] ? $open[array_rand($open)] : mt_rand(1, max(1, $h->periodCount()));
                $e = $plausible && isset($periods[$q]) ? min(self::MAX_V, $periods[$q][0] + mt_rand(1, 6)) : mt_rand(0, self::MAX_V);
                if ($h->apply(['E', $q, $e])[0]) {
                    $periods[$q][1] = $e;
                }
            }
        }

        return $h;
    }

    /** @return array<string, mixed> every Slice 0 predicate at every grid point, keyed by period number */
    private static function snapshot(DualHistory $h, int $shift): array
    {
        $r = $h->resolved();
        $out = [];
        for ($t = -1; $t <= 2 * (self::MAX_V + 1) + 1; $t++) {
            $tp = TimePoint::of($t + $shift);
            $row = ['member' => $r->isMember($tp)];
            foreach ($r->periods() as $n => $p) {
                $row["p$n"] = [$p->inLifetime($tp), $p->isEnded($tp), $p->isTermCovered($tp), $p->isInForce($tp), $p->isLapsed($tp)];
            }
            $out[$t] = $row;
        }

        return $out;
    }

    public function test_property_production_agrees_with_the_oracle_and_the_invariants_hold(): void
    {
        $problems = [];
        for ($seed = 1; $seed <= self::SEEDS; $seed++) {
            foreach (self::history($seed)->check(self::MAX_V) as $p) {
                $problems[] = "seed $seed: $p";
            }
        }

        $this->assertSame([], array_slice($problems, 0, 10));
    }

    public function test_property_resolution_is_deterministic(): void
    {
        for ($seed = 1; $seed <= 50; $seed++) {
            $h = self::history($seed);
            $this->assertEquals(self::snapshot($h, 0), self::snapshot($h, 0), "seed $seed");
            $this->assertEquals($h->member->resolve(), $h->member->resolve(), "seed $seed");
        }
    }

    public function test_metamorphic_time_translation(): void
    {
        foreach ([1, 7, -40, 1000] as $k) {
            for ($seed = 1; $seed <= 60; $seed++) {
                $this->assertEquals(
                    self::snapshot(self::history($seed), 0),
                    self::snapshot(self::history($seed, $k), $k),
                    "seed $seed, shift $k: P(F', t + k) must equal P(F, t)",
                );
            }
        }
    }

    public function test_metamorphic_renaming_ids_changes_nothing(): void
    {
        for ($seed = 1; $seed <= 60; $seed++) {
            $this->assertEquals(self::snapshot(self::history($seed), 0), self::snapshot(self::history($seed, 0, 'renamed-'), 0), "seed $seed");
        }
    }

    public function test_metamorphic_known_at_is_stable_under_later_recordings(): void
    {
        for ($seed = 1; $seed <= 60; $seed++) {
            $member = self::history($seed)->member;
            $n = count($member->log());
            for ($k = 0; $k <= $n; $k++) {
                $prefix = Member::fromLog($member->id(), $member->holder(), $member->organisation(), ...array_slice($member->log(), 0, $k));
                $tau = Instant::of($k);
                $this->assertEquals($prefix->resolve(), $member->knownAt($tau), "seed $seed, τ = $k");
            }
        }
    }

    public function test_metamorphic_an_end_only_shrinks_its_own_period_and_touches_no_other(): void
    {
        for ($seed = 1; $seed <= 120; $seed++) {
            $before = null;
            $h = self::history($seed);
            $log = $h->member->log();
            foreach ($log as $k => $recorded) {
                $after = Member::fromLog($h->member->id(), $h->member->holder(), $h->member->organisation(), ...array_slice($log, 0, $k + 1))->resolve();
                if ($before !== null && $recorded->fact() instanceof End) {
                    $this->assertEndOnlyShrinks($before, $after, $recorded->fact()->period(), "seed $seed, fact $k");
                }
                $before = $after;
            }
        }
    }

    private function assertEndOnlyShrinks(ResolvedMember $before, ResolvedMember $after, PeriodId $ended, string $where): void
    {
        foreach ($before->periods() as $p) {
            $q = $after->period($p->id());
            if (!$p->id()->equals($ended)) {
                $this->assertTrue($p->lifetime()->equals($q->lifetime()), "$where: another period changed");
                continue;
            }
            $this->assertTrue(
                $q->lifetime()->union($p->lifetime())->equals($p->lifetime()),
                "$where: lifetime after an End must be a subset of the lifetime before",
            );
        }
    }
}
