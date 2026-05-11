<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Semantics;

use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode;
use PHPUnit\Framework\TestCase;

class GovernanceSemanticCodeTest extends TestCase
{
    /**
     * @test
     * Semantic code preserves exact value
     */
    public function test_semantic_code_preserves_exact_value(): void
    {
        $code = GovernanceSemanticCode::fromString('voting_legitimacy');

        $this->assertSame('voting_legitimacy', $code->toString());
    }

    /**
     * @test
     * Semantic code equality works
     */
    public function test_semantic_code_equality_works(): void
    {
        $code1 = GovernanceSemanticCode::fromString('quorum_met');
        $code2 = GovernanceSemanticCode::fromString('quorum_met');
        $code3 = GovernanceSemanticCode::fromString('quorum_failed');

        $this->assertTrue($code1->equals($code2));
        $this->assertFalse($code1->equals($code3));
    }

    /**
     * @test
     * Semantic code rejects empty values
     */
    public function test_semantic_code_rejects_empty_values(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        GovernanceSemanticCode::fromString('');
    }

    /**
     * @test
     * Semantic code rejects whitespace-only values
     */
    public function test_semantic_code_rejects_whitespace_only_values(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        GovernanceSemanticCode::fromString('   ');
    }

    /**
     * @test
     * Semantic code normalizes casing consistently
     */
    public function test_semantic_code_normalizes_casing_consistently(): void
    {
        $code1 = GovernanceSemanticCode::fromString('VOTING_LEGITIMACY');
        $code2 = GovernanceSemanticCode::fromString('voting_legitimacy');

        $this->assertTrue($code1->equals($code2));
        $this->assertSame('voting_legitimacy', $code1->toString());
        $this->assertSame('voting_legitimacy', $code2->toString());
    }

    /**
     * @test
     * Semantic code is immutable
     */
    public function test_semantic_code_is_immutable(): void
    {
        $code = GovernanceSemanticCode::fromString('delegation_authority');

        // Call toString multiple times — must return same value
        $first = $code->toString();
        $second = $code->toString();

        $this->assertSame($first, $second);
        $this->assertSame('delegation_authority', $first);
    }

    /**
     * @test
     * Semantic code supports replay-safe equality
     */
    public function test_semantic_code_supports_replay_safe_equality(): void
    {
        $code1 = GovernanceSemanticCode::fromString('proxy_vote');
        $code2 = GovernanceSemanticCode::fromString('proxy_vote');

        // Replay safety: same code created in different processes should be equal
        $this->assertTrue($code1->equals($code2));

        // Hash should be deterministic
        $hash1 = hash('sha256', $code1->toString());
        $hash2 = hash('sha256', $code2->toString());
        $this->assertSame($hash1, $hash2);
    }
}
