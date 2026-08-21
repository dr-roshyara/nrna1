<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain;

/**
 * What a document-local register currently contains — the section identifiers, in
 * document order, supplied from outside.
 *
 * S1's analog of {@see SeriesContents}: where SeriesContents is what a register(ns)
 * holds across files, this is what ONE document holds, as its numbered headings.
 * Same discipline as SeriesContents — READ-ONLY, immutable, an OBSERVATION of an
 * existing document, never a registry of our own (AP-4).
 */
final readonly class DocumentSectionSequence
{
    /** @var list<DocumentSectionIdentifier> in document order */
    private array $identifiers;

    /** @param list<DocumentSectionIdentifier> $identifiers */
    private function __construct(array $identifiers)
    {
        $this->identifiers = array_values($identifiers);
    }

    /** @param list<DocumentSectionIdentifier> $identifiers */
    public static function fromIdentifiers(array $identifiers): self
    {
        return new self($identifiers);
    }

    /** @return list<DocumentSectionIdentifier> */
    public function identifiers(): array
    {
        return $this->identifiers;
    }

    public function isEmpty(): bool
    {
        return $this->identifiers === [];
    }

    public function count(): int
    {
        return count($this->identifiers);
    }
}
