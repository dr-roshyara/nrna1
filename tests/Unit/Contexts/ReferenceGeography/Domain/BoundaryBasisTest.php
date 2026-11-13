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
 * G-2 finding F-2 (Principal, 2026-11-13; v3.11 §3 Boundary row "Territorial, or a cartographic source attributed as
 * such"): only an explicitly attributed cartographic source qualifies for the boundary exception of D-82_BASIS; a
 * generic "other" basis does not, and the cartographic basis qualifies for no other claim kind.
 */
final class BoundaryBasisTest extends TestCase
{
    private const UNIT = '3c3c3c3c-3c3c-4c3c-8c3c-3c3c3c3c3c3c';

    private static function rules(BasisKind $kind, SlotKey $slot, string $value = 'polygon-ref'): array
    {
        $published = [CompetentInstrument::competenceClaim(), new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000003001'), SlotKey::existence(ReferenceUnitId::fromString(self::UNIT)), ClaimValue::of('exists'), new ValidInterval(ValidDate::fromString('1950-01-01')), new Basis(BasisKind::Instrument, 'act'))];
        $p = 0;
        $atlas = new InMemoryPublishedAtlas(count($published), array_map(static function (Claim $c) use (&$p): PublishedClaim { $p++; return new PublishedClaim($c, BeforeImage::of(SlotHistory::for($c->slot), $c->interval, $p - 1), $p); }, $published));
        $claim = new Claim(ClaimId::fromString('00000000-0000-4000-8000-000000003002'), $slot, ClaimValue::of($value), new ValidInterval(ValidDate::fromString('2016-01-01')), new Basis($kind, 'survey-dept-sheet-72'));

        return array_map(static fn ($v): string => $v->rule, (new RevisionValidator())->validate([$claim], new DependencyRecordingReader($atlas), CompetentInstrument::byId(['act']))->violations);
    }

    public function test_a_boundary_on_an_attributed_cartographic_source_is_accepted(): void
    {
        $this->assertSame([], self::rules(BasisKind::AttributedCartographicSource, SlotKey::boundary(ReferenceUnitId::fromString(self::UNIT), 'survey-dept')));
    }

    public function test_a_boundary_on_a_generic_other_basis_is_refused(): void
    {
        $this->assertSame(['D-82_BASIS'], self::rules(BasisKind::Other, SlotKey::boundary(ReferenceUnitId::fromString(self::UNIT), 'survey-dept')));
    }

    public function test_a_cartographic_source_is_no_basis_for_any_other_territorial_claim(): void
    {
        // the fixture unit has no country, so D-83 also (rightly) fires; this test is about the basis
        $this->assertContains('D-82_BASIS', self::rules(BasisKind::AttributedCartographicSource, SlotKey::classification(ReferenceUnitId::fromString(self::UNIT), 'admin'), 'district'));
        $this->assertNotContains('D-82_BASIS', self::rules(BasisKind::Instrument, SlotKey::classification(ReferenceUnitId::fromString(self::UNIT), 'admin'), 'district'), 'control: the same claim on an instrument');
    }
}
