<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational\Support;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\CommandOutcome;
use App\Contexts\Membership\Application\Organisational\EndMembership;
use App\Contexts\Membership\Application\Organisational\EndMembershipHandler;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Seeded property run shared by every MemberRepositoryPort adapter (Slice 1a / 1b).
 * Random commands over 2 tenants and 3 persons, with retries (same key, same command),
 * key reuse (same key, other command) and the three initial-term states, compared after
 * every command with the independent ReferenceMembershipModel.
 *
 * Properties: same outcome; per-Member fact order; contiguous seq = version; earlier
 * records unchanged; tenant isolation; IsMember / InForce / Lapsed / Ended / CurrentPeriod
 * at every sampled time.
 */
final class ReferencePropertyRun
{
    private const PERSONS = ['P1', 'P2', 'P3'];
    private const MAX_TIME = 21;

    /** @var array<string, int> outcome kind => count, over all seeds */
    public array $stats = [];

    /**
     * @param \Closure(): MemberRepositoryPort        $makeRepository a fresh repository per seed
     * @param \Closure(int $seed): array{0: string, 1: string} $tenants two tenant ids per seed
     * @return list<string> failures (empty = all properties held)
     */
    public function run(\Closure $makeRepository, \Closure $tenants, int $seeds, int $steps): array
    {
        for ($seed = 1; $seed <= $seeds; $seed++) {
            mt_srand($seed);
            $tenantIds = $tenants($seed);
            $repo = $makeRepository();
            $authority = new FakeAuthority();
            $admit = new AdmitMemberHandler($repo, $authority);
            $end = new EndMembershipHandler($repo, $authority);
            $model = new ReferenceMembershipModel();
            $issued = [];
            $snapshots = [];

            for ($i = 0; $i < $steps; $i++) {
                $tenant = $tenantIds[mt_rand(0, 1)];
                $person = self::PERSONS[mt_rand(0, 2)];
                $roll = mt_rand(0, 99);
                $termKind = ['none', 'until', 'unlimited'][mt_rand(0, 2)];
                if ($roll < 15 && $issued !== []) {
                    $cmd = $issued[array_rand($issued)];
                } elseif ($roll < 22 && $issued !== []) {
                    $prev = $issued[array_rand($issued)];
                    $at = mt_rand(0, 20);
                    $cmd = ['admit', $prev[1], $prev[2], 'F' . mt_rand(0, 99), $at, $prev[5], $termKind, $at + mt_rand(1, 6)];
                } elseif ($roll < 60) {
                    $at = mt_rand(0, 20);
                    $cmd = ['admit', $tenant, $person, 'F' . mt_rand(0, 30), $at, "k$i", $termKind, $at + mt_rand(1, 6)];
                } else {
                    $periods = $model->periodIds($tenant, $person);
                    $period = $periods === [] || mt_rand(0, 9) === 0 ? 'F' . mt_rand(0, 30) : $periods[array_rand($periods)];
                    $cmd = ['end', $tenant, $person, 'F' . mt_rand(0, 30), mt_rand(0, 20), "k$i", $period];
                }
                $issued[] = $cmd;

                if ($cmd[0] === 'admit') {
                    $until = $cmd[6] === 'until' ? $cmd[7] : null;
                    $command = new AdmitMember($cmd[1], $cmd[2], "M-$cmd[2]", $cmd[3], $cmd[4], $until, $cmd[6] === 'unlimited', 'registrar', null, null, $cmd[5]);
                    $expected = $model->admit($cmd[1], $cmd[2], $cmd[3], $cmd[4], $cmd[5], $command->fingerprint(), $cmd[6], $until);
                    $actual = self::normalise($admit->handle($command));
                } else {
                    $command = new EndMembership($cmd[1], "M-$cmd[2]", $cmd[3], $cmd[6], $cmd[4], 'resignation', 'registrar', null, $cmd[5]);
                    $expected = $model->end($cmd[1], $cmd[2], $cmd[3], $cmd[6], $cmd[4], $cmd[5], $command->fingerprint());
                    $actual = self::normalise($end->handle($command));
                }
                $label = $expected['kind'] . ':' . ($expected['reason'] ?? '');
                $this->stats[$label] = ($this->stats[$label] ?? 0) + 1;
                if ($expected != $actual) {
                    return ["seed $seed step $i " . json_encode($cmd) . ': expected ' . json_encode($expected) . ' got ' . json_encode($actual)];
                }

                $failures = $this->checkState($repo, $model, $tenantIds, $snapshots, "seed $seed step $i");
                if ($failures !== []) {
                    return $failures;
                }
            }
        }

        return [];
    }

    private static function normalise(CommandOutcome $o): array
    {
        return $o->isAppended()
            ? ['kind' => 'appended', 'seq' => $o->appended()->seq()]
            : ['kind' => 'refused', 'reason' => ($o->domainRefusal() ?? $o->applicationRefusal())->value];
    }

    /** @param array{0: string, 1: string} $tenantIds */
    private function checkState(MemberRepositoryPort $repo, ReferenceMembershipModel $model, array $tenantIds, array &$snapshots, string $where): array
    {
        $failures = [];
        foreach ($tenantIds as $t) {
            foreach (self::PERSONS as $p) {
                $member = $repo->findByHolder(TenantId::fromString($t), PersonRef::fromString($p));
                $facts = $model->members[$t][$p]['facts'] ?? null;
                $log = $member?->log() ?? [];
                if ($facts !== null && array_map(static fn ($r) => $r->fact()->id()->value(), $log) !== $facts) {
                    $failures[] = "$where: fact order differs for $t/$p";
                }
                if ($log !== [] && array_map(static fn ($r) => $r->seq(), $log) !== range(1, count($log))) {
                    $failures[] = "$where: seq not contiguous for $t/$p";
                }
                if ($member !== null && $member->version() !== count($log)) {
                    $failures[] = "$where: version mismatch for $t/$p";
                }
                if ($member !== null && !$member->organisation()->equals(TenantId::fromString($t))) {
                    $failures[] = "$where: tenant isolation for $t/$p";
                }
                foreach ($snapshots["$t/$p"] ?? [] as $k => $old) {
                    if (!isset($log[$k]) || $log[$k] != $old) {
                        $failures[] = "$where: record $k of $t/$p changed (append-only)";
                    }
                }
                $snapshots["$t/$p"] = $log;

                $resolved = $member?->resolve();
                for ($time = 0; $time <= self::MAX_TIME; $time++) {
                    $tp = TimePoint::of($time);
                    if (($resolved?->isMember($tp) ?? false) !== $model->isMember($t, $p, $time)) {
                        $failures[] = "$where: IsMember of $t/$p differs at $time";
                    }
                    if (($resolved?->currentPeriod($tp)?->value()) !== $model->currentPeriod($t, $p, $time)) {
                        $failures[] = "$where: CurrentPeriod of $t/$p differs at $time";
                    }
                    foreach ($model->periodIds($t, $p) as $period) {
                        $rp = $resolved?->period(PeriodId::fromString($period));
                        $actual = [$rp?->isInForce($tp), $rp?->isLapsed($tp), $rp?->isEnded($tp)];
                        $expected = [$model->inForce($t, $p, $period, $time), $model->lapsed($t, $p, $period, $time), $model->ended($t, $p, $period, $time)];
                        if ($actual !== $expected) {
                            $failures[] = "$where: InForce/Lapsed/Ended of $t/$p/$period differ at $time";
                        }
                    }
                }
            }
        }

        return $failures;
    }
}
