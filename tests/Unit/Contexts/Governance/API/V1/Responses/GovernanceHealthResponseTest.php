<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\API\V1\Responses;

use App\Contexts\Governance\API\V1\Responses\GovernanceHealthResponse;
use PHPUnit\Framework\TestCase;

final class GovernanceHealthResponseTest extends TestCase
{
    public function test_constructs_with_all_fields(): void
    {
        $response = new GovernanceHealthResponse(
            status: 'healthy',
            projectionGeneration: 'gen-001',
            rebuiltAt: '2026-05-10T12:00:00+00:00',
            staleCommitteeCount: 0,
            totalCommittees: 10,
            projectionAgeMs: 5000,
        );

        $this->assertSame('healthy', $response->status);
        $this->assertSame('gen-001', $response->projectionGeneration);
        $this->assertSame('2026-05-10T12:00:00+00:00', $response->rebuiltAt);
        $this->assertSame(0, $response->staleCommitteeCount);
        $this->assertSame(10, $response->totalCommittees);
        $this->assertSame(5000, $response->projectionAgeMs);
    }

    public function test_constructs_with_default_projection_age(): void
    {
        $response = new GovernanceHealthResponse(
            status: 'stale',
            projectionGeneration: 'none',
            rebuiltAt: null,
            staleCommitteeCount: 5,
            totalCommittees: 10,
        );

        $this->assertSame(0, $response->projectionAgeMs);
        $this->assertNull($response->rebuiltAt);
    }

    public function test_json_serialize_returns_expected_structure(): void
    {
        $response = new GovernanceHealthResponse(
            status: 'healthy',
            projectionGeneration: 'gen-001',
            rebuiltAt: '2026-05-10T12:00:00+00:00',
            staleCommitteeCount: 0,
            totalCommittees: 10,
            projectionAgeMs: 5000,
        );

        $this->assertSame([
            'status' => 'healthy',
            'projectionGeneration' => 'gen-001',
            'rebuiltAt' => '2026-05-10T12:00:00+00:00',
            'staleCommitteeCount' => 0,
            'totalCommittees' => 10,
            'projectionAgeMs' => 5000,
        ], $response->jsonSerialize());
    }
}
