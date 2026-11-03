<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\Admit;
use App\Contexts\Membership\Domain\Organisational\Annul;
use App\Contexts\Membership\Domain\Organisational\Correct;
use App\Contexts\Membership\Domain\Organisational\End;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\Fact;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RecordedFact;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Domain\Organisational\TrimConsequence;
use App\Contexts\Membership\Domain\Organisational\VoidFact;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * COR-4 step 1 (PBDIGIT-329; Q-3): structural validation of a stored log in Member::fromLog, the one rule for every
 * load path (roadmap V-1, V-14, V-15; G-A3 / AO-1). A log the kernel could never have decided is refused on load,
 * instead of being resolved into a different membership truth.
 *
 * Deliberately NOT re-checked: G* and the guards that depend on time or authority. A stored history is the record (R-12).
 */
final class MemberLogValidationTest extends TestCase
{
    private static function id(string $id): FactId
    {
        return FactId::fromString($id);
    }

    private static function decision(): Provenance
    {
        return Provenance::decision('registrar-1', null, 'decl-fixture');
    }

    private static function admit(string $id, int $at = 1): Admit
    {
        return new Admit(self::id($id), TimePoint::of($at), TimePoint::infinity(), self::decision());
    }

    private static function end(string $id, string $period, int $at = 5): End
    {
        return new End(self::id($id), PeriodId::fromString($period), TimePoint::of($at), EndKind::fromString('resignation'), self::decision());
    }

    private static function annul(string $id, string $target, ?Provenance $provenance = null): Annul
    {
        return new Annul(self::id($id), self::id($target), $provenance ?? self::decision(), Instant::of(900));
    }

    private static function void(string $id, string $target): VoidFact
    {
        return new VoidFact(self::id($id), self::id($target), self::decision());
    }

    /** @param list<TrimConsequence> $consequences */
    private static function correct(string $id, string $target, int $value = 0, array $consequences = []): Correct
    {
        return new Correct(self::id($id), self::id($target), TimePoint::of($value), self::decision(), null, $consequences);
    }

    private static function trim(string $period, int $from, int $until): TrimConsequence
    {
        return new TrimConsequence(PeriodId::fromString($period), IntervalSet::of(Interval::of(TimePoint::of($from), TimePoint::of($until))));
    }

    private static function load(Fact ...$facts): Member
    {
        $log = [];
        foreach (array_values($facts) as $k => $fact) {
            $log[] = RecordedFact::of($k + 1, Instant::of(1000 + $k), $fact);
        }

        return Member::fromLog(MemberId::fromString('M1'), PersonRef::fromString('P1'), TenantId::fromString('O1'), ...$log);
    }

    /** @return array<string, array{list<Fact>}> */
    public static function invalidLogs(): array
    {
        return [
            'duplicate fact id' => [[self::admit('A1'), self::end('A1', 'A1')]],
            'End of an unknown period' => [[self::admit('A1'), self::end('E1', 'A9')]],
            'End whose period is a later Admit' => [[self::admit('A0'), self::end('E1', 'A2'), self::admit('A2', 6)]],
            'End whose "period" is not an Admit' => [[self::admit('A1'), self::end('E1', 'A1'), self::end('E2', 'E1')]],
            'reversal of a missing target' => [[self::admit('A1'), self::void('V1', 'ZZ')]],
            'reversal of a later fact' => [[self::admit('A1'), self::annul('X1', 'E2'), self::end('E2', 'A1')]],
            'reversal of itself' => [[self::admit('A1'), self::void('V1', 'V1')]],
            'Annul of a Void (N-3)' => [[self::admit('A1'), self::void('V1', 'A1'), self::annul('X1', 'V1')]],
            'Annul without decision provenance (R-7)' => [[self::admit('A1'), self::annul('X1', 'A1', Provenance::rule('statute-7', 'v1'))]],
            'Correct of itself (V-14)' => [[self::admit('A1'), self::correct('C1', 'C1')]],
            'Correct of a later fact (V-14)' => [[self::admit('A1'), self::correct('C1', 'C2'), self::correct('C2', 'A1')]],
            'Correct of an Annul (N-3)' => [[self::admit('A1'), self::annul('X1', 'A1'), self::correct('C1', 'X1')]],
            'Correct of a Void (N-3)' => [[self::admit('A1'), self::void('V1', 'A1'), self::correct('C1', 'V1')]],
            'two consequences on one period (V-15)' => [[self::admit('A0', 0), self::end('E0', 'A0', 2), self::admit('A1', 2), self::correct('C1', 'E0', 4, [self::trim('A1', 2, 3), self::trim('A1', 3, 4)])]],
            'consequence on an unknown period (V-15)' => [[self::admit('A0', 0), self::end('E0', 'A0', 2), self::correct('C1', 'E0', 4, [self::trim('A9', 2, 4)])]],
            'consequence on a period admitted later (V-15)' => [[self::admit('A0', 0), self::end('E0', 'A0', 2), self::correct('C1', 'E0', 4, [self::trim('A1', 2, 4)]), self::admit('A1', 2)]],
            'consequence on a non-Admit (V-15)' => [[self::admit('A0', 0), self::end('E0', 'A0', 2), self::correct('C1', 'E0', 4, [self::trim('E0', 2, 4)])]],
            // Review F-3 (PO 2026-11-03, option A): structural, independent of time and authority.
            'Annul without decidedAt (F-3)' => [[self::admit('A1'), new Annul(self::id('X1'), self::id('A1'), self::decision())]],
            'Annul of a target without decision provenance (F-3)' => [[new Admit(self::id('A1'), TimePoint::of(1), TimePoint::infinity(), Provenance::rule('statute-7', 'v1')), self::annul('X1', 'A1')]],
            'Correct trimming its own period (F-3)' => [[self::admit('A0', 0), self::end('E0', 'A0', 2), self::correct('C1', 'E0', 4, [self::trim('A0', 2, 4)])]],
            'Correct of a Correct trimming the chain root period (F-3)' => [[self::admit('A0', 0), self::end('E0', 'A0', 2), self::correct('C1', 'E0', 3), self::correct('C2', 'C1', 4, [self::trim('A0', 1, 2)])]],
        ];
    }

    /** @param list<Fact> $facts */
    #[DataProvider('invalidLogs')]
    public function test_a_structurally_invalid_stored_log_is_refused_on_load(array $facts): void
    {
        $this->expectException(\InvalidArgumentException::class);

        self::load(...$facts);
    }

    // Every structurally valid shape still loads, including what G* would now refuse: a stored history is the record,
    // and guards are not re-run on load (an overlap, a no-op correction, a correction of an annulled fact, Void(Void)).
    public function test_valid_logs_of_every_kind_load_even_when_g_star_would_refuse_them_today(): void
    {
        $member = self::load(
            self::admit('A0', 0),
            self::end('E0', 'A0', 2),
            self::admit('A1', 2),
            self::correct('C1', 'E0', 4, [self::trim('A1', 2, 4)]),
            self::correct('C2', 'C1', 3, [self::trim('A1', 2, 3)]),
            self::annul('X1', 'C2'),
            self::annul('X2', 'X1'),
            self::void('V1', 'X2'),
            self::void('V2', 'V1'),
            self::annul('X3', 'A1'),
            self::correct('C3', 'A1', 1),
            self::correct('C4', 'C3', 1),
            self::admit('A2', 0),
        );

        $this->assertSame(13, $member->version());
    }
}
