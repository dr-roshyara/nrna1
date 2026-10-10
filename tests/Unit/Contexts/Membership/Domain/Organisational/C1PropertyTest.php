<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Admit;
use App\Contexts\Membership\Domain\Organisational\End;
use App\Contexts\Membership\Domain\Organisational\Fact;
use App\Contexts\Membership\Domain\Organisational\FactId;
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
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\C1Histories;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\G6CorrectionOracle;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * C-1 properties with their contract domains (PBDIGIT-268, plan step 12; contract §13.5, §14.4),
 * checked over every kernel-accepted history within H = 5 and the stated lengths.
 *
 * They are computed by means other than the production liveness pass, to reduce the
 * correlated-error risk between the kernel and the g6 port (§13.5): fact deletion (a),
 * order swapping (e) and the recursive §14.2 definition of liveness (d).
 *
 * Status of a pass: VERIFIED WITHIN BOUNDS, not a proof.
 */
final class C1PropertyTest extends TestCase
{
    private const H = 5;

    // P-REV-V, P-REV-A, P-APPEAL (consecutive form): reversing the last fact x restores F.
    public function test_reversing_the_last_fact_restores_the_previous_state(): void
    {
        $checked = ['void' => 0, 'annul' => 0, 'appeal' => 0];
        foreach (C1Histories::accepted(self::H, 4) as [$log, $ids, $h]) {
            if ($log === []) {
                continue;
            }
            $k = count($log);
            $x = end($log);
            $before = Resolution::of(array_slice(self::facts($h), 0, -1));

            $this->assertEquals($before, (clone $h)->commit($h->void("V$k", end($ids)))->member->resolve(), 'P-REV-V');
            $checked['void']++;
            if ($x['k'] !== 'Void') {
                $this->assertEquals($before, (clone $h)->commit($h->annul("X$k", end($ids)))->member->resolve(), 'P-REV-A / P-APPEAL');
                $checked[$x['k'] === 'Annul' ? 'appeal' : 'annul']++;
            }
        }
        $this->assertGreaterThan(0, min($checked), json_encode($checked));
    }

    // (a) deletion counterfactual: Res(H·Rev(c)) = Res(H ∖ D(c)); accepted iff H ∖ D(c) satisfies G*, else the same refusal.
    public function test_a_reversal_resolves_as_deleting_its_target_and_dependants(): void
    {
        $refusedByGStar = 0;
        foreach (C1Histories::accepted(self::H, 4) as [$log, $ids, $h]) {
            $k = count($log);
            $facts = self::facts($h);
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
                foreach ($decisions as $op => $decision) {
                    $where = "$op({$ids[$i]}) after " . implode(' ', $ids);
                    if ($violations === []) {
                        $this->assertTrue($decision->isAccepted(), $where);
                        $this->assertEquals($deleted, (clone $h)->commit($decision)->member->resolve(), $where);
                    } else {
                        $this->assertSame($violations[0], $decision->refusal(), $where);
                        $refusedByGStar++;
                    }
                }
            }
        }
        $this->assertGreaterThan(0, $refusedByGStar, 'the G* branch is exercised');
    }

    // P-EQ: for a live, non-Void target, Annul and Void decide and resolve identically (resolution only).
    public function test_annul_and_void_of_the_same_target_resolve_identically(): void
    {
        foreach (C1Histories::accepted(self::H, 4) as [$log, $ids, $h]) {
            $k = count($log);
            $facts = self::facts($h);
            foreach ($facts as $i => $x) {
                if (!self::live($facts, $i) || $x instanceof VoidFact) {
                    continue;
                }
                $annul = $h->annul("R$k", $ids[$i]);
                $void = $h->void("R$k", $ids[$i]);
                $this->assertSame($annul->refusal(), $void->refusal());
                if ($annul->isAccepted()) {
                    $this->assertEquals((clone $h)->commit($annul)->member->resolve(), (clone $h)->commit($void)->member->resolve());
                }
            }
        }
    }

    // P-COMM: when both orders of two reversals are accepted, they resolve identically. The contract's
    // independence condition is sufficient for this; the test checks the weaker premise "both orders accepted".
    public function test_two_reversals_accepted_in_both_orders_commute(): void
    {
        $bothOrders = $oneOrderOnly = 0;
        foreach (C1Histories::accepted(self::H, 3) as [$log, $ids, $h]) {
            $k = count($log);
            $targets = array_keys(array_filter(self::facts($h), fn (Fact $f, int $i) => self::live(self::facts($h), $i), ARRAY_FILTER_USE_BOTH));
            foreach ($targets as $a) {
                foreach ($targets as $b) {
                    if ($a >= $b) {
                        continue;
                    }
                    foreach (['annul', 'void'] as $opA) {
                        foreach (['annul', 'void'] as $opB) {
                            $ab = $this->apply($h, [[$opA, "P$k", $ids[$a]], [$opB, "Q$k", $ids[$b]]]);
                            $ba = $this->apply($h, [[$opB, "Q$k", $ids[$b]], [$opA, "P$k", $ids[$a]]]);
                            if ($ab !== null && $ba !== null) {
                                $this->assertEquals($ab, $ba, "$opA({$ids[$a]}) / $opB({$ids[$b]}) after " . implode(' ', $ids));
                                $bothOrders++;
                            } elseif ($ab !== null || $ba !== null) {
                                $oneOrderOnly++;
                            }
                        }
                    }
                }
            }
        }
        $this->assertGreaterThan(0, $bothOrders);
        $this->assertGreaterThan(0, $oneOrderOnly, 'order-dependent acceptance exists (the §14.4 counterexample class)');
    }

    // §14.4 counterexample: disjoint targets are not enough for commutation, the claims interact.
    public function test_disjoint_targets_whose_claims_interact_do_not_commute(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A0', 0))->commit($h->end('E0', 'A0', 2))->commit($h->admit('A1', 3));

        $this->assertNotNull($this->apply($h, [['annul', 'X1', 'A1'], ['annul', 'X2', 'E0']]), 'Annul(A1) then Annul(E0) is accepted');
        $this->assertSame(RefusalReason::OverlappingMembershipPeriods, $h->annul('X2', 'E0')->refusal(), 'Annul(E0) first is refused');
    }

    // (d) C-1 closed form: lifetime(q) = [value of q's live Admit, value of q's latest live End, or ∞);
    // a reversal that passes its guards is accepted iff the closed-form lifetimes are pairwise disjoint.
    public function test_the_closed_form_gives_lifetimes_and_acceptance(): void
    {
        foreach (C1Histories::accepted(self::H, 4) as [$log, $ids, $h]) {
            $facts = self::facts($h);
            $kernel = [];
            foreach ($h->member->resolve()->periods() as $p) {
                $kernel[$p->id()->admitFactId()->value()] = [$p->baseClaim()->start()->ordinal(), $p->baseClaim()->isOpenEnded() ? null : $p->baseClaim()->until()->ordinal()];
            }
            $this->assertSame(self::closedForm($facts), $kernel, implode(' ', $ids));

            $k = count($log);
            foreach ($facts as $i => $c) {
                if (!self::live($facts, $i)) {
                    continue;
                }
                $candidate = [...$facts, new VoidFact(FactId::fromString("V$k"), $c->id(), MemberHistory::provenance())];
                $this->assertSame(self::disjoint(self::closedForm($candidate)), $h->void("V$k", $ids[$i])->isAccepted(), "Void({$ids[$i]}) after " . implode(' ', $ids));
            }
        }
    }

    // K-H1 / P-KNOWN (R-12, §14.4), over every accepted history (H = 5, length <= 5, one record per commit, recordedAt
    // strictly increasing; ties are not enumerated). For every τ before, at and after each recordedAt:
    // (i) reference: knownAt(τ) equals the g6 port's resolution of the facts recorded <= τ (no production liveness);
    // (ii) metamorphic: for F·f and τ < recordedAt(f), knownAt over F·f equals knownAt over F.
    // Sensitivity: M8 (knownAt ignores τ), M9 (recordedAt <= τ becomes < τ).
    public function test_known_at_equals_the_oracle_and_is_prefix_stable(): void
    {
        $oracle = new G6CorrectionOracle(self::H);
        $checked = 0;
        foreach (C1Histories::accepted(self::H, 5) as [$log, $ids, $h]) {
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
                $this->assertSame(self::oracleLifetimes($oracle, array_slice($log, 0, $recorded)), self::lifetimes($known), "(i) $where");
                if ($tau < end($instants)) {
                    $this->assertEquals($prefix->knownAt(Instant::of($tau)), $known, "(ii) $where");
                }
                $checked++;
            }
        }
        $this->assertGreaterThan(0, $checked);
    }

    /** @return array<string, list<int>> period => lifetime points on 0..H-1 */
    private static function lifetimes(ResolvedMember $resolved): array
    {
        $out = [];
        foreach ($resolved->periods() as $period) {
            $out[$period->id()->admitFactId()->value()] = array_values(array_filter(range(0, self::H - 1), static fn (int $t): bool => $period->inLifetime(TimePoint::of($t))));
        }

        return $out;
    }

    /** @return array<string, list<int>> live period => lifetime, from the g6 port (a dead admission has no period) */
    private static function oracleLifetimes(G6CorrectionOracle $oracle, array $log): array
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

    /** F ∖ D(c): c, the reversals targeting a deleted fact and the Ends of a deleted admission, transitively. */
    private static function without(array $facts, Fact $c): array
    {
        $deleted = [$c->id()->value() => true];
        do {
            $grew = false;
            foreach ($facts as $f) {
                $dependsOn = match (true) {
                    $f instanceof ReversalFact => $f->target()->value(),
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

    /** @return array<string, array{int, ?int}> live admission id => [start, end or null for ∞] */
    private static function closedForm(array $facts): array
    {
        $out = [];
        foreach ($facts as $a => $admit) {
            if (!$admit instanceof Admit || !self::live($facts, $a)) {
                continue;
            }
            $start = $admit->effectiveAt()->ordinal();
            $end = null;
            foreach ($facts as $e => $f) {
                if ($f instanceof End && $f->period()->equals($admit->periodId()) && self::live($facts, $e)) {
                    $end = max($start, $f->effectiveAt()->ordinal());
                }
            }
            $out[$admit->id()->value()] = [$start, $end];
        }

        return $out;
    }

    private static function disjoint(array $claims): bool
    {
        $claims = array_values($claims);
        foreach ($claims as $i => [$s1, $e1]) {
            foreach (array_slice($claims, $i + 1) as [$s2, $e2]) {
                if (($e2 === null || $s1 < $e2) && ($e1 === null || $s2 < $e1)) {
                    return false;
                }
            }
        }

        return true;
    }

    /** Applies the reversals in order; the resolution, or null as soon as one is refused. */
    private function apply(MemberHistory $h, array $steps): ?ResolvedMember
    {
        $h = clone $h;
        foreach ($steps as [$op, $id, $target]) {
            $decision = $h->$op($id, $target);
            if (!$decision->isAccepted()) {
                return null;
            }
            $h->commit($decision);
        }

        return $h->member->resolve();
    }
}
