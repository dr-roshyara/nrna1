<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational\Support;

/**
 * Independent reference model for Slice 1a (contract §1–§5). Plain arrays; it
 * shares no code with production (no Member, Resolution, port or handler).
 *
 * Given the history so far and a command, it predicts the outcome and the
 * resulting store state.
 */
final class ReferenceMembershipModel
{
    /** @var array<string, array<string, array{facts: list<string>, periods: array<string, array{0:int,1:?int}>}>> tenant => person => member */
    public array $members = [];
    /** @var array<string, array<string, array{fp: string, out: array}>> tenant => key => applied */
    private array $applied = [];

    /** @return array{kind: string, reason?: string, seq?: int} */
    public function admit(string $tenant, string $person, string $factId, int $at, string $key, string $fp): array
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
        $m['periods'][$factId] = [$at, null];
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
        [$start, $end] = $m['periods'][$period];
        if ($end !== null) {
            return ['kind' => 'refused', 'reason' => 'period_already_ended'];
        }
        if ($at <= $start) {
            return ['kind' => 'refused', 'reason' => 'end_not_after_period_start'];
        }
        $m['facts'][] = $factId;
        $m['periods'][$period] = [$start, $at];
        $this->members[$tenant][$person] = $m;

        return $this->remember($tenant, $key, $fp, ['kind' => 'appended', 'seq' => count($m['facts'])]);
    }

    /** IsMember at t, straight from the recorded periods (every admission in this model has an unlimited term). */
    public function isMember(string $tenant, string $person, int $t): bool
    {
        foreach ($this->members[$tenant][$person]['periods'] ?? [] as [$start, $end]) {
            if ($start <= $t && ($end === null || $t < $end)) {
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
