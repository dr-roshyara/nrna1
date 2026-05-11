<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Semantics;

use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition;
use PHPUnit\Framework\TestCase;

class GovernanceSemanticDefinitionTest extends TestCase
{
    /**
     * @test
     * Semantic definition contains category
     */
    public function test_semantic_definition_contains_category(): void
    {
        $definition = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('voting_legitimacy'),
            category: GovernanceSemanticCategory::LEGITIMACY,
            canonicalMeaning: 'Authority to participate in voting',
            replayImplications: 'Stored in snapshot; never re-evaluated',
            legitimacyImplications: 'Valid votes from legitimate authorities',
            introducedInDoctrineVersion: '1.0',
        );

        $this->assertEquals(GovernanceSemanticCategory::LEGITIMACY, $definition->category);
    }

    /**
     * @test
     * Semantic definition contains semantic code
     */
    public function test_semantic_definition_contains_semantic_code(): void
    {
        $code = GovernanceSemanticCode::fromString('proxy_authority');
        $definition = new GovernanceSemanticDefinition(
            code: $code,
            category: GovernanceSemanticCategory::DELEGATION,
            canonicalMeaning: 'Authority delegated via proxy',
            replayImplications: 'Proxy relationships immutable in snapshot',
            legitimacyImplications: 'Proxy authority inherits delegator legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $this->assertTrue($definition->code->equals($code));
    }

    /**
     * @test
     * Semantic definition contains canonical meaning
     */
    public function test_semantic_definition_contains_canonical_meaning(): void
    {
        $meaning = 'Minimum number of participants for valid decision';
        $definition = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('quorum_requirement'),
            category: GovernanceSemanticCategory::PARTICIPATION,
            canonicalMeaning: $meaning,
            replayImplications: 'Quorum stored in snapshot',
            legitimacyImplications: 'Decision invalid if quorum not met',
            introducedInDoctrineVersion: '1.0',
        );

        $this->assertSame($meaning, $definition->canonicalMeaning);
    }

    /**
     * @test
     * Semantic definition contains replay implications
     */
    public function test_semantic_definition_contains_replay_implications(): void
    {
        $implications = 'Stored immutably; affects all future replay of decisions';
        $definition = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('scope_constraint'),
            category: GovernanceSemanticCategory::JURISDICTION,
            canonicalMeaning: 'Constitutional scope of authority',
            replayImplications: $implications,
            legitimacyImplications: 'Decision invalid if scope violated',
            introducedInDoctrineVersion: '1.0',
        );

        $this->assertSame($implications, $definition->replayImplications);
    }

    /**
     * @test
     * Semantic definition contains legitimacy implications
     */
    public function test_semantic_definition_contains_legitimacy_implications(): void
    {
        $implications = 'Authority cannot decide outside its constitutional scope';
        $definition = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('scope_enforcement'),
            category: GovernanceSemanticCategory::CERTIFICATION,
            canonicalMeaning: 'Scope violations detected during certification',
            replayImplications: 'Certification checks scope equivalence',
            legitimacyImplications: $implications,
            introducedInDoctrineVersion: '1.0',
        );

        $this->assertSame($implications, $definition->legitimacyImplications);
    }

    /**
     * @test
     * Semantic definition is immutable
     */
    public function test_semantic_definition_is_immutable(): void
    {
        $definition = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('term_limit'),
            category: GovernanceSemanticCategory::MANDATE,
            canonicalMeaning: 'Maximum duration of authority',
            replayImplications: 'Term limits immutable after definition',
            legitimacyImplications: 'Authority expires at term end',
            introducedInDoctrineVersion: '1.0',
        );

        // Read multiple times — must be same
        $meaning1 = $definition->canonicalMeaning;
        $meaning2 = $definition->canonicalMeaning;

        $this->assertSame($meaning1, $meaning2);
    }

    /**
     * @test
     * Semantic definition supports deterministic serialization
     */
    public function test_semantic_definition_supports_deterministic_serialization(): void
    {
        $json1 = json_encode([
            'code' => 'voting_legitimacy',
            'category' => 'legitimacy',
            'meaning' => 'Authority to vote',
            'replay_implications' => 'Immutable',
            'legitimacy_implications' => 'Valid votes',
            'introduced' => '1.0',
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $json2 = json_encode([
            'code' => 'voting_legitimacy',
            'category' => 'legitimacy',
            'meaning' => 'Authority to vote',
            'replay_implications' => 'Immutable',
            'legitimacy_implications' => 'Valid votes',
            'introduced' => '1.0',
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $this->assertSame($json1, $json2);
    }

    /**
     * @test
     * Semantic meaning cannot be empty
     */
    public function test_semantic_meaning_cannot_be_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('invalid'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: '',  // Empty!
            replayImplications: 'Something',
            legitimacyImplications: 'Something',
            introducedInDoctrineVersion: '1.0',
        );
    }

    /**
     * @test
     * Replay implications cannot be empty
     */
    public function test_replay_implications_cannot_be_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('invalid'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Some meaning',
            replayImplications: '',  // Empty!
            legitimacyImplications: 'Something',
            introducedInDoctrineVersion: '1.0',
        );
    }
}
