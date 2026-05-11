<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\LegitimacyImpact;

final class ConstitutionalReasonTest extends TestCase
{
    public function test_resolved_factory_sets_code_and_summary(): void
    {
        $reason = ConstitutionalReason::resolved('exception', 'Exception authority applied');

        $this->assertSame('exception', $reason->code);
        $this->assertSame('Exception authority applied', $reason->summary);
    }

    public function test_no_authority_factory_has_invalid_impact(): void
    {
        $reason = ConstitutionalReason::noAuthority();

        $this->assertSame(LegitimacyImpact::INVALID, $reason->legitimacyImpact);
        $this->assertSame('no_authority', $reason->code);
    }

    public function test_article_codes_preserved(): void
    {
        $reason = new ConstitutionalReason(
            code: 'code',
            summary: 'summary',
            explanation: 'explanation',
            articleCodes: ['art.1', 'art.2'],
        );

        $this->assertSame(['art.1', 'art.2'], $reason->articleCodes);
    }
}
