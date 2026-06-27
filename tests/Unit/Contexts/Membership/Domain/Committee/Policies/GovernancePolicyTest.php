<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GovernanceAssignment;
use App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use DomainException;
use PHPUnit\Framework\TestCase;

final class GovernancePolicyTest extends TestCase
{
    public function test_allows_valid_assignment(): void
    {
        $matrix = GovernanceMatrix::fromRows([
            ['level' => 2, 'is_active' => true],
        ]);

        $policy = new GovernancePolicy($matrix);

        $policy->assertAllowed(
            new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
        );

        $this->expectNotToPerformAssertions();
    }

    public function test_rejects_invalid_assignment(): void
    {
        $matrix = GovernanceMatrix::fromRows([
            ['level' => 2, 'is_active' => false],
        ]);

        $policy = new GovernancePolicy($matrix);

        $this->expectException(DomainException::class);

        $policy->assertAllowed(
            new GovernanceAssignment(2, 2, GeoUnitId::fromInt(1))
        );
    }

    public function test_rejects_absent_assignment(): void
    {
        $matrix = GovernanceMatrix::fromRows([
            ['level' => 2, 'is_active' => true],
        ]);

        $policy = new GovernancePolicy($matrix);

        $this->expectException(DomainException::class);

        $policy->assertAllowed(
            new GovernanceAssignment(2, 1, GeoUnitId::fromInt(1))
        );
    }
}
