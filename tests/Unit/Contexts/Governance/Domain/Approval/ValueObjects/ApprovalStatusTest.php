<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Approval\ValueObjects;

use App\Contexts\Governance\Domain\Approval\ValueObjects\ApprovalStatus;
use PHPUnit\Framework\TestCase;

final class ApprovalStatusTest extends TestCase
{
    public function test_has_four_cases_in_correct_order(): void
    {
        $names = array_map(fn($c) => $c->name, ApprovalStatus::cases());
        $this->assertSame(['PENDING', 'APPROVED', 'REJECTED', 'EXPIRED'], $names);
    }

    public function test_is_string_backed_with_correct_values(): void
    {
        $this->assertSame('PENDING', ApprovalStatus::PENDING->value);
        $this->assertSame('APPROVED', ApprovalStatus::APPROVED->value);
        $this->assertSame('REJECTED', ApprovalStatus::REJECTED->value);
        $this->assertSame('EXPIRED', ApprovalStatus::EXPIRED->value);
    }

    public function test_can_be_constructed_from_string_value(): void
    {
        $this->assertSame(ApprovalStatus::PENDING, ApprovalStatus::from('PENDING'));
        $this->assertSame(ApprovalStatus::APPROVED, ApprovalStatus::from('APPROVED'));
        $this->assertSame(ApprovalStatus::REJECTED, ApprovalStatus::from('REJECTED'));
        $this->assertSame(ApprovalStatus::EXPIRED, ApprovalStatus::from('EXPIRED'));
    }

    public function test_invalid_value_throws(): void
    {
        $this->expectException(\ValueError::class);
        ApprovalStatus::from('INVALID');
    }

    public function test_is_terminal_returns_false_for_pending(): void
    {
        $this->assertFalse(ApprovalStatus::PENDING->isTerminal());
    }

    public function test_is_terminal_returns_true_for_approved(): void
    {
        $this->assertTrue(ApprovalStatus::APPROVED->isTerminal());
    }

    public function test_is_terminal_returns_true_for_rejected(): void
    {
        $this->assertTrue(ApprovalStatus::REJECTED->isTerminal());
    }

    public function test_is_terminal_returns_true_for_expired(): void
    {
        $this->assertTrue(ApprovalStatus::EXPIRED->isTerminal());
    }
}
