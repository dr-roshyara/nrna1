<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\ValueObjects\TemporalGovernanceState;
use PHPUnit\Framework\TestCase;

final class TemporalGovernanceStateTest extends TestCase
{
    public function test_has_six_cases(): void
    {
        $cases = TemporalGovernanceState::cases();
        $this->assertCount(6, $cases);
    }

    public function test_is_string_backed(): void
    {
        $this->assertSame('VALID', TemporalGovernanceState::VALID->value);
        $this->assertSame('EXPIRING', TemporalGovernanceState::EXPIRING->value);
        $this->assertSame('EXPIRED', TemporalGovernanceState::EXPIRED->value);
        $this->assertSame('CARETAKER', TemporalGovernanceState::CARETAKER->value);
        $this->assertSame('NO_TERM', TemporalGovernanceState::NO_TERM->value);
        $this->assertSame('NOT_YET_ACTIVE', TemporalGovernanceState::NOT_YET_ACTIVE->value);
    }

    public function test_can_be_constructed_from_string_value(): void
    {
        $this->assertSame(TemporalGovernanceState::VALID, TemporalGovernanceState::from('VALID'));
        $this->assertSame(TemporalGovernanceState::CARETAKER, TemporalGovernanceState::from('CARETAKER'));
        $this->assertSame(TemporalGovernanceState::NO_TERM, TemporalGovernanceState::from('NO_TERM'));
        $this->assertSame(TemporalGovernanceState::NOT_YET_ACTIVE, TemporalGovernanceState::from('NOT_YET_ACTIVE'));
    }

    public function test_invalid_value_throws(): void
    {
        $this->expectException(\ValueError::class);
        TemporalGovernanceState::from('UNKNOWN');
    }
}
