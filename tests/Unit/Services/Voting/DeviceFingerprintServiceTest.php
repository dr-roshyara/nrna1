<?php

namespace Tests\Unit\Services\Voting;

use Tests\TestCase;
use App\Services\Voting\DeviceFingerprintService;
use Illuminate\Http\Request;
use Mockery;

class DeviceFingerprintServiceTest extends TestCase
{
    /**
     * RED TEST 1: Device fingerprint generates a SHA-256 hash
     */
    public function test_generate_fingerprint_returns_sha256_hash()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('userAgent')->andReturn('Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
        $request->shouldReceive('header')->with('Accept-Language')->andReturn('en-US');
        $request->shouldReceive('header')->with('X-Timezone-Offset')->andReturn(null);
        $request->shouldReceive('header')->with('X-Screen-Resolution')->andReturn(null);

        $service = new DeviceFingerprintService($request);
        $hash = $service->generateFingerprint();

        // SHA-256 hashes are 64 hex characters
        $this->assertNotNull($hash);
        $this->assertEquals(64, strlen($hash));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $hash);
    }

    /**
     * RED TEST 2: Same device produces same fingerprint (deterministic)
     */
    public function test_same_device_produces_same_fingerprint()
    {
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
        $language = 'en-US';

        $request1 = Mockery::mock(Request::class);
        $request1->shouldReceive('userAgent')->andReturn($userAgent);
        $request1->shouldReceive('header')->with('Accept-Language')->andReturn($language);
        $request1->shouldReceive('header')->with('X-Timezone-Offset')->andReturn(null);
        $request1->shouldReceive('header')->with('X-Screen-Resolution')->andReturn(null);

        $request2 = Mockery::mock(Request::class);
        $request2->shouldReceive('userAgent')->andReturn($userAgent);
        $request2->shouldReceive('header')->with('Accept-Language')->andReturn($language);
        $request2->shouldReceive('header')->with('X-Timezone-Offset')->andReturn(null);
        $request2->shouldReceive('header')->with('X-Screen-Resolution')->andReturn(null);

        $service1 = new DeviceFingerprintService($request1);
        $service2 = new DeviceFingerprintService($request2);

        $hash1 = $service1->generateFingerprint();
        $hash2 = $service2->generateFingerprint();

        $this->assertEquals($hash1, $hash2);
    }

    /**
     * RED TEST 3: Different devices produce different fingerprints
     */
    public function test_different_devices_produce_different_fingerprints()
    {
        $request1 = Mockery::mock(Request::class);
        $request1->shouldReceive('userAgent')->andReturn('Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
        $request1->shouldReceive('header')->with('Accept-Language')->andReturn('en-US');
        $request1->shouldReceive('header')->with('X-Timezone-Offset')->andReturn(null);
        $request1->shouldReceive('header')->with('X-Screen-Resolution')->andReturn(null);

        $request2 = Mockery::mock(Request::class);
        $request2->shouldReceive('userAgent')->andReturn('Mozilla/5.0 (iPhone; CPU iPhone OS 15_0)');
        $request2->shouldReceive('header')->with('Accept-Language')->andReturn('en-US');
        $request2->shouldReceive('header')->with('X-Timezone-Offset')->andReturn(null);
        $request2->shouldReceive('header')->with('X-Screen-Resolution')->andReturn(null);

        $service1 = new DeviceFingerprintService($request1);
        $service2 = new DeviceFingerprintService($request2);

        $hash1 = $service1->generateFingerprint();
        $hash2 = $service2->generateFingerprint();

        $this->assertNotEquals($hash1, $hash2);
    }

    /**
     * RED TEST 4: Generate anonymized metadata returns array
     */
    public function test_generate_anonymized_metadata_returns_array()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('userAgent')->andReturn('Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
        $request->shouldReceive('header')->with('Accept-Language')->andReturn('en-US');
        $request->shouldReceive('header')->with('X-Timezone-Offset')->andReturn(null);
        $request->shouldReceive('header')->with('X-Screen-Resolution')->andReturn(null);

        $service = new DeviceFingerprintService($request);
        $metadata = $service->generateAnonymizedMetadata();

        $this->assertIsArray($metadata);
        $this->assertArrayHasKey('browser', $metadata);
        $this->assertArrayHasKey('platform', $metadata);
        $this->assertArrayHasKey('is_mobile', $metadata);
        $this->assertArrayHasKey('fingerprint_time', $metadata);
    }

    /**
     * RED TEST 5: Metadata does NOT contain PII
     */
    public function test_anonymized_metadata_contains_no_pii()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('userAgent')->andReturn('Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
        $request->shouldReceive('header')->with('Accept-Language')->andReturn('en-US');
        $request->shouldReceive('header')->with('X-Timezone-Offset')->andReturn(null);
        $request->shouldReceive('header')->with('X-Screen-Resolution')->andReturn(null);

        $service = new DeviceFingerprintService($request);
        $metadata = $service->generateAnonymizedMetadata();

        $metadataString = json_encode($metadata);

        // Should NOT contain common PII
        $this->assertStringNotContainsString('email', strtolower($metadataString));
        $this->assertStringNotContainsString('phone', strtolower($metadataString));
        $this->assertStringNotContainsString('name', strtolower($metadataString));
        $this->assertStringNotContainsString('address', strtolower($metadataString));
    }

    /**
     * RED TEST 6: Returns null on fingerprint generation error
     */
    public function test_generate_fingerprint_returns_null_on_error()
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('userAgent')->andThrow(new \Exception('Agent error'));

        $service = new DeviceFingerprintService($request);
        $hash = $service->generateFingerprint();

        $this->assertNull($hash);
    }
}
