<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\BallotAuthorizationProtocol;
use PHPUnit\Framework\TestCase;

class BallotAuthorizationProtocolTest extends TestCase
{
    public function test_unified_token_protocol_requires_one_step(): void
    {
        $this->assertEquals(1, BallotAuthorizationProtocol::UnifiedTokenProtocol->authorizationSteps());
    }

    public function test_split_authorization_protocol_requires_two_steps(): void
    {
        $this->assertEquals(2, BallotAuthorizationProtocol::SplitAuthorizationProtocol->authorizationSteps());
    }

    public function test_unified_token_does_not_require_separate_commit(): void
    {
        $this->assertFalse(BallotAuthorizationProtocol::UnifiedTokenProtocol->requiresSeparateCommit());
    }

    public function test_split_authorization_requires_separate_commit(): void
    {
        $this->assertTrue(BallotAuthorizationProtocol::SplitAuthorizationProtocol->requiresSeparateCommit());
    }

    public function test_split_authorization_requires_view_token(): void
    {
        $this->assertTrue(BallotAuthorizationProtocol::SplitAuthorizationProtocol->requiresViewToken());
    }
}
