<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority\ValueObjects;

use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use PHPUnit\Framework\TestCase;

final class AuthorityAssignmentIdTest extends TestCase
{
    public function test_generate_produces_non_empty_id(): void
    {
        $id = AuthorityAssignmentId::generate();
        $this->assertNotEmpty($id->value());
    }

    public function test_two_generated_ids_are_different(): void
    {
        $a = AuthorityAssignmentId::generate();
        $b = AuthorityAssignmentId::generate();

        $this->assertFalse($a->equals($b));
    }

    public function test_from_constructs_with_known_value(): void
    {
        $id = AuthorityAssignmentId::from('assignment-uuid-123');
        $this->assertSame('assignment-uuid-123', $id->value());
    }

    public function test_from_empty_string_throws(): void
    {
        $this->expectException(\DomainException::class);
        AuthorityAssignmentId::from('');
    }

    public function test_equals_same_value(): void
    {
        $a = AuthorityAssignmentId::from('same-uuid');
        $b = AuthorityAssignmentId::from('same-uuid');

        $this->assertTrue($a->equals($b));
    }

    public function test_not_equals_different_value(): void
    {
        $a = AuthorityAssignmentId::from('uuid-aaa');
        $b = AuthorityAssignmentId::from('uuid-bbb');

        $this->assertFalse($a->equals($b));
    }

    public function test_has_private_constructor(): void
    {
        $reflection = new \ReflectionClass(AuthorityAssignmentId::class);
        $constructor = $reflection->getConstructor();

        $this->assertTrue($constructor->isPrivate());
    }
}
