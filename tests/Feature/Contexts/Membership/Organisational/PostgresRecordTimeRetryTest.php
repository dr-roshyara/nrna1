<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\ApplicationRefusal;
use App\Contexts\Membership\Application\Organisational\DecideAndAppend;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ScriptedLedgerClockReader;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * PBDIGIT-343 task 5 (plan test 9, proof G1d-1): on PostgreSQL, a record-time refusal re-enters the bounded loop,
 * re-loads and re-decides; a successful retry stores exactly one fact and one C1 row at the fresh reading;
 * exhaustion refuses with RecordTimeUnavailable and writes nothing; a replay of the key writes nothing.
 */
final class PostgresRecordTimeRetryTest extends MembershipPostgresTestCase
{
    /** @var list<int> */
    private array $readings = [];

    private function repo(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(fn (): int => array_shift($this->readings) ?? throw new \LogicException('unexpected reading')));
    }

    private function admitted(PostgresMemberRepository $repo): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'lc-retry', 'slug' => 'lc-retry-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $t = TenantId::fromString($id);
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('person-M1'))->member();
        $this->readings = [5_000];
        $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k-admit'), 'fp-admit');

        return $t;
    }

    private function end(PostgresMemberRepository $repo, TenantId $t, int &$decides): \App\Contexts\Membership\Application\Organisational\CommandOutcome
    {
        return (new DecideAndAppend($repo, new FakeAuthority()))->run($t, IdempotencyKey::fromString('k-end'), 'fp-end', 'registrar', MembershipAct::End,
            static fn (): Member => $repo->load($t, MemberId::fromString('M1')),
            static function (Member $m, AuthorityEvidence $e) use (&$decides): Decision {
                ++$decides;

                return $m->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(20_100), TimePoint::of(20_100), EndKind::fromString('resignation'), Provenance::decision('registrar', $e));
            });
    }

    private function counts(TenantId $t): array
    {
        return [
            DB::table('org_membership_facts')->where('organisation_id', $t->value())->count(),
            DB::table('outbox_events')->where('organisation_id', $t->value())->where('event_type', 'MembershipCoverageResolved')->count(),
        ];
    }

    public function test_a_refused_reading_is_retried_and_one_fact_and_one_event_are_stored_at_the_fresh_reading(): void
    {
        $repo = $this->repo();
        $t = $this->admitted($repo);
        $this->readings = [4_000, 5_000, 6_000];   // behind, equal, fresh
        $decides = 0;

        $outcome = $this->end($repo, $t, $decides);

        $this->assertTrue($outcome->isAppended());
        $this->assertSame(3, $decides);
        $this->assertSame(6_000, $outcome->appended()->recordedAt()->ordinal());
        $this->assertSame([2, 2], $this->counts($t), 'admit + end; one C1 row each');
    }

    public function test_exhaustion_refuses_with_record_time_unavailable_and_writes_nothing(): void
    {
        $repo = $this->repo();
        $t = $this->admitted($repo);
        $this->readings = array_fill(0, DecideAndAppend::MAX_ATTEMPTS, 4_000);
        $decides = 0;

        $outcome = $this->end($repo, $t, $decides);

        $this->assertSame(ApplicationRefusal::RecordTimeUnavailable, $outcome->applicationRefusal());
        $this->assertSame([1, 1], $this->counts($t));
    }

    public function test_a_replay_after_a_retried_success_writes_nothing(): void
    {
        $repo = $this->repo();
        $t = $this->admitted($repo);
        $this->readings = [5_000, 6_000];
        $decides = 0;
        $first = $this->end($repo, $t, $decides);

        $replay = $this->end($repo, $t, $decides);   // answered from the key before any reading

        $this->assertTrue($replay->isAppended());
        $this->assertSame($first->appended()->recordedAt()->ordinal(), $replay->appended()->recordedAt()->ordinal());
        $this->assertSame([2, 2], $this->counts($t));
    }
}
