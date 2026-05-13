<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Membership\Infrastructure\Services\CanonicalGeoSerializer;
use PHPUnit\Framework\TestCase;

final class CanonicalGeoSerializerTest extends TestCase
{
    private CanonicalGeoSerializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serializer = new CanonicalGeoSerializer();
    }

    // --- Serialization ---

    public function test_serializes_full_identity_region_country_geo(): void
    {
        $result = $this->serializer->serialize(7, 'asia', 'IN');

        $this->assertSame('region:asia.country:IN.geo:7', $result);
    }

    public function test_serializes_without_region(): void
    {
        $result = $this->serializer->serialize(7, null, 'IN');

        $this->assertSame('country:IN.geo:7', $result);
    }

    public function test_serializes_without_country(): void
    {
        $result = $this->serializer->serialize(7, 'asia', null);

        $this->assertSame('region:asia.geo:7', $result);
    }

    public function test_serializes_minimal_geo_only(): void
    {
        $result = $this->serializer->serialize(7, null, null);

        $this->assertSame('geo:7', $result);
    }

    public function test_serialize_returns_null_when_no_geo_unit(): void
    {
        $result = $this->serializer->serialize(null, 'asia', 'IN');

        $this->assertNull($result);
    }

    public function test_serialize_is_deterministic(): void
    {
        $first = $this->serializer->serialize(7, 'asia', 'IN');
        $second = $this->serializer->serialize(7, 'asia', 'IN');

        $this->assertSame($first, $second);
        $this->assertSame('region:asia.country:IN.geo:7', $first);
    }

    // --- Deserialization ---

    public function test_deserializes_full_string(): void
    {
        $result = $this->serializer->deserialize('region:asia.country:IN.geo:7');

        $this->assertNotNull($result);
        $this->assertSame(7, $result->geoUnitId);
        $this->assertSame('asia', $result->regionCode);
        $this->assertSame('IN', $result->countryCode);
    }

    public function test_deserializes_partial_string_without_region(): void
    {
        $result = $this->serializer->deserialize('country:IN.geo:7');

        $this->assertNotNull($result);
        $this->assertSame(7, $result->geoUnitId);
        $this->assertNull($result->regionCode);
        $this->assertSame('IN', $result->countryCode);
    }

    public function test_deserialize_returns_null_on_null_input(): void
    {
        $result = $this->serializer->deserialize(null);

        $this->assertNull($result);
    }

    public function test_deserialize_returns_null_on_empty_string(): void
    {
        $result = $this->serializer->deserialize('');

        $this->assertNull($result);
    }

    public function test_deserialize_returns_null_on_invalid_format(): void
    {
        $result = $this->serializer->deserialize('not-a-valid-format');

        $this->assertNull($result);
    }

    public function test_deserialize_returns_null_when_missing_geo(): void
    {
        $result = $this->serializer->deserialize('region:asia.country:IN');

        $this->assertNull($result);
    }

    public function test_deserialize_ignores_unknown_keys(): void
    {
        $result = $this->serializer->deserialize('foo:bar.geo:7');

        $this->assertNotNull($result);
        $this->assertSame(7, $result->geoUnitId);
        $this->assertNull($result->regionCode);
        $this->assertNull($result->countryCode);
    }

    // --- Round-trip ---

    public function test_serialize_deserialize_roundtrip(): void
    {
        $serialized = $this->serializer->serialize(42, 'europe', 'DE');
        $deserialized = $this->serializer->deserialize($serialized);

        $this->assertNotNull($deserialized);
        $this->assertSame(42, $deserialized->geoUnitId);
        $this->assertSame('europe', $deserialized->regionCode);
        $this->assertSame('DE', $deserialized->countryCode);
    }
}
