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
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * G-2 acceptance review (PBDIGIT-346): VERIFICATION tests for the "null position" question. They passed on first run
 * (no code change); they pin why an undetermined position does not let a G-4 edge escape:
 * - an unclassified unit has no position, so its edge is checked when it (or its parent) is classified, because a
 *   classification change reads the parent AND the children (v3.11 §5.2 G-4 rows);
 * - a classified unit with no reachable country is refused by D-83 (no territorial position without a country);
 * - scheme isolation: a profile change in one scheme does not reach units classified in another.
 * NOT covered here (open finding F-1 of the review): a containment or admission change that changes country(u) for a
 * whole subtree; see evidence probe-cross-country-move.php.
 */
final class UndeterminedPositionTest extends TestCase
{
    private const NP = '1a1a1a1a-1a1a-4a1a-8a1a-1a1a1a1a1a1a';
    private const P = '2b2b2b2b-2b2b-4b2b-8b2b-2b2b2b2b2b2b';
    private const X = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';

    private static int $n = 2000;

    private static function u(string $id): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    private static function claim(SlotKey $slot, string $value): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, ClaimValue::of($value), new ValidInterval(ValidDate::fromString('1950-01-01')), new Basis(BasisKind::Instrument, 'act'));
    }

    /** NP admitted, province 3 / district 4; P and X exist; P ⊂ NP. Plus $more. */
    private static function rules(array $revision, array $more): array
    {
        $np = self::u(self::NP);
        $all = [
            CompetentInstrument::competenceClaim(),
            self::claim(SlotKey::admission($np), 'admitted'),
            self::claim(SlotKey::profileTier($np, 'admin', 3), 'TERRITORIAL:province'),
            self::claim(SlotKey::profileTier($np, 'admin', 4), 'TERRITORIAL:district'),
            self::claim(SlotKey::profileClass($np, 'admin', 'province'), '3'),
            self::claim(SlotKey::profileClass($np, 'admin', 'district'), '4'),
            ...array_map(fn (string $x): Claim => self::claim(SlotKey::existence(self::u($x)), 'exists'), [self::NP, self::P, self::X]),
            self::claim(SlotKey::containment(self::u(self::P), 'admin'), self::NP),
            ...$more,
        ];
        $p = 0;
        $atlas = new InMemoryPublishedAtlas(count($all), array_map(static function (Claim $c) use (&$p): PublishedClaim { $p++; return new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, $p - 1), $p); }, $all));

        return array_map(static fn ($v): string => $v->rule, (new RevisionValidator())->validate($revision, new DependencyRecordingReader($atlas), CompetentInstrument::byId(['act']))->violations);
    }

    public function test_an_edge_under_an_unclassified_parent_is_checked_when_the_parent_is_classified(): void
    {
        // published: X (province, 3) under P, P not yet classified: the edge has no parent position yet
        $published = [self::claim(SlotKey::classification(self::u(self::X), 'admin'), 'province'), self::claim(SlotKey::containment(self::u(self::X), 'admin'), self::P)];

        $this->assertSame(['G-4'], self::rules([self::claim(SlotKey::classification(self::u(self::P), 'admin'), 'district')], $published), 'classifying P as district (4) above a province (3) is refused');
        $this->assertSame([], self::rules([self::claim(SlotKey::classification(self::u(self::P), 'admin'), 'province')], [self::claim(SlotKey::classification(self::u(self::X), 'admin'), 'district'), self::claim(SlotKey::containment(self::u(self::X), 'admin'), self::P)]), 'a correct one is accepted');
    }

    public function test_an_edge_of_an_unclassified_child_is_checked_when_the_child_is_classified(): void
    {
        // published: P (district, 4); X under P, X not yet classified
        $published = [self::claim(SlotKey::classification(self::u(self::P), 'admin'), 'district'), self::claim(SlotKey::containment(self::u(self::X), 'admin'), self::P)];

        $this->assertSame(['G-4'], self::rules([self::claim(SlotKey::classification(self::u(self::X), 'admin'), 'province')], $published));
    }

    public function test_a_profile_change_in_one_scheme_does_not_touch_units_classified_in_another_scheme(): void
    {
        // X carries the class "district" only in the "electoral" scheme; ending the "admin" district class must not reach it
        $published = [self::claim(SlotKey::containment(self::u(self::X), 'electoral'), self::NP), self::claim(SlotKey::classification(self::u(self::X), 'electoral'), 'district')];
        $end = new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000002999'), SlotKey::profileClass(self::u(self::NP), 'admin', 'district'), ClaimValue::none(), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis(BasisKind::Instrument, 'act'));

        $this->assertSame([], self::rules([$end], $published));
    }

    public function test_a_classified_unit_with_no_reachable_country_is_refused_by_d83(): void
    {
        // X classified, but X has no containment at all: no country, so no territorial position
        $this->assertSame(['D-83'], self::rules([self::claim(SlotKey::classification(self::u(self::X), 'admin'), 'district')], []));
    }
}
