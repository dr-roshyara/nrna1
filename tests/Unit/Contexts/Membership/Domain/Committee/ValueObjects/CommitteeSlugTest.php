<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\Exceptions\InvalidCommitteeSlugException;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeSlug;
use PHPUnit\Framework\TestCase;

final class CommitteeSlugTest extends TestCase
{
    public function test_fromString_normalizes_input(): void
    {
        $slug = CommitteeSlug::fromString('NRNA ICC');
        $this->assertSame('nrna-icc', $slug->value());
    }

    public function test_fromString_removes_special_characters(): void
    {
        $slug = CommitteeSlug::fromString('NRNA-Österreich (2026)');
        $this->assertSame('nrna-osterreich-2026', $slug->value());
    }

    public function test_fromString_collapses_multiple_hyphens(): void
    {
        $slug = CommitteeSlug::fromString('test---multiple---hyphens');
        $this->assertSame('test-multiple-hyphens', $slug->value());
    }

    public function test_fromString_trims_leading_trailing_hyphens(): void
    {
        $slug = CommitteeSlug::fromString('  ---test-slug---  ');
        $this->assertSame('test-slug', $slug->value());
    }

    public function test_fromString_throws_on_empty(): void
    {
        $this->expectException(InvalidCommitteeSlugException::class);
        CommitteeSlug::fromString('   ');
    }

    public function test_fromString_throws_on_reserved_admin(): void
    {
        $this->expectException(InvalidCommitteeSlugException::class);
        CommitteeSlug::fromString('admin');
    }

    public function test_fromString_throws_on_reserved_api(): void
    {
        $this->expectException(InvalidCommitteeSlugException::class);
        CommitteeSlug::fromString('api');
    }

    public function test_fromString_throws_on_reserved_dashboard(): void
    {
        $this->expectException(InvalidCommitteeSlugException::class);
        CommitteeSlug::fromString('dashboard');
    }

    public function test_fromString_throws_on_reserved_settings(): void
    {
        $this->expectException(InvalidCommitteeSlugException::class);
        CommitteeSlug::fromString('settings');
    }

    public function test_fromName_uses_slug_helper(): void
    {
        $slug = CommitteeSlug::fromName('NRNA ICC');
        $this->assertSame('nrna-icc', $slug->value());
    }

    public function test_toString_returns_value(): void
    {
        $slug = CommitteeSlug::fromString('test-slug');
        $this->assertSame('test-slug', (string) $slug);
    }

    public function test_value_returns_normalized_string(): void
    {
        $slug = CommitteeSlug::fromString('Test Slug');
        $this->assertSame('test-slug', $slug->value());
    }

    public function test_slug_is_immutable(): void
    {
        $slug1 = CommitteeSlug::fromString('test');
        $slug2 = CommitteeSlug::fromString('test');
        $this->assertSame($slug1->value(), $slug2->value());
    }
}
