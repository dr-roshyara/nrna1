<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\CommandOutcome;
use App\Contexts\Membership\Application\Organisational\EndMembership;
use App\Contexts\Membership\Application\Organisational\EndMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\ReferenceMembershipModel;

/**
 * Seeded property test (Slice 1a contract): handlers + repository + Slice 0 resolution
 * against the independent ReferenceMembershipModel. Random commands over 2 tenants and
 * 3 persons, with retries (same key, same command) and key reuse (same key, other command).
 *
 * Properties after every command: same outcome; same per-Member fact order; contiguous
 * seq (= version); earlier records unchanged (append-only); tenant isolation; replay
 * equivalence of IsMember. PBDIGIT-259.
 */
final class ReferenceModelPropertyTest extends TestCase
{
    private const SEEDS = 200;
    private const STEPS = 40;
    private const TENANTS = ['O1', 'O2'];
    private const PERSONS = ['P1', 'P2', 'P3'];

    private static function normalise(CommandOutcome $o): array
    {
        if ($o->isAppended()) {
            return ['kind' => 'appended', 'seq' => $o->appended()->seq()];
        }

        return ['kind' => 'refused', 'reason' => ($o->domainRefusal() ?? $o->applicationRefusal())->value];
    }

    public function test_handlers_and_repository_agree_with_the_reference_model(): void
    {
        $failures = [];
        for ($seed = 1; $seed <= self::SEEDS; $seed++) {
            mt_srand($seed);
            $repo = new InMemoryMemberRepository(static fn (): int => 1000);
            $authority = new FakeAuthority();
            $admit = new AdmitMemberHandler($repo, $authority);
            $end = new EndMembershipHandler($repo, $authority);
            $model = new ReferenceMembershipModel();
            $issued = [];   // previously issued commands, for retries / key reuse
            $snapshots = [];

            for ($i = 0; $i < self::STEPS; $i++) {
                $tenant = self::TENANTS[mt_rand(0, 1)];
                $person = self::PERSONS[mt_rand(0, 2)];
                $roll = mt_rand(0, 99);
                if ($roll < 15 && $issued !== []) {
                    $cmd = $issued[array_rand($issued)];                         // retry (same key, same command)
                } elseif ($roll < 22 && $issued !== []) {
                    $prev = $issued[array_rand($issued)];                        // key reuse with another command
                    $cmd = ['admit', $prev[1], $prev[2], 'F' . mt_rand(0, 99), mt_rand(0, 20), $prev[5]];
                } elseif ($roll < 60) {
                    $cmd = ['admit', $tenant, $person, 'F' . mt_rand(0, 30), mt_rand(0, 20), "k$i"];
                } else {
                    $periods = array_keys($model->members[$tenant][$person]['periods'] ?? []);
                    $period = $periods === [] || mt_rand(0, 9) === 0 ? 'F' . mt_rand(0, 30) : $periods[array_rand($periods)];
                    $cmd = ['end', $tenant, $person, 'F' . mt_rand(0, 30), mt_rand(0, 20), "k$i", $period];
                }
                $issued[] = $cmd;

                if ($cmd[0] === 'admit') {
                    $command = new AdmitMember($cmd[1], $cmd[2], "M-$cmd[1]-$cmd[2]", $cmd[3], $cmd[4], null, true, 'registrar', null, null, $cmd[5]);
                    $expected = $model->admit($cmd[1], $cmd[2], $cmd[3], $cmd[4], $cmd[5], $command->fingerprint());
                    $actual = self::normalise($admit->handle($command));
                } else {
                    $command = new EndMembership($cmd[1], "M-$cmd[1]-$cmd[2]", $cmd[3], $cmd[6], $cmd[4], 'resignation', 'registrar', null, $cmd[5]);
                    $expected = $model->end($cmd[1], $cmd[2], $cmd[3], $cmd[6], $cmd[4], $cmd[5], $command->fingerprint());
                    $actual = self::normalise($end->handle($command));
                }
                if ($expected != $actual) {
                    $failures[] = "seed $seed step $i " . json_encode($cmd) . ': expected ' . json_encode($expected) . ' got ' . json_encode($actual);
                    break;
                }

                foreach (self::TENANTS as $t) {
                    foreach (self::PERSONS as $p) {
                        $member = $repo->findByHolder(TenantId::fromString($t), PersonRef::fromString($p));
                        $facts = $model->members[$t][$p]['facts'] ?? null;
                        $log = $member?->log() ?? [];
                        if ($facts !== null && array_map(static fn ($r) => $r->fact()->id()->value(), $log) !== $facts) {
                            $failures[] = "seed $seed step $i: fact order differs for $t/$p";
                        }
                        if ($member !== null && array_map(static fn ($r) => $r->seq(), $log) !== range(1, max(1, count($log))) && $log !== []) {
                            $failures[] = "seed $seed step $i: seq not contiguous for $t/$p";
                        }
                        if ($member !== null && $member->version() !== count($log)) {
                            $failures[] = "seed $seed step $i: version mismatch";
                        }
                        foreach ($snapshots["$t/$p"] ?? [] as $k => $old) {
                            if (!isset($log[$k]) || $log[$k] != $old) {
                                $failures[] = "seed $seed step $i: record $k of $t/$p changed (append-only)";
                            }
                        }
                        $snapshots["$t/$p"] = $log;
                        if ($member !== null && !$member->organisation()->equals(TenantId::fromString($t))) {
                            $failures[] = "seed $seed step $i: tenant isolation";
                        }
                        for ($time = 0; $time <= 21; $time++) {
                            if (($member?->resolve()->isMember(TimePoint::of($time)) ?? false) !== $model->isMember($t, $p, $time)) {
                                $failures[] = "seed $seed step $i: replay of $t/$p differs at $time";
                                break;
                            }
                        }
                    }
                }
                if ($failures !== []) {
                    break 2;
                }
            }
        }

        $this->assertSame([], $failures);
    }
}
