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
use App\Contexts\ReferenceGeography\Domain\DependencySet;
use App\Contexts\ReferenceGeography\Domain\FamilyDependency;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotDependency;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\SubjectFamilyDependency;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-2b, cycle 2 (v3.11 §5.1 "Two dependency forms", "Gains and losses by kind", "Not stale"):
 * - a SLOT dependency is invalidated by any claim published after p₀ in that slot over an overlapping interval,
 *   whatever its value;
 * - a FAMILY dependency (one kind, fixed key components, value = X, over V) is invalidated by a claim in the family
 *   whose NEW value is X (a gain) or whose BEFORE-IMAGE had X (a loss) over an overlapping interval;
 * - a SUBJECT family (every slot about one unit, all time) serves reconciliation;
 * - claims at or before p₀, in other families, or outside the intervals never make a revision stale.
 */
final class DependencyTest extends TestCase
{
    private const A = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const B = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';
    private const C = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const E = '9e8d7c6b-5a4f-4e3d-8c2b-1a0f9e8d7c6b';
    private const P0 = 10;

    private static int $n = 0;

    private static function u(string $id): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    /** A claim published at a position, with its before-image computed from what the slot held before. */
    private static function published(SlotKey $slot, ?string $value, ValidInterval $interval, int $position, ?SlotHistory $before = null): PublishedClaim
    {
        self::$n++;
        $claim = new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, $value === null ? ClaimValue::none() : ClaimValue::of($value), $interval, new Basis(BasisKind::Instrument, 'act'));

        return new PublishedClaim($claim, BeforeImage::of($before ?? SlotHistory::for($slot), $interval, $position - 1), $position);
    }

    private static function history(SlotKey $slot, string $value, string $from): SlotHistory
    {
        self::$n++;

        return SlotHistory::for($slot)->with(new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, ClaimValue::of($value), self::iv($from), new Basis(BasisKind::Instrument, 'act')), 1);
    }

    private static function childrenOfA(): FamilyDependency
    {
        return FamilyDependency::of(ClaimKind::Containment, ['scheme' => 'admin'], self::A, self::iv('2016-01-01', '2017-01-01'));
    }

    public function test_slot_dependency_any_claim_in_the_slot_overlapping_whatever_its_value(): void
    {
        $mandate = SlotKey::mandate('npl-constitution');
        $dep = new SlotDependency($mandate, self::iv('2016-01-01', '2017-01-01'));

        $this->assertTrue($dep->isInvalidatedBy(self::published($mandate, null, self::iv('2016-06-01'), 11)), 'a mandate withdrawn');
        $this->assertTrue($dep->isInvalidatedBy(self::published($mandate, 'rescoped', self::iv('2015-01-01'), 11)), 'a mandate rescoped');
        $this->assertFalse($dep->isInvalidatedBy(self::published($mandate, 'x', self::iv('2017-01-01'), 11)), 'outside the interval');
        $this->assertFalse($dep->isInvalidatedBy(self::published(SlotKey::mandate('other'), 'x', self::iv('2016-01-01'), 11)), 'another slot');
    }

    public function test_a_dispute_state_change_on_relied_upon_evidence_is_a_slot_dependency(): void
    {
        $relied = ClaimId::fromString('00000000-0000-4000-8000-999999999999');
        $dep = new SlotDependency(SlotKey::dispute($relied), self::iv('2016-01-01'));

        $this->assertTrue($dep->isInvalidatedBy(self::published(SlotKey::dispute($relied), 'disputed', self::iv('2016-01-01'), 11)));
    }

    public function test_family_loss_c_moves_from_a_to_b(): void
    {
        $slot = SlotKey::containment(self::u(self::C), 'admin');
        $move = self::published($slot, self::B, self::iv('2016-03-01'), 11, self::history($slot, self::A, '2015-01-01'));

        $this->assertTrue(self::childrenOfA()->isInvalidatedBy($move), 'before-image A: a loss for "children of A"');
    }

    public function test_family_gain_a_new_child_of_a(): void
    {
        $this->assertTrue(self::childrenOfA()->isInvalidatedBy(self::published(SlotKey::containment(self::u(self::E), 'admin'), self::A, self::iv('2016-05-01'), 11)), 'new value A: a gain');
    }

    public function test_family_loss_a_shortened_valid_time_is_a_withdrawal_whose_before_image_matches(): void
    {
        $slot = SlotKey::containment(self::u(self::C), 'admin');
        $shortening = self::published($slot, null, self::iv('2016-06-01'), 11, self::history($slot, self::A, '2015-01-01'));

        $this->assertTrue(self::childrenOfA()->isInvalidatedBy($shortening));
    }

    public function test_family_not_stale_other_scheme_outside_interval_or_another_kind(): void
    {
        $this->assertFalse(self::childrenOfA()->isInvalidatedBy(self::published(SlotKey::containment(self::u(self::E), 'electoral'), self::A, self::iv('2016-05-01'), 11)), 'another scheme');
        $this->assertFalse(self::childrenOfA()->isInvalidatedBy(self::published(SlotKey::containment(self::u(self::E), 'admin'), self::A, self::iv('2017-01-01'), 11)), 'after V');
        $this->assertFalse(self::childrenOfA()->isInvalidatedBy(self::published(SlotKey::classification(self::u(self::C), 'admin'), self::A, self::iv('2016-01-01'), 11)), 'another kind');
        $this->assertFalse(self::childrenOfA()->isInvalidatedBy(self::published(SlotKey::containment(self::u(self::E), 'admin'), self::B, self::iv('2016-05-01'), 11)), 'B, never A');
    }

    public function test_identifier_holders_gain_and_loss(): void
    {
        $holdersOfX = FamilyDependency::of(ClaimKind::Identifier, ['namespace' => 'iso3166-1-alpha2'], 'XK', self::iv('2016-01-01'));
        $slotC = SlotKey::identifier(self::u(self::C), 'iso3166-1-alpha2');

        $this->assertTrue($holdersOfX->isInvalidatedBy(self::published($slotC, null, self::iv('2016-01-01'), 11, self::history($slotC, 'XK', '2008-01-01'))), 'a holder relinquishes X');
        $this->assertTrue($holdersOfX->isInvalidatedBy(self::published(SlotKey::identifier(self::u(self::E), 'iso3166-1-alpha2'), 'XK', self::iv('2016-01-01'), 11)), 'a new holder takes X');
        $this->assertFalse($holdersOfX->isInvalidatedBy(self::published(SlotKey::identifier(self::u(self::E), 'fips'), 'XK', self::iv('2016-01-01'), 11)), 'another namespace');
    }

    public function test_class_family_a_unit_leaves_the_class(): void
    {
        $unitsOfClassDistrict = FamilyDependency::of(ClaimKind::Classification, ['scheme' => 'admin'], 'district', self::iv('2016-01-01'));
        $slot = SlotKey::classification(self::u(self::C), 'admin');

        $this->assertTrue($unitsOfClassDistrict->isInvalidatedBy(self::published($slot, 'province', self::iv('2016-02-01'), 11, self::history($slot, 'district', '2010-01-01'))));
    }

    public function test_subject_family_every_slot_about_the_unit_all_time(): void
    {
        $dep = new SubjectFamilyDependency(self::u(self::A));

        $this->assertTrue($dep->isInvalidatedBy(self::published(SlotKey::sourceName(self::u(self::A), 'src', 'ne', 'official'), 'X', self::iv('1900-01-01', '1901-01-01'), 11)), 'any kind, any time');
        $this->assertTrue($dep->isInvalidatedBy(self::published(SlotKey::reconciliation(self::u(self::A)), self::B, self::iv('2016-01-01'), 11)));
        $this->assertFalse($dep->isInvalidatedBy(self::published(SlotKey::existence(self::u(self::B)), 'exists', self::iv('2016-01-01'), 11)), 'another unit');
        $this->assertFalse($dep->isInvalidatedBy(self::published(SlotKey::containment(self::u(self::E), 'admin'), self::A, self::iv('2016-01-01'), 11)), 'a value naming A is an incoming mapping, a separate family dependency');
    }

    public function test_a_dependency_set_ignores_claims_at_or_before_p0_and_reports_the_first_invalidating_one(): void
    {
        $set = new DependencySet(self::P0, [self::childrenOfA(), new SlotDependency(SlotKey::mandate('m'), self::iv('2016-01-01'))]);
        $atP0 = self::published(SlotKey::containment(self::u(self::E), 'admin'), self::A, self::iv('2016-05-01'), self::P0);
        $unrelated = self::published(SlotKey::existence(self::u(self::B)), 'exists', self::iv('2016-01-01'), 11);
        $gain = self::published(SlotKey::containment(self::u(self::E), 'admin'), self::A, self::iv('2016-05-01'), 12);

        $this->assertNull($set->firstInvalidating([$atP0, $unrelated]), 'not stale: known at p₀, or unrelated');
        $this->assertSame($gain, $set->firstInvalidating([$atP0, $unrelated, $gain]));
        $this->assertSame(self::P0, $set->p0);
        $this->assertNull((new DependencySet(self::P0, []))->firstInvalidating([$gain]), 'no dependencies: never stale');
    }

    public function test_slot_keys_expose_their_components_and_subject(): void
    {
        $key = SlotKey::containment(self::u(self::C), 'admin');

        $this->assertSame('admin', $key->component('scheme'));
        $this->assertNull($key->component('namespace'));
        $this->assertSame(self::C, $key->subject()?->value());
        $this->assertNull(SlotKey::mandate('m')->subject(), 'a registry item is no unit');
    }
}
