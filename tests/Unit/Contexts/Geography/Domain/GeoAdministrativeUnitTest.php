<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Geography\Domain;

use App\Contexts\Geography\Domain\Entities\GeoAdministrativeUnit;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeographyLevel;
use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Geography\Domain\ValueObjects\LocalizedName;
use App\Contexts\Geography\Domain\ValueObjects\GeographicCode;
use DomainException;
use PHPUnit\Framework\TestCase;

final class GeoAdministrativeUnitTest extends TestCase
{
    // ── Setup helpers ──

    private function makeId(int $i = 1): GeoUnitId
    {
        return GeoUnitId::fromInt($i);
    }

    private function makeCountry(string $code = 'NP'): CountryCode
    {
        return CountryCode::fromString($code);
    }

    private function makeName(string $en = 'Test Unit'): LocalizedName
    {
        return LocalizedName::english($en);
    }

    // ── GEO-INV-01: Cannot be own parent ──

    public function test_cannot_have_self_as_parent(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('cannot be its own parent');

        $id = $this->makeId(1);
        new GeoAdministrativeUnit(
            $id,
            $this->makeCountry(),
            GeographyLevel::fromInt(1),
            $id, // parent = self
            GeoPath::fromArray([1]),
            $this->makeName()
        );
    }

    // ── GEO-INV-02: Child level must be lower than parent ──

    public function test_child_level_must_be_higher_than_parent(): void
    {
        $parent = GeoAdministrativeUnit::createRoot(
            $this->makeId(1),
            $this->makeCountry(),
            $this->makeName('Parent')
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('must be higher than parent level');

        GeoAdministrativeUnit::createChild(
            $this->makeId(2),
            $this->makeCountry(),
            GeographyLevel::fromInt(1), // same level as parent
            $parent,
            $this->makeName('Child')
        );
    }

    // ── GEO-INV-03: Path must contain full ancestor chain ──

    public function test_root_unit_path_must_equal_id(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('path should be');

        new GeoAdministrativeUnit(
            $this->makeId(1),
            $this->makeCountry(),
            GeographyLevel::fromInt(1),
            null,
            GeoPath::fromArray([999]), // wrong path
            $this->makeName()
        );
    }

    public function test_child_path_must_end_with_child_id(): void
    {
        $parent = GeoAdministrativeUnit::createRoot(
            $this->makeId(1),
            $this->makeCountry(),
            $this->makeName('Parent')
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Path should end with unit ID');

        new GeoAdministrativeUnit(
            $this->makeId(2),
            $this->makeCountry(),
            GeographyLevel::fromInt(2),
            $parent->getId(),
            GeoPath::fromArray([1, 999]), // ends with 999, not 2
            $this->makeName()
        );
    }

    public function test_child_path_must_contain_parent_id(): void
    {
        $parent = GeoAdministrativeUnit::createRoot(
            $this->makeId(5),
            $this->makeCountry(),
            $this->makeName('Parent')
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Path should contain parent ID');

        new GeoAdministrativeUnit(
            $this->makeId(10),
            $this->makeCountry(),
            GeographyLevel::fromInt(2),
            $parent->getId(),
            GeoPath::fromArray([1, 10]), // parent 5 not in path
            $this->makeName()
        );
    }

    // ── GEO-INV-04: Country must match parent's country ──

    public function test_child_country_must_match_parent_country(): void
    {
        $parent = GeoAdministrativeUnit::createRoot(
            $this->makeId(1),
            $this->makeCountry('NP'),
            $this->makeName('Nepal')
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('must match parent country');

        GeoAdministrativeUnit::createChild(
            $this->makeId(2),
            $this->makeCountry('IN'), // different country
            GeographyLevel::fromInt(2),
            $parent,
            $this->makeName('Child')
        );
    }

    // ── GEO-INV-05: Temporal range consistency ──

    public function test_valid_from_cannot_be_after_valid_to(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('valid_from');

        new GeoAdministrativeUnit(
            $this->makeId(1),
            $this->makeCountry(),
            GeographyLevel::fromInt(1),
            null,
            GeoPath::fromArray([1]),
            $this->makeName(),
            null,
            1,
            new \DateTimeImmutable('2026-06-01'),
            new \DateTimeImmutable('2026-01-01')  // before valid_from
        );
    }

    // ── GEO-INV-06: Version starts at 1 ──

    public function test_version_defaults_to_one(): void
    {
        $unit = GeoAdministrativeUnit::createRoot(
            $this->makeId(1),
            $this->makeCountry(),
            $this->makeName()
        );

        $this->assertSame(1, $unit->getVersion());
    }

    // ── createRoot factory ──

    public function test_create_root_unit(): void
    {
        $unit = GeoAdministrativeUnit::createRoot(
            $this->makeId(42),
            $this->makeCountry('NP'),
            $this->makeName('Nepal'),
            GeographicCode::fromString('NP')
        );

        $this->assertSame(42, $unit->getId()->toInt());
        $this->assertSame('NP', $unit->getCountryCode()->toString());
        $this->assertSame(1, $unit->getLevel()->toInt());
        $this->assertNull($unit->getParentId());
        $this->assertTrue($unit->isRoot());
        $this->assertSame('42', $unit->getPath()->toString());
    }

    // ── createChild factory ──

    public function test_create_child_unit(): void
    {
        $parent = GeoAdministrativeUnit::createRoot(
            $this->makeId(1),
            $this->makeCountry('NP'),
            $this->makeName('Nepal')
        );

        $child = GeoAdministrativeUnit::createChild(
            $this->makeId(2),
            $this->makeCountry('NP'),
            GeographyLevel::fromInt(2),
            $parent,
            $this->makeName('Province 1'),
            GeographicCode::fromString('NP-P1')
        );

        $this->assertSame(2, $child->getId()->toInt());
        $this->assertSame(1, $child->getParentId()->toInt());
        $this->assertSame(2, $child->getLevel()->toInt());
        $this->assertStringStartsWith('1.', $child->getPath()->toString());
        $this->assertStringEndsWith('.2', $child->getPath()->toString());
    }

    // ── Mutators ──

    public function test_update_name(): void
    {
        $unit = GeoAdministrativeUnit::createRoot(
            $this->makeId(1),
            $this->makeCountry(),
            $this->makeName('Old')
        );

        $unit->updateName($this->makeName('New'));
        $this->assertSame('New', $unit->getEnglishName());
    }

    public function test_update_path_validates_hierarchy(): void
    {
        $unit = GeoAdministrativeUnit::createRoot(
            $this->makeId(1),
            $this->makeCountry(),
            $this->makeName('Root')
        );

        $this->expectException(DomainException::class);
        $unit->updatePath(GeoPath::fromArray([2, 3])); // doesn't start with 1
    }

    public function test_change_parent_rejects_self(): void
    {
        $unit = GeoAdministrativeUnit::createRoot(
            $this->makeId(1),
            $this->makeCountry(),
            $this->makeName('Root')
        );

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('cannot be its own parent');

        $unit->changeParent($this->makeId(1));
    }

    // ── Version increment ──

    public function test_increment_version(): void
    {
        $unit = GeoAdministrativeUnit::createRoot(
            $this->makeId(1),
            $this->makeCountry(),
            $this->makeName()
        );

        $this->assertSame(1, $unit->getVersion());
        $unit->incrementVersion();
        $this->assertSame(2, $unit->getVersion());
    }

    // ─── getters ───

    public function test_getters_return_expected_values(): void
    {
        $code = GeographicCode::fromString('NP');
        $unit = GeoAdministrativeUnit::createRoot(
            $this->makeId(7),
            $this->makeCountry('NP'),
            $this->makeName('Test'),
            $code
        );

        $this->assertSame('Test', $unit->getEnglishName());
        $this->assertSame('NP', $unit->getOfficialCode()->toString());
        $this->assertSame('Test', $unit->getNameWithFallback(['en', 'np']));
        $this->assertSame(1, $unit->getDepth());
    }
}
