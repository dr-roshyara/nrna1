<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational\Support;

/**
 * PHP port of the g6 correction oracle (`docs/publicdigit/membership/architecture/models/g6_correction_oracle.py`)
 * for the C-2 fragment (PBDIGIT-327, COR-2 plan steps 1–2), with the restrictions of
 * `docs/publicdigit/membership/architecture/models/g6_c2_golden.py`:
 * - D-COR-2: Annul / Void of a non-live target → TargetNotLive;
 * - P-1: a Correct whose chain root is voided → CorrectOfVoidedFact;
 * - P-3: a Correct to the target's current value → ValueUnchanged;
 * - P-4: a Correct of an End to a value at or before its period's start, or of an Admit to a value at or after
 *   its period's live End → EndNotAfterPeriodStart;
 * - BD-REV-ER = refuse → PeriodAbsorbed.
 * P-5 (dating rules) is not modelled (no decision date).
 *
 * Deliberately shares NO code with production: no Resolution, Member, Interval or value object.
 * Discrete valid time 0..H-1, ∞ represented as H; a time set is a sorted list of ints.
 * A fact: ['k' => kind, 'pid' => ?int, 'v' => ?int, 't' => ?int (target index), 'cons' => list<array{int, list<int>}>]
 * where a consequence is [the affected period's Admit index, the removed times] (absolute, as the oracle).
 *
 * Evidence, not the contract: agreement is VERIFIED WITHIN BOUNDS only.
 */
final class G6C2CorrectionOracle
{
    private const REFUSAL = [
        'PeriodNotFound' => 'period_not_found',
        'PeriodAlreadyEnded' => 'period_already_ended',
        'EndNotAfterPeriodStart' => 'end_not_after_period_start',
        'N3_AnnulOfVoid' => 'annul_of_void_refused',
        'N3_CorrectOfAnnulOrVoid' => 'correction_of_annul_or_void_refused',
        'CorrectOfVoidedRefused' => 'correct_of_voided_fact',
        'ValueUnchanged' => 'value_unchanged',
        'TargetNotLive' => 'target_not_live',
        'INV9_overlap' => 'overlapping_membership_periods',
        'INV_EP_fragmented' => 'period_lifetime_not_contiguous',
        'INV_COV' => 'membership_coverage_lost',
        'BDREVER_absorption' => 'period_absorbed',
        'DuplicatePeriod' => 'duplicate_period',
    ];

    public function __construct(private readonly int $h)
    {
    }

    /**
     * Every decision reachable within L, breadth first, in the generator's order.
     *
     * @return \Generator<array{int, string, ?string}> [depth, canonical line, refusal value or null]
     */
    public static function enumerate(int $h, int $l): \Generator
    {
        $oracle = new self($h);
        $frontier = [[]];
        for ($depth = 1; $depth <= $l; $depth++) {
            $next = [];
            foreach ($frontier as $s) {
                foreach ($oracle->candidates($s) as $f) {
                    [$refusal, $log] = $oracle->decide($s, $f);
                    yield [$depth, $oracle->line([...$s, $f], $refusal, $log), $refusal];
                    if ($refusal === null) {
                        $next[] = $log;
                    }
                }
            }
            $frontier = $next;
        }
    }

    /** @return list<array> the candidate facts after S, in the Python oracle's order */
    public function candidates(array $s): array
    {
        $pids = array_values($this->admits($s));
        $next = $pids === [] ? 0 : max($pids) + 1;
        $out = [];
        for ($t = 0; $t < $this->h; $t++) {
            $out[] = self::fact('Admit', pid: $next, v: $t);
        }
        foreach ($pids as $p) {
            for ($t = 1; $t < $this->h; $t++) {
                $out[] = self::fact('End', pid: $p, v: $t);
            }
        }
        foreach (array_keys($s) as $i) {
            $out[] = self::fact('Annul', t: $i);
            $out[] = self::fact('Void', t: $i);
            for ($t = 0; $t < $this->h; $t++) {
                $out[] = self::fact('Correct', v: $t, t: $i);
            }
        }

        return $out;
    }

    /** @return array{?string, ?array} [refusal value or null, the accepted log (with the decided fact's pid and cons)] */
    public function decide(array $s, array $f): array
    {
        $r = $this->structuralRefusal($s, $f);
        if ($r === null && in_array($f['k'], ['Annul', 'Void'], true) && !$this->live($s, $f['t'])) {
            $r = 'TargetNotLive';
        }
        if ($r === null && $f['k'] === 'Correct') {
            $r = $this->c2CorrectRefusal($s, $f);
        }
        if ($r !== null) {
            return [self::REFUSAL[$r], null];
        }
        if (in_array($f['k'], ['Annul', 'Void', 'Correct'], true)) {
            $f['pid'] = $s[$this->root($s, $f['t'])]['pid'];
        }
        $f['cons'] = $this->g2($s, $f);
        $log = [...$s, $f];
        $v = $this->invariantViolation($log);
        if ($v !== null) {
            return [self::REFUSAL[$v], null];
        }
        foreach (array_keys($this->admits($log)) as $a) {   // BD-REV-ER = refuse
            if ($this->live($log, $a) && $this->baseClaim($log, $a) !== [] && $this->lifetime($log, $a) === []) {
                return [self::REFUSAL['BDREVER_absorption'], null];
            }
        }

        return [null, $log];
    }

    /** @return array<int, list<int>> pid => lifetime, per Admit in log order */
    public function resolve(array $f): array
    {
        $out = [];
        foreach ($this->admits($f) as $a => $pid) {
            $out[$pid] = $this->lifetime($f, $a);
        }

        return $out;
    }

    public function line(array $facts, ?string $refusal, ?array $log): string
    {
        $tokens = implode(' ', array_map(static fn (array $x): string => match ($x['k']) {
            'Admit' => "A{$x['pid']}@{$x['v']}",
            'End' => "E{$x['pid']}@{$x['v']}",
            'Correct' => "C{$x['t']}@{$x['v']}",
            'Annul' => "N{$x['t']}",
            'Void' => "V{$x['t']}",
        }, $facts));
        if ($refusal !== null) {
            return "$tokens|$refusal|-|-";
        }
        $res = [];
        foreach ($this->resolve($log) as $pid => $lifetime) {
            $res[] = "$pid:" . implode(',', $lifetime);
        }
        $cons = [];
        foreach ($log[count($log) - 1]['cons'] as [$q, $x]) {
            $cons[] = $log[$q]['pid'] . ':' . implode(',', $x);
        }

        return "$tokens|ok|" . implode(';', $res) . '|' . implode(';', $cons);
    }

    // ── the oracle's definitions (final-proof §14.2; canonical §3) ──────────────────────────────────

    private static function fact(string $k, ?int $pid = null, ?int $v = null, ?int $t = null): array
    {
        return ['k' => $k, 'pid' => $pid, 'v' => $v, 't' => $t, 'cons' => []];
    }

    public function live(array $f, int $i): bool
    {
        for ($j = $i + 1, $n = count($f); $j < $n; $j++) {
            if (($f[$j]['k'] === 'Annul' || $f[$j]['k'] === 'Void') && $f[$j]['t'] === $i && $this->live($f, $j)) {
                return false;
            }
        }

        return true;
    }

    private function root(array $f, int $i): int
    {
        return $f[$i]['k'] !== 'Correct' ? $i : $this->root($f, $f[$i]['t']);
    }

    private function active(array $f, int $i): bool
    {
        return $this->live($f, $i) && ($f[$i]['k'] !== 'Correct' || $this->active($f, $f[$i]['t']));
    }

    private function superseded(array $f, int $i): bool
    {
        for ($j = $i + 1, $n = count($f); $j < $n; $j++) {
            if ($f[$j]['k'] === 'Correct' && $f[$j]['t'] === $i && $this->live($f, $j)) {
                return true;
            }
        }

        return false;
    }

    private function value(array $f, int $i): int
    {
        $latest = null;
        for ($j = $i + 1, $n = count($f); $j < $n; $j++) {
            if ($f[$j]['k'] === 'Correct' && $f[$j]['t'] === $i && $this->live($f, $j)) {
                $latest = $j;
            }
        }

        return $latest === null ? $f[$i]['v'] : $this->value($f, $latest);
    }

    /** @return array<int, int> Admit index => pid */
    private function admits(array $f): array
    {
        $out = [];
        foreach ($f as $i => $x) {
            if ($x['k'] === 'Admit') {
                $out[$i] = $x['pid'];
            }
        }

        return $out;
    }

    /** @return list<int> [value(Admit), max(value(Admit), value(latest live End))) or to ∞; [] when the Admit is not live */
    private function baseClaim(array $f, int $a): array
    {
        if (!$this->live($f, $a)) {
            return [];
        }
        $s = $this->value($f, $a);
        $lastEnd = null;
        foreach ($f as $i => $x) {
            if ($x['k'] === 'End' && $x['pid'] === $f[$a]['pid'] && $this->live($f, $i)) {
                $lastEnd = $i;
            }
        }
        $e = $lastEnd === null ? $this->h : max($s, $this->value($f, $lastEnd));

        return $s < $e ? range($s, $e - 1) : [];
    }

    private function periodAdmit(array $f, int $i): int
    {
        foreach ($f as $k => $x) {
            if ($x['k'] === 'Admit' && $x['pid'] === $f[$i]['pid']) {
                return $k;
            }
        }
        throw new \LogicException('no Admit for the period');
    }

    private function targetPeriodAdmit(array $f, int $i): int
    {
        $r = $this->root($f, $i);

        return $f[$r]['k'] === 'Admit' ? $r : $this->periodAdmit($f, $r);
    }

    /** @return list<int> */
    private function liveCutsOn(array $f, int $qAdmit): array
    {
        $out = [];
        foreach ($f as $i => $x) {
            foreach ($x['cons'] as [$q, $cut]) {
                if ($q === $qAdmit && $this->active($f, $i) && !$this->superseded($f, $i)) {
                    $out = self::union($out, self::intersect($cut, $this->baseClaim($f, $this->targetPeriodAdmit($f, $i))));
                }
            }
        }

        return $out;
    }

    /** @return list<int> */
    private function lifetime(array $f, int $a): array
    {
        return self::diff($this->baseClaim($f, $a), $this->liveCutsOn($f, $a));
    }

    /** @return list<array{int, list<int>}> the frozen consequences of f appended to S (trim policy) */
    private function g2(array $s, array $f): array
    {
        if ($f['k'] !== 'End' && $f['k'] !== 'Correct') {
            return [];
        }
        $sf = [...$s, $f];
        $p = $this->targetPeriodAdmit($sf, count($s));
        $delta = self::diff(self::diff($this->baseClaim($sf, $p), $this->baseClaim($s, $p)), $this->liveCutsOn($s, $p));
        $cons = [];
        foreach (array_keys($this->admits($s)) as $q) {
            if ($q === $p) {
                continue;
            }
            $x = self::intersect($delta, $this->lifetime($s, $q));
            if ($x !== []) {
                $cons[] = [$q, $x];
            }
        }
        if ($f['k'] === 'Correct') {
            foreach ($s[$f['t']]['cons'] as [$q, $x]) {
                $y = self::intersect($x, $this->baseClaim($sf, $p));
                if ($y !== []) {
                    $cons[] = [$q, $y];
                }
            }
        }

        return $cons;
    }

    private function structuralRefusal(array $s, array $f): ?string
    {
        if ($f['k'] === 'Admit') {
            return in_array($f['pid'], $this->admits($s), true) ? 'DuplicatePeriod' : null;
        }
        if ($f['k'] === 'End') {
            $a = array_search($f['pid'], $this->admits($s), true);
            if ($a === false || !$this->live($s, $a)) {
                return 'PeriodNotFound';
            }
            foreach ($s as $i => $x) {
                if ($x['k'] === 'End' && $x['pid'] === $f['pid'] && $this->live($s, $i)) {
                    return 'PeriodAlreadyEnded';
                }
            }

            return $this->value($s, $a) < $f['v'] ? null : 'EndNotAfterPeriodStart';
        }
        $target = $s[$f['t']];
        if ($f['k'] === 'Annul' && $target['k'] === 'Void') {
            return 'N3_AnnulOfVoid';
        }
        if ($f['k'] === 'Correct') {
            if ($target['k'] === 'Annul' || $target['k'] === 'Void') {
                return 'N3_CorrectOfAnnulOrVoid';
            }
            if ($this->voided($s, $f['t'])) {
                return 'CorrectOfVoidedRefused';
            }
        }

        return null;
    }

    /** P-1, P-3, P-4 (the generator's c2_decide), after the structural guards. */
    private function c2CorrectRefusal(array $s, array $f): ?string
    {
        $r0 = $this->root($s, $f['t']);
        if ($this->voided($s, $r0)) {
            return 'CorrectOfVoidedRefused';
        }
        if ($f['v'] === $this->value($s, $f['t'])) {
            return 'ValueUnchanged';
        }
        if ($s[$r0]['k'] === 'End') {
            return $this->value($s, $this->periodAdmit($s, $r0)) < $f['v'] ? null : 'EndNotAfterPeriodStart';
        }
        $lastEnd = null;
        foreach ($s as $i => $x) {
            if ($x['k'] === 'End' && $x['pid'] === $s[$r0]['pid'] && $this->live($s, $i)) {
                $lastEnd = $i;
            }
        }
        if ($lastEnd !== null && !($f['v'] < $this->value($s, $lastEnd))) {
            return 'EndNotAfterPeriodStart';
        }

        return null;
    }

    private function voided(array $s, int $i): bool
    {
        for ($j = $i + 1, $n = count($s); $j < $n; $j++) {
            if ($s[$j]['k'] === 'Void' && $s[$j]['t'] === $i && $this->live($s, $j)) {
                return true;
            }
        }

        return false;
    }

    private function invariantViolation(array $f): ?string
    {
        $lifetimes = array_values($this->resolve($f));
        foreach ($lifetimes as $i => $a) {
            foreach (array_slice($lifetimes, $i + 1) as $b) {
                if (array_intersect($a, $b) !== []) {
                    return 'INV9_overlap';
                }
            }
        }
        foreach ($lifetimes as $l) {
            if ($l !== [] && max($l) - min($l) + 1 !== count($l)) {
                return 'INV_EP_fragmented';
            }
        }
        if ($lifetimes !== []) {
            $claims = [];
            foreach (array_keys($this->admits($f)) as $a) {
                $claims = self::union($claims, $this->baseClaim($f, $a));
            }
            if (self::union([], ...$lifetimes) !== $claims) {
                return 'INV_COV';
            }
        }

        return null;
    }

    // ── sorted int-set helpers ─────────────────────────────────────────────────────────────────────

    /** @return list<int> */
    private static function union(array ...$sets): array
    {
        $u = array_values(array_unique(array_merge(...$sets)));
        sort($u);

        return $u;
    }

    /** @return list<int> */
    private static function intersect(array $a, array $b): array
    {
        return array_values(array_intersect($a, $b));
    }

    /** @return list<int> */
    private static function diff(array $a, array $b): array
    {
        return array_values(array_diff($a, $b));
    }
}
