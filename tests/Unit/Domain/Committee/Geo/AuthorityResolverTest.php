<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityResolver;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraph;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationEdge;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationType;
use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;

final class AuthorityResolverTest extends TestCase
{
    private AuthorityResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new AuthorityResolver();
    }

    public function test_no_matching_node_returns_none_authority(): void
    {
        $scope = new GeographicScope('national', null);
        $graph = new GeoAuthorityGraph(nodes: [], edges: []);

        $resolved = $this->resolver->resolve($scope, $graph, new \DateTimeImmutable());

        $this->assertTrue($resolved->isNone());
        $this->assertNull($resolved->directJurisdictionId);
        $this->assertEmpty($resolved->delegatedJurisdictionIds);
        $this->assertEquals(0, $resolved->authorityScore);
    }

    public function test_direct_jurisdiction_resolved_for_exact_scope_match(): void
    {
        $scope = new GeographicScope('state', 'BY');
        $node = new JurisdictionNode('jur-1', 'state', 'BY', true);
        $graph = new GeoAuthorityGraph(nodes: [$node], edges: []);

        $resolved = $this->resolver->resolve($scope, $graph, new \DateTimeImmutable());

        $this->assertNotNull($resolved->directJurisdictionId);
        $this->assertEquals('jur-1', $resolved->directJurisdictionId);
        $this->assertEmpty($resolved->delegatedJurisdictionIds);
        $this->assertEquals(100, $resolved->authorityScore);
    }

    public function test_delegated_jurisdictions_populated_from_edges(): void
    {
        $now = new \DateTimeImmutable();
        $scope = new GeographicScope('national', null);
        $rootNode = new JurisdictionNode('jur-1', 'national', null, true);
        $delegatedNode = new JurisdictionNode('jur-2', 'state', 'BY', true);

        $edge = new DelegationEdge(
            fromJurisdictionId: 'jur-1',
            toJurisdictionId: 'jur-2',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $graph = new GeoAuthorityGraph(
            nodes: [$rootNode, $delegatedNode],
            edges: [$edge]
        );

        $resolved = $this->resolver->resolve($scope, $graph, $now);

        $this->assertEquals('jur-1', $resolved->directJurisdictionId);
        $this->assertCount(1, $resolved->delegatedJurisdictionIds);
        $this->assertContains('jur-2', $resolved->delegatedJurisdictionIds);
        $this->assertEquals(100, $resolved->authorityScore);
    }

    public function test_override_edges_populate_exception_zones(): void
    {
        $now = new \DateTimeImmutable();
        $scope = new GeographicScope('state', 'BY');
        $rootNode = new JurisdictionNode('jur-1', 'state', 'BY', true);

        $graph = new GeoAuthorityGraph(
            nodes: [$rootNode],
            edges: []
        );

        $resolved = $this->resolver->resolve($scope, $graph, $now);

        $this->assertEquals('jur-1', $resolved->directJurisdictionId);
        $this->assertEmpty($resolved->exceptionZoneIds);
    }

    public function test_authority_score_is_100_for_direct_jurisdiction(): void
    {
        $scope = new GeographicScope('national', null);
        $node = new JurisdictionNode('jur-1', 'national', null, true);
        $graph = new GeoAuthorityGraph(nodes: [$node], edges: []);

        $resolved = $this->resolver->resolve($scope, $graph, new \DateTimeImmutable());

        $this->assertEquals(100, $resolved->authorityScore);
    }

    public function test_inactive_root_node_returns_none_authority(): void
    {
        $scope = new GeographicScope('national', null);
        $node = new JurisdictionNode('jur-1', 'national', null, false);
        $graph = new GeoAuthorityGraph(nodes: [$node], edges: []);

        $resolved = $this->resolver->resolve($scope, $graph, new \DateTimeImmutable());

        $this->assertTrue($resolved->isNone());
        $this->assertNull($resolved->directJurisdictionId);
    }
}
