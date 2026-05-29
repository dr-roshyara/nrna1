<?php

namespace Tests\Unit\Domain\Election\Replay;

use App\Domain\Election\Replay\ReplayCompatibilityVersion;
use PHPUnit\Framework\TestCase;

class ReplayCompatibilityVersionTest extends TestCase
{
    public function test_current_version_returns_consistent_value(): void
    {
        $v1 = ReplayCompatibilityVersion::current();
        $v2 = ReplayCompatibilityVersion::current();

        $this->assertTrue($v1->equals($v2));
        $this->assertSame('1.0', $v1->toString());
    }

    public function test_from_string_parses_major_minor(): void
    {
        $v = ReplayCompatibilityVersion::fromString('2.3');

        $this->assertSame('2.3', $v->toString());
    }

    public function test_matching_versions_are_equal(): void
    {
        $a = ReplayCompatibilityVersion::fromString('1.0');
        $b = ReplayCompatibilityVersion::fromString('1.0');

        $this->assertTrue($a->equals($b));
    }

    public function test_different_versions_are_not_equal(): void
    {
        $a = ReplayCompatibilityVersion::fromString('1.0');
        $b = ReplayCompatibilityVersion::fromString('2.0');

        $this->assertFalse($a->equals($b));
    }

    public function test_same_major_is_compatible(): void
    {
        $a = ReplayCompatibilityVersion::fromString('1.0');
        $b = ReplayCompatibilityVersion::fromString('1.5');

        $this->assertTrue($a->isCompatibleWith($b));
    }

    public function test_different_major_is_not_compatible(): void
    {
        $a = ReplayCompatibilityVersion::fromString('1.0');
        $b = ReplayCompatibilityVersion::fromString('2.0');

        $this->assertFalse($a->isCompatibleWith($b));
    }

    public function test_invalid_format_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ReplayCompatibilityVersion::fromString('invalid');
    }

    public function test_partial_format_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ReplayCompatibilityVersion::fromString('1');
    }

    /**
     * REPLAY CONTRACT: Same parsing input must produce identical version.
     * Deterministic construction invariant.
     */
    public function test_deterministic_construction(): void
    {
        $v1 = ReplayCompatibilityVersion::fromString('3.7');
        $v2 = ReplayCompatibilityVersion::fromString('3.7');

        $this->assertSame($v1->toString(), $v2->toString());
        $this->assertTrue($v1->equals($v2));
    }
}
