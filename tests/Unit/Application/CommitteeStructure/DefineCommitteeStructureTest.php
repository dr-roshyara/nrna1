<?php

declare(strict_types=1);

namespace Tests\Unit\Application\CommitteeStructure;

use App\Contexts\Membership\Application\CommitteeStructure\DefineCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use Tests\TestCase;
use Mockery;

final class DefineCommitteeStructureTest extends TestCase
{
    private $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = Mockery::mock(CommitteeStructureRepositoryInterface::class);
    }

    public function test_it_creates_structure_in_draft_state(): void
    {
        // Arrange
        $command = [
            'tenantId' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'Main Committee Structure',
            'levels' => [
                [
                    'index' => 1,
                    'name' => 'Level 1',
                    'geoPolicy' => 'none',
                ]
            ]
        ];

        $this->repo
            ->shouldReceive('persist')
            ->once()
            ->with(Mockery::on(function ($structure) {
                return $structure instanceof CommitteeStructure
                    && $structure->isDraft()
                    && $structure->version() === 1;
            }));

        $useCase = new DefineCommitteeStructure($this->repo);

        // Act
        $result = $useCase->execute($command);

        // Assert
        $this->assertInstanceOf(CommitteeStructure::class, $result);
        $this->assertTrue($result->isDraft());
        $this->assertEquals(1, $result->version());
    }
}
