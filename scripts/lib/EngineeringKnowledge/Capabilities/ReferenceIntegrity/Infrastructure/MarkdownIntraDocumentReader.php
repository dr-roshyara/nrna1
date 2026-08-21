<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\ReferenceIntegrity\Infrastructure;

use EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure\MarkdownDocumentContentsReader;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Application\IntraDocumentContentsReader;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\ReferenceIntegrityContents;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\SectionReference;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\StepDefinition;
use EngineeringKnowledge\Capabilities\ReferenceIntegrity\Domain\StepReference;
use RuntimeException;

/**
 * Markdown adapter for the intra-document reference register (S2).
 *
 * Reads the DOCUMENT ITSELF (DR-1 / AP-2: never a projection). Read-only (AP-7 / DR-4).
 *
 * Parsing rules, each pinned to the corpus:
 *   • The heading register is REUSED from CAP-001 (`MarkdownDocumentContentsReader`),
 *     so the §-side resolves against exactly the register S1 validates (ES-005.4 —
 *     consume the existing capability, never build a second reader).
 *   • §-references (`§4.1`, `§18`, `§3.2/§4.1`) are extracted from any non-fence line,
 *     INCLUDING blockquotes — the corpus cites sections inside quoted note blocks.
 *   • Step-references (`step 5`, `Phase 5 step 5`, `steps 3→5`, incl. blockquotes) are
 *     extracted from non-fence lines. AMD5's DI-5 references live in a table row and two
 *     blockquotes — skipping blockquotes would MISS the defect.
 *   • Step DEFINITIONS come only from numeric-leading lines inside fenced code blocks
 *     (`^\s*(\d+[a-z]?)\s{2,}\S`) — the "Phase 5, mandated internal order" block. Quoted
 *     lines can never be definitions (they start with `>`, so `^\s*\d` cannot match).
 *   • Line numbers are 1-based and NEL-safe: `preg_split('/\r\n|\r|\n/')` — `\R` would
 *     treat U+0085 (the corpus's in-line separator) as a line break and inflate numbers.
 *
 * NOT-CHECKED (D-4, stated so the report does not overclaim): bracketed references
 * (`[§] 0.4`) and spaced forms are not extracted; a `step N.N` decimal is never a step.
 */
final readonly class MarkdownIntraDocumentReader implements IntraDocumentContentsReader
{
    public function __construct(
        private MarkdownDocumentContentsReader $headingReader = new MarkdownDocumentContentsReader(),
    ) {
    }

    public function read(string $path): ReferenceIntegrityContents
    {
        if (! is_readable($path)) {
            throw new RuntimeException("document not readable at {$path}");
        }

        $markdown = file_get_contents($path);

        if ($markdown === false) {
            throw new RuntimeException("document could not be read at {$path}");
        }

        $lines = preg_split('/\r\n|\r|\n/', $markdown);

        if ($lines === false) {
            throw new RuntimeException("Unable to split document '{$path}'.");
        }

        $sectionReferences = [];
        $stepReferences = [];
        $stepDefinitions = [];
        $inFence = false;

        foreach ($lines as $index => $line) {
            $number = $index + 1;

            if (str_starts_with(ltrim($line), '```')) {
                $inFence = !$inFence;
                continue;
            }

            if ($inFence) {
                array_push($stepDefinitions, ...self::stepDefinitionsFrom($line, $number));
                continue;
            }

            array_push($sectionReferences, ...self::sectionReferencesFrom($line, $number));
            array_push($stepReferences, ...self::stepReferencesFrom($line, $number));
        }

        return ReferenceIntegrityContents::of(
            $this->headingReader->read($path),
            $sectionReferences,
            $stepReferences,
            $stepDefinitions,
        );
    }

    /** @return list<SectionReference> */
    private static function sectionReferencesFrom(string $line, int $number): array
    {
        $refs = [];

        if (preg_match_all('/§(\d+(?:\.\d+)*)/', $line, $matches) !== false) {
            foreach ($matches[1] as $referencedNumber) {
                $refs[] = SectionReference::fromNumberLine($referencedNumber, $number);
            }
        }

        return $refs;
    }

    /**
     * Matches `step 5`, `steps 3→5`, `Phase 5 step 5`, `step 1b` — a step is
     * `\d+[a-z]?`, never a decimal (`(?!\.\d)` keeps `step 4.1` unmatched). A range
     * (`steps 3→5`) yields BOTH endpoints as references; both must be defined.
     *
     * @return list<StepReference>
     */
    private static function stepReferencesFrom(string $line, int $number): array
    {
        $refs = [];

        $matched = preg_match_all(
            '/\b(?:steps|step)\s+(\d+[a-z]?)(?!\.\d)(?:\s*(?:→|->)\s*(\d+[a-z]?)(?!\.\d))?/i',
            $line,
            $matches,
        );

        if ($matched === false) {
            return $refs;
        }

        foreach ($matches[1] as $i => $step) {
            $refs[] = StepReference::fromStepLine($step, $number);

            if (isset($matches[2][$i]) && $matches[2][$i] !== '') {
                $refs[] = StepReference::fromStepLine($matches[2][$i], $number);
            }
        }

        return $refs;
    }

    /** @return list<StepDefinition> */
    private static function stepDefinitionsFrom(string $line, int $number): array
    {
        if (preg_match('/^\s*(\d+[a-z]?)\s{2,}\S/', $line, $matches) === 1) {
            return [StepDefinition::fromStepLine($matches[1], $number)];
        }

        return [];
    }
}
