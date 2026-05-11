<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassifier;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationEdge;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationType;

final class AuthorityClassifierTest extends TestCase
{
    private AuthorityClassifier $classifier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->classifier = new AuthorityClassifier();
    }

    public function test_null_root_and_no_reachable_returns_empty_classification(): void
    {
        $classification = $this->classifier->classify(null, []);

        $this->assertTrue($classification->isEmpty());
        $this->assertNull($classification->direct);
        $this->assertEmpty($classification->delegated);
        $this->assertEmpty($classification->overrides);
        $this->assertEmpty($classification->exceptions);
    }

    public function test_root_node_becomes_direct_authority(): void
    {
        $root = new JurisdictionNode('node-1', 'national', null, true, false);

        $classification = $this->classifier->classify($root, []);

        $this->assertNotNull($classification->direct);
        $this->assertEquals('node-1', $classification->direct->id);
        $this->assertEmpty($classification->delegated);
        $this->assertEmpty($classification->overrides);
        $this->assertEmpty($classification->exceptions);
    }

    public function test_authority_type_edge_classified_as_delegated(): void
    {
        $now = new \DateTimeImmutable();
        $root = new JurisdictionNode('node-1', 'national', null, true, false);
        $delegatedNode = new JurisdictionNode('node-2', 'state', 'BY', true, false);
        $edge = new DelegationEdge('node-1', 'node-2', DelegationType::AUTHORITY, $now, null);
        $reachable = [['node' => $delegatedNode, 'edge' => $edge]];

        $classification = $this->classifier->classify($root, $reachable);

        $this->assertCount(1, $classification->delegated);
        $this->assertEquals('node-2', $classification->delegated[0]->id);
        $this->assertEmpty($classification->overrides);
        $this->assertEmpty($classification->exceptions);
    }

    public function test_override_type_edge_classified_as_override(): void
    {
        $now = new \DateTimeImmutable();
        $root = new JurisdictionNode('node-1', 'national', null, true, false);
        $overrideNode = new JurisdictionNode('node-3', 'state', 'BY', true, false);
        $edge = new DelegationEdge('node-1', 'node-3', DelegationType::OVERRIDE, $now, null);
        $reachable = [['node' => $overrideNode, 'edge' => $edge]];

        $classification = $this->classifier->classify($root, $reachable);

        $this->assertEmpty($classification->delegated);
        $this->assertCount(1, $classification->overrides);
        $this->assertEquals('node-3', $classification->overrides[0]->id);
        $this->assertEmpty($classification->exceptions);
    }

    public function test_exception_zone_node_classified_as_exception_regardless_of_edge_type(): void
    {
        $now = new \DateTimeImmutable();
        $root = new JurisdictionNode('node-1', 'national', null, true, false);
        $exceptionNode = new JurisdictionNode('node-4', 'district', 'BY-01', true, true); // isExceptionZone=true
        $edge = new DelegationEdge('node-1', 'node-4', DelegationType::AUTHORITY, $now, null);
        $reachable = [['node' => $exceptionNode, 'edge' => $edge]];

        $classification = $this->classifier->classify($root, $reachable);

        $this->assertEmpty($classification->delegated);
        $this->assertEmpty($classification->overrides);
        $this->assertCount(1, $classification->exceptions);
        $this->assertEquals('node-4', $classification->exceptions[0]->id);
    }
}
