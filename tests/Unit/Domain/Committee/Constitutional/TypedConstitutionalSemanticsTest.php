<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalSeverity;
use App\Contexts\Membership\Domain\Committee\Constitutional\LegitimacyImpact;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\CanonicalConstitutionalSerializer;
use PHPUnit\Framework\TestCase;

class TypedConstitutionalSemanticsTest extends TestCase
{
    /**
     * @test
     * ConstitutionalSeverity values match legacy constants
     */
    public function test_constitutional_severity_values_match_legacy_constants(): void
    {
        $this->assertSame('determinative', ConstitutionalSeverity::DETERMINATIVE->value);
        $this->assertSame('binding', ConstitutionalSeverity::BINDING->value);
        $this->assertSame('persuasive', ConstitutionalSeverity::PERSUASIVE->value);
    }

    /**
     * @test
     * LegitimacyImpact values match legacy constants
     */
    public function test_legitimacy_impact_values_match_legacy_constants(): void
    {
        $this->assertSame('valid', LegitimacyImpact::VALID->value);
        $this->assertSame('questionable', LegitimacyImpact::QUESTIONABLE->value);
        $this->assertSame('invalid', LegitimacyImpact::INVALID->value);
    }

    /**
     * @test
     * ConstitutionalReason accepts typed severity parameter
     */
    public function test_constitutional_reason_accepts_typed_severity(): void
    {
        $reason = new ConstitutionalReason(
            code: 'test',
            summary: 'Test',
            explanation: 'Test explanation',
            articleCodes: [],
            severity: ConstitutionalSeverity::BINDING,
            legitimacyImpact: LegitimacyImpact::VALID
        );

        $this->assertSame(ConstitutionalSeverity::BINDING, $reason->severity);
    }

    /**
     * @test
     * ConstitutionalReason::noAuthority() produces typed impact
     */
    public function test_constitutional_reason_factory_produces_typed_impact(): void
    {
        $reason = ConstitutionalReason::noAuthority();

        $this->assertSame(LegitimacyImpact::INVALID, $reason->legitimacyImpact);
        $this->assertSame(ConstitutionalSeverity::DETERMINATIVE, $reason->severity);
    }

    /**
     * @test
     * Canonical serializer JSON unchanged after typed migration
     */
    public function test_canonical_serializer_json_unchanged_after_typed_migration(): void
    {
        $serializer = new CanonicalConstitutionalSerializer();

        $reason = new ConstitutionalReason(
            code: 'test',
            summary: 'Test Reason',
            explanation: 'Test explanation',
            articleCodes: ['art.1'],
            severity: ConstitutionalSeverity::BINDING,
            legitimacyImpact: LegitimacyImpact::VALID
        );

        $json = $serializer->serializeReason($reason);
        $decoded = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('binding', $decoded['severity']);
        $this->assertSame('valid', $decoded['legitimacyImpact']);
    }
}
