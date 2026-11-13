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
use App\Contexts\ReferenceGeography\Domain\TierKind;
use App\Contexts\ReferenceGeography\Domain\TierMeaning;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-2d-3 (v3.11 §13; D-81, D-83, D-91; §5.2 G-4 rows):
 * - a PROFILE_TIER value is a tier kind (TERRITORIAL | ADDRESS_COMPONENT) and a label; a PROFILE_CLASS value is the
 *   class's position; profile slots exist only for positions 3–10 (0–2 are fixed);
 * - level 2 is marked by ADMISSION (D-91, §14); position(u) = PROFILE_CLASS(country(u), scheme, class(u)), country(u)
 *   being u's level-2 ancestor through containment; a level-2 entry is at position 2;
 * - D-83: a class used in CLASSIFICATION must sit at a TERRITORIAL position;
 * - G-4: for every containment u → P at t, position(P) < position(u); checked on containment AND on classification
 *   changes (parent and children); never by counting parents; over every date of the interval where a read fact changes.
 */
final class TierAndPositionTest extends TestCase
{
    private const NP = '1a1a1a1a-1a1a-4a1a-8a1a-1a1a1a1a1a1a';
    private const PROV = '2b2b2b2b-2b2b-4b2b-8b2b-2b2b2b2b2b2b';
    private const DIST = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';
    private const X = '4d4d4d4d-4d4d-4d4d-8d4d-4d4d4d4d4d4d';

    private static int $n = 1200;

    private static function u(string $id): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function claim(SlotKey $slot, string $value, ValidInterval $interval): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, ClaimValue::of($value), $interval, new Basis(BasisKind::Instrument, 'act'));
    }

    /** Nepal admitted; profile: province = 3, district = 4 (territorial), street = 9 (address component); PROV ⊂ NP, DIST ⊂ PROV. */
    private static function atlas(array $more = [], array $districtPositions = [['4', '1950-01-01', null]]): InMemoryPublishedAtlas
    {
        $np = self::u(self::NP);
        $claims = [
            CompetentInstrument::competenceClaim(),
            self::claim(SlotKey::admission($np), 'admitted', self::iv('1950-01-01')),
            self::claim(SlotKey::profileTier($np, 'admin', 3), (new TierMeaning(TierKind::Territorial, 'province'))->encoded(), self::iv('1950-01-01')),
            self::claim(SlotKey::profileTier($np, 'admin', 4), (new TierMeaning(TierKind::Territorial, 'district'))->encoded(), self::iv('1950-01-01')),
            self::claim(SlotKey::profileTier($np, 'admin', 9), (new TierMeaning(TierKind::AddressComponent, 'street'))->encoded(), self::iv('1950-01-01')),
            self::claim(SlotKey::profileClass($np, 'admin', 'province'), '3', self::iv('1950-01-01')),
            self::claim(SlotKey::profileClass($np, 'admin', 'street'), '9', self::iv('1950-01-01')),
            ...array_map(fn (array $d): Claim => self::claim(SlotKey::profileClass($np, 'admin', 'district'), $d[0], self::iv($d[1], $d[2])), $districtPositions),
            ...array_map(fn (string $unit): Claim => self::claim(SlotKey::existence(self::u($unit)), 'exists', self::iv('1950-01-01')), [self::NP, self::PROV, self::DIST]),
            self::claim(SlotKey::containment(self::u(self::PROV), 'admin'), self::NP, self::iv('1950-01-01')),
            self::claim(SlotKey::classification(self::u(self::PROV), 'admin'), 'province', self::iv('1950-01-01')),
            self::claim(SlotKey::containment(self::u(self::DIST), 'admin'), self::PROV, self::iv('1950-01-01')),
            self::claim(SlotKey::classification(self::u(self::DIST), 'admin'), 'district', self::iv('1950-01-01')),
            ...$more,
        ];
        $position = 0;

        return new InMemoryPublishedAtlas(count($claims), array_map(static function (Claim $c) use (&$position): PublishedClaim { $position++; return new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, $position - 1), $position); }, $claims));
    }

    private static function validate(array $claims, ?InMemoryPublishedAtlas $atlas = null)
    {
        return (new RevisionValidator())->validate($claims, new DependencyRecordingReader($atlas ?? self::atlas()), CompetentInstrument::byId(['act']));
    }

    private static function rules($result): array
    {
        return array_map(static fn ($v): string => $v->rule, $result->violations);
    }

    private static function newUnit(string $class, string $parent, string $from = '2016-01-01'): array
    {
        return [
            self::claim(SlotKey::existence(self::u(self::X)), 'exists', self::iv($from)),
            self::claim(SlotKey::classification(self::u(self::X), 'admin'), $class, self::iv($from)),
            self::claim(SlotKey::containment(self::u(self::X), 'admin'), $parent, self::iv($from)),
        ];
    }

    public function test_a_tier_meaning_is_a_kind_and_a_label_canonically_encoded(): void
    {
        $t = new TierMeaning(TierKind::AddressComponent, 'street');

        $this->assertSame('ADDRESS_COMPONENT:street', $t->encoded());
        $this->assertEquals($t, TierMeaning::parse($t->encoded()));
        $this->assertSame(['TERRITORIAL', 'ADDRESS_COMPONENT'], array_map(static fn (TierKind $k): string => $k->value, TierKind::cases()));
        foreach (['district', 'TERRITORIAL:', 'COUNTY:district', ''] as $bad) {
            try {
                TierMeaning::parse($bad);
                $this->fail("'{$bad}' must be refused");
            } catch (\InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_profile_slots_exist_only_at_positions_3_to_10_with_well_formed_values(): void
    {
        $np = self::u(self::NP);

        $this->assertSame(['PROFILE_FORM'], self::rules(self::validate([self::claim(SlotKey::profileTier($np, 'admin', 2), 'TERRITORIAL:country', self::iv('2016-01-01'))])), 'positions 0–2 are fixed');
        $this->assertSame(['PROFILE_FORM'], self::rules(self::validate([self::claim(SlotKey::profileTier($np, 'admin', 5), 'municipality', self::iv('2016-01-01'))])), 'kind and label');
        $this->assertSame(['PROFILE_FORM'], self::rules(self::validate([self::claim(SlotKey::profileClass($np, 'admin', 'ward'), '11', self::iv('2016-01-01'))])));
        $this->assertTrue(self::validate([self::claim(SlotKey::profileTier($np, 'admin', 5), 'TERRITORIAL:municipality', self::iv('2016-01-01'))])->isValid());
    }

    public function test_d83_a_class_must_sit_at_a_territorial_position(): void
    {
        $this->assertTrue(self::validate(self::newUnit('district', self::PROV))->isValid());
        $this->assertSame(['D-83'], self::rules(self::validate(self::newUnit('street', self::PROV))), 'an address component is never an official unit');
        $this->assertSame(['D-83'], self::rules(self::validate(self::newUnit('ward', self::PROV))), 'a class with no position');
    }

    public function test_g4_a_parent_sits_above_its_child_and_positions_may_be_skipped(): void
    {
        $this->assertTrue(self::validate(self::newUnit('district', self::NP))->isValid(), 'district (4) directly under the country (2): a skipped position');
        $this->assertSame(['G-4'], self::rules(self::validate(self::newUnit('province', self::DIST))), 'province (3) under a district (4)');
        $this->assertSame(['G-4'], self::rules(self::validate(self::newUnit('district', self::DIST))), 'equal positions');
    }

    public function test_g4_on_a_classification_change_reads_the_parent_and_the_children(): void
    {
        $promote = self::claim(SlotKey::classification(self::u(self::PROV), 'admin'), 'district', self::iv('2016-01-01'));   // PROV 3 → 4, its child DIST is 4

        $result = self::validate([$promote]);

        $this->assertSame(['G-4'], self::rules($result));
        $this->assertContains(FamilyDependency::of(ClaimKind::Containment, ['scheme' => 'admin'], self::PROV, self::iv('2016-01-01'))->describe(), $result->dependencies->describe(), 'the children are a recorded family read');
    }

    public function test_g4_is_checked_wherever_a_read_fact_changes_inside_the_interval(): void
    {
        // the district class moves from position 4 to position 3 in 2020: from then, a district under a province breaks G-4
        $atlas = self::atlas(districtPositions: [['4', '1950-01-01', '2020-01-01'], ['3', '2020-01-01', null]]);

        $this->assertSame(['G-4'], self::rules(self::validate(self::newUnit('district', self::PROV, '2016-01-01'), $atlas)));
        $this->assertTrue(self::validate([...self::newUnit('district', self::PROV, '2016-01-01')], self::atlas())->isValid(), 'without the 2020 change it is valid');
    }

    public function test_a_later_reclassification_of_the_parent_makes_the_validation_stale(): void
    {
        $result = self::validate(self::newUnit('district', self::PROV));
        $later = self::claim(SlotKey::classification(self::u(self::PROV), 'admin'), 'district', self::iv('2018-01-01'));
        $published = new PublishedClaim($later, BeforeImage::of(SlotHistory::for($later->slot), $later->interval, 99), 100);

        $this->assertSame($published, $result->dependencies->firstInvalidating([$published]));
    }
}
