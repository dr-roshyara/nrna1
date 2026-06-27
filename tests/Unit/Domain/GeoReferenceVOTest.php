<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use App\Contexts\Geography\Domain\ValueObjects\GeoReference;
use PHPUnit\Framework\TestCase;

final class GeoReferenceVOTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function fromString_parses_legacy_format(): void
    {
        $ref = GeoReference::fromString('np.3.15.234');

        $this->assertSame('NP', $ref->getCountryCode());
        $this->assertNull($ref->region);
        $this->assertSame([3, 15, 234], $ref->geoPath->toArray());
        $this->assertTrue($ref->isLegacy());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function fromString_parses_composite_format(): void
    {
        $ref = GeoReference::fromString('region:europe.country:DE.geo:3.15');

        $this->assertSame('europe', $ref->region);
        $this->assertSame('DE', $ref->getCountryCode());
        $this->assertSame([3, 15], $ref->geoPath->toArray());
        $this->assertFalse($ref->isLegacy());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function fromString_throws_on_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GeoReference cannot be empty');

        GeoReference::fromString('');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function fromString_throws_on_whitespace_only(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GeoReference cannot be empty');

        GeoReference::fromString('   ');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function toString_roundtrips_legacy(): void
    {
        $original = 'np.3.15.234';
        $ref = GeoReference::fromString($original);

        // Legacy format should roundtrip: np.3.15.234 → NP,geo=[3,15,234],legacy → np.3.15.234
        $this->assertSame($original, $ref->toString());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function toString_roundtrips_composite(): void
    {
        $original = 'region:europe.country:DE.geo:3.15';
        $ref = GeoReference::fromString($original);

        $this->assertSame($original, $ref->toString());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function isLegacy_true_for_legacy_format(): void
    {
        $ref = GeoReference::fromString('in.2.3.4');

        $this->assertTrue($ref->isLegacy());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function isLegacy_false_for_composite(): void
    {
        $ref = GeoReference::fromString('region:asia.country:NP');

        $this->assertFalse($ref->isLegacy());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function fromSelections_builds_composite(): void
    {
        $selections = [
            'region' => 'europe',
            'country' => 'de',
            'geo' => [3, 15],
        ];

        $ref = GeoReference::fromSelections($selections);

        $this->assertSame('europe', $ref->region);
        $this->assertSame('DE', $ref->getCountryCode());
        $this->assertSame([3, 15], $ref->geoPath->toArray());
        $this->assertFalse($ref->isLegacy());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function getCountryCode_returns_country_for_composite(): void
    {
        $ref = GeoReference::fromString('region:asia.country:NP.geo:3');

        $this->assertSame('NP', $ref->getCountryCode());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function getCountryCode_returns_country_for_legacy(): void
    {
        $ref = GeoReference::fromString('np.3');

        $this->assertSame('NP', $ref->getCountryCode());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function geoPath_is_GeoPath_instance(): void
    {
        $ref = GeoReference::fromString('np.3.15');

        $this->assertInstanceOf(GeoPath::class, $ref->geoPath);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function composite_with_no_geo_units_is_valid(): void
    {
        $ref = GeoReference::fromString('region:europe.country:DE');

        $this->assertSame('europe', $ref->region);
        $this->assertSame('DE', $ref->getCountryCode());
        $this->assertSame(0, $ref->geoPath->depth());
        $this->assertTrue($ref->geoPath->isEmpty());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function legacy_with_only_country_is_valid(): void
    {
        $ref = GeoReference::fromString('np');

        $this->assertSame('NP', $ref->getCountryCode());
        $this->assertNull($ref->region);
        $this->assertTrue($ref->geoPath->isEmpty());
        $this->assertTrue($ref->isLegacy());
    }
}
