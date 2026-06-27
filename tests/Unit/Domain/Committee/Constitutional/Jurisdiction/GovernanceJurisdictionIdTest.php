<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Jurisdiction;

use App\Contexts\Membership\Domain\Committee\Constitutional\Jurisdiction\GovernanceJurisdictionId;
use PHPUnit\Framework\TestCase;

class GovernanceJurisdictionIdTest extends TestCase
{
    /**
     * @test
     * GovernanceJurisdictionId preserves value from string
     */
    public function test_from_string_preserves_value(): void
    {
        $jurisdictionId = GovernanceJurisdictionId::fromString('nrna-eu');

        $this->assertSame('nrna-eu', $jurisdictionId->toString());
    }

    /**
     * @test
     * GovernanceJurisdictionId equality compares values
     */
    public function test_equals_returns_true_for_same_value(): void
    {
        $jurisdictionId1 = GovernanceJurisdictionId::fromString('nrna-eu');
        $jurisdictionId2 = GovernanceJurisdictionId::fromString('nrna-eu');

        $this->assertTrue($jurisdictionId1->equals($jurisdictionId2));
    }

    /**
     * @test
     * GovernanceJurisdictionId rejects empty string
     */
    public function test_empty_string_throws_invalid_argument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GovernanceJurisdictionId cannot be empty');

        GovernanceJurisdictionId::fromString('');
    }
}
