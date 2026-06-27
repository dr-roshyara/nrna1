<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\API\V1\Responses;

use App\Contexts\Governance\API\V1\Responses\CommitteeGovernanceResponse;
use PHPUnit\Framework\TestCase;

final class CommitteeGovernanceResponseTest extends TestCase
{
    public function test_constructs_with_all_fields(): void
    {
        $response = new CommitteeGovernanceResponse(
            operationalState: 'ACTIVE',
            temporalState: 'CURRENT',
            legitimacy: 'CONSTITUTIONAL',
            canAct: true,
            isFullyOperational: true,
            termStart: '2026-01-01',
            termEnd: '2026-12-31',
            evaluatedAt: '2026-05-10T12:00:00+00:00',
            projectionGeneration: 'gen-001',
        );

        $this->assertSame('ACTIVE', $response->operationalState);
        $this->assertSame('CURRENT', $response->temporalState);
        $this->assertSame('CONSTITUTIONAL', $response->legitimacy);
        $this->assertTrue($response->canAct);
        $this->assertTrue($response->isFullyOperational);
        $this->assertSame('2026-01-01', $response->termStart);
        $this->assertSame('2026-12-31', $response->termEnd);
        $this->assertSame('2026-05-10T12:00:00+00:00', $response->evaluatedAt);
        $this->assertSame('gen-001', $response->projectionGeneration);
    }

    public function test_constructs_with_nullable_fields(): void
    {
        $response = new CommitteeGovernanceResponse(
            operationalState: 'ACTIVE',
            temporalState: 'CURRENT',
            legitimacy: 'CONSTITUTIONAL',
            canAct: true,
            isFullyOperational: true,
            termStart: null,
            termEnd: null,
            evaluatedAt: null,
            projectionGeneration: '',
        );

        $this->assertNull($response->termStart);
        $this->assertNull($response->termEnd);
        $this->assertNull($response->evaluatedAt);
        $this->assertSame('', $response->projectionGeneration);
    }

    public function test_json_serialize_returns_expected_structure(): void
    {
        $response = new CommitteeGovernanceResponse(
            operationalState: 'ACTIVE',
            temporalState: 'CURRENT',
            legitimacy: 'CONSTITUTIONAL',
            canAct: true,
            isFullyOperational: true,
            termStart: '2026-01-01',
            termEnd: '2026-12-31',
            evaluatedAt: '2026-05-10T12:00:00+00:00',
            projectionGeneration: 'gen-001',
        );

        $serialized = $response->jsonSerialize();

        $this->assertSame([
            'operationalState' => 'ACTIVE',
            'temporalState' => 'CURRENT',
            'legitimacy' => 'CONSTITUTIONAL',
            'canAct' => true,
            'isFullyOperational' => true,
            'termStart' => '2026-01-01',
            'termEnd' => '2026-12-31',
            'evaluatedAt' => '2026-05-10T12:00:00+00:00',
            'projectionGeneration' => 'gen-001',
        ], $serialized);
    }

    public function test_from_array_creates_response(): void
    {
        $response = CommitteeGovernanceResponse::fromArray([
            'operationalState' => 'ACTIVE',
            'temporalState' => 'CURRENT',
            'legitimacy' => 'CONSTITUTIONAL',
            'canAct' => true,
            'isFullyOperational' => true,
            'termStart' => '2026-01-01',
            'projectionGeneration' => 'gen-001',
        ]);

        $this->assertSame('ACTIVE', $response->operationalState);
        $this->assertSame('CURRENT', $response->temporalState);
        $this->assertSame('gen-001', $response->projectionGeneration);
    }

    public function test_from_array_uses_defaults_for_missing_fields(): void
    {
        $response = CommitteeGovernanceResponse::fromArray([]);

        $this->assertSame('UNKNOWN', $response->operationalState);
        $this->assertSame('UNKNOWN', $response->temporalState);
        $this->assertSame('UNKNOWN', $response->legitimacy);
        $this->assertFalse($response->canAct);
        $this->assertFalse($response->isFullyOperational);
        $this->assertNull($response->termStart);
        $this->assertNull($response->termEnd);
        $this->assertNull($response->evaluatedAt);
        $this->assertSame('', $response->projectionGeneration);
    }
}
