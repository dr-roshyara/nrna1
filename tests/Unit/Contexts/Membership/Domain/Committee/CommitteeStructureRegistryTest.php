<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\CommitteeStructureRegistry;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\Committee\Strategies\CentralCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\GeographicCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\YouthWingStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\WomenWingStructure;
use App\Contexts\Membership\Domain\Committee\Strategies\StudentWingStructure;
use PHPUnit\Framework\TestCase;

final class CommitteeStructureRegistryTest extends TestCase
{
    /** @test */
    public function forType_central_returns_CentralCommitteeStructure(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::central());
        $this->assertInstanceOf(CentralCommitteeStructure::class, $structure);
    }

    /** @test */
    public function forType_province_returns_GeographicCommitteeStructure(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::province());
        $this->assertInstanceOf(GeographicCommitteeStructure::class, $structure);
    }

    /** @test */
    public function forType_district_returns_GeographicCommitteeStructure(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::district());
        $this->assertInstanceOf(GeographicCommitteeStructure::class, $structure);
    }

    /** @test */
    public function forType_ward_returns_GeographicCommitteeStructure(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::ward());
        $this->assertInstanceOf(GeographicCommitteeStructure::class, $structure);
    }

    /** @test */
    public function forType_youth_returns_YouthWingStructure(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::youth());
        $this->assertInstanceOf(YouthWingStructure::class, $structure);
    }

    /** @test */
    public function forType_women_returns_WomenWingStructure(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::women());
        $this->assertInstanceOf(WomenWingStructure::class, $structure);
    }

    /** @test */
    public function forType_student_returns_StudentWingStructure(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::student());
        $this->assertInstanceOf(StudentWingStructure::class, $structure);
    }

    /** @test */
    public function forType_unknown_throws_DomainException(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Unknown committee type');

        // diaspora is a valid CommitteeType but not handled by registry
        CommitteeStructureRegistry::forType(CommitteeType::diaspora());
    }
}
