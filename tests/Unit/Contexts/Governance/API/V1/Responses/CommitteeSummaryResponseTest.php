<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\API\V1\Responses;

use App\Contexts\Governance\API\V1\Responses\CommitteeSummaryResponse;
use PHPUnit\Framework\TestCase;

final class CommitteeSummaryResponseTest extends TestCase
{
    public function test_constructs_with_all_fields(): void
    {
        $response = new CommitteeSummaryResponse(
            id: 'cmt-001',
            name: 'Executive Committee',
            level: 1,
            type: 'central',
            operationalState: 'ACTIVE',
            canAct: true,
        );

        $this->assertSame('cmt-001', $response->id);
        $this->assertSame('Executive Committee', $response->name);
        $this->assertSame(1, $response->level);
        $this->assertSame('central', $response->type);
        $this->assertSame('ACTIVE', $response->operationalState);
        $this->assertTrue($response->canAct);
    }

    public function test_json_serialize_returns_expected_structure(): void
    {
        $response = new CommitteeSummaryResponse(
            id: 'cmt-001',
            name: 'Executive Committee',
            level: 1,
            type: 'central',
            operationalState: 'ACTIVE',
            canAct: false,
        );

        $this->assertSame([
            'id' => 'cmt-001',
            'name' => 'Executive Committee',
            'level' => 1,
            'type' => 'central',
            'operationalState' => 'ACTIVE',
            'canAct' => false,
        ], $response->jsonSerialize());
    }
}
