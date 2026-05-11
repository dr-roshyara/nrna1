<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority\ValueObjects;

use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationType;
use PHPUnit\Framework\TestCase;

final class DelegationTypeTest extends TestCase
{
    public function test_priority_order_authority_overrides_override_overrides_temporary(): void
    {
        $this->assertGreaterThan(DelegationType::OVERRIDE->priority(), DelegationType::AUTHORITY->priority());
        $this->assertGreaterThan(DelegationType::TEMPORARY->priority(), DelegationType::OVERRIDE->priority());
    }

    public function test_priority_values_are_canonical(): void
    {
        $this->assertSame(80, DelegationType::AUTHORITY->priority());
        $this->assertSame(70, DelegationType::OVERRIDE->priority());
        $this->assertSame(60, DelegationType::TEMPORARY->priority());
    }

    public function test_has_exactly_three_cases_in_correct_order(): void
    {
        $this->assertSame(
            ['AUTHORITY', 'OVERRIDE', 'TEMPORARY'],
            array_map(fn($c) => $c->name, DelegationType::cases())
        );
    }

    public function test_is_string_backed_with_correct_values(): void
    {
        $this->assertSame('AUTHORITY', DelegationType::AUTHORITY->value);
        $this->assertSame('OVERRIDE', DelegationType::OVERRIDE->value);
        $this->assertSame('TEMPORARY', DelegationType::TEMPORARY->value);
    }

    public function test_can_be_constructed_from_string_value(): void
    {
        $this->assertSame(DelegationType::AUTHORITY, DelegationType::from('AUTHORITY'));
        $this->assertSame(DelegationType::OVERRIDE, DelegationType::from('OVERRIDE'));
        $this->assertSame(DelegationType::TEMPORARY, DelegationType::from('TEMPORARY'));
    }

    public function test_priority_is_used_for_resolution_ordering(): void
    {
        $types = DelegationType::cases();
        usort($types, fn($a, $b) => $b->priority() <=> $a->priority());

        $this->assertSame(DelegationType::AUTHORITY, $types[0]);
        $this->assertSame(DelegationType::OVERRIDE, $types[1]);
        $this->assertSame(DelegationType::TEMPORARY, $types[2]);
    }
}
