<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;

/**
 * The domain service that applies DP-3 to a document's vocabulary (S3).
 *
 * DP-3 — the catalogue's policy this service executes, stated once, never owned here
 * (PA: "Capabilities must execute policies. Capabilities must never own policies."):
 *
 *   "A governed term shall carry one meaning per context; where overloaded, it shall
 *    be qualified."
 *
 * Both S3 checks are ONE discriminator — LIVE usage without qualification is a defect,
 * cited or declared usage is not:
 *
 *   1. RETIRED TERMS (DI-2, `Phase 2b` → `2c-commit`): a LIVE occurrence is an
 *      unqualified overload of the past against the present (AMD4's §5 sentence and
 *      §10 criterion 1). A CITED occurrence is qualified — quoted superseded wording,
 *      a `§4 Phase 2b` recap label, or a `Traceability (` block (AMD5/AMD6, all cited).
 *   2. CONFUSABLE IDENTIFIERS (DI-7, `CASE B` vs `CASE β`): a glyph whose homoglyph
 *      collides with another identifier in the same family is a defect when the
 *      collision is NOT declared. A document carrying its own collision finding
 *      (`DI-7` — the declaration the reader observed) has QUALIFIED the overload and
 *      is not defective (AMD6's quiet row; AMD4 has no Greek glyph, so no pair).
 *
 * Verdict mapping, each traced:
 *   a live retired term, or an undeclared confusable collision → FAIL
 *   occurrences exist, none is an unqualified overload          → PASS
 *   no vocabulary at all                                        → INCONCLUSIVE
 *                                                    (D-2: nothing to evaluate; absence
 *                                                     of evidence is never PASS)
 *
 * ⛔ This service owns EXECUTION only — it creates no term, no identifier, no policy.
 */
final readonly class AssessesVocabularyIntegrity
{
    public const POLICY = VocabularyPolicy::STATEMENT;

    public const GOVERNING_RULE = VocabularyPolicy::GOVERNING_RULE;

    public function validate(VocabularyContents $contents, string $subject): Assessment
    {
        $stale = $contents->staleTermOccurrences();
        $identifiers = $contents->confusableIdentifiers();

        if ($stale === [] && $identifiers === []) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Vocabulary integrity of '%s' (DP-3): no retired-term occurrence and "
                    .'no confusable identifier found; nothing to evaluate. Absence of '
                    .'evidence is not PASS.',
                    $subject,
                ),
                $subject,
            );
        }

        $defects = [
            ...$this->liveStaleTerms($stale),
            ...$this->undeclaredCollisions($contents),
        ];

        if ($defects !== []) {
            return Assessment::of(
                Verdict::FAIL,
                sprintf(
                    "Vocabulary integrity of '%s' violates DP-3: %s.",
                    $subject,
                    implode('; ', $defects),
                ),
                $subject,
            );
        }

        return Assessment::of(
            Verdict::PASS,
            sprintf(
                "Vocabulary integrity of '%s' (DP-3): %d retired-term occurrence(s), %d "
                .'cited and %d live; %d confusable identifier(s)%s. No unqualified homonym.',
                $subject,
                count($stale),
                count($stale) - $this->liveCount($stale),
                $this->liveCount($stale),
                count($identifiers),
                $this->collisionDisposition($contents),
            ),
            $subject,
        );
    }

    /**
     * A LIVE retired term is an unqualified overload (DI-2). One defect per occurrence,
     * naming the term and its line so an author can find it.
     *
     * @param  list<TermOccurrence>  $stale
     * @return list<string>
     */
    private function liveStaleTerms(array $stale): array
    {
        $defects = [];

        foreach ($stale as $occurrence) {
            if (! $occurrence->cited()) {
                $defects[] = sprintf(
                    "retired term '%s' used live without qualification (line %d)",
                    $occurrence->term(),
                    $occurrence->line(),
                );
            }
        }

        return $defects;
    }

    /**
     * A confusable collision is a defect only while UNDECLARED (DI-7). One defect per
     * colliding PAIR, naming both glyphs and every line each appears on.
     *
     * @return list<string>
     */
    private function undeclaredCollisions(VocabularyContents $contents): array
    {
        $pairs = $this->collidingPairs($contents->confusableIdentifiers());

        if ($pairs === [] || $contents->confusableCollisionDeclared()) {
            return [];
        }

        return array_map(
            fn (array $pair): string => $this->collisionDescription($pair),
            $pairs,
        );
    }

    /**
     * The glyphs of each family, in pairs: two DISTINCT glyphs whose shapes collide
     * (β↔B via the HomoglyphMap) form a confusable pair. One pair per glyph-pair, with
     * every line of both members.
     *
     * @param  list<ConfusableIdentifier>  $identifiers
     * @return list<array{family: string, first: string, firstLines: list<int>, second: string, secondLines: list<int>}>
     */
    private function collidingPairs(array $identifiers): array
    {
        $byFamily = [];

        foreach ($identifiers as $identifier) {
            $byFamily[$identifier->family()][] = $identifier;
        }

        $pairs = [];

        foreach ($byFamily as $family => $members) {
            $linesByGlyph = [];

            foreach ($members as $member) {
                $linesByGlyph[$member->glyph()][] = $member->line();
            }

            $glyphs = array_keys($linesByGlyph);

            foreach ($glyphs as $index => $first) {
                foreach (array_slice($glyphs, $index + 1) as $second) {
                    if (HomoglyphMap::collides($first, $second)) {
                        $pairs[] = [
                            'family' => $family,
                            'first' => $first,
                            'firstLines' => $this->uniqueLines($linesByGlyph[$first]),
                            'second' => $second,
                            'secondLines' => $this->uniqueLines($linesByGlyph[$second]),
                        ];
                    }
                }
            }
        }

        return $pairs;
    }

    /** @param  list<int>  $lines */
    private function uniqueLines(array $lines): array
    {
        return array_values(array_unique($lines));
    }

    /** @param  array{family: string, first: string, firstLines: list<int>, second: string, secondLines: list<int>}  $pair */
    private function collisionDescription(array $pair): string
    {
        return sprintf(
            "confusable identifiers '%s %s' (line %s) and '%s %s' (line %s) coexist in the "
            .'same family with no declaration of the collision',
            $pair['family'],
            $pair['first'],
            implode(', ', $pair['firstLines']),
            $pair['family'],
            $pair['second'],
            implode(', ', $pair['secondLines']),
        );
    }

    private function collisionDisposition(VocabularyContents $contents): string
    {
        $pairs = $this->collidingPairs($contents->confusableIdentifiers());

        if ($pairs === []) {
            return '; no confusable collision';
        }

        return $contents->confusableCollisionDeclared()
            ? '; confusable collision DECLARED'
            : '; confusable collision UNDECLARED';
    }

    /** @param  list<TermOccurrence>  $stale */
    private function liveCount(array $stale): int
    {
        $live = 0;

        foreach ($stale as $occurrence) {
            if (! $occurrence->cited()) {
                $live++;
            }
        }

        return $live;
    }
}
