<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\FamilyDependency;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\SuccessionEvent;
use App\Contexts\ReferenceGeography\Domain\SuccessionKind;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-2d-1 (v3.11 §3 SUCCESSION and withdrawal rows, §5.2 G-5; closure G-5, SuccessorSet; D-228):
 * - a succession event names its kind (division · merger · absorption · partial split) and ≥ 1 successor;
 * - successors exist on the event date;
 * - division, merger and absorption END the predecessor by that date; after a partial split it continues;
 * - no live child under an ended unit (children moved or ended, published or in this revision);
 * - a withdrawal (a none claim) rests on an instrument or a correction; an ending is a none EXISTENCE claim on an
 *   instrument; published claims are never edited.
 */
final class SuccessionValidationTest extends TestCase
{
    private const OLD = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const S1 = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const S2 = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';
    private const CHILD = '9e8d7c6b-5a4f-4e3d-8c2b-1a0f9e8d7c6b';
    private const D = '2015-09-20';

    private static int $n = 700;

    private static function u(string $id): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function claim(SlotKey $slot, ?string $value, ValidInterval $interval, BasisKind $basis = BasisKind::Instrument): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, $value === null ? ClaimValue::none() : ClaimValue::of($value), $interval, new Basis($basis, 'act-2015'));
    }

    private static function exists(string $unit, string $from = '1990-01-01'): Claim
    {
        return self::claim(SlotKey::existence(self::u($unit)), 'exists', self::iv($from));
    }

    private static function ends(string $unit, string $from = self::D, BasisKind $basis = BasisKind::Instrument): Claim
    {
        return self::claim(SlotKey::existence(self::u($unit)), null, self::iv($from), $basis);
    }

    private static function succession(SuccessionKind $kind, array $successors, string $from = self::D): Claim
    {
        $event = new SuccessionEvent($kind, array_map(static fn (string $s): ReferenceUnitId => self::u($s), $successors));

        return self::claim(SlotKey::succession(self::u(self::OLD), 'event-' . $from), $event->encoded(), self::iv($from));
    }

    private static function atlas(array $claims = []): InMemoryPublishedAtlas
    {
        $position = 0;
        $published = array_map(static function (Claim $c) use (&$position): PublishedClaim { $position++; return new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, $position - 1), $position); }, [
            CompetentInstrument::competenceClaim(), self::exists(self::OLD), self::exists(self::S1), self::exists(self::S2), ...$claims,
        ]);

        return new InMemoryPublishedAtlas(count($published), $published);
    }

    private static function validate(array $claims, ?InMemoryPublishedAtlas $atlas = null)
    {
        return (new RevisionValidator())->validate($claims, new DependencyRecordingReader($atlas ?? self::atlas()), CompetentInstrument::byId(['act-2015']));
    }

    private static function rules($result): array
    {
        return array_map(static fn ($v): string => $v->rule, $result->violations);
    }

    public function test_a_succession_event_has_a_canonical_encoding_and_needs_a_successor(): void
    {
        $event = new SuccessionEvent(SuccessionKind::Division, [self::u(self::S2), self::u(self::S1), self::u(self::S1)]);

        $this->assertSame('division:' . self::S2 . ',' . self::S1, $event->encoded(), 'sorted (6c5d… < 8a7b…), de-duplicated');
        $this->assertEquals($event, SuccessionEvent::parse($event->encoded()));
        $this->assertSame(['division', 'merger', 'absorption', 'partial-split'], array_map(static fn (SuccessionKind $k): string => $k->value, SuccessionKind::cases()));
        $this->assertTrue(SuccessionKind::Merger->endsPredecessor());
        $this->assertFalse(SuccessionKind::PartialSplit->endsPredecessor());
        foreach (['division:', 'secession:' . self::S1, 'division:Koshi', ''] as $bad) {
            try {
                SuccessionEvent::parse($bad);
                $this->fail("'{$bad}' must be refused");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_a_division_with_its_ending_and_existing_successors_is_valid(): void
    {
        $this->assertTrue(self::validate([self::ends(self::OLD), self::succession(SuccessionKind::Division, [self::S1, self::S2])])->isValid());
    }

    public function test_a_succession_value_that_is_not_an_event_is_refused(): void
    {
        $bad = self::claim(SlotKey::succession(self::u(self::OLD), 'event-x'), 'Koshi and Bagmati', self::iv(self::D));

        $this->assertSame(['G-5_SUCCESSION_FORM'], self::rules(self::validate([self::ends(self::OLD), $bad])));
    }

    public function test_successors_must_exist_on_the_event_date(): void
    {
        $unknown = '7f7f7f7f-7f7f-4f7f-8f7f-7f7f7f7f7f7f';

        $this->assertSame(['G-5_SUCCESSOR_EXISTS'], self::rules(self::validate([self::ends(self::OLD), self::succession(SuccessionKind::Merger, [$unknown])])));
        $this->assertTrue(self::validate([self::ends(self::OLD), self::exists($unknown, self::D), self::succession(SuccessionKind::Merger, [$unknown])])->isValid(), 'a successor created in this revision');
    }

    public function test_division_merger_and_absorption_end_the_predecessor_a_partial_split_does_not(): void
    {
        $this->assertSame(['G-5_PREDECESSOR_ENDS'], self::rules(self::validate([self::succession(SuccessionKind::Absorption, [self::S1])])), 'the predecessor still exists');
        $this->assertTrue(self::validate([self::succession(SuccessionKind::PartialSplit, [self::S1])])->isValid(), 'after a partial split it continues');
    }

    public function test_no_live_child_under_an_ended_unit(): void
    {
        $withChild = self::atlas([self::exists(self::CHILD), self::claim(SlotKey::containment(self::u(self::CHILD), 'admin'), self::OLD, self::iv('1990-01-01'))]);
        $moveChild = self::claim(SlotKey::containment(self::u(self::CHILD), 'admin'), self::S1, self::iv(self::D));

        $orphaning = self::validate([self::ends(self::OLD)], $withChild);
        $this->assertSame(['G-5_LIVE_CHILD'], self::rules($orphaning));
        $this->assertContains(FamilyDependency::of(ClaimKind::Containment, [], self::OLD, self::iv(self::D))->describe(), $orphaning->dependencies->describe(), 'children of the ended unit: a recorded family read');
        $this->assertTrue(self::validate([self::ends(self::OLD), $moveChild], $withChild)->isValid(), 'the child is moved in the same revision');
        $this->assertTrue(self::validate([self::ends(self::OLD), self::ends(self::CHILD)], $withChild)->isValid(), 'or ended too');
    }

    public function test_a_withdrawal_rests_on_an_instrument_or_a_correction(): void
    {
        $this->assertTrue(self::validate([self::ends(self::S2, basis: BasisKind::Correction)])->isValid(), 'a correction');
        $this->assertTrue(self::validate([self::ends(self::S2, basis: BasisKind::Instrument)])->isValid(), 'a real ending on its instrument');
        $this->assertSame(['WITHDRAWAL_BASIS'], self::rules(self::validate([self::ends(self::S2, basis: BasisKind::Other)])));
    }

    public function test_a_later_new_child_of_the_ended_unit_makes_the_validation_stale(): void
    {
        $result = self::validate([self::ends(self::OLD)]);
        $lateChild = self::claim(SlotKey::containment(self::u(self::CHILD), 'electoral'), self::OLD, self::iv('2016-01-01'));
        $published = new PublishedClaim($lateChild, BeforeImage::of(SlotHistory::for($lateChild->slot), $lateChild->interval, 9), 10);

        $this->assertSame($published, $result->dependencies->firstInvalidating([$published]), 'any scheme: the family has no fixed scheme');
    }
}
