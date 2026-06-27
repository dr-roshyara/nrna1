<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Contexts\Geography\Application\Services\GeoReferenceParser;
use App\Contexts\Geography\Domain\ValueObjects\GeoReference;
use PHPUnit\Framework\TestCase;

final class GeoReferenceParserTest extends TestCase
{
    private GeoReferenceParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new GeoReferenceParser();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function parse_legacy_format_returns_correct_GeoReference(): void
    {
        $ref = $this->parser->parse('np.3.15.234');

        $this->assertInstanceOf(GeoReference::class, $ref);
        $this->assertSame('NP', $ref->getCountryCode());
        $this->assertTrue($ref->isLegacy());
        $this->assertSame([3, 15, 234], $ref->geoPath->toArray());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function parse_composite_format_returns_correct_GeoReference(): void
    {
        $ref = $this->parser->parse('region:europe.country:DE.geo:3.15');

        $this->assertInstanceOf(GeoReference::class, $ref);
        $this->assertSame('europe', $ref->region);
        $this->assertSame('DE', $ref->getCountryCode());
        $this->assertFalse($ref->isLegacy());
        $this->assertSame([3, 15], $ref->geoPath->toArray());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function parse_detects_format_by_colon_presence(): void
    {
        // Legacy: no colon
        $legacy = $this->parser->parse('in.2.3');
        $this->assertTrue($legacy->isLegacy());

        // Composite: has colon
        $composite = $this->parser->parse('region:asia.country:IN');
        $this->assertFalse($composite->isLegacy());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function parse_throws_on_empty_string(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GeoReference cannot be empty');

        $this->parser->parse('');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function parse_throws_on_malformed_segment(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        // Missing country code in legacy format
        $this->parser->parse('abc');
    }
}
