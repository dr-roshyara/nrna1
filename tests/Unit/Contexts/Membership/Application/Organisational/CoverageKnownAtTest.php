<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\CoverageKnownAt;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\LedgerBoundaryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipSourceOfTruthPort;
use App\Contexts\Membership\Application\Organisational\Ports\WorkingOrganisationPort;
use App\Contexts\Membership\Application\Organisational\UnknownMembershipId;
use App\Contexts\Membership\Application\PublishedLanguage\CoverageInterval;
use App\Contexts\Membership\Application\PublishedLanguage\CoverageKnownAtRefusal;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\DateOrdinal;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\InMemoryMemberRepository;

/**
 * PBDIGIT-343 task 8 (plan D-7, D-8; C1/C2 spec §6, §11 K-2 A3, K-3, K-5; CR-01 §7, §10 / D-77): C2 CoverageKnownAt.
 * Order: working organisation → source of truth → close (committed) → boundary check → only then read facts.
 * Complete or refused; refusals are exactly BOUNDARY_NOT_CLOSED, SOURCE_NOT_LEDGER and cross-organisation.
 * An unknown membershipId and a batch above 1 000 keys are caller errors: the whole call fails, never partially.
 */
final class CoverageKnownAtTest extends TestCase
{
    private const ORG = 'O1';

    /** @var list<string> */
    private array $calls = [];
    private ?int $boundary = 5_000;
    private bool $ledger = true;
    private ?string $working = self::ORG;
    /** @var list<int> */
    private array $readings = [];

    private function repository(): InMemoryMemberRepository
    {
        return new InMemoryMemberRepository(fn (): int => array_shift($this->readings) ?? throw new \LogicException('unexpected reading'));
    }

    private function service(InMemoryMemberRepository $repository): CoverageKnownAt
    {
        $calls = &$this->calls;
        $test = $this;

        return new CoverageKnownAt(
            new class ($repository, $calls) implements \App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort {
                public function __construct(private InMemoryMemberRepository $inner, private array &$calls) {}
                public function load(TenantId $t, MemberId $id): ?\App\Contexts\Membership\Domain\Organisational\Member { $this->calls[] = 'load'; return $this->inner->load($t, $id); }
                public function findByHolder(TenantId $t, PersonRef $h): ?\App\Contexts\Membership\Domain\Organisational\Member { $this->calls[] = 'load'; return $this->inner->findByHolder($t, $h); }
                public function create(TenantId $t, MemberId $id, PersonRef $h): \App\Contexts\Membership\Application\Organisational\Ports\CreateResult { throw new \LogicException('C2 never writes'); }
                public function appliedCommand(TenantId $t, IdempotencyKey $k): ?\App\Contexts\Membership\Application\Organisational\Ports\AppliedCommand { throw new \LogicException('C2 never writes'); }
                public function append(TenantId $t, MemberId $id, \App\Contexts\Membership\Domain\Organisational\Decision $d, IdempotencyKey $k, string $fp): \App\Contexts\Membership\Application\Organisational\Ports\AppendResult { throw new \LogicException('C2 never writes'); }
            },
            new class ($test, $calls) implements LedgerBoundaryPort {
                public function __construct(private CoverageKnownAtTest $test, private array &$calls) {}
                public function closeOrRead(TenantId $organisation): ?Instant { $this->calls[] = 'close'; return $this->test->boundary(); }
            },
            new class ($test, $calls) implements MembershipSourceOfTruthPort {
                public function __construct(private CoverageKnownAtTest $test, private array &$calls) {}
                public function isLedger(TenantId $organisation): bool { $this->calls[] = 'source'; return $this->test->ledger(); }
            },
            new class ($test) implements WorkingOrganisationPort {
                public function __construct(private CoverageKnownAtTest $test) {}
                public function current(): ?TenantId { return $this->test->working(); }
            },
        );
    }

    public function boundary(): ?Instant { return $this->boundary === null ? null : Instant::of($this->boundary); }
    public function ledger(): bool { return $this->ledger; }
    public function working(): ?TenantId { return $this->working === null ? null : TenantId::fromString($this->working); }

    /**
     * CR-01 §7: admitted (recorded at 100, effective day 20 000); End effective day 20 400, recorded at 500 ("5 Dec");
     * the End annulled at 1 500 ("15 Dec"). Day 20 403 plays "1 Dec".
     */
    private function cr01Scenario(): InMemoryMemberRepository
    {
        $repo = $this->repository();
        $t = TenantId::fromString(self::ORG);
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
        $this->readings = [100];
        $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');
        $m = $repo->load($t, $m->id());
        $this->readings = [500];
        $repo->append($t, $m->id(), $m->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(20_400), TimePoint::of(20_404), EndKind::fromString('resignation'), Provenance::decision('registrar')), IdempotencyKey::fromString('k2'), 'fp2');
        $m = $repo->load($t, $m->id());
        $this->readings = [1_500];
        $repo->append($t, $m->id(), $m->decideAnnul(FactId::fromString('X1'), FactId::fromString('E1'), Provenance::decision('appeals-committee'), Instant::of(1_400)), IdempotencyKey::fromString('k3'), 'fp3');

        return $repo;
    }

    private static function covers(array $intervals, int $day): bool
    {
        $date = DateOrdinal::toDate(TimePoint::of($day));
        foreach ($intervals as $i) {
            if ($i->from <= $date && ($i->to === null || $date < $i->to)) {
                return true;
            }
        }

        return false;
    }

    public function test_the_cr01_scenario_gives_each_knowledge_boundary_its_answer(): void
    {
        $c2 = $this->service($this->cr01Scenario());
        $this->boundary = 2_000;
        $ask = fn (int $tau) => $c2->byMembershipIds(TenantId::fromString(self::ORG), [MemberId::fromString('M1')], Instant::of($tau))->items()[0];

        $atCutOff = $ask(200);       // "1 Dec, end of day": the End is not yet known
        $tenthDec = $ask(1_000);     // End known, not yet annulled
        $now = $ask(2_000);          // End annulled

        $this->assertTrue(self::covers($atCutOff->inForce, 20_403), 'member at the cut-off');
        $this->assertSame(1, $atCutOff->seq);
        $this->assertFalse(self::covers($tenthDec->inForce, 20_403), 'not a member, asked on 10 December');
        $this->assertSame(2, $tenthDec->seq);
        $this->assertTrue(self::covers($now->inForce, 20_403), 'member again, asked today');
        $this->assertSame(3, $now->seq);
    }

    public function test_the_answer_carries_the_boundary_and_the_contract_fields(): void
    {
        $answer = $this->service($this->cr01Scenario())->byMembershipIds(TenantId::fromString(self::ORG), [MemberId::fromString('M1')], Instant::of(200));

        $this->assertTrue($answer->isComplete());
        $this->assertSame(5_000, $answer->closedThroughMicros());
        $item = $answer->items()[0];
        $this->assertSame('M1', $item->membershipId);
        $this->assertNull($item->personRef);
        $this->assertEquals([new CoverageInterval(DateOrdinal::toDate(TimePoint::of(20_000)), null)], $item->coverage);
        $this->assertSame([], $item->suspended);
    }

    public function test_person_refs_are_an_alternative_key_and_an_unknown_person_is_empty_with_seq_zero(): void
    {
        $answer = $this->service($this->cr01Scenario())->byPersonRefs(TenantId::fromString(self::ORG), [PersonRef::fromString('P1'), PersonRef::fromString('nobody')], Instant::of(200));

        [$known, $unknown] = $answer->items();
        $this->assertSame('P1', $known->personRef);
        $this->assertSame(1, $known->seq);
        $this->assertSame('nobody', $unknown->personRef);
        $this->assertSame(0, $unknown->seq);
        $this->assertSame([], $unknown->coverage);
        $this->assertSame([], $unknown->inForce);
        $this->assertSame([], $unknown->suspended);
    }

    public function test_an_unknown_membership_id_fails_the_whole_call(): void
    {
        $c2 = $this->service($this->cr01Scenario());

        $this->expectException(UnknownMembershipId::class);
        $c2->byMembershipIds(TenantId::fromString(self::ORG), [MemberId::fromString('M1'), MemberId::fromString('M-unknown')], Instant::of(200));
    }

    public function test_a_source_that_is_not_the_ledger_is_refused_before_closing_or_reading(): void
    {
        $this->ledger = false;

        $answer = $this->service($this->cr01Scenario())->byMembershipIds(TenantId::fromString(self::ORG), [MemberId::fromString('M1')], Instant::of(200));

        $this->assertSame(CoverageKnownAtRefusal::SourceNotLedger, $answer->refusal());
        $this->assertSame(['source'], $this->calls);
    }

    public function test_another_organisation_is_refused_before_anything_else(): void
    {
        $this->working = 'O2';

        $answer = $this->service($this->cr01Scenario())->byMembershipIds(TenantId::fromString(self::ORG), [MemberId::fromString('M1')], Instant::of(200));

        $this->assertSame(CoverageKnownAtRefusal::CrossOrganisation, $answer->refusal());
        $this->assertSame([], $this->calls);
    }

    public function test_no_working_organisation_is_refused_as_cross_organisation(): void
    {
        $this->working = null;

        $this->assertSame(CoverageKnownAtRefusal::CrossOrganisation, $this->service($this->cr01Scenario())->byMembershipIds(TenantId::fromString(self::ORG), [MemberId::fromString('M1')], Instant::of(200))->refusal());
    }

    public function test_tau_beyond_the_boundary_is_refused_with_the_boundary_and_nothing_is_read(): void
    {
        $this->boundary = 1_000;

        $answer = $this->service($this->cr01Scenario())->byMembershipIds(TenantId::fromString(self::ORG), [MemberId::fromString('M1')], Instant::of(1_001));

        $this->assertSame(CoverageKnownAtRefusal::BoundaryNotClosed, $answer->refusal());
        $this->assertSame(1_000, $answer->closedThroughMicros());
        $this->assertSame(['source', 'close'], $this->calls);
    }

    public function test_never_closed_is_refused(): void
    {
        $this->boundary = null;

        $answer = $this->service($this->cr01Scenario())->byMembershipIds(TenantId::fromString(self::ORG), [MemberId::fromString('M1')], Instant::of(1));

        $this->assertSame(CoverageKnownAtRefusal::BoundaryNotClosed, $answer->refusal());
        $this->assertNull($answer->closedThroughMicros());
    }

    public function test_the_close_happens_before_any_fact_is_read(): void
    {
        $this->service($this->cr01Scenario())->byMembershipIds(TenantId::fromString(self::ORG), [MemberId::fromString('M1')], Instant::of(200));

        $this->assertSame(['source', 'close', 'load'], $this->calls);
    }

    public function test_a_batch_of_one_thousand_keys_is_answered_and_one_more_is_refused_as_an_error(): void
    {
        $c2 = $this->service($this->cr01Scenario());
        $people = array_map(static fn (int $i): PersonRef => PersonRef::fromString("p$i"), range(1, CoverageKnownAt::MAX_KEYS));

        $this->assertCount(CoverageKnownAt::MAX_KEYS, $c2->byPersonRefs(TenantId::fromString(self::ORG), $people, Instant::of(200))->items());

        $this->expectException(\InvalidArgumentException::class);
        $c2->byPersonRefs(TenantId::fromString(self::ORG), [...$people, PersonRef::fromString('one-more')], Instant::of(200));
    }

    public function test_published_language_dates_agree_with_the_ledger_date_ordinal(): void
    {
        foreach ([-719_162, -1, 0, 1, 19_782, 20_000, 20_403, 2_932_896] as $day) {
            $set = \App\Contexts\Membership\Domain\Organisational\IntervalSet::of(\App\Contexts\Membership\Domain\Organisational\Interval::of(TimePoint::of($day), TimePoint::of($day + 1)));
            $this->assertSame(DateOrdinal::toDate(TimePoint::of($day)), CoverageInterval::listFrom($set)[0]->from, "day $day");
        }
    }
}
