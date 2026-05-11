<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalScope;

final class ConstitutionalScopeTest extends TestCase
{
    public function test_national_scope_has_correct_value(): void
    {
        $this->assertSame('national', ConstitutionalScope::NATIONAL->value);
    }

    public function test_all_scope_cases_exist(): void
    {
        $cases = ConstitutionalScope::cases();
        $names = array_map(fn($c) => $c->name, $cases);

        $this->assertContains('NATIONAL', $names);
        $this->assertContains('REGIONAL', $names);
        $this->assertContains('EMERGENCY', $names);
        $this->assertContains('CARETAKER', $names);
        $this->assertContains('ELECTION', $names);
    }

    public function test_from_value_resolves_correctly(): void
    {
        $this->assertSame(ConstitutionalScope::REGIONAL, ConstitutionalScope::from('regional'));
        $this->assertSame(ConstitutionalScope::EMERGENCY, ConstitutionalScope::from('emergency'));
    }
}
