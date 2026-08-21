<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain;

use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;

/**
 * The domain service that applies DP-4 to a document's intra-document references (S2).
 *
 * DP-4 — the catalogue's policy this service executes, stated once, never owned here
 * (PA: "Capabilities must execute policies. Capabilities must never own policies."):
 *
 *   "A reference shall resolve to an existing target, or be classified as evidence —
 *    never repaired on a guess."
 *
 * The two reference families the corpus actually uses:
 *   1. §-references (`§4.1`, `§18`)   — resolved against the document's OWN numbered
 *      heading register (CAP-001's `DocumentSectionSequence`). A `§N` with exactly one
 *      matching heading is RESOLVED; with two or more, AMBIGUOUS (FAIL — the AMD4
 *      duplicate `4.1`/`4.2` case); with none, CLASSIFIED as cross-document evidence
 *      (NOT-CHECKED — the AMD5/AMD6 `its §13` traceability case). That zero-match branch
 *      is DP-4's own escape hatch, not a new policy.
 *   2. step-references (`step 5`, `steps 3→5`) — resolved against the mandated
 *      internal-order block's step register. A step the block does not define is a
 *      MISSING reference (FAIL — the AMD5 `step 5` dangling case, DI-5).
 *
 * Verdict mapping, each traced:
 *   a defect (ambiguous § or missing step)      → FAIL
 *   at least one reference resolves, none defect → PASS
 *   no references at all                         → INCONCLUSIVE (D-2: nothing to evaluate;
 *                                                     absence of evidence is never PASS)
 *   every reference classified, none resolves    → INCONCLUSIVE (same D-2 branch — the
 *                                                     checker validated nothing
 *                                                     intra-documentally)
 *
 * ⛔ This service owns EXECUTION only — it creates no reference, no target, no policy.
 */
final readonly class AssessesIntraDocumentReferences
{
    public const POLICY = ReferencePolicy::STATEMENT;

    public const GOVERNING_RULE = ReferencePolicy::GOVERNING_RULE;

    public function validate(ReferenceIntegrityContents $contents, string $subject): Assessment
    {
        if ($contents->sectionReferences() === [] && $contents->stepReferences() === []) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Intra-document references in '%s' (DP-4): no § or step references "
                    .'found; nothing to evaluate. Absence of evidence is not PASS.',
                    $subject,
                ),
                $subject,
            );
        }

        $defects = [
            ...$this->sectionAmbiguities($contents),
            ...$this->danglingSteps($contents),
        ];

        if ($defects !== []) {
            return Assessment::of(
                Verdict::FAIL,
                sprintf(
                    "Intra-document references in '%s' violate DP-4: %s.",
                    $subject,
                    implode('; ', $defects),
                ),
                $subject,
            );
        }

        [$resolvedSections, $classifiedSections] = $this->sectionResolution($contents);
        $resolvedSteps = $this->resolvedSteps($contents);

        if ($resolvedSections === 0 && $resolvedSteps === 0) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Intra-document references in '%s' (DP-4): all %d § reference(s) "
                    .'classified as cross-document evidence (not checked); no step '
                    .'reference resolves within the document. Absence of evidence is not PASS.',
                    $subject,
                    count($contents->sectionReferences()),
                ),
                $subject,
            );
        }

        return Assessment::of(
            Verdict::PASS,
            sprintf(
                "Intra-document references in '%s' (DP-4): %d § reference(s) resolved; "
                .'%d § reference(s) classified as cross-document evidence (not checked); '
                .'%d step reference(s) resolve against the mandated block.',
                $subject,
                $resolvedSections,
                $classifiedSections,
                $resolvedSteps,
            ),
            $subject,
        );
    }

    /**
     * A `§N` reference is AMBIGUOUS when the document names `N` more than once (the
     * AMD4 `0a2fa71d` duplicate-heading shape). One defect per ambiguous NUMBER, not per
     * reference, mirroring the S1 collision-reporting style.
     *
     * @return list<string>
     */
    private function sectionAmbiguities(ReferenceIntegrityContents $contents): array
    {
        $headingLinesByNumber = $this->headingLinesByNumber($contents);
        $referenceLinesByNumber = [];

        foreach ($contents->sectionReferences() as $ref) {
            $referenceLinesByNumber[$ref->number()][] = $ref->line();
        }

        $out = [];

        foreach ($referenceLinesByNumber as $number => $referenceLines) {
            $headingLines = $headingLinesByNumber[$number] ?? [];

            if (count($headingLines) > 1) {
                $out[] = sprintf(
                    "ambiguous section reference '§%s' (line %s) — the document names "
                    ."'%s' at lines %s",
                    $number,
                    implode(', ', $referenceLines),
                    $number,
                    implode(', ', $headingLines),
                );
            }
        }

        return $out;
    }

    /**
     * A `step N` reference is MISSING when the mandated block does not define `N` (the
     * AMD5 `7d3abc59` dangling-`step 5` shape, DI-5). One defect per missing STEP, with
     * every reference line to it. The evidence names the register so a reader sees why
     * `step 5` dangles when the block defines `1 · 2 · 3 · 4`.
     *
     * @return list<string>
     */
    private function danglingSteps(ReferenceIntegrityContents $contents): array
    {
        $register = [];

        foreach ($contents->stepDefinitions() as $definition) {
            $register[$definition->step()] = true;
        }

        $referenceLinesByStep = [];

        foreach ($contents->stepReferences() as $ref) {
            $referenceLinesByStep[$ref->step()][] = $ref->line();
        }

        $out = [];

        foreach ($referenceLinesByStep as $step => $lines) {
            if (!isset($register[$step])) {
                $out[] = sprintf(
                    "dangling step reference 'step %s' (line %s) — the mandated block defines %s",
                    $step,
                    implode(', ', $lines),
                    $this->registerDescription($contents),
                );
            }
        }

        return $out;
    }

    /**
     * @return array<string, int>  the number of references resolving to exactly one
     *                             heading, and the number classified as evidence
     */
    private function sectionResolution(ReferenceIntegrityContents $contents): array
    {
        $headingLinesByNumber = $this->headingLinesByNumber($contents);

        $resolved = 0;
        $classified = 0;

        foreach ($contents->sectionReferences() as $ref) {
            $matches = $headingLinesByNumber[$ref->number()] ?? [];

            if (count($matches) === 1) {
                $resolved++;
            } elseif ($matches === []) {
                $classified++;
            }
        }

        return [$resolved, $classified];
    }

    private function resolvedSteps(ReferenceIntegrityContents $contents): int
    {
        $register = [];

        foreach ($contents->stepDefinitions() as $definition) {
            $register[$definition->step()] = true;
        }

        $resolved = 0;

        foreach ($contents->stepReferences() as $ref) {
            if (isset($register[$ref->step()])) {
                $resolved++;
            }
        }

        return $resolved;
    }

    /** @return array<string, list<int>> */
    private function headingLinesByNumber(ReferenceIntegrityContents $contents): array
    {
        $byNumber = [];

        foreach ($contents->headings()->identifiers() as $id) {
            $byNumber[$id->number()][] = $id->line();
        }

        return $byNumber;
    }

    /** The register in document order, e.g. `1 · 2 · 3 · 4`. */
    private function registerDescription(ReferenceIntegrityContents $contents): string
    {
        $steps = [];

        foreach ($contents->stepDefinitions() as $definition) {
            $steps[$definition->step()] = true;
        }

        if ($steps === []) {
            return 'nothing — no mandated internal-order block found';
        }

        return implode(' · ', array_keys($steps));
    }
}
