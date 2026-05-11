<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Geography\Domain\Repositories\GeoUnitRepositoryInterface;
use App\Contexts\Geography\Domain\Services\GeographyDomainService;
use PHPUnit\Framework\TestCase;

class GeographyDomainServiceTest extends TestCase
{
    private GeographyDomainService $service;
    private GeoUnitRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(GeoUnitRepositoryInterface::class);
        $this->service = new GeographyDomainService($this->repository);
    }

    public function test_valid_unit_ids_pass_validation(): void
    {
        $this->repository
            ->expects($this->exactly(3))
            ->method('exists')
            ->willReturn(true);

        $result = $this->service->validateHierarchy('NP', [1, 2, 3]);

        $this->assertTrue($result);
    }

    public function test_nonexistent_unit_ids_fail_validation(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('exists')
            ->with(999, 'NP')
            ->willReturn(false);

        $result = $this->service->validateHierarchy('NP', [999]);

        $this->assertFalse($result);
    }

    public function test_out_of_order_hierarchy_fails_validation(): void
    {
        // First level exists, second exists but is not a child of first
        $this->repository
            ->expects($this->exactly(2))
            ->method('exists')
            ->willReturn(true);

        $unit1 = new class {
            public function parentId() { return null; }
        };
        $unit2 = new class {
            public function parentId() { return 999; } // Not a child of unit 1
        };

        $this->repository
            ->expects($this->exactly(1))
            ->method('findById')
            ->with(2)
            ->willReturn($unit2);

        $result = $this->service->validateHierarchy('NP', [1, 2]);

        $this->assertFalse($result);
    }

    public function test_empty_unit_ids_pass_validation(): void
    {
        $result = $this->service->validateHierarchy('NP', []);

        $this->assertTrue($result);
    }
}
