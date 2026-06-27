<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo\Resolution;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\StableAuthoritySelectionPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final class StableAuthoritySelectionPolicyTest extends TestCase
{
    private StableAuthoritySelectionPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new StableAuthoritySelectionPolicy();
    }

    public function test_single_node_is_returned(): void
    {
        $node = new JurisdictionNode('node-a', 'national', null, true, false);

        $selected = $this->policy->select([$node]);

        $this->assertSame($node, $selected);
    }

    public function test_lexicographically_first_id_wins(): void
    {
        $nodeZ = new JurisdictionNode('node-z', 'state', 'ZZ', true, false);
        $nodeA = new JurisdictionNode('node-a', 'state', 'AA', true, false);
        $nodeM = new JurisdictionNode('node-m', 'state', 'MM', true, false);

        $selected = $this->policy->select([$nodeZ, $nodeA, $nodeM]);

        $this->assertEquals('node-a', $selected->id);
    }

    public function test_already_sorted_input_is_stable(): void
    {
        $nodeA = new JurisdictionNode('node-a', 'state', 'AA', true, false);
        $nodeB = new JurisdictionNode('node-b', 'state', 'BB', true, false);
        $nodeC = new JurisdictionNode('node-c', 'state', 'CC', true, false);

        $selected = $this->policy->select([$nodeA, $nodeB, $nodeC]);

        $this->assertEquals('node-a', $selected->id);
    }
}
