<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Semantics;

use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory;
use PHPUnit\Framework\TestCase;

class GovernanceSemanticCategoryTest extends TestCase
{
    /**
     * @test
     * Authority category exists
     */
    public function test_authority_category_exists(): void
    {
        $this->assertTrue(defined('App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory::AUTHORITY'));
        $this->assertSame('authority', GovernanceSemanticCategory::AUTHORITY->value);
    }

    /**
     * @test
     * Legitimacy category exists
     */
    public function test_legitimacy_category_exists(): void
    {
        $this->assertSame('legitimacy', GovernanceSemanticCategory::LEGITIMACY->value);
    }

    /**
     * @test
     * Delegation category exists
     */
    public function test_delegation_category_exists(): void
    {
        $this->assertSame('delegation', GovernanceSemanticCategory::DELEGATION->value);
    }

    /**
     * @test
     * Participation category exists
     */
    public function test_participation_category_exists(): void
    {
        $this->assertSame('participation', GovernanceSemanticCategory::PARTICIPATION->value);
    }

    /**
     * @test
     * Certification category exists
     */
    public function test_certification_category_exists(): void
    {
        $this->assertSame('certification', GovernanceSemanticCategory::CERTIFICATION->value);
    }

    /**
     * @test
     * Temporal category exists
     */
    public function test_temporal_category_exists(): void
    {
        $this->assertSame('temporality', GovernanceSemanticCategory::TEMPORALITY->value);
    }

    /**
     * @test
     * Jurisdiction category exists
     */
    public function test_jurisdiction_category_exists(): void
    {
        $this->assertSame('jurisdiction', GovernanceSemanticCategory::JURISDICTION->value);
    }

    /**
     * @test
     * Mandate category exists
     */
    public function test_mandate_category_exists(): void
    {
        $this->assertSame('mandate', GovernanceSemanticCategory::MANDATE->value);
    }

    /**
     * @test
     * Conflict category exists
     */
    public function test_conflict_category_exists(): void
    {
        $this->assertSame('conflict', GovernanceSemanticCategory::CONFLICT->value);
    }

    /**
     * @test
     * Provenance category exists
     */
    public function test_provenance_category_exists(): void
    {
        $this->assertSame('provenance', GovernanceSemanticCategory::PROVENANCE->value);
    }

    /**
     * @test
     * Category values are immutable
     */
    public function test_category_values_are_immutable(): void
    {
        $category1 = GovernanceSemanticCategory::AUTHORITY;
        $category2 = GovernanceSemanticCategory::AUTHORITY;

        $this->assertSame($category1, $category2);
        $this->assertSame('authority', $category1->value);
    }

    /**
     * @test
     * Category names remain stable
     */
    public function test_category_names_remain_stable(): void
    {
        $cases = GovernanceSemanticCategory::cases();

        $this->assertCount(10, $cases);
        $this->assertContains(GovernanceSemanticCategory::AUTHORITY, $cases);
        $this->assertContains(GovernanceSemanticCategory::LEGITIMACY, $cases);
        $this->assertContains(GovernanceSemanticCategory::DELEGATION, $cases);
        $this->assertContains(GovernanceSemanticCategory::PARTICIPATION, $cases);
        $this->assertContains(GovernanceSemanticCategory::CERTIFICATION, $cases);
        $this->assertContains(GovernanceSemanticCategory::TEMPORALITY, $cases);
        $this->assertContains(GovernanceSemanticCategory::JURISDICTION, $cases);
        $this->assertContains(GovernanceSemanticCategory::MANDATE, $cases);
        $this->assertContains(GovernanceSemanticCategory::CONFLICT, $cases);
        $this->assertContains(GovernanceSemanticCategory::PROVENANCE, $cases);
    }
}
