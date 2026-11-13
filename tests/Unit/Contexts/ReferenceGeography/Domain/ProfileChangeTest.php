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
 * PBDIGIT-346 G-2d-4 (v3.11 §13 "G-6 (derived)", §5.2 G-6 rows):
 * - a PROFILE_TIER or PROFILE_CLASS claim may not end, move or change kind while it would break G-4 or leave units
 *   classified into a class without a (territorial) position;
 * - dependencies: the family (CLASSIFICATION, *, scheme) with value = the class, plus those units' containment edges;
 *   a tier change reaches the classes at its position through the family (PROFILE_CLASS, country, scheme, *) = position;
 * - only units of the profile's country are affected.
 */
final class ProfileChangeTest extends TestCase
{
    private const NP = '1a1a1a1a-1a1a-4a1a-8a1a-1a1a1a1a1a1a';
    private const DE = '5e5e5e5e-5e5e-4e5e-8e5e-5e5e5e5e5e5e';
    private const PROV = '2b2b2b2b-2b2b-4b2b-8b2b-2b2b2b2b2b2b';
    private const DIST = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';

    private static int $n = 1500;

    private static function u(string $id): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function claim(SlotKey $slot, ?string $value, ValidInterval $interval): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, $value === null ? ClaimValue::none() : ClaimValue::of($value), $interval, new Basis(BasisKind::Instrument, 'act'));
    }

    private static function tier(string $country, int $position, TierKind $kind, string $label, string $from = '1950-01-01'): Claim
    {
        return self::claim(SlotKey::profileTier(self::u($country), 'admin', $position), (new TierMeaning($kind, $label))->encoded(), self::iv($from));
    }

    private static function cls(string $country, string $class, ?string $position, string $from = '1950-01-01'): Claim
    {
        return self::claim(SlotKey::profileClass(self::u($country), 'admin', $class), $position, self::iv($from));
    }

    /** Nepal: province 3, district 4, unused class "zone" at 5 (territorial), street 9 (address). PROV ⊂ NP, DIST ⊂ PROV. Germany admitted, its own profile. */
    private static function atlas(): InMemoryPublishedAtlas
    {
        $claims = [
            CompetentInstrument::competenceClaim(),
            self::claim(SlotKey::admission(self::u(self::NP)), 'admitted', self::iv('1950-01-01')),
            self::claim(SlotKey::admission(self::u(self::DE)), 'admitted', self::iv('1950-01-01')),
            self::tier(self::NP, 3, TierKind::Territorial, 'province'),
            self::tier(self::NP, 4, TierKind::Territorial, 'district'),
            self::tier(self::NP, 5, TierKind::Territorial, 'zone'),
            self::tier(self::NP, 9, TierKind::AddressComponent, 'street'),
            self::cls(self::NP, 'province', '3'),
            self::cls(self::NP, 'district', '4'),
            self::cls(self::NP, 'zone', '5'),
            self::tier(self::DE, 4, TierKind::Territorial, 'kreis'),
            self::cls(self::DE, 'district', '4'),
            ...array_map(fn (string $unit): Claim => self::claim(SlotKey::existence(self::u($unit)), 'exists', self::iv('1950-01-01')), [self::NP, self::DE, self::PROV, self::DIST]),
            self::claim(SlotKey::containment(self::u(self::PROV), 'admin'), self::NP, self::iv('1950-01-01')),
            self::claim(SlotKey::classification(self::u(self::PROV), 'admin'), 'province', self::iv('1950-01-01')),
            self::claim(SlotKey::containment(self::u(self::DIST), 'admin'), self::PROV, self::iv('1950-01-01')),
            self::claim(SlotKey::classification(self::u(self::DIST), 'admin'), 'district', self::iv('1950-01-01')),
        ];
        $position = 0;

        return new InMemoryPublishedAtlas(count($claims), array_map(static function (Claim $c) use (&$position): PublishedClaim { $position++; return new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, $position - 1), $position); }, $claims));
    }

    private static function validate(array $claims)
    {
        return (new RevisionValidator())->validate($claims, new DependencyRecordingReader(self::atlas()), CompetentInstrument::byId(['act']));
    }

    private static function rules($result): array
    {
        return array_map(static fn ($v): string => $v->rule, $result->violations);
    }

    public function test_a_class_may_not_end_while_units_carry_it(): void
    {
        $result = self::validate([self::cls(self::NP, 'district', null, '2016-01-01')]);

        $this->assertSame(['G-6'], self::rules($result), 'DIST would be classified into a class without a position');
        $this->assertContains(FamilyDependency::of(ClaimKind::Classification, ['scheme' => 'admin'], 'district', self::iv('2016-01-01'))->describe(), $result->dependencies->describe());
    }

    public function test_a_class_nobody_carries_may_end(): void
    {
        $this->assertTrue(self::validate([self::cls(self::NP, 'zone', null, '2016-01-01')])->isValid());
    }

    public function test_moving_a_class_must_keep_parent_above_child(): void
    {
        $this->assertSame(['G-6'], self::rules(self::validate([self::cls(self::NP, 'district', '3', '2016-01-01')])), 'district at 3 = its parent province at 3');
        $this->assertTrue(self::validate([self::cls(self::NP, 'district', '5', '2016-01-01')])->isValid(), 'district at 5 (a territorial tier) keeps the order');
    }

    public function test_moving_a_class_to_a_position_that_is_not_territorial_is_refused(): void
    {
        $this->assertSame(['G-6'], self::rules(self::validate([self::cls(self::NP, 'district', '9', '2016-01-01')])), 'position 9 is an address component (D-83)');
        $this->assertSame(['G-6'], self::rules(self::validate([self::cls(self::NP, 'district', '7', '2016-01-01')])), 'position 7 has no tier');
    }

    public function test_a_tier_may_not_end_or_change_kind_while_units_sit_at_it(): void
    {
        $this->assertSame(['G-6'], self::rules(self::validate([self::tier(self::NP, 4, TierKind::AddressComponent, 'district', '2016-01-01')])), 'districts would sit at an address component');
        $this->assertSame(['G-6'], self::rules(self::validate([self::claim(SlotKey::profileTier(self::u(self::NP), 'admin', 4), null, self::iv('2016-01-01'))])), 'districts would sit at no tier');
        $this->assertTrue(self::validate([self::tier(self::NP, 4, TierKind::Territorial, 'district (renamed)', '2016-01-01')])->isValid(), 'a relabel keeps the kind');
        $this->assertTrue(self::validate([self::tier(self::NP, 9, TierKind::AddressComponent, 'street and lane', '2016-01-01')])->isValid(), 'nobody is classified at 9');
    }

    public function test_only_units_of_the_profiles_country_are_affected(): void
    {
        $this->assertTrue(self::validate([self::cls(self::DE, 'district', null, '2016-01-01')])->isValid(), 'Germany\'s "district" class carries no Nepali unit');
    }

    public function test_a_later_unit_classified_into_the_moved_class_makes_the_validation_stale(): void
    {
        $result = self::validate([self::cls(self::NP, 'zone', null, '2016-01-01')]);
        $later = self::claim(SlotKey::classification(self::u(self::DIST), 'admin'), 'zone', self::iv('2017-01-01'));
        $published = new PublishedClaim($later, BeforeImage::of(SlotHistory::for($later->slot), $later->interval, 99), 100);

        $this->assertSame($published, $result->dependencies->firstInvalidating([$published]), 'a gain in the class family');
    }
}
