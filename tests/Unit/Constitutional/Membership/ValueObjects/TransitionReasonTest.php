<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\ValueObjects;

use App\Contexts\Membership\Domain\Membership\ValueObjects\TransitionReason;
use PHPUnit\Framework\TestCase;

final class TransitionReasonTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_empty_string(): void
    {
        $this->expectException(\DomainException::class);
        TransitionReason::fromString('');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_whitespace_only(): void
    {
        $this->expectException(\DomainException::class);
        TransitionReason::fromString('   ');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_accepts_valid_reason(): void
    {
        $reason = TransitionReason::fromString('Member resigned from committee');
        $this->assertSame('Member resigned from committee', $reason->value());
    }
}
