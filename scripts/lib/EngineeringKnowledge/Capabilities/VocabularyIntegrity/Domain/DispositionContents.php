<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

/**
 * What the S5 reader OBSERVES in a document.
 *
 * The disposition side carries the observed disposition rows (each already classed
 * labelled/two-branch by the reader) and the observed split declarations. The declaration
 * side carries ONE document-level fact: does this document declare the canonical-document
 * rule (`policyDeclared` — a `## 0.4.4` heading AND the "no two competing current
 * definitions" statement)? Whether a competing unlabelled single-remedy row is a DEFECT is
 * the domain service's call under §0.4.4 / DP-3 — this is the observation, nothing more.
 *
 * This is the read side only. Nothing here authors, edits, or mints (DR-1 / AP-7).
 */
final readonly class DispositionContents
{
    /**
     * @param  list<DispositionRow>     $dispositionRows
     * @param  list<SplitDeclaration>   $splitDeclarations
     */
    private function __construct(
        private array $rows,
        private array $splits,
        private bool $policyDeclared,
    ) {
    }

    /**
     * @param  list<DispositionRow>     $rows
     * @param  list<SplitDeclaration>   $splits
     */
    public static function of(
        array $rows,
        array $splits,
        bool $policyDeclared,
    ): self {
        return new self($rows, $splits, $policyDeclared);
    }

    /** @return list<DispositionRow> */
    public function dispositionRows(): array
    {
        return $this->rows;
    }

    /** @return list<SplitDeclaration> */
    public function splitDeclarations(): array
    {
        return $this->splits;
    }

    public function policyDeclared(): bool
    {
        return $this->policyDeclared;
    }
}
