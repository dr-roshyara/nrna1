<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;

final class ConstitutionalArbitrationTraceTest extends TestCase
{
    public function test_has_selected_node_when_winner_present(): void
    {
        $trace = new ConstitutionalArbitrationTrace(
            evaluatedNodeIds: ['node-1', 'node-2'],
            selectedNodeId: 'node-1',
            precedenceReason: 'exception_precedence',
            doctrineRulesApplied: ['exception'],
        );

        $this->assertTrue($trace->hasSelectedNode());
    }

    public function test_has_no_selected_node_when_null(): void
    {
        $trace = new ConstitutionalArbitrationTrace(
            evaluatedNodeIds: [],
            selectedNodeId: null,
            precedenceReason: 'no_authority_found',
            doctrineRulesApplied: [],
        );

        $this->assertFalse($trace->hasSelectedNode());
    }

    public function test_doctrine_rules_preserved(): void
    {
        $trace = new ConstitutionalArbitrationTrace(
            evaluatedNodeIds: ['node-1'],
            selectedNodeId: 'node-1',
            precedenceReason: 'override_precedence',
            doctrineRulesApplied: ['override', 'fallback'],
        );

        $this->assertSame(['override', 'fallback'], $trace->doctrineRulesApplied);
    }
}
