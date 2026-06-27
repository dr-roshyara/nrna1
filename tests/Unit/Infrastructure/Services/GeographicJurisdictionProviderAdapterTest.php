<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Services;

use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdiction;
use App\Contexts\Membership\Infrastructure\Services\GeographicJurisdictionProviderAdapter;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

final class GeographicJurisdictionProviderAdapterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_resolve_with_valid_id_returns_jurisdiction(): void
    {
        $model = Mockery::mock(GeoAdministrativeUnit::class);
        $model->shouldReceive('find')
            ->with(42)
            ->andReturn((object) [
                'id' => 42,
                'admin_level' => 2,
                'region_code' => 'asia',
                'country_code' => 'NP',
            ]);

        $adapter = new GeographicJurisdictionProviderAdapter($model);
        $result = $adapter->resolve(42);

        $this->assertInstanceOf(GeographicJurisdiction::class, $result);
        $this->assertSame(42, $result->geoUnitId);
        $this->assertSame(2, $result->adminLevel);
        $this->assertSame('asia', $result->regionCode);
        $this->assertSame('NP', $result->countryCode);
    }

    public function test_resolve_with_invalid_id_returns_null(): void
    {
        $model = Mockery::mock(GeoAdministrativeUnit::class);
        $model->shouldReceive('find')
            ->with(99999)
            ->andReturn(null);

        $adapter = new GeographicJurisdictionProviderAdapter($model);
        $result = $adapter->resolve(99999);

        $this->assertNull($result);
    }
}
