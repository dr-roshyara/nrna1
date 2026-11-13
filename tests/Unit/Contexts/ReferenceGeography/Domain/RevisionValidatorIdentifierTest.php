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
use App\Contexts\ReferenceGeography\Domain\ValidDate;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use PHPUnit\Framework\TestCase;
use Tests\Support\ReferenceGeography\InMemoryPublishedAtlas;

/**
 * PBDIGIT-346 G-2c, cycle 4 (v3.11 §2 I-1, §5.2 "I-1 (u takes X in N): holdersOf(N, X, V); family (IDENTIFIER, *, N)
 * = X over V"): a value identifies at most one LIVE subject at a time, across the published atlas and this revision.
 */
final class RevisionValidatorIdentifierTest extends TestCase
{
    private const NEW = '3f2b8c1d-6a4e-4b7f-9c2d-1e5a7b9c0d3f';
    private const OLD = '8a7b6c5d-4e3f-4a1b-9c8d-7e6f5a4b3c2d';
    private const THIRD = '6c5d4e3f-2a1b-4c9d-8e7f-6a5b4c3d2e1f';
    private const N = 'iso3166-1-alpha2';

    private static int $n = 500;

    private static function iv(string $from, ?string $to = null): ValidInterval
    {
        return new ValidInterval(ValidDate::fromString($from), $to === null ? null : ValidDate::fromString($to));
    }

    private static function claim(SlotKey $slot, ?string $value, ValidInterval $interval, BasisKind $basis = BasisKind::Other): Claim
    {
        self::$n++;

        return new Claim(ClaimId::fromString(sprintf('00000000-0000-4000-8000-%012d', self::$n)), $slot, $value === null ? ClaimValue::none() : ClaimValue::of($value), $interval, new Basis($basis, 'iso-newsletter'));
    }

    private static function pub(Claim $c, int $position): PublishedClaim
    {
        return new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, $position - 1), $position);
    }

    private static function id(string $unit, string $value, string $from, ?string $to = null, string $namespace = self::N): Claim
    {
        return self::claim(SlotKey::identifier(ReferenceUnitId::fromString($unit), $namespace), $value, self::iv($from, $to));
    }

    private static function exists(string $unit, string $from, ?string $to = null): Claim
    {
        return self::claim(SlotKey::existence(ReferenceUnitId::fromString($unit)), 'exists', self::iv($from, $to), BasisKind::Instrument);   // I-4
    }

    /** OLD exists from 1974 and holds "CS" over [1974, 1993) — or as given. */
    private static function atlas(array $published): InMemoryPublishedAtlas
    {
        $position = 0;

        return new InMemoryPublishedAtlas(10, array_map(static function (Claim $c) use (&$position): PublishedClaim { return self::pub($c, ++$position); }, $published));
    }

    private static function rules(array $claims, InMemoryPublishedAtlas $atlas): array
    {
        return array_map(static fn ($v): string => $v->rule, (new RevisionValidator())->validate($claims, new DependencyRecordingReader($atlas))->violations);
    }

    public function test_taking_a_value_another_live_subject_holds_violates_i1(): void
    {
        $atlas = self::atlas([self::exists(self::OLD, '1974-01-01'), self::id(self::OLD, 'CS', '1974-01-01')]);

        $this->assertSame(['I-1'], self::rules([self::id(self::NEW, 'CS', '2003-07-23')], $atlas));
    }

    public function test_code_reuse_after_the_earlier_holders_tenure_is_allowed(): void
    {
        $atlas = self::atlas([self::exists(self::OLD, '1974-01-01'), self::id(self::OLD, 'CS', '1974-01-01', '1993-01-01')]);

        $this->assertSame([], self::rules([self::id(self::NEW, 'CS', '2003-07-23', '2006-09-26')], $atlas));
    }

    public function test_a_holder_that_no_longer_exists_is_not_live(): void
    {
        $atlas = self::atlas([self::exists(self::OLD, '1974-01-01', '1993-01-01'), self::id(self::OLD, 'CS', '1974-01-01')]);   // identifier never withdrawn

        $this->assertSame([], self::rules([self::id(self::NEW, 'CS', '2003-07-23')], $atlas));
    }

    public function test_the_holder_relinquishing_in_the_same_revision_frees_the_value(): void
    {
        $atlas = self::atlas([self::exists(self::OLD, '1974-01-01'), self::id(self::OLD, 'CS', '1974-01-01')]);
        $relinquish = self::claim(SlotKey::identifier(ReferenceUnitId::fromString(self::OLD), self::N), null, self::iv('2003-07-23'));

        $this->assertSame([], self::rules([$relinquish, self::id(self::NEW, 'CS', '2003-07-23')], $atlas));
    }

    public function test_two_subjects_taking_one_value_in_the_same_revision_violate_i1(): void
    {
        $atlas = self::atlas([self::exists(self::THIRD, '1990-01-01')]);

        $this->assertContains('I-1', self::rules([self::id(self::NEW, 'XK', '2008-01-01'), self::id(self::THIRD, 'XK', '2010-01-01')], $atlas));
    }

    public function test_another_namespace_is_no_conflict(): void
    {
        $atlas = self::atlas([self::exists(self::OLD, '1974-01-01'), self::id(self::OLD, 'CS', '1974-01-01')]);

        $this->assertSame([], self::rules([self::id(self::NEW, 'CS', '2003-07-23', null, 'fips')], $atlas));
    }

    public function test_a_later_new_holder_or_relinquishment_makes_the_validation_stale(): void
    {
        $atlas = self::atlas([self::exists(self::OLD, '1974-01-01'), self::id(self::OLD, 'XK', '1974-01-01', '2000-01-01')]);
        $result = (new RevisionValidator())->validate([self::id(self::NEW, 'XK', '2008-01-01')], new DependencyRecordingReader($atlas));

        $this->assertContains(FamilyDependency::of(ClaimKind::Identifier, ['namespace' => self::N], 'XK', self::iv('2008-01-01'))->describe(), $result->dependencies->describe());
        $gain = self::pub(self::id(self::THIRD, 'XK', '2010-01-01'), 11);
        $this->assertSame($gain, $result->dependencies->firstInvalidating([$gain]), 'someone else took XK after p₀');
    }
}
