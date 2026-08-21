<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionIdentifier;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * S1 — the document-local identifier value object.
 *
 * Extends CAP-001's register notion (DP-1: unique within its register(ns)) to the
 * register = THE DOCUMENT: numbered section headings are the identifiers. This VO
 * carries the number, the tuple it parses to, and the line it appears on — so an
 * assessment can state WHERE a defect is, not only what it is.
 *
 * Pure value object: parses, compares, and nothing else (PA: VOs remain pure).
 */
final class DocumentSectionIdentifierTest extends TestCase
{
    public function test_it_parses_a_number_and_keeps_its_line(): void
    {
        $id = DocumentSectionIdentifier::fromNumberLine('4.1', 349);

        self::assertSame('4.1', $id->number());
        self::assertSame(349, $id->line());
    }

    public function test_single_component_and_deep_numbers_parse(): void
    {
        self::assertSame('4', DocumentSectionIdentifier::fromNumberLine('4', 1)->number());
        self::assertSame('0.6.5', DocumentSectionIdentifier::fromNumberLine('0.6.5', 2)->number());
    }

    public function test_a_non_numeric_identifier_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        DocumentSectionIdentifier::fromNumberLine('CASE A', 1);
    }

    public function test_an_empty_number_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        DocumentSectionIdentifier::fromNumberLine('', 1);
    }

    /** Tuple lexicographic: 4.2 < 4.4 within the same parent. */
    public function test_it_orders_within_a_parent_section(): void
    {
        self::assertLessThan(0, DocumentSectionIdentifier::fromNumberLine('4.2', 1)->compareTo(
            DocumentSectionIdentifier::fromNumberLine('4.4', 1)
        ));
    }

    /** A parent is ordered before its children: 4 < 4.0 (prefix, shorter wins). */
    public function test_a_parent_precedes_its_children(): void
    {
        self::assertLessThan(0, DocumentSectionIdentifier::fromNumberLine('4', 1)->compareTo(
            DocumentSectionIdentifier::fromNumberLine('4.0', 1)
        ));
    }

    /** A child follows its parent: 4.1 > 4. */
    public function test_a_child_follows_its_parent(): void
    {
        self::assertGreaterThan(0, DocumentSectionIdentifier::fromNumberLine('4.1', 1)->compareTo(
            DocumentSectionIdentifier::fromNumberLine('4', 1)
        ));
    }

    /** A section boundary: 4.7 < 5. */
    public function test_a_section_precedes_the_next_top_level(): void
    {
        self::assertLessThan(0, DocumentSectionIdentifier::fromNumberLine('4.7', 1)->compareTo(
            DocumentSectionIdentifier::fromNumberLine('5', 1)
        ));
    }

    /** Decimal component is a LEVEL, not a fractional value: 4.10 follows 4.2 (4.2 < 4.10). */
    public function test_components_are_integer_levels_not_decimals(): void
    {
        self::assertLessThan(0, DocumentSectionIdentifier::fromNumberLine('4.2', 1)->compareTo(
            DocumentSectionIdentifier::fromNumberLine('4.10', 1)
        ));
    }

    /** Equality is by number string — the collision identity. */
    public function test_equality_is_by_number_string(): void
    {
        self::assertTrue(
            DocumentSectionIdentifier::fromNumberLine('4.1', 349)->equals(
                DocumentSectionIdentifier::fromNumberLine('4.1', 436)
            )
        );
        self::assertFalse(
            DocumentSectionIdentifier::fromNumberLine('4.1', 349)->equals(
                DocumentSectionIdentifier::fromNumberLine('4.2', 349)
            )
        );
    }
}
