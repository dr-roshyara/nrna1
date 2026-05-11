<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DomainException;
use PHPUnit\Framework\TestCase;

final class MemberIdTest extends TestCase
{
    public function test_it_creates_member_id_from_string(): void
    {
        $memberId = MemberId::from('member-123');

        $this->assertEquals('member-123', $memberId->value());
    }

    public function test_it_rejects_empty_string(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Member ID cannot be empty');

        MemberId::from('');
    }

    public function test_it_rejects_whitespace_only(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Member ID cannot be empty');

        MemberId::from('   ');
    }

    public function test_it_normalizes_whitespace(): void
    {
        $memberId = MemberId::from('  member-456  ');

        $this->assertEquals('member-456', $memberId->value());
    }

    public function test_it_supports_value_equality(): void
    {
        $memberId1 = MemberId::from('member-789');
        $memberId2 = MemberId::from('member-789');

        $this->assertTrue($memberId1->equals($memberId2));
    }

    public function test_it_detects_unequal_member_ids(): void
    {
        $memberId1 = MemberId::from('member-111');
        $memberId2 = MemberId::from('member-222');

        $this->assertFalse($memberId1->equals($memberId2));
    }

    public function test_it_is_string_castable(): void
    {
        $memberId = MemberId::from('member-cast-test');

        $this->assertEquals('member-cast-test', (string) $memberId);
    }

    public function test_it_is_readonly(): void
    {
        $memberId = MemberId::from('member-immutable');

        $this->assertIsObject($memberId);
        $this->assertEquals('member-immutable', $memberId->value());
    }
}
