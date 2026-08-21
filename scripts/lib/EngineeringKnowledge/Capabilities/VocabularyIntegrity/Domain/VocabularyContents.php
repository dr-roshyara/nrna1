<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

/**
 * What the S3 reader OBSERVES in a document.
 *
 * The vocabulary side carries the retired-term occurrences (each already classed
 * cited/live by the reader against the document's own citation constructs) and the
 * confusable identifiers. The declaration side carries ONE document-level fact: does
 * this document declare its confusable collision (`confusableCollisionDeclared`)?
 * Whether a live occurrence or an undeclared collision is a DEFECT is the domain
 * service's call under DP-3 — this is the observation, nothing more.
 *
 * This is the read side only. Nothing here authors, edits, or mints (DR-1 / AP-7).
 */
final readonly class VocabularyContents
{
    /**
     * @param  list<TermOccurrence>         $staleTermOccurrences
     * @param  list<ConfusableIdentifier>   $confusableIdentifiers
     */
    private function __construct(
        private array $staleTermOccurrences,
        private array $confusableIdentifiers,
        private bool $confusableCollisionDeclared,
    ) {
    }

    /**
     * @param  list<TermOccurrence>         $staleTermOccurrences
     * @param  list<ConfusableIdentifier>   $confusableIdentifiers
     */
    public static function of(
        array $staleTermOccurrences,
        array $confusableIdentifiers,
        bool $confusableCollisionDeclared,
    ): self {
        return new self($staleTermOccurrences, $confusableIdentifiers, $confusableCollisionDeclared);
    }

    /** @return list<TermOccurrence> */
    public function staleTermOccurrences(): array
    {
        return $this->staleTermOccurrences;
    }

    /** @return list<ConfusableIdentifier> */
    public function confusableIdentifiers(): array
    {
        return $this->confusableIdentifiers;
    }

    public function confusableCollisionDeclared(): bool
    {
        return $this->confusableCollisionDeclared;
    }
}
