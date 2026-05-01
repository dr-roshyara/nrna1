<?php

namespace Tests\Unit\Application\Locale;

use App\Application\Locale\DetectLocaleUseCase;
use App\Domain\Locale\ValueObjects\Locale;
use App\Services\GeoLocation\Contracts\GeoIpProvider;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;

class MockRequest {
    public string $ip;
    public HeaderBag $headers;

    public function __construct(string $ip, HeaderBag $headers) {
        $this->ip = $ip;
        $this->headers = $headers;
    }

    public function ip() {
        return $this->ip;
    }

    public function header($key, $default = null) {
        return $this->headers->get($key, $default);
    }
}

class DetectLocaleUseCaseTest extends TestCase
{
    private DetectLocaleUseCase $useCase;
    private GeoIpProvider $mockGeoProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockGeoProvider = $this->createMock(GeoIpProvider::class);
        $this->useCase = new DetectLocaleUseCase($this->mockGeoProvider);
    }

    /** @test */
    public function org_language_overrides_geo_detected_language()
    {
        // Scenario: NRNA user in Germany
        // Organization: np (Nepali)
        // Geo-detected: de (Germany)
        // Expected result: np (org wins)

        $request = $this->createMockRequest('8.8.8.8', 'Europe/Berlin', 'de');
        $this->mockGeoProvider->expects($this->any())
            ->method('getCountryCode')
            ->with('8.8.8.8')
            ->willReturn('DE');

        $locale = $this->useCase->execute($request, 'np');

        $this->assertEquals('np', $locale->value());
    }

    /** @test */
    public function geo_detected_language_used_when_no_org_language()
    {
        // Scenario: User without organization, IP from Germany
        // Organization: none
        // Geo-detected: de (Germany)
        // Expected result: de (geo wins)

        $request = $this->createMockRequest('8.8.8.8', 'Europe/Berlin', 'de');
        $this->mockGeoProvider->expects($this->any())
            ->method('getCountryCode')
            ->with('8.8.8.8')
            ->willReturn('DE');

        $locale = $this->useCase->execute($request, null);

        $this->assertEquals('de', $locale->value());
    }

    /** @test */
    public function unsupported_language_falls_back_to_english()
    {
        // Scenario: User without organization, IP from Portugal (unsupported)
        // Organization: none
        // Geo-detected: pt (Portugal - unsupported)
        // Expected result: en (fallback)

        $request = $this->createMockRequest('195.23.100.0', 'Europe/Lisbon', null);
        $this->mockGeoProvider->expects($this->any())
            ->method('getCountryCode')
            ->with('195.23.100.0')
            ->willReturn('PT');

        $locale = $this->useCase->execute($request, null);

        $this->assertEquals('en', $locale->value());
    }

    /** @test */
    public function org_language_overrides_unsupported_geo_detected()
    {
        // Scenario: NRNA user in Portugal
        // Organization: np (Nepali)
        // Geo-detected: pt (Portugal - unsupported)
        // Expected result: np (org wins)

        $request = $this->createMockRequest('195.23.100.0', 'Europe/Lisbon', null);
        $this->mockGeoProvider->expects($this->any())
            ->method('getCountryCode')
            ->with('195.23.100.0')
            ->willReturn('PT');

        $locale = $this->useCase->execute($request, 'np');

        $this->assertEquals('np', $locale->value());
    }

    /** @test */
    public function browser_accept_language_header_used_for_private_ip()
    {
        // Scenario: Local development, private IP
        // Organization: none
        // Geo-detected: none (private IP 127.0.0.1)
        // Browser Accept-Language: de
        // Expected result: de (browser fallback)

        $request = $this->createMockRequest('127.0.0.1', 'UTC', 'de');
        $request->headers->set('Accept-Language', 'de,de-DE;q=0.9,en;q=0.8');

        $this->mockGeoProvider->expects($this->any())
            ->method('getCountryCode')
            ->with('127.0.0.1')
            ->willReturn(null);

        $locale = $this->useCase->execute($request, null);

        $this->assertEquals('de', $locale->value());
    }

    /** @test */
    public function unsupported_browser_language_falls_back_to_english()
    {
        // Scenario: Local development, private IP
        // Organization: none
        // Geo-detected: none (private IP)
        // Browser Accept-Language: pt (unsupported)
        // Expected result: en (fallback)

        $request = $this->createMockRequest('127.0.0.1', 'UTC', null);
        $request->headers->set('Accept-Language', 'pt,pt-PT;q=0.9,en;q=0.8');

        $this->mockGeoProvider->expects($this->any())
            ->method('getCountryCode')
            ->with('127.0.0.1')
            ->willReturn(null);

        $locale = $this->useCase->execute($request, null);

        $this->assertEquals('en', $locale->value());
    }

    /** @test */
    public function nepali_org_language_is_respected()
    {
        // Scenario: NRNA user anywhere
        // Organization: np (Nepali)
        // Geo-detected: en (USA)
        // Expected result: np (org wins)

        $request = $this->createMockRequest('8.8.4.4', 'America/New_York', 'en');
        $this->mockGeoProvider->expects($this->any())
            ->method('getCountryCode')
            ->with('8.8.4.4')
            ->willReturn('US');

        $locale = $this->useCase->execute($request, 'np');

        $this->assertEquals('np', $locale->value());
    }

    /** @test */
    public function german_speaking_countries_map_to_de()
    {
        // Austria
        $request = $this->createMockRequest('1.2.3.4', 'Europe/Vienna', null);
        $this->mockGeoProvider->expects($this->any())
            ->method('getCountryCode')
            ->willReturn('AT');

        $locale = $this->useCase->execute($request, null);
        $this->assertEquals('de', $locale->value());
    }

    /** @test */
    public function nepali_country_maps_to_np()
    {
        $request = $this->createMockRequest('103.20.30.40', 'Asia/Kathmandu', null);
        $this->mockGeoProvider->expects($this->any())
            ->method('getCountryCode')
            ->with('103.20.30.40')
            ->willReturn('NP');

        $locale = $this->useCase->execute($request, null);

        $this->assertEquals('np', $locale->value());
    }

    private function createMockRequest(string $ip, string $timezone, ?string $acceptLanguage = null): MockRequest
    {
        $headerBag = new HeaderBag();
        if ($acceptLanguage) {
            $headerBag->set('Accept-Language', $acceptLanguage);
        }

        return new MockRequest($ip, $headerBag);
    }
}
