<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\G6CorrectionOracle;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * K-X2 (PBDIGIT-268, plan step 11): exhaustive differential of the kernel against the PHP port
 * of the g6 oracle over the C-1 fragment, H = 5, L <= 5 (the golden fixture's bounds).
 *
 * For every reachable decision: the verdict, the refusal reason and, when accepted, every live
 * period's lifetime on 0..H-1 are equal. Fact ids: Admit of period p = "A<p>"; End, Annul and
 * Void at log position k = "E<k>", "X<k>", "V<k>". Every fact has decision provenance (R-2 trivial).
 *
 * Status of a pass: VERIFIED WITHIN BOUNDS, not a proof; the oracle is evidence, not the contract.
 */
final class C1ExhaustiveDifferentialTest extends TestCase
{
    private const H = 5;
    private const L = 5;

    public function test_the_kernel_equals_the_g6_port_within_bounds(): void
    {
        $oracle = new G6CorrectionOracle(self::H);
        $decisions = 0;
        $mismatches = [];
        $frontier = [[[], [], MemberHistory::start()]];
        for ($depth = 1; $depth <= self::L; $depth++) {
            $next = [];
            foreach ($frontier as [$s, $ids, $history]) {
                foreach ($oracle->candidates($s) as $f) {
                    $decisions++;
                    $k = count($s);
                    [$id, $decision] = $this->decide($history, $f, $ids, $k);
                    $expected = $oracle->decide($s, $f);
                    $actual = $decision->isAccepted() ? null : $decision->refusal()->value;
                    $log = [...$s, $f];
                    if ($expected !== $actual) {
                        $mismatches[] = $oracle->line($log, $expected) . ' / kernel: ' . ($actual ?? 'ok');
                        continue;
                    }
                    if ($actual !== null) {
                        continue;
                    }
                    $branch = clone $history;
                    $branch->commit($decision);
                    if ($this->kernelLifetimes($branch) !== $this->oracleLifetimes($oracle, $log)) {
                        $mismatches[] = $oracle->line($log, null) . ' / kernel lifetimes: ' . json_encode($this->kernelLifetimes($branch));
                        continue;
                    }
                    $next[] = [$log, [...$ids, $id], $branch];
                }
            }
            $frontier = $next;
        }

        $this->assertSame([], array_slice($mismatches, 0, 10), 'first mismatches');
        $this->assertSame(15530, $decisions, 'decisions explored (golden fixture total)');
    }

    /** @return array{string, Decision} the new fact's id and the kernel's decision */
    private function decide(MemberHistory $h, array $f, array $ids, int $k): array
    {
        return match ($f['k']) {
            'Admit' => ["A{$f['pid']}", $h->admit("A{$f['pid']}", $f['v'])],
            'End' => ["E$k", $h->end("E$k", "A{$f['pid']}", $f['v'])],
            'Annul' => ["X$k", $h->annul("X$k", $ids[$f['t']])],
            'Void' => ["V$k", $h->void("V$k", $ids[$f['t']])],
        };
    }

    /** @return array<string, list<int>> period => lifetime points on 0..H-1 */
    private function kernelLifetimes(MemberHistory $h): array
    {
        $out = [];
        foreach ($h->member->resolve()->periods() as $period) {
            $points = [];
            for ($t = 0; $t < self::H; $t++) {
                if ($period->inLifetime(TimePoint::of($t))) {
                    $points[] = $t;
                }
            }
            $out[$period->id()->admitFactId()->value()] = $points;
        }

        return $out;
    }

    /** @return array<string, list<int>> live period => lifetime (a dead admission has no period) */
    private function oracleLifetimes(G6CorrectionOracle $oracle, array $log): array
    {
        $out = [];
        foreach ($oracle->resolve($log) as $pid => $lifetime) {
            foreach ($log as $i => $x) {
                if ($x['k'] === 'Admit' && $x['pid'] === $pid && $oracle->live($log, $i)) {
                    $out["A$pid"] = $lifetime;
                }
            }
        }

        return $out;
    }
}
