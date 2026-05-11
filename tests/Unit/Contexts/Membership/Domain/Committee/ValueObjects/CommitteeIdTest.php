<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DomainException;
use PHPUnit\Framework\TestCase;

final class CommitteeIdTest extends TestCase
{
    public function test_it_generates_valid_uuid(): void
    {
        $committeeId = CommitteeId::generate();

        $this->assertNotEmpty($committeeId->value());
        $this->assertIsString($committeeId->value());
    }

    public function test_it_creates_committee_id_from_string(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $committeeId = CommitteeId::fromString($uuid);

        $this->assertEquals($uuid, $committeeId->value());
    }

    public function test_it_rejects_empty_string(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Committee ID cannot be empty');

        CommitteeId::fromString('');
    }

    public function test_it_rejects_whitespace_only(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Committee ID cannot be empty');

        CommitteeId::fromString('   ');
    }

    public function test_it_supports_value_equality(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $committeeId1 = CommitteeId::fromString($uuid);
        $committeeId2 = CommitteeId::fromString($uuid);

        $this->assertTrue($committeeId1->equals($committeeId2));
    }

    public function test_it_detects_unequal_ids(): void
    {
        $committeeId1 = CommitteeId::generate();
        $committeeId2 = CommitteeId::generate();

        $this->assertFalse($committeeId1->equals($committeeId2));
    }

    public function test_it_is_string_castable(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $committeeId = CommitteeId::fromString($uuid);

        $this->assertEquals($uuid, (string) $committeeId);
    }

    public function test_it_is_readonly(): void
    {
        $committeeId = CommitteeId::generate();

        $this->assertIsObject($committeeId);
        $this->assertNotEmpty($committeeId->value());
    }
}
