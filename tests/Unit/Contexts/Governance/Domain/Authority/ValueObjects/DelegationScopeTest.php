<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority\ValueObjects;

use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationScope;
use PHPUnit\Framework\TestCase;

final class DelegationScopeTest extends TestCase
{
    public function test_constructs_from_non_empty_string(): void
    {
        $scope = DelegationScope::from('COMMITTEE_FORMATION');
        $this->assertSame('COMMITTEE_FORMATION', $scope->value());
    }

    public function test_empty_string_throws_domain_exception(): void
    {
        $this->expectException(\DomainException::class);
        DelegationScope::from('');
    }

    public function test_whitespace_only_string_throws_domain_exception(): void
    {
        $this->expectException(\DomainException::class);
        DelegationScope::from('   ');
    }

    public function test_equals_same_scope(): void
    {
        $a = DelegationScope::from('FINANCIAL_AUTHORITY');
        $b = DelegationScope::from('FINANCIAL_AUTHORITY');

        $this->assertTrue($a->equals($b));
    }

    public function test_not_equals_different_scope(): void
    {
        $a = DelegationScope::from('FINANCIAL_AUTHORITY');
        $b = DelegationScope::from('COMMITTEE_FORMATION');

        $this->assertFalse($a->equals($b));
    }

    public function test_value_is_trimmed(): void
    {
        $scope = DelegationScope::from('  FULL_AUTHORITY  ');
        $this->assertSame('FULL_AUTHORITY', $scope->value());
    }

    public function test_is_immutable(): void
    {
        $reflection = new \ReflectionClass(DelegationScope::class);
        $this->assertTrue($reflection->isReadOnly(), 'DelegationScope must be readonly class');
    }
}
