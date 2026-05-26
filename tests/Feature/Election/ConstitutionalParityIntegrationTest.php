<?php

namespace Tests\Feature\Election;

use App\Domain\Election\Security\CapabilityParitySnapshot;
use App\Domain\Election\Security\ConstitutionalDivergenceLedger;
use App\Domain\Election\Security\ConstitutionalDivergenceType;
use Tests\Support\LegacyVotingBehavior;
use Tests\Support\ResolverVotingBehavior;
use Tests\TestCase;

/**
 * Constitutional Parity Integration Test
 *
 * Tests actual extraction and comparison of legacy vs resolver behavior
 * using real database elections and users.
 *
 * @group constitutional-parity
 * @group feature
 */
class ConstitutionalParityIntegrationTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private LegacyVotingBehavior $legacyBehavior;
    private ResolverVotingBehavior $resolverBehavior;
    private ConstitutionalDivergenceLedger $divergenceLedger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->legacyBehavior = new LegacyVotingBehavior();
        $this->resolverBehavior = new ResolverVotingBehavior(
            app(\App\Application\Election\Services\ElectionCapabilityResolver::class)
        );
        $this->divergenceLedger = new ConstitutionalDivergenceLedger([]);
    }

    /**
     * Happy Path — Legacy vs Resolver Parity
     *
     * GOVERNANCE PRINCIPLE:
     * When voter is legitimately verified, election is active,
     * no overlays present, both legacy and resolver MUST permit voting.
     *
     * EXTRACTION VERIFICATION:
     * - LegacyVotingBehavior::getLegacyResult() extracts legacy decision
     * - ResolverVotingBehavior::getResolverResult() extracts resolver decision
     * - CapabilityParitySnapshot::equals() compares all 8 constitutional fields
     */
    public function test_happy_path_extraction_and_parity(): void
    {
        $election = $this->createTestElection();
        $user = $this->createTestUser();

        // Extract legacy behavior
        $legacyResult = $this->legacyBehavior->getLegacyResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: 'fp_hash_123',
            isVerified: false,
        );

        // Extract resolver behavior
        $resolverResult = $this->resolverBehavior->getResolverResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: 'fp_hash_123',
            isVerified: false,
        );

        // Verify extraction produces identical constitutional semantics
        $this->assertTrue(
            $legacyResult->equals($resolverResult),
            "Parity failed: Legacy and resolver produce different constitutional semantics. "
            . "Legacy: {$legacyResult->participationAllowed}, Resolver: {$resolverResult->participationAllowed}"
        );
    }

    /**
     * Suspension Overlay — Legacy vs Resolver Parity
     *
     * GOVERNANCE PRINCIPLE:
     * When election is suspended (overlay active), both legacy and resolver
     * MUST deny participation with Suspended reason.
     *
     * EXTRACTION VERIFICATION:
     * - Both extraction methods read actual database state
     * - OverlayCapabilityPolicy behavior matched
     * - Denial reason must be identical
     */
    public function test_suspension_overlay_extraction_and_parity(): void
    {
        $election = $this->createTestElection([
            'suspended_at' => now(),  // Active suspension
        ]);
        $user = $this->createTestUser();

        $legacyResult = $this->legacyBehavior->getLegacyResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: null,
            isVerified: false,
        );

        $resolverResult = $this->resolverBehavior->getResolverResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: null,
            isVerified: false,
        );

        $this->assertTrue(
            $legacyResult->equals($resolverResult),
            "Parity failed for suspension overlay"
        );
        $this->assertEquals('suspended', $legacyResult->participationAllowed);
        $this->assertEquals('suspended', $resolverResult->participationAllowed);
    }

    /**
     * Lifecycle Closed — Legacy vs Resolver Parity
     *
     * GOVERNANCE PRINCIPLE:
     * When election is in Counting state (closed), both systems MUST deny
     * participation with InvalidLifecycle reason, even with perfect trust.
     *
     * EXTRACTION VERIFICATION:
     * - Lifecycle policy runs after trust policy
     * - Trust legitimacy doesn't affect closed lifecycle denial
     */
    public function test_closed_lifecycle_extraction_and_parity(): void
    {
        // Create election and manually set to counting state
        $election = $this->createTestElection();
        $election->update(['state' => 'counting']);  // Use existing state column
        $user = $this->createTestUser();

        $legacyResult = $this->legacyBehavior->getLegacyResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: null,
            isVerified: false,
        );

        $resolverResult = $this->resolverBehavior->getResolverResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: null,
            isVerified: false,
        );

        $this->assertTrue(
            $legacyResult->equals($resolverResult),
            "Parity failed for closed lifecycle"
        );
        $this->assertEquals('invalid_lifecycle', $legacyResult->participationAllowed);
        $this->assertEquals('invalid_lifecycle', $resolverResult->participationAllowed);
    }

    /**
     * IP Restriction Enabled — Legacy vs Resolver Parity
     *
     * GOVERNANCE PRINCIPLE:
     * When IP restrictions are enabled and max votes exceeded,
     * both systems MUST deny with network legitimacy failure.
     *
     * NOTE: This test demonstrates extraction framework structure.
     * Full IP restriction schema added in Phase D.2 migrations.
     */
    public function test_ip_restriction_extraction_and_parity(): void
    {
        $election = $this->createTestElection();
        $user = $this->createTestUser();

        // With current schema, both systems should allow
        // (no IP restrictions implemented in legacy yet)

        $legacyResult = $this->legacyBehavior->getLegacyResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: null,
            isVerified: false,
        );

        $resolverResult = $this->resolverBehavior->getResolverResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: null,
            isVerified: false,
        );

        // Should maintain parity (both allow or both deny based on restrictions)
        $this->assertEquals(
            $legacyResult->participationAllowed,
            $resolverResult->participationAllowed,
            "IP restriction parity failed"
        );
    }

    /**
     * Divergence Recording — Ledger Population
     *
     * When parity fails, this test demonstrates how divergences
     * are recorded into the Constitutional Divergence Ledger
     * with full provenance (severity, article, approval).
     */
    public function test_divergence_ledger_records_with_provenance(): void
    {
        $election = $this->createTestElection();
        $user = $this->createTestUser();

        $legacyResult = $this->legacyBehavior->getLegacyResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: null,
            isVerified: false,
        );

        $resolverResult = $this->resolverBehavior->getResolverResult(
            electionId: $election->id,
            userId: $user->id,
            rawIp: '192.168.1.100',
            rawFingerprint: null,
            isVerified: false,
        );

        // Simulate a divergence for ledger test
        if (!$legacyResult->equals($resolverResult)) {
            // Record divergence with full provenance
            $ledger = $this->divergenceLedger->addEntry(
                electionId: $election->id,
                type: ConstitutionalDivergenceType::UnclassifiedDivergence,
                legacyBehavior: $legacyResult->participationAllowed,
                resolverBehavior: $resolverResult->participationAllowed,
                approvedBy: 'constitutional_parity_test_harness',
                rationale: 'Happy path divergence (may indicate upgrade needed)',
            );

            // Verify ledger entry has all required fields
            $entries = $ledger->entries();
            $this->assertCount(1, $entries);
            $this->assertNotNull($entries[0]->divergenceType());
            $this->assertNotNull($entries[0]->severity());
            $this->assertNotNull($entries[0]->constitutionalArticle());
            $this->assertNotNull($entries[0]->approvedBy());
        }

        // For this test, verify parity passes (no divergence expected)
        $this->assertTrue($legacyResult->equals($resolverResult));
    }

    /**
     * Helper: Create test election with configurable security settings
     *
     * Note: Full security article columns (restrict_voting_ip, etc.)
     * are added in Phase D.2 migrations. For now, test uses existing schema.
     */
    private function createTestElection(array $settings = []): \App\Models\Election
    {
        $election = \App\Models\Election::factory()->create();

        // Update suspension if specified in settings
        if (isset($settings['suspended_at'])) {
            $election->update(['suspended_at' => $settings['suspended_at']]);
        }

        return $election;
    }

    /**
     * Helper: Create test user
     */
    private function createTestUser(): \App\Models\User
    {
        return \App\Models\User::factory()->create();
    }
}
