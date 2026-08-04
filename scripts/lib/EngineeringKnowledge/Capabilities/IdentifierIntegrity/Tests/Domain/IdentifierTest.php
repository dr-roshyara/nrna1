<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\Identifier;
use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\IdentifierSeries;
use PHPUnit\Framework\TestCase;

/**
 * CAP-001 pure value objects.
 *
 * M4 §1.1  identity is per-kind; the register(ns) is the namespace unit
 * M4 §1.3  semantic identity != representational identity
 * M6 R-M6-8  `register` has three senses; this capability means register(ns) only,
 *            which is why the type is named IdentifierSeries, not Register
 */
final class IdentifierTest extends TestCase
{
    public function test_identifier_exposes_its_series_and_value(): void
    {
        $id = Identifier::fromString('R-65');

        self::assertSame('R', $id->series()->prefix());
        self::assertSame('R-65', $id->value());
        self::assertSame('65', $id->ordinal());
    }

    public function test_series_prefix_may_be_multi_character(): void
    {
        self::assertSame('ES', Identifier::fromString('ES-005')->series()->prefix());
        self::assertSame('PMR', Identifier::fromString('PMR-10')->series()->prefix());
        self::assertSame('CAP', Identifier::fromString('CAP-001')->series()->prefix());
    }

    public function test_ordinal_may_be_alphanumeric(): void
    {
        self::assertSame('T22', Identifier::fromString('ADR-T22')->ordinal());
    }

    public function test_two_identifiers_with_the_same_value_are_equal(): void
    {
        self::assertTrue(Identifier::fromString('R-65')->equals(Identifier::fromString('R-65')));
    }

    /** The cross-kind case: same ordinal, different register(ns) — NOT equal. */
    public function test_same_ordinal_in_different_series_are_not_equal(): void
    {
        self::assertFalse(Identifier::fromString('R-7')->equals(Identifier::fromString('ES-7')));
    }

    public function test_surrounding_whitespace_is_ignored(): void
    {
        self::assertTrue(Identifier::fromString('  R-65  ')->equals(Identifier::fromString('R-65')));
    }

    public function test_malformed_identifier_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Identifier::fromString('not an identifier');
    }

    public function test_identifier_without_an_ordinal_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Identifier::fromString('R-');
    }

    public function test_empty_identifier_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Identifier::fromString('');
    }

    public function test_series_equality_is_by_prefix(): void
    {
        self::assertTrue((new IdentifierSeries('R'))->equals(new IdentifierSeries('R')));
        self::assertFalse((new IdentifierSeries('R'))->equals(new IdentifierSeries('ES')));
    }

    public function test_malformed_series_prefix_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new IdentifierSeries('R-1');
    }
}
