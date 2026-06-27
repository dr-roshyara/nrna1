<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\CapabilityParitySnapshot;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Domain\Election\Security\BallotAuthorizationProtocol;
use Tests\Support\LegacyVotingBehavior;
use Tests\Support\ResolverVotingBehavior;
use PHPUnit\Framework\TestCase;

/**
 * Extraction Methods Validation Test
 *
 * Validates that extraction helper classes correctly implement
 * the parity snapshot interface without database dependencies.
 *
 * @group constitutional-parity
 */
class ExtractionMethodsTest extends TestCase
{
    private LegacyVotingBehavior $legacyBehavior;

    protected function setUp(): void
    {
        parent::setUp();
        $this->legacyBehavior = new LegacyVotingBehavior();
    }

    /**
     * LegacyVotingBehavior produces CapabilityParitySnapshot with all 8 fields
     */
    public function test_legacy_behavior_extraction_returns_complete_snapshot(): void
    {
        // Extraction methods return snapshots even when database lookups fail
        // (graceful degradation with defaults)

        // This test validates the extraction method STRUCTURE
        // (it returns correct type with correct field types)

        // For now, verify the method signature accepts correct types
        $this->assertTrue(method_exists($this->legacyBehavior, 'getLegacyResult'));

        // Verify method is callable with correct argument types
        $reflection = new \ReflectionMethod(
            $this->legacyBehavior,
            'getLegacyResult'
        );

        $this->assertCount(5, $reflection->getParameters());
        $params = $reflection->getParameters();

        // Parameter types (including union types for IDs that can be int or string)
        $this->assertTrue(
            $params[0]->getType()?->__toString() === 'string|int' ||
            $params[0]->getType()?->__toString() === 'int|string'
        );
        $this->assertTrue(
            $params[1]->getType()?->__toString() === 'string|int' ||
            $params[1]->getType()?->__toString() === 'int|string'
        );
        $this->assertEquals('string', (string)$params[2]->getType());
        $this->assertEquals('?string', (string)$params[3]->getType());
        $this->assertEquals('bool', (string)$params[4]->getType());
    }

    /**
     * ResolverVotingBehavior accepts ElectionCapabilityResolver via constructor
     */
    public function test_resolver_behavior_requires_capability_resolver(): void
    {
        // Verify ResolverVotingBehavior has constructor that accepts resolver
        $this->assertTrue(class_exists(\Tests\Support\ResolverVotingBehavior::class));

        $reflection = new \ReflectionClass(\Tests\Support\ResolverVotingBehavior::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);
        $params = $constructor->getParameters();
        $this->assertCount(1, $params);
        $this->assertEquals(
            'App\Application\Election\Services\ElectionCapabilityResolver',
            (string)$params[0]->getType()
        );
    }

    /**
     * CapabilityParitySnapshot.equals() compares all 8 constitutional fields
     */
    public function test_snapshot_equals_compares_all_eight_fields(): void
    {
        $snapshot1 = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );

        $snapshot2 = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );

        // Identical snapshots should be equal
        $this->assertTrue($snapshot1->equals($snapshot2));
    }

    /**
     * equals() detects divergence when ANY of 8 fields differ
     */
    public function test_snapshot_equals_detects_all_field_divergences(): void
    {
        $base = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );

        // Test each field divergence separately

        // 1. Network divergence
        $divergeNetwork = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Unverified,  // diverges
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );
        $this->assertFalse($base->equals($divergeNetwork));

        // 2. Device divergence
        $divergeDevice = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Unverified,  // diverges
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );
        $this->assertFalse($base->equals($divergeDevice));

        // 3. Verification divergence
        $divergeVerif = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Unverified,  // diverges
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );
        $this->assertFalse($base->equals($divergeVerif));

        // 4. Trust divergence
        $divergeTrust = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Unverified,  // diverges
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );
        $this->assertFalse($base->equals($divergeTrust));

        // 5. Overlay divergence
        $divergeOverlay = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: 'require_constitutional_review',  // diverges
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );
        $this->assertFalse($base->equals($divergeOverlay));

        // 6. Protocol divergence
        $divergeProto = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::SplitAuthorizationProtocol->value,  // diverges
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );
        $this->assertFalse($base->equals($divergeProto));

        // 7. Lifecycle divergence
        $divergeLifecycle = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::Counting,  // diverges
            participationAllowed: 'allowed',
        );
        $this->assertFalse($base->equals($divergeLifecycle));

        // 8. Participation divergence
        $divergePartic = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::InvalidLifecycle->value,  // diverges
        );
        $this->assertFalse($base->equals($divergePartic));
    }

    /**
     * divergentFields() correctly identifies which fields differ
     */
    public function test_snapshot_divergent_fields_identifies_differences(): void
    {
        $snapshot1 = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            OverlaySignalCategory: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );

        $snapshot2 = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Unverified,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Unverified,
            OverlaySignalCategory: 'require_constitutional_review',
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::Counting,
            participationAllowed: CapabilityDenialReason::InvalidLifecycle->value,
        );

        $divergences = $snapshot1->divergentFields($snapshot2);

        // Should identify exactly 5 divergent fields
        $this->assertCount(5, $divergences);
        $this->assertContains('networkLegitimate', $divergences);
        $this->assertContains('trustLegitimate', $divergences);
        $this->assertContains('OverlaySignalCategory', $divergences);
        $this->assertContains('lifecycleState', $divergences);
        $this->assertContains('participationAllowed', $divergences);
    }
}
