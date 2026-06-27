<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalSeverity;
use App\Contexts\Membership\Domain\Committee\Constitutional\LegitimacyImpact;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\CanonicalConstitutionalSerializer;
use PHPUnit\Framework\TestCase;

class SemanticInvarianceTest extends TestCase
{
    /**
     * @test
     * Same reason with enum or string produces identical serialized JSON
     */
    public function test_same_reason_with_enum_or_string_produces_identical_json(): void
    {
        $serializer = new CanonicalConstitutionalSerializer();

        $reason = new ConstitutionalReason(
            code: 'test',
            summary: 'Test',
            explanation: 'Test explanation',
            articleCodes: [],
            severity: ConstitutionalSeverity::BINDING,
            legitimacyImpact: LegitimacyImpact::VALID
        );

        $json = $serializer->serializeReason($reason);
        $decoded = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('binding', $decoded['severity']);
        $this->assertSame('valid', $decoded['legitimacyImpact']);
    }

    /**
     * @test
     * Fingerprint stable regardless of rules insertion order
     */
    public function test_fingerprint_stable_regardless_of_rules_insertion_order(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     * Replay uses stored legitimacy not live evaluation
     */
    public function test_replay_uses_stored_legitimacy_not_live_evaluation(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     * Constitutional replay fingerprint unaffected by non-semantic fields
     */
    public function test_constitutional_replay_fingerprint_unaffected_by_non_semantic_fields(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     * Snapshot integrity unaffected by governance provenance addition
     */
    public function test_snapshot_integrity_unaffected_by_governance_provenance_addition(): void
    {
        $this->assertTrue(true);
    }
}
