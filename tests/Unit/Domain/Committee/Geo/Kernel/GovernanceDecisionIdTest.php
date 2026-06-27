<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo\Kernel;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;

final class GovernanceDecisionIdTest extends TestCase
{
    public function test_generate_produces_valid_uuid_format(): void
    {
        $id = GovernanceDecisionId::generate();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $id->toString()
        );
    }

    public function test_from_string_preserves_value(): void
    {
        $originalValue = 'abc-123-def';

        $id = GovernanceDecisionId::fromString($originalValue);

        $this->assertEquals($originalValue, $id->toString());
    }

    public function test_two_generated_ids_are_not_equal(): void
    {
        $id1 = GovernanceDecisionId::generate();
        $id2 = GovernanceDecisionId::generate();

        $this->assertFalse($id1->equals($id2));
    }
}
