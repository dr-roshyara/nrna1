<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain;

/**
 * What the S4 reader OBSERVES in a document: the flat, line-numbered stream of table
 * rows. Grouping rows into TABLE BLOCKS and judging their consistency is the domain
 * service's call; the reader only reports what a line is.
 *
 * This is the read side only. Nothing here authors, edits, or mints (DR-1 / AP-7).
 */
final readonly class TableContents
{
    /**
     * @param  list<TableRow>  $rows  in document order, 1-based lines
     */
    private function __construct(
        private array $rows,
    ) {
    }

    /**
     * @param  list<TableRow>  $rows
     */
    public static function of(array $rows): self
    {
        return new self($rows);
    }

    /** @return list<TableRow> */
    public function rows(): array
    {
        return $this->rows;
    }
}
