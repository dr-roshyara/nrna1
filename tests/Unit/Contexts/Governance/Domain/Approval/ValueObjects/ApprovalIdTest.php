<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Approval\ValueObjects;

use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalId;
use PHPUnit\Framework\TestCase;

final class ApprovalIdTest extends TestCase
{
    public function test_generate_produces_uuid(): void
    {
        $id = ApprovalId::generate();
        $this->assertInstanceOf(ApprovalId::class, $id);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $id->value()
        );
    }

    public function test_two_generated_ids_are_different(): void
    {
        $a = ApprovalId::generate();
        $b = ApprovalId::generate();
        $this->assertFalse($a->equals($b));
    }

    public function test_from_constructs_with_known_value(): void
    {
        $id = ApprovalId::from('approval-uuid-123');
        $this->assertSame('approval-uuid-123', $id->value());
    }

    public function test_from_empty_string_throws(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('ApprovalId cannot be empty');
        ApprovalId::from('');
    }

    public function test_from_whitespace_throws(): void
    {
        $this->expectException(\DomainException::class);
        ApprovalId::from('   ');
    }

    public function test_equals_same_value(): void
    {
        $a = ApprovalId::from('same-uuid');
        $b = ApprovalId::from('same-uuid');
        $this->assertTrue($a->equals($b));
    }

    public function test_not_equals_different_value(): void
    {
        $a = ApprovalId::from('uuid-one');
        $b = ApprovalId::from('uuid-two');
        $this->assertFalse($a->equals($b));
    }
}
