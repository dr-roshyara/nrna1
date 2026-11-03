<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Correct;
use App\Contexts\Membership\Domain\Organisational\End;
use App\Contexts\Membership\Domain\Organisational\Fact;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\InvariantSet;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\RecordedFact;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\Resolution;
use App\Contexts\Membership\Domain\Organisational\ResolvedMember;
use App\Contexts\Membership\Domain\Organisational\ReversalFact;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Domain\Organisational\VoidFact;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\C2Histories;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\G6C2CorrectionOracle;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * COR-2 step 7, AC-4 (PBDIGIT-327): the contract §5 metamorphic properties and §14.4 properties on the C-2 fragment,
 * over every kernel-accepted history within H = 5 and L = 4 (Correct facts and their consequences included).
 *
 * The counterfactual is computed by fact deletion and the liveness used to pick targets by the recursive §14.2
 * definition, not by the production liveness pass (§13.5, correlated-error risk).
 *
 * Status of a pass: VERIFIED WITHIN BOUNDS, not a proof.
 */
final class C2PropertyTest extends TestCase
{
    private const H = 5;
    private const L = 4;

    /** @var list<array{list<array>, list<string>, MemberHistory}>|null */
    private static ?array $histories = null;

    /** @return list<array{list<array>, list<string>, MemberHistory}> */
    private static function histories(): array
    {
        return self::$histories ??= C2Histories::accepted(self::H, self::L);
    }

    // §5 (b), P-REV-V / P-REV-A: a live fact appended last and then voided (or, if not a Void, annulled) resolves like F.
    public function test_reversing_the_last_fact_restores_the_previous_state(): void
    {
        $checked = ['Correct' => 0, 'other' => 0];
        foreach (self::histories() as [$log, $ids, $h]) {
            if ($log === []) {
                continue;
            }
            $k = count($log);
            $facts = self::facts($h);
            $before = Resolution::of(array_slice($facts, 0, -1));
            $x = end($facts);

            $this->assertEquals($before, (clone $h)->commit($h->void("V$k", end($ids)))->member->resolve(), 'P-REV-V after ' . implode(' ', $ids));
            if (!$x instanceof VoidFact && !($x instanceof ReversalFact && $log[$k - 1]['k'] === 'Annul')) {
                $this->assertEquals($before, (clone $h)->commit($h->annul("X$k", end($ids)))->member->resolve(), 'P-REV-A after ' . implode(' ', $ids));
            }
            $checked[$x instanceof Correct ? 'Correct' : 'other']++;
        }
        $this->assertGreaterThan(0, min($checked), json_encode($checked));
    }

    // §5 (c), P-APPEAL: F·a·Annul(a)·Annul(Annul(a)) resolves like F·a, or the last step is refused by G*.
    public function test_an_appeal_restores_the_annulled_fact_or_is_refused_by_g_star(): void
    {
        $restored = $refused = 0;
        foreach (self::histories() as [$log, $ids, $h]) {
            $facts = self::facts($h);
            if ($facts === [] || end($facts) instanceof VoidFact) {
                continue;
            }
            $k = count($facts);
            $annul = $h->annul("X$k", end($ids));
            if (!$annul->isAccepted()) {
                continue;
            }
            $after = (clone $h)->commit($annul);
            $appeal = $after->annul('X' . ($k + 1), "X$k");
            if ($appeal->isAccepted()) {
                $this->assertEquals($h->member->resolve(), (clone $after)->commit($appeal)->member->resolve(), implode(' ', $ids));
                $restored++;
            } else {
                $this->assertContains($appeal->refusal(), self::gStar(), implode(' ', $ids));
                $refused++;
            }
        }
        $this->assertGreaterThan(0, $restored);
    }

    // §5 (d): repeating an accepted correction with the same value is refused as ValueUnchanged.
    public function test_repeating_a_correction_is_refused_as_value_unchanged(): void
    {
        $checked = 0;
        foreach (self::histories() as [$log, $ids, $h]) {
            $last = $log === [] ? null : end($log);
            if ($last === null || $last['k'] !== 'Correct') {
                continue;
            }
            $k = count($log);
            $this->assertSame(RefusalReason::ValueUnchanged, $h->correct("C$k", $ids[$last['t']], $last['v'])->refusal(), implode(' ', $ids));
            $checked++;
        }
        $this->assertGreaterThan(0, $checked);
    }

    // §5 (e), P-CFC (FP §12.3): Res(F·Annul(c)) = Res(F ∖ D(c)) with the recorded consequences, not a replay; the
    // reversal is accepted iff F ∖ D(c) satisfies G*, and otherwise refused with the same reason. P-EQ: Annul and
    // Void of the same live, non-Void target resolve identically.
    public function test_a_reversal_resolves_as_deleting_its_target_and_dependants(): void
    {
        $withConsequences = $refusedByGStar = 0;
        foreach (self::histories() as [$log, $ids, $h]) {
            $k = count($log);
            $facts = self::facts($h);
            $hasConsequences = array_filter($facts, static fn (Fact $f): bool => $f instanceof Correct && $f->consequences() !== []) !== [];
            foreach ($facts as $i => $c) {
                if (!self::live($facts, $i)) {
                    continue;
                }
                $deleted = Resolution::of(self::without($facts, $c));
                $violations = InvariantSet::violations($deleted);
                $decisions = ['Void' => $h->void("V$k", $ids[$i])];
                if (!$c instanceof VoidFact) {
                    $decisions['Annul'] = $h->annul("X$k", $ids[$i]);
                }
                $resolved = [];
                foreach ($decisions as $op => $decision) {
                    $where = "$op({$ids[$i]}) after " . implode(' ', $ids);
                    if ($violations === []) {
                        $this->assertTrue($decision->isAccepted(), $where);
                        $resolved[$op] = (clone $h)->commit($decision)->member->resolve();
                        $this->assertEquals($deleted, $resolved[$op], $where);
                    } else {
                        $this->assertSame($violations[0], $decision->refusal(), $where);
                        $refusedByGStar++;
                    }
                }
                if (count($resolved) === 2) {
                    $this->assertEquals($resolved['Void'], $resolved['Annul'], 'P-EQ ' . implode(' ', $ids));
                }
                $withConsequences += $hasConsequences ? 1 : 0;
            }
        }
        $this->assertGreaterThan(0, $withConsequences, 'P-CFC is exercised on histories with recorded consequences');
        $this->assertGreaterThan(0, $refusedByGStar, 'the G* branch is exercised');
    }

    // §5 (a), P-KNOWN: knownAt(τ) equals the oracle's resolution of the facts recorded <= τ, and appending a fact never
    // changes knownAt for τ before its recordedAt.
    public function test_known_at_equals_the_oracle_and_is_prefix_stable(): void
    {
        $oracle = new G6C2CorrectionOracle(self::H);
        $checked = 0;
        foreach (self::histories() as [$log, $ids, $h]) {
            $records = $h->member->log();
            if ($records === []) {
                continue;
            }
            $instants = array_map(static fn (RecordedFact $r): int => $r->recordedAt()->ordinal(), $records);
            $taus = [min($instants) - 1, ...$instants, max($instants) + 1];
            $prefix = Member::fromLog($h->member->id(), $h->member->holder(), $h->member->organisation(), ...array_slice($records, 0, -1));
            foreach ($taus as $tau) {
                $where = 'τ=' . $tau . ' after ' . implode(' ', $ids);
                $recorded = count(array_filter($instants, static fn (int $at): bool => $at <= $tau));
                $known = $h->member->knownAt(Instant::of($tau));
                $this->assertSame(self::oracleLifetimes($oracle, array_slice($log, 0, $recorded)), self::lifetimes($known), "(a) $where");
                if ($tau < end($instants)) {
                    $this->assertEquals($prefix->knownAt(Instant::of($tau)), $known, "P-KNOWN $where");
                }
                $checked++;
            }
        }
        $this->assertGreaterThan(0, $checked);
    }

    /** @return list<RefusalReason> the G* reasons (invariants of the enforcement set, ER included) */
    private static function gStar(): array
    {
        return [
            RefusalReason::OverlappingMembershipPeriods,
            RefusalReason::PeriodLifetimeNotContiguous,
            RefusalReason::MembershipCoverageLost,
            RefusalReason::PeriodAbsorbed,
        ];
    }

    /** @return array<string, list<int>> period => lifetime points on 0..H-1 */
    private static function lifetimes(ResolvedMember $resolved): array
    {
        $out = [];
        foreach ($resolved->periods() as $period) {
            $out[$period->id()->admitFactId()->value()] = array_values(array_filter(range(0, self::H - 1), static fn (int $t): bool => $period->inLifetime(TimePoint::of($t))));
        }
        ksort($out);

        return $out;
    }

    /** @return array<string, list<int>> live period => lifetime, from the oracle port (a dead admission has no period) */
    private static function oracleLifetimes(G6C2CorrectionOracle $oracle, array $log): array
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

    /** @return list<Fact> */
    private static function facts(MemberHistory $h): array
    {
        return array_map(static fn (RecordedFact $r): Fact => $r->fact(), $h->member->log());
    }

    /** §14.2, recursive: live(f) ⇔ no later live Annul / Void targets f. */
    private static function live(array $facts, int $i): bool
    {
        for ($j = $i + 1; $j < count($facts); $j++) {
            if ($facts[$j] instanceof ReversalFact && $facts[$j]->target()->equals($facts[$i]->id()) && self::live($facts, $j)) {
                return false;
            }
        }

        return true;
    }

    /**
     * F ∖ D(c): c and, transitively, the reversals and corrections targeting a deleted fact and the Ends of a deleted
     * admission. Every other fact keeps its recorded consequences (no replay).
     */
    private static function without(array $facts, Fact $c): array
    {
        $deleted = [$c->id()->value() => true];
        do {
            $grew = false;
            foreach ($facts as $f) {
                $dependsOn = match (true) {
                    $f instanceof ReversalFact, $f instanceof Correct => $f->target()->value(),
                    $f instanceof End => $f->period()->admitFactId()->value(),
                    default => null,
                };
                if ($dependsOn !== null && isset($deleted[$dependsOn]) && !isset($deleted[$f->id()->value()])) {
                    $deleted[$f->id()->value()] = $grew = true;
                }
            }
        } while ($grew);

        return array_values(array_filter($facts, static fn (Fact $f): bool => !isset($deleted[$f->id()->value()])));
    }
}
