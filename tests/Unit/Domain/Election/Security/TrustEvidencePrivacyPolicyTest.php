<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\TrustEvidencePrivacyPolicy;
use PHPUnit\Framework\TestCase;

class TrustEvidencePrivacyPolicyTest extends TestCase
{
    public function test_hash_ip_returns_hash_not_raw(): void
    {
        $policy = new TrustEvidencePrivacyPolicy();
        $rawIp = '192.168.1.1';
        $salt = 'election_123';

        $hash = $policy->hashIp($rawIp, $salt);

        $this->assertNotEquals($rawIp, $hash);
        $this->assertIsString($hash);
    }

    public function test_hash_ip_is_deterministic(): void
    {
        $policy = new TrustEvidencePrivacyPolicy();
        $rawIp = '192.168.1.1';
        $salt = 'election_123';

        $hash1 = $policy->hashIp($rawIp, $salt);
        $hash2 = $policy->hashIp($rawIp, $salt);

        $this->assertEquals($hash1, $hash2);
    }

    public function test_hash_fingerprint_returns_hash_not_raw(): void
    {
        $policy = new TrustEvidencePrivacyPolicy();
        $fp = 'canvas_fingerprint_value_here';
        $salt = 'salt_value';

        $hash = $policy->hashFingerprint($fp, $salt);

        $this->assertNotEquals($fp, $hash);
        $this->assertIsString($hash);
    }

    public function test_can_retain_raw_ip_defaults_false(): void
    {
        $policy = new TrustEvidencePrivacyPolicy();
        $this->assertFalse($policy->canRetainRawIp(null));
    }
}
