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
use App\Contexts\ReferenceGeography\Domain\PublishedAtlas;
use App\Contexts\ReferenceGeography\Domain\PublishedClaim;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionValidator;
use App\Contexts\ReferenceGeography\Domain\SlotDependency;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\CompetentInstrument;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-2c, cycle 3 (v3.11 §5, §5.1, §5.2, §14). The validator, over the recording reader only:
 * - before-images at p₀, protected by slot dependencies on the revision's own target slots;
 * - the derived structural classification;
 * - "Existence of parents": a named parent exists over the whole interval (published, or created in this revision);
 * - "Admission before grouping" (D-86): a GROUPING needs the entry's ADMISSION over its interval;
 * - every read is recorded; a later relevant publication invalidates, an unrelated one does not.
 */
final class RevisionValidatorTest extends TestCase
{
    private const C = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const P = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const NP = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';
    private const P0 = 5;

    private static int $n = 100;

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function u(string $id): ReferenceUnitId
    {
        return ReferenceUnitId::fromString($id);
    }

    private static function claim(SlotKey $slot, ?string $value, ValidInterval $interval, BasisKind $basis = BasisKind::Instrument): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, $value === null ? ClaimValue::none() : ClaimValue::of($value), $interval, new Basis($basis, 'act'));
    }

    private static function pub(Claim $claim, int $position): PublishedClaim
    {
        return new PublishedClaim($claim, BeforeImage::of(SlotHistory::for($claim->slot), $claim->interval, $position - 1), $position);
    }

    /** P exists from 2010 (position 1); C's parent was P from 2012 (position 2). */
    private static function atlas(array $more = []): InMemoryPublishedAtlas
    {
        return new InMemoryPublishedAtlas(self::P0, [
            self::pub(CompetentInstrument::competenceClaim(), 1),   // D-82: the instruments' issuer is competent
            self::pub(self::claim(SlotKey::existence(self::u(self::P)), 'exists', self::iv('2010-01-01')), 1),
            self::pub(self::claim(SlotKey::containment(self::u(self::C), 'admin'), self::P, self::iv('2012-01-01')), 2),
            ...$more,
        ]);
    }

    private static function validate(array $claims, ?InMemoryPublishedAtlas $atlas = null)
    {
        return (new RevisionValidator())->validate($claims, new DependencyRecordingReader($atlas ?? self::atlas()), CompetentInstrument::byId(['act']));
    }

    /** @return list<string> */
    private static function rules($result): array
    {
        return array_map(static fn ($v): string => $v->rule, $result->violations);
    }

    public function test_before_images_are_computed_at_p0_and_protected_by_slot_dependencies_on_the_target_slots(): void
    {
        $slot = SlotKey::containment(self::u(self::C), 'admin');
        $claim = self::claim($slot, null, self::iv('2016-01-01'));   // withdraw C's parent from 2016

        $result = self::validate([$claim]);

        $this->assertSame([['2016-01-01', null, self::P]], array_map(static fn (array $s): array => [$s[0]->from->value(), $s[0]->to?->value(), $s[1]->describe()], $result->beforeImageOf($claim->id)->segments()));
        $this->assertContains((new SlotDependency($slot, self::iv('2016-01-01')))->describe(), $result->dependencies->describe());
        $this->assertSame(self::P0, $result->dependencies->p0);
    }

    public function test_the_structural_classification_is_derived(): void
    {
        $this->assertTrue(self::validate([self::claim(SlotKey::containment(self::u(self::C), 'admin'), self::P, self::iv('2016-01-01'))])->structural);
        $this->assertFalse(self::validate([self::claim(SlotKey::sourceName(self::u(self::C), 's', 'ne', 'official'), 'Koshi', self::iv('2016-01-01'))])->structural);
    }

    public function test_a_parent_must_exist_over_the_whole_interval(): void
    {
        $ok = self::validate([self::claim(SlotKey::containment(self::u(self::NP), 'admin'), self::P, self::iv('2016-01-01'))]);
        $tooEarly = self::validate([self::claim(SlotKey::containment(self::u(self::NP), 'admin'), self::P, self::iv('2009-01-01'))]);
        $unknown = self::validate([self::claim(SlotKey::containment(self::u(self::NP), 'admin'), '7f7f7f7f-7f7f-4f7f-8f7f-7f7f7f7f7f7f', self::iv('2016-01-01'))]);
        $notAUnit = self::validate([self::claim(SlotKey::containment(self::u(self::NP), 'admin'), 'Bagmati', self::iv('2016-01-01'))]);

        $this->assertTrue($ok->isValid());
        $this->assertContains((new SlotDependency(SlotKey::existence(self::u(self::P)), self::iv('2016-01-01')))->describe(), $ok->dependencies->describe(), 'the existence read is recorded');
        $this->assertSame(['PARENT_EXISTS'], self::rules($tooEarly), 'P exists only from 2010');
        $this->assertSame(['PARENT_EXISTS'], self::rules($unknown));
        $this->assertSame(['PARENT_NOT_A_UNIT'], self::rules($notAUnit), 'a parent is an identity, never a name');
    }

    public function test_a_parent_ending_mid_interval_violates_and_one_created_in_this_revision_satisfies(): void
    {
        $ending = self::atlas([self::pub(self::claim(SlotKey::existence(self::u(self::P)), null, self::iv('2018-01-01')), 3)]);
        $this->assertSame(['PARENT_EXISTS'], self::rules(self::validate([self::claim(SlotKey::containment(self::u(self::NP), 'admin'), self::P, self::iv('2016-01-01'))], $ending)));

        $newParent = '5a5a5a5a-5a5a-4a5a-8a5a-5a5a5a5a5a5a';
        $result = self::validate([
            self::claim(SlotKey::existence(self::u($newParent)), 'exists', self::iv('2016-01-01')),
            self::claim(SlotKey::containment(self::u(self::NP), 'admin'), $newParent, self::iv('2016-01-01')),
        ]);
        $this->assertTrue($result->isValid(), 'the parent is created in the same revision');
    }

    public function test_grouping_needs_the_entrys_admission_over_its_interval(): void
    {
        $grouping = fn (string $from) => self::claim(SlotKey::grouping(self::u(self::NP), 'm49'), 'asia', self::iv($from));
        $admission = self::claim(SlotKey::admission(self::u(self::NP)), 'admitted', self::iv('2000-01-01'));

        $this->assertSame(['ADMISSION_BEFORE_GROUPING'], self::rules(self::validate([$grouping('2016-01-01')])));
        $this->assertTrue(self::validate([$admission, $grouping('2016-01-01')])->isValid(), 'admitted in the same revision');
        $published = self::atlas([self::pub($admission, 3)]);
        $result = self::validate([$grouping('2016-01-01')], $published);
        $this->assertTrue($result->isValid());
        $this->assertContains((new SlotDependency(SlotKey::admission(self::u(self::NP)), self::iv('2016-01-01')))->describe(), $result->dependencies->describe());
        $this->assertSame(['ADMISSION_BEFORE_GROUPING'], self::rules(self::validate([$grouping('1999-01-01')], $published)), 'grouping before the admission');
    }

    public function test_a_later_relevant_publication_invalidates_and_an_unrelated_one_does_not(): void
    {
        $result = self::validate([self::claim(SlotKey::containment(self::u(self::NP), 'admin'), self::P, self::iv('2016-01-01'))]);
        $parentEnds = self::pub(self::claim(SlotKey::existence(self::u(self::P)), null, self::iv('2020-01-01')), 7);
        $unrelated = self::pub(self::claim(SlotKey::sourceName(self::u(self::P), 's', 'en', 'official'), 'Bagmati', self::iv('2016-01-01')), 8);

        $this->assertSame($parentEnds, $result->dependencies->firstInvalidating([$unrelated, $parentEnds]));
        $this->assertNull($result->dependencies->firstInvalidating([$unrelated]));
    }

    public function test_the_validator_accepts_only_the_recording_reader(): void
    {
        $types = array_map(static fn (\ReflectionParameter $p): string => (string) $p->getType(), (new \ReflectionMethod(RevisionValidator::class, 'validate'))->getParameters());

        $this->assertContains(DependencyRecordingReader::class, $types);
        $this->assertNotContains(PublishedAtlas::class, $types, 'no unrecorded route to the atlas');
        $this->assertNull((new \ReflectionClass(RevisionValidator::class))->getConstructor(), 'it holds no atlas of its own');
    }
}
