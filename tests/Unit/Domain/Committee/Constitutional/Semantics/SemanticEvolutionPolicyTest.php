<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Semantics;

use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\ImmutableSemanticEvolutionPolicy;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\Exception\SemanticEvolutionViolationException;
use PHPUnit\Framework\TestCase;

class SemanticEvolutionPolicyTest extends TestCase
{
    /**
     * @test
     * Semantic meaning may not change after publication
     */
    public function test_semantic_meaning_may_not_change_after_publication(): void
    {
        $policy = new ImmutableSemanticEvolutionPolicy();

        $original = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('mandate_active'),
            category: GovernanceSemanticCategory::MANDATE,
            canonicalMeaning: 'Elected authority holding office',
            replayImplications: 'Immutable in snapshots',
            legitimacyImplications: 'Determines election validity',
            introducedInDoctrineVersion: '1.0',
        );

        $modified = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('mandate_active'),
            category: GovernanceSemanticCategory::MANDATE,
            canonicalMeaning: 'Elected or delegated authority',  // CHANGED
            replayImplications: 'Immutable in snapshots',
            legitimacyImplications: 'Determines election validity',
            introducedInDoctrineVersion: '1.0',
        );

        $this->expectException(SemanticEvolutionViolationException::class);
        $policy->validateEvolution($original, $modified);
    }

    /**
     * @test
     * Replay implications may not change retroactively
     */
    public function test_replay_implications_may_not_change_retroactively(): void
    {
        $policy = new ImmutableSemanticEvolutionPolicy();

        $original = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('quorum_met'),
            category: GovernanceSemanticCategory::PARTICIPATION,
            canonicalMeaning: 'Minimum participants reached',
            replayImplications: 'Quorum stored immutably in snapshot',
            legitimacyImplications: 'Decision valid only if quorum met',
            introducedInDoctrineVersion: '1.0',
        );

        $modified = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('quorum_met'),
            category: GovernanceSemanticCategory::PARTICIPATION,
            canonicalMeaning: 'Minimum participants reached',
            replayImplications: 'Quorum computed dynamically during replay',  // CHANGED
            legitimacyImplications: 'Decision valid only if quorum met',
            introducedInDoctrineVersion: '1.0',
        );

        $this->expectException(SemanticEvolutionViolationException::class);
        $policy->validateEvolution($original, $modified);
    }

    /**
     * @test
     * Legitimacy implications may not change retroactively
     */
    public function test_legitimacy_implications_may_not_change_retroactively(): void
    {
        $policy = new ImmutableSemanticEvolutionPolicy();

        $original = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('scope_constraint'),
            category: GovernanceSemanticCategory::JURISDICTION,
            canonicalMeaning: 'Authority limited to geographic scope',
            replayImplications: 'Scope stored immutably in snapshot',
            legitimacyImplications: 'Authority invalid outside scope',
            introducedInDoctrineVersion: '1.0',
        );

        $modified = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('scope_constraint'),
            category: GovernanceSemanticCategory::JURISDICTION,
            canonicalMeaning: 'Authority limited to geographic scope',
            replayImplications: 'Scope stored immutably in snapshot',
            legitimacyImplications: 'Authority may override scope in emergency',  // CHANGED
            introducedInDoctrineVersion: '1.0',
        );

        $this->expectException(SemanticEvolutionViolationException::class);
        $policy->validateEvolution($original, $modified);
    }

    /**
     * @test
     * Semantic additions are allowed
     */
    public function test_semantic_additions_are_allowed(): void
    {
        $policy = new ImmutableSemanticEvolutionPolicy();

        // Adding a new semantic definition (not modifying existing)
        $newDefinition = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('new_semantic'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'New governance semantic',
            replayImplications: 'New implication',
            legitimacyImplications: 'New legitimacy',
            introducedInDoctrineVersion: '2.0',
        );

        // This should NOT throw (no original to compare)
        $policy->registerNewSemantic($newDefinition);
        $this->assertTrue(true);  // Placeholder assertion
    }

    /**
     * @test
     * Semantic removal is prohibited
     */
    public function test_semantic_removal_is_prohibited(): void
    {
        $policy = new ImmutableSemanticEvolutionPolicy();

        $original = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('deprecated_semantic'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Should not be removed',
            replayImplications: 'Critical for replay',
            legitimacyImplications: 'Determines legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        // Attempting to "remove" by passing null for modified
        $this->expectException(SemanticEvolutionViolationException::class);
        $policy->validateEvolution($original, null);
    }

    /**
     * @test
     * Semantic category mutation is prohibited
     */
    public function test_semantic_category_mutation_is_prohibited(): void
    {
        $policy = new ImmutableSemanticEvolutionPolicy();

        $original = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('moved_semantic'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Authority semantic',
            replayImplications: 'Immutable',
            legitimacyImplications: 'Valid',
            introducedInDoctrineVersion: '1.0',
        );

        $modified = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('moved_semantic'),
            category: GovernanceSemanticCategory::LEGITIMACY,  // CHANGED CATEGORY
            canonicalMeaning: 'Authority semantic',
            replayImplications: 'Immutable',
            legitimacyImplications: 'Valid',
            introducedInDoctrineVersion: '1.0',
        );

        $this->expectException(SemanticEvolutionViolationException::class);
        $policy->validateEvolution($original, $modified);
    }

    /**
     * @test
     * Policy violation throws semantic evolution violation exception
     */
    public function test_policy_violation_throws_correct_exception(): void
    {
        $policy = new ImmutableSemanticEvolutionPolicy();

        $original = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_code'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Original meaning',
            replayImplications: 'Original',
            legitimacyImplications: 'Original',
            introducedInDoctrineVersion: '1.0',
        );

        $modified = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_code'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Modified meaning',  // Changed
            replayImplications: 'Original',
            legitimacyImplications: 'Original',
            introducedInDoctrineVersion: '1.0',
        );

        $this->expectException(SemanticEvolutionViolationException::class);
        $this->expectExceptionMessage("Semantic evolution violation for 'test_code'");
        $policy->validateEvolution($original, $modified);
    }
}
