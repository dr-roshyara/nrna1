<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\DualHistory;

/**
 * Exhaustive bounded verification (contract §20). PBDIGIT-258.
 *
 * Enumerates every command sequence over T domain values and up to L commands.
 * At every reachable state:
 * - production predicates equal the independent PointwiseOracle on the Lemma 4 grid;
 * - INV-2, INV-3, INV-9, INV-EP and INV-COV hold;
 * - Admit acceptance equals the closed form of contract Theorem 3.
 *
 * Differential: the state and decision counts must equal those of the
 * independent research model (Python, `slice0/slice0.py`, run 2026-10-08:
 * contract §20).
 *
 * Status of a pass: VERIFIED WITHIN BOUNDS, not a proof for arbitrary histories.
 */
final class ExhaustiveBoundedVerificationTest extends TestCase
{
    private array $counts = [];
    private array $problems = [];

    /** @return array<string, array{int, int, array{int, int, int, int}}> T, L, research counts [states, accepted, refused, admit attempts] */
    public static function bounds(): array
    {
        return [
            'T=4, L=4' => [4, 4, [373, 372, 5790, 4410]],
            'T=5, L=5' => [5, 5, [3418, 3417, 32478, 25950]],
        ];
    }

    #[DataProvider('bounds')]
    public function test_production_equals_the_oracle_and_the_research_model_within_bounds(int $T, int $L, array $research): void
    {
        $this->counts = ['states' => 0, 'accepted' => 0, 'refused' => 0, 'admit attempts' => 0];
        $this->problems = [];

        $this->explore(new DualHistory(), $T, $L);

        $this->assertSame([], array_slice($this->problems, 0, 10), 'first mismatches / violations');
        $this->assertSame(
            ['states' => $research[0], 'accepted' => $research[1], 'refused' => $research[2], 'admit attempts' => $research[3]],
            $this->counts,
            'differential: decision counts must equal the research model',
        );
    }

    private function explore(DualHistory $h, int $T, int $depth): void
    {
        $this->counts['states']++;
        foreach ($h->check($T) as $problem) {
            $this->problems[] = $problem . ' after ' . json_encode($h->applied);
        }
        if ($depth === 0) {
            return;
        }
        foreach ($this->commands($h, $T) as $cmd) {
            $next = $h->clone();
            [$accepted] = $next->apply($cmd);
            if ($cmd[0] === 'A') {
                $this->counts['admit attempts']++;
                if ($accepted !== $h->oracle()->admissionAcceptable($h->at($cmd[1]))) {
                    $this->problems[] = 'Theorem 3 mismatch for ' . json_encode($cmd) . ' after ' . json_encode($h->applied);
                }
            }
            if ($accepted) {
                $this->counts['accepted']++;
                $this->explore($next, $T, $depth - 1);
            } else {
                $this->counts['refused']++;
            }
        }
    }

    /** The research model's command alphabet (slice0.py `cmds`). */
    private function commands(DualHistory $h, int $T): array
    {
        $out = [];
        for ($s = 0; $s < $T; $s++) {
            foreach ([...range(1, $T), 'inf', null] as $u) {
                if ($u === null || $u === 'inf' || $u > $s) {
                    $out[] = ['A', $s, $u];
                }
            }
        }
        for ($q = 1; $q <= $h->periodCount(); $q++) {
            for ($e = 0; $e < $T; $e++) {
                $out[] = ['E', $q, $e];
            }
        }

        return $out;
    }
}
