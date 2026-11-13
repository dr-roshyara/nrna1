<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\PlaceBelief;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-5a (design: 2026-11-13-reference-geography-g5-read-models-design.md §2; v3.11 §4, §12):
 * what a consumer may believe about ONE place at valid date t, as known at position p, from published slots only.
 * - belief = the highest-position claim ≤ p covering t; a withdrawal gives none; an ended existence is not "exists";
 * - a dispute flags a fact only when it is on the claim IN FORCE, and only while its latest state ≤ p is "disputed";
 *   it never changes the value;
 * - a merge (reconciliation) holds for all time and changes only by knowledge: the latest reconciliation claim ≤ p;
 *   NONE (a reversal) means unmerged; earlier positions keep showing the merge.
 */
final class PlaceBeliefTest extends TestCase
{
    private const UNIT = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const PARENT_A = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const PARENT_B = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';
    private const CANONICAL = '1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d';

    private static int $n = 0;

    private static function u(string $id = self::UNIT): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    private static function claim(SlotKey $slot, ?string $value, string $from, ?string $to = null, ?string $id = null): Claim
    {
        self::$n++;

        return new Claim(
            ClaimId::fromString($id ?? sprintf('00000000-0000-4000-8000-%012d', self::$n)),
            $slot,
            $value === null ? ClaimValue::none() : ClaimValue::of($value),
            new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to)),
            new Basis(BasisKind::Instrument, 'act'),
        );
    }

    /** @param list<array{Claim, int}> $claims */
    private static function history(SlotKey $slot, array $claims): SlotHistory
    {
        $h = SlotHistory::for($slot);
        foreach ($claims as [$c, $p]) {
            $h = $h->with($c, $p);
        }

        return $h;
    }

    private static function t(string $d): ValidDate
    {
        return ValidDate::fromString($d);
    }

    public function test_slot_history_gives_the_latest_claim_known_at_a_position_and_a_claim_position(): void
    {
        $slot = SlotKey::reconciliation(self::u());
        $first = self::claim($slot, self::CANONICAL, '1900-01-01');
        $reversal = self::claim($slot, null, '1900-01-01');
        $h = self::history($slot, [[$first, 3], [$reversal, 7]]);

        $this->assertSame($first, $h->latest(5));
        $this->assertSame($reversal, $h->latest(null));
        $this->assertNull($h->latest(2));
        $this->assertSame(3, $h->positionOf($first->id));
        $this->assertNull($h->positionOf(ClaimId::fromString('99999999-9999-4999-8999-999999999999')));
        $this->assertTrue($h->slot()->equals($slot));
    }

    public function test_a_place_exists_while_its_existence_claim_covers_t_as_known_at_p(): void
    {
        $existence = SlotKey::existence(self::u());
        $slots = [self::history($existence, [[self::claim($existence, 'exists', '2016-01-01', '2020-01-01'), 2]])];

        $this->assertTrue(PlaceBelief::of(self::u(), $slots, [], self::t('2018-06-01'), 2)->exists);
        $this->assertFalse(PlaceBelief::of(self::u(), $slots, [], self::t('2021-01-01'), 2)->exists, 'ended');
        $this->assertTrue(PlaceBelief::of(self::u(), $slots, [], self::t('2021-01-01'), 2)->known, 'known, though ended');
        $this->assertFalse(PlaceBelief::of(self::u(), $slots, [], self::t('2018-06-01'), 1)->known, 'not yet published at p = 1');
    }

    public function test_a_withdrawal_means_the_place_does_not_exist(): void
    {
        $existence = SlotKey::existence(self::u());
        $slots = [self::history($existence, [[self::claim($existence, 'exists', '2016-01-01'), 2], [self::claim($existence, null, '2016-01-01'), 5]])];

        $this->assertTrue(PlaceBelief::of(self::u(), $slots, [], self::t('2018-01-01'), 4)->exists);
        $this->assertFalse(PlaceBelief::of(self::u(), $slots, [], self::t('2018-01-01'), 5)->exists);
    }

    public function test_the_profile_reads_parent_classification_identifiers_and_names_at_t_and_p(): void
    {
        $containment = SlotKey::containment(self::u(), 'admin');
        $slots = [
            self::history(SlotKey::existence(self::u()), [[self::claim(SlotKey::existence(self::u()), 'exists', '2016-01-01'), 1]]),
            self::history($containment, [
                [self::claim($containment, self::PARENT_A, '2016-01-01'), 1],
                [self::claim($containment, self::PARENT_B, '2019-01-01'), 4],
            ]),
            self::history(SlotKey::classification(self::u(), 'admin'), [[self::claim(SlotKey::classification(self::u(), 'admin'), 'district', '2016-01-01'), 1]]),
            self::history(SlotKey::identifier(self::u(), 'iso3166-2'), [[self::claim(SlotKey::identifier(self::u(), 'iso3166-2'), 'NP-P3', '2016-01-01'), 1]]),
            self::history(SlotKey::sourceName(self::u(), 'gazette', 'ne', 'official'), [[self::claim(SlotKey::sourceName(self::u(), 'gazette', 'ne', 'official'), 'बागमती', '2016-01-01'), 1]]),
            self::history(SlotKey::sourceNameValue(self::u(), 'gazette', 'en', 'former', 'Province No. 3'), [[self::claim(SlotKey::sourceNameValue(self::u(), 'gazette', 'en', 'former', 'Province No. 3'), 'Province No. 3', '2016-01-01'), 1]]),
        ];

        $facts = PlaceBelief::of(self::u(), $slots, [], self::t('2020-01-01'), 4);
        $this->assertSame(['admin' => self::PARENT_B], $facts->parents);
        $this->assertSame(['admin' => 'district'], $facts->classifications);
        $this->assertSame(['iso3166-2' => 'NP-P3'], $facts->identifiers);
        $this->assertSame([
            ['source' => 'gazette', 'language' => 'en', 'type' => 'former', 'value' => 'Province No. 3'],
            ['source' => 'gazette', 'language' => 'ne', 'type' => 'official', 'value' => 'बागमती'],
        ], $facts->names);

        $this->assertSame(['admin' => self::PARENT_A], PlaceBelief::of(self::u(), $slots, [], self::t('2020-01-01'), 3)->parents, 'as known at p = 3, before the correction');
        $this->assertSame(['admin' => self::PARENT_A], PlaceBelief::of(self::u(), $slots, [], self::t('2017-01-01'), 4)->parents, 'valid time before the later claim');
    }

    public function test_a_dispute_flags_only_the_claim_in_force_and_never_changes_its_value(): void
    {
        $containment = SlotKey::containment(self::u(), 'admin');
        $old = self::claim($containment, self::PARENT_A, '2016-01-01');
        $correction = self::claim($containment, self::PARENT_B, '2016-01-01');
        $slots = [self::history($containment, [[$old, 1], [$correction, 6]])];
        $disputeOnOld = SlotKey::dispute($old->id);
        $disputes = [$old->id->value() => self::history($disputeOnOld, [[self::claim($disputeOnOld, 'disputed', '2016-01-01'), 3]])];

        $atFour = PlaceBelief::of(self::u(), $slots, $disputes, self::t('2020-01-01'), 4);
        $this->assertSame(['CONTAINMENT'], $atFour->disputedKinds);
        $this->assertSame(['admin' => self::PARENT_A], $atFour->parents, 'a dispute never changes the value');

        $this->assertSame([], PlaceBelief::of(self::u(), $slots, $disputes, self::t('2020-01-01'), 2)->disputedKinds, 'not yet disputed at p = 2');
        $this->assertSame([], PlaceBelief::of(self::u(), $slots, $disputes, self::t('2020-01-01'), 6)->disputedKinds, 'the marker stays with the superseded claim');
    }

    public function test_a_cleared_dispute_no_longer_flags(): void
    {
        $identifier = SlotKey::identifier(self::u(), 'iso3166-2');
        $claim = self::claim($identifier, 'NP-P3', '2016-01-01');
        $dispute = SlotKey::dispute($claim->id);
        $disputes = [$claim->id->value() => self::history($dispute, [[self::claim($dispute, 'disputed', '2016-01-01'), 2], [self::claim($dispute, 'cleared', '2016-01-01'), 5]])];
        $slots = [self::history($identifier, [[$claim, 1]])];

        $this->assertSame(['IDENTIFIER'], PlaceBelief::of(self::u(), $slots, $disputes, self::t('2020-01-01'), 4)->disputedKinds);
        $this->assertSame([], PlaceBelief::of(self::u(), $slots, $disputes, self::t('2020-01-01'), 5)->disputedKinds);
    }

    public function test_a_merge_holds_for_all_time_changes_only_by_knowledge_and_can_be_reversed(): void
    {
        $slot = SlotKey::reconciliation(self::u());
        $merge = self::history($slot, [[self::claim($slot, self::CANONICAL, '1900-01-01'), 3], [self::claim($slot, null, '1900-01-01'), 8]]);

        $this->assertNull(PlaceBelief::resolve(self::u(), $merge, 2), 'not merged before p = 3');
        $atFive = PlaceBelief::resolve(self::u(), $merge, 5);
        $this->assertSame(self::CANONICAL, $atFive?->canonical->value());
        $this->assertSame(3, $atFive?->knownAtPosition);
        $this->assertNull(PlaceBelief::resolve(self::u(), $merge, 8), 'reversed at p = 8');
        $this->assertSame(self::CANONICAL, PlaceBelief::resolve(self::u(), $merge, 7)?->canonical->value(), 'earlier positions keep the merge');

        $facts = PlaceBelief::of(self::u(), [$merge], [], self::t('1850-01-01'), 5);
        $this->assertSame(self::CANONICAL, $facts->mergedInto?->value(), 'valid for all time: even a date before the claim interval');
    }

    public function test_slots_of_another_subject_are_refused(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        PlaceBelief::of(self::u(), [SlotHistory::for(SlotKey::existence(self::u(self::PARENT_A)))], [], self::t('2020-01-01'), 1);
    }
}
