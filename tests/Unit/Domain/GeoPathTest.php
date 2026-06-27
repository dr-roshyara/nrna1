<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use PHPUnit\Framework\TestCase;

final class GeoPathTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function constructor_accepts_positive_integers(): void
    {
        $path = new GeoPath([1, 2, 3]);

        $this->assertSame([1, 2, 3], $path->toArray());
        $this->assertSame(3, $path->depth());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function constructor_rejects_zero_and_negative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GeoPath units must be positive integers');

        new GeoPath([1, 0, 3]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function constructor_rejects_negative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GeoPath units must be positive integers');

        new GeoPath([1, -2, 3]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function fromArray_filters_invalid_values(): void
    {
        $path = GeoPath::fromArray([1, 0, 2, -1, 3, null]);

        // Should filter out 0, -1, null and keep only positive integers
        $this->assertSame([1, 2, 3], $path->toArray());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function depth_returns_count(): void
    {
        $path1 = new GeoPath([1, 2, 3, 4]);
        $path2 = GeoPath::empty();

        $this->assertSame(4, $path1->depth());
        $this->assertSame(0, $path2->depth());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function toString_joins_with_dot(): void
    {
        $path = new GeoPath([3, 15, 234]);

        $this->assertSame('3.15.234', $path->toString());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function empty_creates_zero_depth_path(): void
    {
        $path = GeoPath::empty();

        $this->assertSame(0, $path->depth());
        $this->assertSame([], $path->toArray());
        $this->assertTrue($path->isEmpty());
    }
}
