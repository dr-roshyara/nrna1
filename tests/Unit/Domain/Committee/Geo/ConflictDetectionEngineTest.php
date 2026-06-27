<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\ConflictDetectionEngine;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\ConflictType;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final class ConflictDetectionEngineTest extends TestCase
{
    private ConflictDetectionEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new ConflictDetectionEngine();
    }

    public function test_empty_classification_has_no_conflicts(): void
    {
        $classification = new AuthorityClassification(
            direct: null,
            delegated: [],
            overrides: [],
            exceptions: []
        );

        $conflicts = $this->engine->detect($classification);

        $this->assertEmpty($conflicts);
    }

    public function test_single_direct_authority_has_no_conflicts(): void
    {
        $node = new JurisdictionNode('node-1', 'national', null, true, false);
        $classification = new AuthorityClassification(
            direct: $node,
            delegated: [],
            overrides: [],
            exceptions: []
        );

        $conflicts = $this->engine->detect($classification);

        $this->assertEmpty($conflicts);
    }

    public function test_multiple_authorities_trigger_multi_authority_conflict(): void
    {
        $directNode = new JurisdictionNode('node-1', 'national', null, true, false);
        $delegatedNode = new JurisdictionNode('node-2', 'state', 'BY', true, false);
        $classification = new AuthorityClassification(
            direct: $directNode,
            delegated: [$delegatedNode],
            overrides: [],
            exceptions: []
        );

        $conflicts = $this->engine->detect($classification);

        $this->assertCount(2, $conflicts); // MULTI_AUTHORITY + DIRECT_VS_DELEGATED
        $this->assertTrue(
            in_array(ConflictType::MULTI_AUTHORITY, array_map(fn($c) => $c->type, $conflicts))
        );
    }

    public function test_exception_zones_trigger_exception_present_conflict(): void
    {
        $exceptionNode = new JurisdictionNode('node-4', 'district', 'BY-01', true, true);
        $classification = new AuthorityClassification(
            direct: null,
            delegated: [],
            overrides: [],
            exceptions: [$exceptionNode]
        );

        $conflicts = $this->engine->detect($classification);

        $this->assertCount(1, $conflicts);
        $this->assertEquals(ConflictType::EXCEPTION_PRESENT, $conflicts[0]->type);
    }

    public function test_overrides_trigger_override_present_conflict(): void
    {
        $overrideNode = new JurisdictionNode('node-3', 'state', 'BY', true, false);
        $classification = new AuthorityClassification(
            direct: null,
            delegated: [],
            overrides: [$overrideNode],
            exceptions: []
        );

        $conflicts = $this->engine->detect($classification);

        $this->assertCount(1, $conflicts);
        $this->assertEquals(ConflictType::OVERRIDE_PRESENT, $conflicts[0]->type);
    }

    public function test_direct_and_delegated_together_trigger_direct_vs_delegated_conflict(): void
    {
        $directNode = new JurisdictionNode('node-1', 'national', null, true, false);
        $delegatedNode = new JurisdictionNode('node-2', 'state', 'BY', true, false);
        $classification = new AuthorityClassification(
            direct: $directNode,
            delegated: [$delegatedNode],
            overrides: [],
            exceptions: []
        );

        $conflicts = $this->engine->detect($classification);

        $this->assertTrue(
            in_array(ConflictType::DIRECT_VS_DELEGATED, array_map(fn($c) => $c->type, $conflicts))
        );
    }
}
