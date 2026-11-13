<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\Basis;
use App\Contexts\ReferenceGeography\Domain\BasisKind;
use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\Claim;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimValue;
use App\Contexts\ReferenceGeography\Domain\DependencyRecordingReader;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidationResult;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * G-2 finding F-1, option (a) (Principal, 2026-11-13; v3.11 §13: position = PROFILE_CLASS(country(u), …) and G-4 holds
 * for EVERY containment edge): a claim that changes country(u) (a containment change, a containment ending, an
 * admission starting or ending) re-checks D-83 and G-4 for u's whole affected subtree in that scheme, at every date
 * of its interval where a read fact changes; every descendant read is recorded. A change that leaves country(u)
 * unchanged reads no descendants (no false staleness). Nothing forbids cross-country moves.
 *
 * Tree (scheme admin): NP (admitted) ⊃ P (province) ⊃ X (district) ⊃ W (ward); NP also ⊃ P2 (province).
 * Profiles: NP province 3, district 4, ward 5 · DE province 3, district 4, ward 5 (a valid destination) ·
 * FR district 4, province 6, ward 7 (inverts province and district) · IN province 3, district 4, NO ward.
 */
final class CountryChangeSubtreeTest extends TestCase
{
    private const NP = '1a1a1a1a-1a1a-4a1a-8a1a-1a1a1a1a1a1a';
    private const DE = '5e5e5e5e-5e5e-4e5e-8e5e-5e5e5e5e5e5e';
    private const FR = '6f6f6f6f-6f6f-4f6f-8f6f-6f6f6f6f6f6f';
    private const IN = '7a7a7a7a-7a7a-4a7a-8a7a-7a7a7a7a7a7a';
    private const P = '2b2b2b2b-2b2b-4b2b-8b2b-2b2b2b2b2b2b';
    private const P2 = '2c2c2c2c-2c2c-4c2c-8c2c-2c2c2c2c2c2c';
    private const X = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';
    private const W = '4d4d4d4d-4d4d-4d4d-8d4d-4d4d4d4d4d4d';

    private static int $n = 4000;

    private static function u(string $id): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function claim(SlotKey $slot, ?string $value, string $from = '1950-01-01', ?string $to = null): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, $value === null ? ClaimValue::none() : ClaimValue::of($value), self::iv($from, $to), new Basis(BasisKind::Instrument, 'act'));
    }

    /** @param array<string, string> $classes class => position */
    private static function profile(string $country, array $classes, string $from = '1950-01-01'): array
    {
        $out = [];
        foreach ($classes as $class => $position) {
            $out[] = self::claim(SlotKey::profileTier(self::u($country), 'admin', (int) $position), "TERRITORIAL:{$class}", $from);
            $out[] = self::claim(SlotKey::profileClass(self::u($country), 'admin', $class), $position, $from);
        }

        return $out;
    }

    private static function atlas(array $frProfile = []): InMemoryPublishedAtlas
    {
        $claims = [
            CompetentInstrument::competenceClaim(),
            ...array_map(fn (string $c): Claim => self::claim(SlotKey::admission(self::u($c)), 'admitted'), [self::NP, self::DE, self::FR, self::IN]),
            ...self::profile(self::NP, ['province' => '3', 'district' => '4', 'ward' => '5']),
            ...self::profile(self::DE, ['province' => '3', 'district' => '4', 'ward' => '5']),
            ...($frProfile ?: self::profile(self::FR, ['district' => '4', 'province' => '6', 'ward' => '7'])),
            ...self::profile(self::IN, ['province' => '3', 'district' => '4']),
            ...array_map(fn (string $x): Claim => self::claim(SlotKey::existence(self::u($x)), 'exists'), [self::NP, self::DE, self::FR, self::IN, self::P, self::P2, self::X, self::W]),
            self::claim(SlotKey::containment(self::u(self::P), 'admin'), self::NP),
            self::claim(SlotKey::classification(self::u(self::P), 'admin'), 'province'),
            self::claim(SlotKey::containment(self::u(self::P2), 'admin'), self::NP),
            self::claim(SlotKey::classification(self::u(self::P2), 'admin'), 'province'),
            self::claim(SlotKey::containment(self::u(self::X), 'admin'), self::P),
            self::claim(SlotKey::classification(self::u(self::X), 'admin'), 'district'),
            self::claim(SlotKey::containment(self::u(self::W), 'admin'), self::X),
            self::claim(SlotKey::classification(self::u(self::W), 'admin'), 'ward'),
        ];
        $p = 0;

        return new InMemoryPublishedAtlas(count($claims), array_map(static function (Claim $c) use (&$p): PublishedClaim { $p++; return new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, $p - 1), $p); }, $claims));
    }

    private static function validate(array $claims, ?InMemoryPublishedAtlas $atlas = null): ValidationResult
    {
        return (new RevisionValidator())->validate($claims, new DependencyRecordingReader($atlas ?? self::atlas()), CompetentInstrument::byId(['act']));
    }

    /** @return list<string> "RULE" sorted */
    private static function rules(ValidationResult $result): array
    {
        $rules = array_map(static fn ($v): string => $v->rule, $result->violations);
        sort($rules);

        return $rules;
    }

    private static function moveP(string $to, string $from = '2016-01-01'): Claim
    {
        return self::claim(SlotKey::containment(self::u(self::P), 'admin'), $to, $from);
    }

    private static function later(SlotKey $slot, string $value): PublishedClaim
    {
        $c = self::claim($slot, $value, '2018-01-01');

        return new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, 999), 1000);
    }

    public function test_moving_a_subtree_into_a_country_with_a_compatible_profile_is_accepted(): void
    {
        $this->assertTrue(self::validate([self::moveP(self::DE)])->isValid(), 'cross-country moves are not forbidden');
    }

    public function test_the_nepal_to_france_move_breaks_g4_for_the_descendant_edge(): void
    {
        // FR: province 6, district 4: X (district) would sit above its parent P (province)
        $this->assertSame(['G-4'], self::rules(self::validate([self::moveP(self::FR)])));
    }

    public function test_a_descendant_whose_class_the_destination_profile_lacks_is_refused_by_d83(): void
    {
        $this->assertSame(['D-83'], self::rules(self::validate([self::moveP(self::IN)])), 'W (ward): India has no ward position');
    }

    public function test_ending_a_containment_leaves_the_whole_subtree_without_a_country(): void
    {
        $this->assertSame(['D-83', 'D-83', 'D-83'], self::rules(self::validate([self::claim(SlotKey::containment(self::u(self::P), 'admin'), null, '2016-01-01')])), 'P, X and W are classified with no country');
    }

    public function test_ending_an_admission_leaves_its_classified_units_without_a_country(): void
    {
        $this->assertSame(['D-83', 'D-83', 'D-83', 'D-83'], self::rules(self::validate([self::claim(SlotKey::admission(self::u(self::NP)), null, '2016-01-01')])), 'P, P2, X and W');
    }

    public function test_an_admission_starting_inside_a_tree_is_checked_with_the_proposed_profile_in_the_same_revision(): void
    {
        // P leaves Nepal and becomes a level-2 entry with its own profile, all in one revision
        $detach = self::claim(SlotKey::containment(self::u(self::P), 'admin'), null, '2016-01-01');
        $admit = self::claim(SlotKey::admission(self::u(self::P)), 'admitted', '2016-01-01');

        $this->assertSame(['D-83', 'D-83'], self::rules(self::validate([$detach, $admit])), 'without a profile for P: X and W have no position');
        $this->assertTrue(self::validate([$detach, $admit, ...self::profile(self::P, ['district' => '4', 'ward' => '5'], '2016-01-01')])->isValid());
    }

    public function test_every_period_of_the_interval_is_checked(): void
    {
        // FR swaps its positions only from 2020: the 2016 move is valid until then, and breaks G-4 from 2020
        $fr = [...self::profile(self::FR, ['district' => '4', 'ward' => '7']),
            self::claim(SlotKey::profileTier(self::u(self::FR), 'admin', 3), 'TERRITORIAL:province', '1950-01-01', '2020-01-01'),
            self::claim(SlotKey::profileClass(self::u(self::FR), 'admin', 'province'), '3', '1950-01-01', '2020-01-01'),
            self::claim(SlotKey::profileTier(self::u(self::FR), 'admin', 6), 'TERRITORIAL:province', '2020-01-01'),
            self::claim(SlotKey::profileClass(self::u(self::FR), 'admin', 'province'), '6', '2020-01-01')];

        $this->assertSame(['G-4'], self::rules(self::validate([self::moveP(self::FR)], self::atlas($fr))));
        $this->assertTrue(self::validate([self::claim(SlotKey::containment(self::u(self::P), 'admin'), self::FR, '2016-01-01', '2020-01-01')], self::atlas($fr))->isValid(), 'a move ending before 2020 is valid');
    }

    public function test_a_later_change_to_a_descendant_makes_the_validation_stale(): void
    {
        $result = self::validate([self::moveP(self::DE)]);

        $this->assertNotNull($result->dependencies->firstInvalidating([self::later(SlotKey::classification(self::u(self::W), 'admin'), 'district')]), 'a grandchild\'s classification was read');
        $this->assertNotNull($result->dependencies->firstInvalidating([self::later(SlotKey::containment(self::u(self::P2), 'admin'), self::X)]), 'a new grandchild under X (a family gain)');
    }

    public function test_a_move_within_one_country_reads_no_descendants(): void
    {
        $result = self::validate([self::claim(SlotKey::containment(self::u(self::X), 'admin'), self::P2, '2016-01-01')]);

        $this->assertTrue($result->isValid());
        $this->assertNull($result->dependencies->firstInvalidating([self::later(SlotKey::classification(self::u(self::W), 'admin'), 'province')]), 'no false staleness');
    }

    public function test_a_country_change_in_another_scheme_does_not_touch_this_schemes_subtree(): void
    {
        $result = self::validate([self::claim(SlotKey::containment(self::u(self::P), 'electoral'), self::FR, '2016-01-01')]);

        $this->assertTrue($result->isValid());
        $this->assertNull($result->dependencies->firstInvalidating([self::later(SlotKey::classification(self::u(self::W), 'admin'), 'province')]));
    }
}
