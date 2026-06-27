<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\ValueObjects;

use App\Contexts\Shared\Domain\ValueObjects\ActorId;
use PHPUnit\Framework\TestCase;

final class ActorIdTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_empty_string(): void
    {
        $this->expectException(\DomainException::class);
        ActorId::fromString('');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_whitespace_only(): void
    {
        $this->expectException(\DomainException::class);
        ActorId::fromString('   ');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_accepts_valid_actor_id(): void
    {
        $actorId = ActorId::fromString('actor-uuid-001');
        $this->assertSame('actor-uuid-001', $actorId->value());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_is_equal_when_values_match(): void
    {
        $actorId1 = ActorId::fromString('actor-uuid-001');
        $actorId2 = ActorId::fromString('actor-uuid-001');
        $this->assertTrue($actorId1->equals($actorId2));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_is_not_equal_when_values_differ(): void
    {
        $actorId1 = ActorId::fromString('actor-uuid-001');
        $actorId2 = ActorId::fromString('actor-uuid-002');
        $this->assertFalse($actorId1->equals($actorId2));
    }
}
