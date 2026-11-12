<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Appended;
use App\Contexts\Membership\Application\Organisational\ApplicationRefusal;
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
use App\Contexts\Membership\Domain\Organisational\RecordedFact;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-343 task 5 (plan D-3; blueprint G-1d; D-209; R-1a-2): a record-time refusal is a fresh attempt.
 * The loop re-loads, re-decides against the latest state and appends again; the decision is never reused.
 * When the bounded attempts run out, the command is refused with the last attempt's reason.
 */
final class DecideAndAppendRecordTimeRetryTest extends TestCase
{
    /** @param list<AppendOutcome> $answers the repository's answer to each append */
    private static function scripted(array $answers): MemberRepositoryPort
    {
        return new class ($answers) implements MemberRepositoryPort {
            /** @var list<Decision> */
            public array $decisions = [];

            /** @param list<AppendOutcome> $answers */
            public function __construct(private array $answers)
            {
            }

            public function load(TenantId $t, MemberId $id): ?Member { return null; }
            public function findByHolder(TenantId $t, PersonRef $h): ?Member { return null; }
            public function create(TenantId $t, MemberId $id, PersonRef $h): CreateResult { throw new \LogicException('not used'); }
            public function appliedCommand(TenantId $t, IdempotencyKey $k): ?AppliedCommand { return null; }
            public function append(TenantId $t, MemberId $id, Decision $d, IdempotencyKey $k, string $fp): AppendResult
            {
                $this->decisions[] = $d;
                $answer = array_shift($this->answers) ?? throw new \LogicException('more appends than scripted');

                return $answer === AppendOutcome::Appended
                    ? AppendResult::success(new Appended($id, $d->fact()->id(), $d->basedOnVersion() + 1, Instant::of(900)))
                    : AppendResult::failed($answer);
            }
        };
    }

    /** Each load returns the next state: version 0 (no facts), then version 1 (another command appended meanwhile). */
    private static function changingStates(int &$loads): \Closure
    {
        $tenant = TenantId::fromString('O1');
        $v0 = Member::fromLog(MemberId::fromString('M1'), PersonRef::fromString('P1'), $tenant);
        $other = $v0->decideAdmit(FactId::fromString('A0'), TimePoint::of(1), TimePoint::of(1), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture'));
        $v1 = Member::fromLog(MemberId::fromString('M1'), PersonRef::fromString('P1'), $tenant, RecordedFact::of(1, Instant::of(800), $other->fact()));

        return static function () use (&$loads, $v0, $v1): Member {
            return ++$loads === 1 ? $v0 : $v1;
        };
    }

    private static function runCommand(MemberRepositoryPort $repository, \Closure $load, int &$decides): \App\Contexts\Membership\Application\Organisational\CommandOutcome
    {
        return (new DecideAndAppend($repository, new FakeAuthority()))->run(
            TenantId::fromString('O1'),
            IdempotencyKey::fromString('k1'),
            'fp1',
            'registrar',
            MembershipAct::End,
            $load,
            static function (Member $m, AuthorityEvidence $e) use (&$decides): Decision {
                ++$decides;

                // decides against the state it was given: an End of A0 if it exists, else an Admit
                return $m->log() === []
                    ? $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(1), TimePoint::of(1), TimePoint::infinity(), Provenance::decision('registrar', $e, 'decl-fixture'))
                    : $m->decideEnd(FactId::fromString('E1'), \App\Contexts\Membership\Domain\Organisational\PeriodId::fromString('A0'), TimePoint::of(5), TimePoint::of(5), \App\Contexts\Membership\Domain\Organisational\EndKind::fromString('resignation'), Provenance::decision('registrar', $e));
            },
        );
    }

    public function test_a_record_time_refusal_reloads_and_re_decides_against_the_latest_state(): void
    {
        $repository = self::scripted([AppendOutcome::RecordTimeRefused, AppendOutcome::Appended]);
        [$loads, $decides] = [0, 0];

        $outcome = self::runCommand($repository, self::changingStates($loads), $decides);

        $this->assertTrue($outcome->isAppended());
        $this->assertSame(2, $loads, 're-loaded');
        $this->assertSame(2, $decides, 're-decided');
        $this->assertNotSame($repository->decisions[0], $repository->decisions[1], 'the first decision is never reused');
        $this->assertSame([0, 1], array_map(static fn (Decision $d): int => $d->basedOnVersion(), $repository->decisions), 'the second decision is based on the latest state');
        $this->assertSame(['A1', 'E1'], array_map(static fn (Decision $d): string => $d->fact()->id()->value(), $repository->decisions));
    }

    public function test_exhausting_the_attempts_on_record_time_refusals_refuses_with_record_time_unavailable(): void
    {
        $repository = self::scripted(array_fill(0, DecideAndAppend::MAX_ATTEMPTS, AppendOutcome::RecordTimeRefused));
        [$loads, $decides] = [0, 0];

        $outcome = self::runCommand($repository, self::changingStates($loads), $decides);

        $this->assertSame(ApplicationRefusal::RecordTimeUnavailable, $outcome->applicationRefusal());
        $this->assertCount(DecideAndAppend::MAX_ATTEMPTS, $repository->decisions);
        $this->assertSame(DecideAndAppend::MAX_ATTEMPTS, $decides);
    }

    public function test_the_refusal_names_the_last_attempts_reason(): void
    {
        [$a, $b, $c, $d] = [0, 0, 0, 0];
        $clockLast = self::runCommand(self::scripted([AppendOutcome::ConcurrencyConflict, AppendOutcome::ConcurrencyConflict, AppendOutcome::RecordTimeRefused]), self::changingStates($a), $b);
        $conflictLast = self::runCommand(self::scripted([AppendOutcome::RecordTimeRefused, AppendOutcome::RecordTimeRefused, AppendOutcome::ConcurrencyConflict]), self::changingStates($c), $d);

        $this->assertSame(ApplicationRefusal::RecordTimeUnavailable, $clockLast->applicationRefusal());
        $this->assertSame(ApplicationRefusal::ConcurrentModification, $conflictLast->applicationRefusal(), 'unchanged for a conflict');
    }

    public function test_with_the_reference_adapter_a_retry_takes_a_fresh_reading_and_stores_it_exactly(): void
    {
        $readings = [500, 500, 600];   // the existing fact, a refused equal reading, a fresh one
        $repository = new InMemoryMemberRepository(static function () use (&$readings): int {
            return array_shift($readings) ?? throw new \LogicException('unexpected reading');
        });
        $tenant = TenantId::fromString('O1');
        $member = $repository->create($tenant, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
        $repository->append($tenant, $member->id(), $member->decideAdmit(FactId::fromString('A0'), TimePoint::of(1), TimePoint::of(1), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k0'), 'fp0');
        $decides = 0;

        $outcome = self::runCommand($repository, static fn (): Member => $repository->load($tenant, MemberId::fromString('M1')), $decides);

        $this->assertTrue($outcome->isAppended());
        $this->assertSame(2, $decides);
        $this->assertSame(600, $outcome->appended()->recordedAt()->ordinal());
        $this->assertSame([500, 600], array_map(static fn (RecordedFact $r): int => $r->recordedAt()->ordinal(), $repository->load($tenant, MemberId::fromString('M1'))->log()), 'one new fact, at the exact fresh reading');
    }
}
