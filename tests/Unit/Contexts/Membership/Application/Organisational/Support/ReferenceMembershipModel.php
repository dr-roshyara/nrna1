<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational\Support;

/**
 * Independent reference model for Slice 1a (contract §1–§5). Plain arrays; it
 * shares no code with production (no Member, Resolution, port or handler).
 *
 * Given the history so far and a command, it predicts the outcome, the resulting
 * store state and the membership predicates (IsMember, InForce, Lapsed, Ended,
 * CurrentPeriod), including the initial-term states none / until / unlimited.
 */
final class ReferenceMembershipModel
{
    /** @var array<string, array<string, array{facts: list<string>, periods: array<string, array{0:int,1:?int,2:string,3:?int}>}>> tenant => person => member; period = [start, end, termKind, termUntil] */
    public array $members = [];
    /** @var array<string, array<string, array{fp: string, out: array}>> tenant => key => applied */
    private array $applied = [];

    /**
     * @param 'none'|'until'|'unlimited' $termKind
     * @return array{kind: string, reason?: string, seq?: int}
     */
    public function admit(string $tenant, string $person, string $factId, int $at, string $key, string $fp, string $termKind = 'unlimited', ?int $termUntil = null): array
    {
        if ($seen = $this->applied[$tenant][$key] ?? null) {
            return $seen['fp'] === $fp ? $seen['out'] : ['kind' => 'refused', 'reason' => 'idempotency_key_conflict'];
        }
        $m = $this->members[$tenant][$person] ?? ['facts' => [], 'periods' => []];
        if (in_array($factId, $m['facts'], true)) {
            return ['kind' => 'refused', 'reason' => 'duplicate_fact_id'];
        }
        foreach ($m['periods'] as [$start, $end]) {
            if ($end === null || $end > $at) {
                return ['kind' => 'refused', 'reason' => 'overlapping_membership_periods'];   // contract Theorem 3
            }
        }
        $m['facts'][] = $factId;
        $m['periods'][$factId] = [$at, null, $termKind, $termUntil];
        $this->members[$tenant][$person] = $m;

        return $this->remember($tenant, $key, $fp, ['kind' => 'appended', 'seq' => count($m['facts'])]);
    }

    /** @return array{kind: string, reason?: string, seq?: int} */
    public function end(string $tenant, string $person, string $factId, string $period, int $at, string $key, string $fp): array
    {
        if ($seen = $this->applied[$tenant][$key] ?? null) {
            return $seen['fp'] === $fp ? $seen['out'] : ['kind' => 'refused', 'reason' => 'idempotency_key_conflict'];
        }
        $m = $this->members[$tenant][$person] ?? null;
        if ($m === null) {
            return ['kind' => 'refused', 'reason' => 'member_not_found'];
        }
        if (in_array($factId, $m['facts'], true)) {
            return ['kind' => 'refused', 'reason' => 'duplicate_fact_id'];
        }
        if (!isset($m['periods'][$period])) {
            return ['kind' => 'refused', 'reason' => 'period_not_found'];
        }
        [$start, $end, $termKind, $termUntil] = $m['periods'][$period];
        if ($end !== null) {
            return ['kind' => 'refused', 'reason' => 'period_already_ended'];
        }
        if ($at <= $start) {
            return ['kind' => 'refused', 'reason' => 'end_not_after_period_start'];
        }
        $m['facts'][] = $factId;
        $m['periods'][$period] = [$start, $at, $termKind, $termUntil];
        $this->members[$tenant][$person] = $m;

        return $this->remember($tenant, $key, $fp, ['kind' => 'appended', 'seq' => count($m['facts'])]);
    }

    /** @return list<string> the period ids of a person, in admission order */
    public function periodIds(string $tenant, string $person): array
    {
        return array_keys($this->members[$tenant][$person]['periods'] ?? []);
    }

    public function inLifetime(string $tenant, string $person, string $period, int $t): bool
    {
        [$start, $end] = $this->members[$tenant][$person]['periods'][$period];

        return $start <= $t && ($end === null || $t < $end);
    }

    public function ended(string $tenant, string $person, string $period, int $t): bool
    {
        $end = $this->members[$tenant][$person]['periods'][$period][1];

        return $end !== null && $t >= $end;
    }

    public function inForce(string $tenant, string $person, string $period, int $t): bool
    {
        [$start, , $kind, $until] = $this->members[$tenant][$person]['periods'][$period];
        $covered = match ($kind) {
            'none' => false,
            'unlimited' => $t >= $start,
            'until' => $start <= $t && $t < $until,
        };

        return $covered && $this->inLifetime($tenant, $person, $period, $t);
    }

    public function lapsed(string $tenant, string $person, string $period, int $t): bool
    {
        return $this->inLifetime($tenant, $person, $period, $t) && !$this->inForce($tenant, $person, $period, $t);
    }

    public function currentPeriod(string $tenant, string $person, int $t): ?string
    {
        foreach ($this->periodIds($tenant, $person) as $period) {
            if ($this->inLifetime($tenant, $person, $period, $t)) {
                return $period;
            }
        }

        return null;
    }

    public function isMember(string $tenant, string $person, int $t): bool
    {
        foreach ($this->periodIds($tenant, $person) as $period) {
            if ($this->inForce($tenant, $person, $period, $t)) {
                return true;
            }
        }

        return false;
    }

    private function remember(string $tenant, string $key, string $fp, array $out): array
    {
        $this->applied[$tenant][$key] = ['fp' => $fp, 'out' => $out];

        return $out;
    }
}
