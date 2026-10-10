<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Appended;
use App\Contexts\Membership\Application\Organisational\CommandOutcome;
use App\Contexts\Membership\Application\Organisational\DecideAndAppend;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\AppendResult;
use App\Contexts\Membership\Application\Organisational\Ports\AppliedCommand;
use App\Contexts\Membership\Application\Organisational\Ports\CreateResult;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * E9 (Option E): every AppendOutcome has an explicit application result, and only a
 * concurrency conflict is re-decided (R-1a-2). Rows come from the enum's actual cases;
 * the expected table is keyed by value, so a missing or an unexpected case fails an assertion.
 */
final class AppendOutcomeMappingTest extends TestCase
{
    /** outcome value => [application result, calls to append] */
    private const EXPECTED = [
        'appended' => ['appended', 1],
        'already_applied' => ['appended', 1],          // IDEM-1: the original Appended, as for the first call
        'idempotency_key_conflict' => ['idempotency_key_conflict', 1],
        'member_not_found' => ['member_not_found', 1],
        'valid_time_not_storable' => ['valid_time_not_storable', 1],
        'concurrency_conflict' => ['concurrent_modification', DecideAndAppend::MAX_ATTEMPTS],
    ];

    public function test_every_append_outcome_has_an_expected_application_result(): void
    {
        $actual = array_map(static fn (AppendOutcome $o): string => $o->value, AppendOutcome::cases());
        $expected = array_keys(self::EXPECTED);
        sort($actual);
        sort($expected);

        $this->assertSame($expected, $actual, 'AppendOutcome cases and the expected mapping table differ');
    }

    public function test_each_append_outcome_maps_to_its_result_and_only_a_conflict_is_retried(): void
    {
        foreach (AppendOutcome::cases() as $case) {
            $this->assertArrayHasKey($case->value, self::EXPECTED, "no expected mapping for {$case->value}");
            [$result, $calls] = self::EXPECTED[$case->value];
            $repository = self::alwaysAnswering($case);

            $outcome = self::admitThrough($repository);

            $this->assertSame($result, self::describe($outcome), "result for {$case->value}");
            $this->assertSame($calls, $repository->appends, "append calls for {$case->value}");
        }
    }

    private static function admitThrough(MemberRepositoryPort $repository): CommandOutcome
    {
        $tenant = TenantId::fromString('O1');
        $member = Member::fromLog(MemberId::fromString('M1'), PersonRef::fromString('P1'), $tenant);

        return (new DecideAndAppend($repository, new FakeAuthority()))->run(
            $tenant,
            IdempotencyKey::fromString('k1'),
            'fp1',
            'registrar',
            MembershipAct::Admit,
            static fn (): Member => $member,
            static fn (Member $m, AuthorityEvidence $evidence): Decision => $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(1), TimePoint::infinity(), Provenance::decision('registrar', $evidence)),
        );
    }

    private static function describe(CommandOutcome $outcome): string
    {
        if ($outcome->isAppended()) {
            return 'appended';
        }

        return $outcome->applicationRefusal()?->value ?? 'domain:' . $outcome->domainRefusal()?->value;
    }

    /** A repository whose append always answers with the given outcome; no key is ever known. */
    private static function alwaysAnswering(AppendOutcome $answer): MemberRepositoryPort
    {
        return new class ($answer) implements MemberRepositoryPort {
            public int $appends = 0;

            public function __construct(private readonly AppendOutcome $answer)
            {
            }

            public function load(TenantId $t, MemberId $id): ?Member { return null; }
            public function findByHolder(TenantId $t, PersonRef $h): ?Member { return null; }
            public function create(TenantId $t, MemberId $id, PersonRef $h): CreateResult { throw new \LogicException('not used'); }
            public function appliedCommand(TenantId $t, IdempotencyKey $k): ?AppliedCommand { return null; }
            public function append(TenantId $t, MemberId $id, Decision $d, IdempotencyKey $k, string $fp): AppendResult
            {
                $this->appends++;
                $appended = new Appended($id, FactId::fromString('A1'), 1, Instant::of(100));

                return match ($this->answer) {
                    AppendOutcome::Appended => AppendResult::success($appended),
                    AppendOutcome::AlreadyApplied => AppendResult::retried($appended),
                    default => AppendResult::failed($this->answer),
                };
            }
        };
    }
}
