<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\Simplified\ElectionConstitutionHasher;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSchema;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSnapshot;
use App\Domain\Election\Security\Simplified\ElectionConstitutionValidator;
use PHPUnit\Framework\TestCase;

/**
 * D.2.5 Constitutional Articles Snapshot Test
 *
 * Verifies that:
 * 1. Constitutional schema defines valid configurations
 * 2. Constitutional snapshot is immutable projection
 * 3. Constitutional hasher produces deterministic integrity hashes
 * 4. Constitutional validator enforces business rules
 */
class D2_5ConstitutionalArticlesSnapshotTest extends TestCase
{
    /**
     * Test ElectionConstitutionSchema defines valid network binding strategies
     */
    public function test_schema_defines_valid_network_strategies(): void
    {
        $strategies = ElectionConstitutionSchema::NETWORK_STRATEGIES;
        $this->assertContains('none', $strategies);
        $this->assertContains('ip_count', $strategies);
        $this->assertContains('ip_strict', $strategies);
    }

    /**
     * Test ElectionConstitutionSchema defines valid device binding strategies
     */
    public function test_schema_defines_valid_device_strategies(): void
    {
        $strategies = ElectionConstitutionSchema::DEVICE_STRATEGIES;
        $this->assertContains('none', $strategies);
        $this->assertContains('fingerprint_required', $strategies);
    }

    /**
     * Test ElectionConstitutionSchema defines valid ballot protocols
     */
    public function test_schema_defines_valid_ballot_protocols(): void
    {
        $protocols = ElectionConstitutionSchema::BALLOT_PROTOCOLS;
        $this->assertContains('single_code', $protocols);
        $this->assertContains('dual_code', $protocols);
    }

    /**
     * Test ElectionConstitutionSnapshot is readonly
     */
    public function test_constitutional_snapshot_is_readonly(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $this->assertEquals('ip_count', $snapshot->networkBindingStrategy);
        $this->assertEquals(6, $snapshot->maxVotesPerIp);

        // Verify immutability
        try {
            $snapshot->networkBindingStrategy = 'ip_strict';
            $this->fail('ElectionConstitutionSnapshot should be readonly');
        } catch (\Error $e) {
            $this->assertStringContainsString('readonly', $e->getMessage());
        }
    }

    /**
     * Test ElectionConstitutionHasher produces deterministic hash
     */
    public function test_hasher_produces_deterministic_hash(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $hash1 = ElectionConstitutionHasher::hash($snapshot);
        $hash2 = ElectionConstitutionHasher::hash($snapshot);

        $this->assertEquals($hash1, $hash2);
        $this->assertIsString($hash1);
        $this->assertEquals(64, strlen($hash1)); // SHA-256
    }

    /**
     * Test ElectionConstitutionHasher produces different hashes for different snapshots
     */
    public function test_hasher_produces_different_hashes_for_different_snapshots(): void
    {
        $snapshot1 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $snapshot2 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_strict',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $hash1 = ElectionConstitutionHasher::hash($snapshot1);
        $hash2 = ElectionConstitutionHasher::hash($snapshot2);

        $this->assertNotEquals($hash1, $hash2);
    }

    /**
     * Test ElectionConstitutionValidator allows valid snapshots
     */
    public function test_validator_allows_valid_snapshot(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        // Should not throw
        ElectionConstitutionValidator::validate($snapshot);
        $this->assertTrue(true);
    }

    /**
     * Test ElectionConstitutionValidator rejects max_votes_per_ip < 1
     */
    public function test_validator_rejects_max_votes_per_ip_below_1(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 0,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $this->expectException(\LogicException::class);
        ElectionConstitutionValidator::validate($snapshot);
    }

    /**
     * Test ElectionConstitutionValidator rejects max_votes_per_ip > 100
     */
    public function test_validator_rejects_max_votes_per_ip_above_100(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 101,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $this->expectException(\LogicException::class);
        ElectionConstitutionValidator::validate($snapshot);
    }

    /**
     * Test ElectionConstitutionValidator rejects invalid network binding strategy
     */
    public function test_validator_rejects_invalid_network_strategy(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'invalid_strategy',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $this->expectException(\LogicException::class);
        ElectionConstitutionValidator::validate($snapshot);
    }

    /**
     * Test ElectionConstitutionValidator rejects invalid device binding strategy
     */
    public function test_validator_rejects_invalid_device_strategy(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'invalid_strategy',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $this->expectException(\LogicException::class);
        ElectionConstitutionValidator::validate($snapshot);
    }

    /**
     * Test ElectionConstitutionValidator rejects invalid ballot authorization protocol
     */
    public function test_validator_rejects_invalid_ballot_protocol(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'invalid_protocol',
            verificationRequired: false,
        );

        $this->expectException(\LogicException::class);
        ElectionConstitutionValidator::validate($snapshot);
    }

    /**
     * Test ElectionConstitutionValidator accepts verification required = true
     */
    public function test_validator_allows_verification_required_true(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        ElectionConstitutionValidator::validate($snapshot);
        $this->assertTrue(true);
    }

    /**
     * Test ElectionConstitutionValidator with all boundary values
     */
    public function test_validator_accepts_all_boundary_values(): void
    {
        // Min votes per IP
        $minSnapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 1,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );
        ElectionConstitutionValidator::validate($minSnapshot);

        // Max votes per IP
        $maxSnapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 100,
            deviceBindingStrategy: 'none',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );
        ElectionConstitutionValidator::validate($maxSnapshot);

        $this->assertTrue(true);
    }
}
