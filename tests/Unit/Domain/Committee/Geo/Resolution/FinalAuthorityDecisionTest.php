<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo\Resolution;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\FinalAuthorityDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\AuthorityResolutionType;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final class FinalAuthorityDecisionTest extends TestCase
{
    public function test_none_factory_produces_unresolved_decision(): void
    {
        $decision = FinalAuthorityDecision::none();

        $this->assertNull($decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::NONE, $decision->type);
        $this->assertFalse($decision->isResolved());
    }

    public function test_from_factory_produces_resolved_decision(): void
    {
        $node = new JurisdictionNode('node-1', 'national', null, true, false);
        $decision = FinalAuthorityDecision::from($node, AuthorityResolutionType::EXCEPTION, 'exception_precedence');

        $this->assertSame($node, $decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::EXCEPTION, $decision->type);
        $this->assertTrue($decision->isResolved());
    }

    public function test_resolution_reason_preserved(): void
    {
        $node = new JurisdictionNode('node-1', 'national', null, true, false);
        $reason = 'custom_resolution_reason_with_details';
        $decision = FinalAuthorityDecision::from($node, AuthorityResolutionType::DIRECT, $reason);

        $this->assertEquals($reason, $decision->resolutionReason);
    }
}
