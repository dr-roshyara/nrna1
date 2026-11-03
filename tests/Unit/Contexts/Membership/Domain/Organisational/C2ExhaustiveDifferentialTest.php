<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Correct;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\G6C2CorrectionOracle;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * COR-2 step 7, AC-3 (PBDIGIT-327): exhaustive differential of the kernel against the C-2 oracle port over the full
 * correction fragment (Admit, End, Annul, Void, Correct), H = 5, L = 5 (the golden fixture's bounds; INV-EP reachable).
 *
 * For every reachable decision: the verdict and refusal reason are equal; when accepted, every live period's lifetime
 * on 0..H-1 is equal, and so are the consequences of an accepted Correct (removed points per trimmed period, the
 * PeriodTrimmed data; the oracle's list merged per period, as the kernel stores at most one per period).
 * Fact ids: Admit of period p = "A<p>"; End, Annul, Void, Correct at log position k = "E<k>", "X<k>", "V<k>", "C<k>".
 * Every fact has decision provenance (R-2 trivial); P-5 is not exercised (the decision date lies after every value).
 *
 * Status of a pass: VERIFIED WITHIN BOUNDS, not a proof; the oracle is evidence, not the contract.
 */
final class C2ExhaustiveDifferentialTest extends TestCase
{
    private const H = 5;
    private const L = 5;

    public function test_the_kernel_equals_the_c2_port_within_bounds(): void
    {
        $oracle = new G6C2CorrectionOracle(self::H);
        $decisions = 0;
        $accepted = 0;
        $mismatches = [];
        $frontier = [[[], [], MemberHistory::start()]];
        for ($depth = 1; $depth <= self::L; $depth++) {
            $next = [];
            foreach ($frontier as [$s, $ids, $history]) {
                foreach ($oracle->candidates($s) as $f) {
                    $decisions++;
                    $k = count($s);
                    [$id, $decision] = $this->decide($history, $f, $ids, $k);
                    [$expected, $log] = $oracle->decide($s, $f);
                    $actual = $decision->isAccepted() ? null : $decision->refusal()->value;
                    if ($expected !== $actual) {
                        $mismatches[] = $oracle->line([...$s, $f], $expected, $log) . ' / kernel: ' . ($actual ?? 'ok');
                        continue;
                    }
                    if ($actual !== null) {
                        continue;
                    }
                    $accepted++;
                    $branch = clone $history;
                    $branch->commit($decision);
                    $kernel = [$this->kernelLifetimes($branch), $this->kernelConsequences($decision)];
                    $model = [$this->oracleLifetimes($oracle, $log), $this->oracleConsequences($log)];
                    if ($kernel !== $model) {
                        $mismatches[] = $oracle->line([...$s, $f], null, $log) . ' / kernel: ' . json_encode($kernel) . ' oracle: ' . json_encode($model);
                        continue;
                    }
                    if ($depth < self::L) {
                        $next[] = [$log, [...$ids, $id], $branch];
                    }
                }
            }
            $frontier = $next;
        }

        $this->assertSame([], array_slice($mismatches, 0, 10), 'first mismatches');
        $this->assertSame(277835, $decisions, 'decisions explored (golden fixture total, depths 1-5)');
        $this->assertSame(139722, $accepted, 'accepted decisions (golden fixture total, depths 1-5)');
    }

    /** @return array{string, Decision} the new fact's id and the kernel's decision */
    private function decide(MemberHistory $h, array $f, array $ids, int $k): array
    {
        return match ($f['k']) {
            'Admit' => ["A{$f['pid']}", $h->admit("A{$f['pid']}", $f['v'])],
            'End' => ["E$k", $h->end("E$k", "A{$f['pid']}", $f['v'])],
            'Annul' => ["X$k", $h->annul("X$k", $ids[$f['t']])],
            'Void' => ["V$k", $h->void("V$k", $ids[$f['t']])],
            'Correct' => ["C$k", $h->correct("C$k", $ids[$f['t']], $f['v'])],
        };
    }

    /** @return array<string, list<int>> period => lifetime points on 0..H-1 */
    private function kernelLifetimes(MemberHistory $h): array
    {
        $out = [];
        foreach ($h->member->resolve()->periods() as $period) {
            $out[$period->id()->admitFactId()->value()] = self::points(static fn (TimePoint $t): bool => $period->inLifetime($t));
        }
        ksort($out);

        return $out;
    }

    /** @return array<string, list<int>> live period => lifetime (a dead admission has no period) */
    private function oracleLifetimes(G6C2CorrectionOracle $oracle, array $log): array
    {
        $out = [];
        foreach ($oracle->resolve($log) as $pid => $lifetime) {
            foreach ($log as $i => $x) {
                if ($x['k'] === 'Admit' && $x['pid'] === $pid && $oracle->live($log, $i)) {
                    $out["A$pid"] = $lifetime;
                }
            }
        }
        ksort($out);

        return $out;
    }

    /** @return array<string, list<int>> trimmed period => removed points on 0..H-1 */
    private function kernelConsequences(Decision $decision): array
    {
        $fact = $decision->fact();
        $out = [];
        if ($fact instanceof Correct) {
            foreach ($fact->consequences() as $c) {
                $out[$c->period()->admitFactId()->value()] = self::points(static fn (TimePoint $t): bool => $c->removed()->contains($t));
            }
        }
        ksort($out);

        return $out;
    }

    /** @return array<string, list<int>> the decided fact's consequences, merged per trimmed period */
    private function oracleConsequences(array $log): array
    {
        $out = [];
        foreach (end($log)['cons'] as [$q, $removed]) {
            $key = 'A' . $log[$q]['pid'];
            $merged = array_values(array_unique([...($out[$key] ?? []), ...$removed]));
            sort($merged);
            $out[$key] = $merged;
        }
        ksort($out);

        return $out;
    }

    /** @return list<int> */
    private static function points(callable $in): array
    {
        $points = [];
        for ($t = 0; $t < self::H; $t++) {
            if ($in(TimePoint::of($t))) {
                $points[] = $t;
            }
        }

        return $points;
    }
}
