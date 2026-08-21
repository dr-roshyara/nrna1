<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain\DocumentSectionSequence;

/**
 * What the S2 reader OBSERVES in a document (S2).
 *
 * The heading register is CAP-001's `DocumentSectionSequence` — the same register the
 * S1 capability reads — so the §-side of a reference resolves against EXACTLY the
 * document-local register S1 validates (ES-005.4: consume the existing register notion,
 * never build a second one). To that register S2 adds the references and the mandated
 * block's step definitions the reader found.
 *
 * This is the read side only. Nothing here authors, edits, or mints (DR-1 / AP-7).
 */
final readonly class ReferenceIntegrityContents
{
    /**
     * @param  list<SectionReference>  $sectionReferences
     * @param  list<StepReference>     $stepReferences
     * @param  list<StepDefinition>    $stepDefinitions
     */
    private function __construct(
        private DocumentSectionSequence $headings,
        private array $sectionReferences,
        private array $stepReferences,
        private array $stepDefinitions,
    ) {
    }

    /**
     * @param  list<SectionReference>  $sectionReferences
     * @param  list<StepReference>     $stepReferences
     * @param  list<StepDefinition>    $stepDefinitions
     */
    public static function of(
        DocumentSectionSequence $headings,
        array $sectionReferences,
        array $stepReferences,
        array $stepDefinitions,
    ): self {
        return new self($headings, $sectionReferences, $stepReferences, $stepDefinitions);
    }

    public function headings(): DocumentSectionSequence
    {
        return $this->headings;
    }

    /** @return list<SectionReference> */
    public function sectionReferences(): array
    {
        return $this->sectionReferences;
    }

    /** @return list<StepReference> */
    public function stepReferences(): array
    {
        return $this->stepReferences;
    }

    /** @return list<StepDefinition> */
    public function stepDefinitions(): array
    {
        return $this->stepDefinitions;
    }
}
