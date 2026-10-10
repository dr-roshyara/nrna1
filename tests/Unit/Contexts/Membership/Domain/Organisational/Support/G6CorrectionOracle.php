<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational\Support;

/**
 * PHP port of the g6 correction oracle (`docs/publicdigit/membership/architecture/models/
 * g6_correction_oracle.py`), restricted to the C-1 fragment (PBDIGIT-268, plan step 10):
 * Admit, End, Annul, Void; no Correct (C-2); D-COR-2 (TargetNotLive) after the structural guards.
 *
 * Deliberately shares NO code with production: no Resolution, Member, Interval or value object.
 * Discrete valid time 0..H-1, ∞ represented as H; a lifetime is a sorted list of time points.
 * Facts: ['k' => 'Admit'|'End', 'pid' => int, 'v' => int] or ['k' => 'Annul'|'Void', 't' => fact index].
 *
 * Consequences are omitted: in C-1 only End could carry them and an End never widens a claim,
 * so g2 yields none (D-COR-5 (A): Annul / Void carry none). The golden test checks the omission.
 *
 * Evidence, not the contract (PO constraint 4): agreement is VERIFIED WITHIN BOUNDS only.
 */
final class G6CorrectionOracle
{
    private const REFUSAL = [
        'DuplicatePeriod' => 'duplicate_period',
        'PeriodNotFound' => 'period_not_found',
        'PeriodAlreadyEnded' => 'period_already_ended',
        'EndNotAfterPeriodStart' => 'end_not_after_period_start',
        'N3_AnnulOfVoid' => 'annul_of_void_refused',
        'TargetNotLive' => 'target_not_live',
        'INV9_overlap' => 'overlapping_membership_periods',
        'INV_EP_fragmented' => 'period_lifetime_not_contiguous',
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
                    $refusal = $oracle->decide($s, $f);
                    $log = [...$s, $f];
                    yield [$depth, $oracle->line($log, $refusal), $refusal];
                    if ($refusal === null) {
                        $next[] = $log;
                    }
                }
            }
            $frontier = $next;
        }
    }

    /** @return list<array> the candidate facts after S, in the Python oracle's order (Correct excluded) */
    public function candidates(array $s): array
    {
        $pids = array_values($this->admits($s));
        $next = $pids === [] ? 0 : max($pids) + 1;
        $out = [];
        for ($t = 0; $t < $this->h; $t++) {
            $out[] = ['k' => 'Admit', 'pid' => $next, 'v' => $t];
        }
        foreach ($pids as $p) {
            for ($t = 1; $t < $this->h; $t++) {
                $out[] = ['k' => 'End', 'pid' => $p, 'v' => $t];
            }
        }
        foreach (array_keys($s) as $i) {
            $out[] = ['k' => 'Annul', 't' => $i];
            $out[] = ['k' => 'Void', 't' => $i];
        }

        return $out;
    }

    /** null when accepted, else the kernel RefusalReason value. */
    public function decide(array $s, array $f): ?string
    {
        $r = $this->structuralRefusal($s, $f) ?? $this->invariantViolation([...$s, $f]);

        return $r === null ? null : self::REFUSAL[$r];
    }

    /** @return array<int, list<int>> pid => lifetime, per Admit in log order */
    public function resolve(array $f): array
    {
        $out = [];
        foreach ($this->admits($f) as $a => $pid) {
            $out[$pid] = $this->baseClaim($f, $a);
        }

        return $out;
    }

    public function live(array $f, int $i): bool
    {
        for ($j = $i + 1; $j < count($f); $j++) {
            if (in_array($f[$j]['k'], ['Annul', 'Void'], true) && $f[$j]['t'] === $i && $this->live($f, $j)) {
                return false;
            }
        }

        return true;
    }

    public function line(array $log, ?string $refusal): string
    {
        $facts = implode(' ', array_map(static fn (array $x): string => match ($x['k']) {
            'Admit' => "A{$x['pid']}@{$x['v']}",
            'End' => "E{$x['pid']}@{$x['v']}",
            'Annul' => "N{$x['t']}",
            'Void' => "V{$x['t']}",
        }, $log));
        if ($refusal !== null) {
            return "$facts|$refusal|-";
        }
        $res = [];
        foreach ($this->resolve($log) as $pid => $lifetime) {
            $res[] = "$pid:" . implode(',', $lifetime);
        }

        return "$facts|ok|" . implode(';', $res);
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

    /** @return list<int> [start, max(start, end)) over live facts; [start, ∞) without a live End */
    private function baseClaim(array $f, int $a): array
    {
        if (!$this->live($f, $a)) {
            return [];
        }
        $s = $f[$a]['v'];
        $end = null;
        foreach ($f as $i => $x) {
            if ($x['k'] === 'End' && $x['pid'] === $f[$a]['pid'] && $this->live($f, $i)) {
                $end = $x['v'];
            }
        }
        $e = $end === null ? $this->h : max($s, $end);

        return $s < $e ? range($s, $e - 1) : [];
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

            return $s[$a]['v'] < $f['v'] ? null : 'EndNotAfterPeriodStart';
        }
        if ($f['k'] === 'Annul' && $s[$f['t']]['k'] === 'Void') {
            return 'N3_AnnulOfVoid';
        }

        return $this->live($s, $f['t']) ? null : 'TargetNotLive';
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
        // INV-COV (∪ lifetime = ∪ BaseClaim) is not checked: without consequences lifetime = BaseClaim, so it cannot fire in C-1.

        return null;
    }
}
