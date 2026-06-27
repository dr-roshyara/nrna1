<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\DefaultAuthorityPrecedencePolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final class DefaultAuthorityPrecedencePolicyTest extends TestCase
{
    private DefaultAuthorityPrecedencePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new DefaultAuthorityPrecedencePolicy();
    }

    public function test_empty_classification_returns_empty_ranking(): void
    {
        $classification = new AuthorityClassification(
            direct: null,
            delegated: [],
            overrides: [],
            exceptions: []
        );

        $ranking = $this->policy->rank($classification);

        $this->assertEmpty($ranking);
    }

    public function test_direct_authority_has_score_60(): void
    {
        $node = new JurisdictionNode('node-1', 'national', null, true, false);
        $classification = new AuthorityClassification(
            direct: $node,
            delegated: [],
            overrides: [],
            exceptions: []
        );

        $ranking = $this->policy->rank($classification);

        $this->assertCount(1, $ranking);
        $this->assertEquals('node-1', $ranking[0]['node']->id);
        $this->assertEquals(60, $ranking[0]['score']);
    }

    public function test_override_ranked_above_direct(): void
    {
        $directNode = new JurisdictionNode('node-1', 'national', null, true, false);
        $overrideNode = new JurisdictionNode('node-3', 'state', 'BY', true, false);
        $classification = new AuthorityClassification(
            direct: $directNode,
            delegated: [],
            overrides: [$overrideNode],
            exceptions: []
        );

        $ranking = $this->policy->rank($classification);

        $this->assertCount(2, $ranking);
        $this->assertEquals('node-3', $ranking[0]['node']->id); // Override first (score 80)
        $this->assertEquals(80, $ranking[0]['score']);
        $this->assertEquals('node-1', $ranking[1]['node']->id); // Direct second (score 60)
        $this->assertEquals(60, $ranking[1]['score']);
    }

    public function test_exception_zone_ranked_highest_with_score_100(): void
    {
        $directNode = new JurisdictionNode('node-1', 'national', null, true, false);
        $delegatedNode = new JurisdictionNode('node-2', 'state', 'BY', true, false);
        $overrideNode = new JurisdictionNode('node-3', 'state', 'NRW', true, false);
        $exceptionNode = new JurisdictionNode('node-4', 'district', 'BY-01', true, true);

        $classification = new AuthorityClassification(
            direct: $directNode,
            delegated: [$delegatedNode],
            overrides: [$overrideNode],
            exceptions: [$exceptionNode]
        );

        $ranking = $this->policy->rank($classification);

        $this->assertCount(4, $ranking);
        $this->assertEquals('node-4', $ranking[0]['node']->id); // Exception (100)
        $this->assertEquals(100, $ranking[0]['score']);
        $this->assertEquals('node-3', $ranking[1]['node']->id); // Override (80)
        $this->assertEquals(80, $ranking[1]['score']);
        $this->assertEquals('node-1', $ranking[2]['node']->id); // Direct (60)
        $this->assertEquals(60, $ranking[2]['score']);
        $this->assertEquals('node-2', $ranking[3]['node']->id); // Delegated (40)
        $this->assertEquals(40, $ranking[3]['score']);
    }
}
