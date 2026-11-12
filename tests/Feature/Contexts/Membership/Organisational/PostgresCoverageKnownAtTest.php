<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\CoverageKnownAt;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipSourceOfTruthPort;
use App\Contexts\Membership\Application\Organisational\Ports\WorkingOrganisationPort;
use App\Contexts\Membership\Application\PublishedLanguage\CoverageKnownAtRefusal;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\FailClosedMembershipSourceOfTruth;
use App\Contexts\Membership\Infrastructure\Organisational\InstantMicros;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockCloser;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockCloserBoundary;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-343 task 8 (plan tests 11, 12; D-7; C1/C2 spec §6, §11 A3): C2 on PostgreSQL with the real repository and
 * the real LedgerClockCloser. The CR-01 §7 scenario end to end; the default source port refuses; a stale committed
 * boundary (the closer was busy) only ever leads to a refusal, never to a false complete answer.
 */
final class PostgresCoverageKnownAtTest extends MembershipPostgresTestCase
{
    /** @var list<int> */
    private array $readings = [];

    private function reader(): ScriptedLedgerClockReader
    {
        return new ScriptedLedgerClockReader(fn (): int => array_shift($this->readings) ?? throw new \LogicException('unexpected reading'));
    }

    private static function working(TenantId $t): WorkingOrganisationPort
    {
        return new class ($t) implements WorkingOrganisationPort {
            public function __construct(private TenantId $t) {}
            public function current(): ?TenantId { return $this->t; }
        };
    }

    private static function ledger(): MembershipSourceOfTruthPort
    {
        return new class () implements MembershipSourceOfTruthPort {
            public function isLedger(TenantId $organisation): bool { return true; }   // TEST ONLY: the real flag is slice 2
        };
    }

    private function c2(TenantId $t, ?MembershipSourceOfTruthPort $source = null): CoverageKnownAt
    {
        return new CoverageKnownAt(
            new PostgresMemberRepository(DB::connection(), $this->reader()),
            new LedgerClockCloserBoundary(new LedgerClockCloser(DB::connection(), $this->reader())),
            $source ?? self::ledger(),
            self::working($t),
        );
    }

    /** CR-01 §7 on PostgreSQL: admit recorded at 100, End at 500, Annul at 1 500 (µs since the epoch, scripted). */
    private function scenario(): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'c2', 'slug' => 'c2-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $t = TenantId::fromString($id);
        $repo = new PostgresMemberRepository(DB::connection(), $this->reader());
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
        $this->readings = [100];
        $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');
        $m = $repo->load($t, $m->id());
        $this->readings = [500];
        $repo->append($t, $m->id(), $m->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(20_400), TimePoint::of(20_404), EndKind::fromString('resignation'), Provenance::decision('registrar')), IdempotencyKey::fromString('k2'), 'fp2');
        $m = $repo->load($t, $m->id());
        $this->readings = [1_500];
        $repo->append($t, $m->id(), $m->decideAnnul(FactId::fromString('X1'), FactId::fromString('E1'), Provenance::decision('appeals-committee'), Instant::of(1_400)), IdempotencyKey::fromString('k3'), 'fp3');

        return $t;
    }

    public function test_the_cr01_scenario_end_to_end_with_the_real_closer(): void
    {
        $t = $this->scenario();
        $this->readings = [2_000];   // the closer's reading
        $answer = $this->c2($t)->byMembershipIds($t, [MemberId::fromString('M1')], Instant::of(200));
        $this->readings = [2_100];
        $tenth = $this->c2($t)->byMembershipIds($t, [MemberId::fromString('M1')], Instant::of(1_000));
        $this->readings = [2_200];
        $now = $this->c2($t)->byPersonRefs($t, [PersonRef::fromString('P1')], Instant::of(2_200));

        $this->assertTrue($answer->isComplete());
        $this->assertSame(2_000, $answer->closedThroughMicros());
        $this->assertSame([1, 2, 3], [$answer->items()[0]->seq, $tenth->items()[0]->seq, $now->items()[0]->seq]);
        $this->assertNull($answer->items()[0]->inForce[0]->to, 'member at the cut-off: open-ended');
        $this->assertNotNull($tenth->items()[0]->inForce[0]->to, 'End known on 10 December');
        $this->assertNull($now->items()[0]->inForce[0]->to, 'End annulled: open-ended again');
    }

    public function test_the_default_source_port_refuses_every_organisation(): void
    {
        $t = $this->scenario();

        $answer = $this->c2($t, new FailClosedMembershipSourceOfTruth())->byMembershipIds($t, [MemberId::fromString('M1')], Instant::of(200));

        $this->assertSame(CoverageKnownAtRefusal::SourceNotLedger, $answer->refusal());
    }

    public function test_a_stale_committed_boundary_only_refuses_and_never_answers_falsely(): void
    {
        $t = $this->scenario();
        $this->readings = [2_000];
        $this->c2($t)->byMembershipIds($t, [MemberId::fromString('M1')], Instant::of(200));   // committed boundary 2 000
        $default = config('database.default');
        config(['database.connections.pbdigit343_holder' => config("database.connections.$default")]);
        $holder = DB::connection('pbdigit343_holder');
        $holder->beginTransaction();
        $holder->select('SELECT 1 FROM org_membership_ledger_clocks WHERE organisation_id = ? FOR UPDATE', [$t->value()]);   // a writer in flight

        try {
            $this->readings = [];   // busy: the closer takes no reading
            $beyond = $this->c2($t)->byMembershipIds($t, [MemberId::fromString('M1')], Instant::of(2_500));
            $within = $this->c2($t)->byMembershipIds($t, [MemberId::fromString('M1')], Instant::of(2_000));
        } finally {
            $holder->rollBack();
        }

        $this->assertSame(CoverageKnownAtRefusal::BoundaryNotClosed, $beyond->refusal(), 'beyond the stale boundary: refused');
        $this->assertSame(2_000, $beyond->closedThroughMicros());
        $this->assertTrue($within->isComplete(), 'within it: answered');
        $this->assertSame(3, $within->items()[0]->seq, 'and the answer equals the full history up to it');
    }

    public function test_c2_never_writes_a_fact_or_an_event(): void
    {
        $t = $this->scenario();
        $counts = static fn (): array => [DB::table('org_membership_facts')->where('organisation_id', $t->value())->count(), DB::table('outbox_events')->where('organisation_id', $t->value())->count()];
        $before = $counts();
        $this->readings = [2_000];

        $this->c2($t)->byMembershipIds($t, [MemberId::fromString('M1')], Instant::of(200));

        $this->assertSame($before, $counts());
        $this->assertNotNull(DB::table('org_membership_ledger_clocks')->where('organisation_id', $t->value())->value(DB::raw(InstantMicros::selectExpression('closed_through'))), 'only the boundary moved');
    }
}
