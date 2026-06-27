<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Approval\ValueObjects;

use App\Contexts\Governance\Domain\Approval\ValueObjects\IdempotencyKey;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class IdempotencyKeyTest extends TestCase
{
    public function test_generate_creates_key_with_expected_pattern(): void
    {
        $committeeId = CommitteeId::generate();
        $now = new DateTimeImmutable('2026-01-01 10:00:00');

        $key = IdempotencyKey::generate($committeeId, $now);

        $this->assertInstanceOf(IdempotencyKey::class, $key);
        $this->assertMatchesRegularExpression(
            '/^approval_[a-f0-9\-]+_20260101_100000_[a-f0-9]{8}$/',
            $key->value()
        );
    }

    public function test_different_timestamps_produce_different_keys(): void
    {
        $committeeId = CommitteeId::generate();
        $t1 = new DateTimeImmutable('2026-01-01 10:00:00');
        $t2 = new DateTimeImmutable('2026-01-01 11:00:00');

        $key1 = IdempotencyKey::generate($committeeId, $t1);
        $key2 = IdempotencyKey::generate($committeeId, $t2);

        $this->assertFalse($key1->equals($key2));
    }

    public function test_from_constructs_with_known_value(): void
    {
        $key = IdempotencyKey::from('my-custom-key-123');
        $this->assertSame('my-custom-key-123', $key->value());
    }

    public function test_from_empty_string_throws(): void
    {
        $this->expectException(\DomainException::class);
        IdempotencyKey::from('');
    }

    public function test_from_whitespace_throws(): void
    {
        $this->expectException(\DomainException::class);
        IdempotencyKey::from('   ');
    }

    public function test_equals_same_value(): void
    {
        $a = IdempotencyKey::from('key-abc');
        $b = IdempotencyKey::from('key-abc');
        $this->assertTrue($a->equals($b));
    }

    public function test_not_equals_different_value(): void
    {
        $a = IdempotencyKey::from('key-abc');
        $b = IdempotencyKey::from('key-xyz');
        $this->assertFalse($a->equals($b));
    }
}
