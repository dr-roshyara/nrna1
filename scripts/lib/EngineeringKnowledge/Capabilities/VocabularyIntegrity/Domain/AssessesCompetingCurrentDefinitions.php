<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain;

use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;

/**
 * The domain service that applies §0.4.4's canonical-document rule to a document's
 * DISPOSITIONS (S5).
 *
 * DP-3 — the catalogue's policy this service executes, stated once, never owned here
 * (PA: "Capabilities must execute policies. Capabilities must never own policies."):
 *
 *   "A governed term shall carry one meaning per context; where overloaded, it shall
 *    be qualified."
 *
 * §0.4.4 is DP-3 applied to a whole document: "no two competing current definitions are
 * left standing; superseded wording remains ONLY where explicitly labelled." Under the
 * OQ-2 hypothesis, DI-4 (competing current definitions) is genuinely this policy, so S5
 * proceeds as a WARN-only heuristic and records its evidence.
 *
 * The ONE mechanical shape this service detects, and no other:
 *   a disposition row that (a) is UNLABELLED (no superseded marker), (b) is SINGLE-remedy
 *   (carries no two-branch shape — including a trigger that names ONE branch), and
 *   (c) shares ≥ 2 distinctive SUBJECT tokens with a line declaring the same disposition
 *   SPLIT elsewhere
 *       → WARN  (DI-4 — AMD5's §8 Phase-7 row vs §4.4's RD-3 split)
 *   the same row LABELLED superseded, or carrying the two-branch shape, or sharing no
 *   subject with a declared split → quiet (AMD6 repaired · AMD4's split is INV-R1)
 *
 *   "Distinctive subject tokens" excludes (see distinctiveTokens): amendment citations
 *   (amd4 · rc-3 …), branch-boundary words (writer · switch · case · pre · post · demotion
 *   · branch), and generic process words. A row whose TRIGGER names one branch ("Phase 7,
 *   ONLY on a POST-DEMOTION disposition") is a constituent of the split the reader has
 *   already classed two-branch — it is not a competing whole-disposition definition.
 *
 * ⛔ What this service does NOT detect (D-4, stated so the report does not overclaim):
 *   an unlabelled stale disposition when NO split is declared; disagreement between two
 *   current single-remedy statements; superseded wording in prose rather than a table
 *   row; or any semantic reading of what is or is not superseded. A clean verdict means
 *   only that this shape is absent.
 *
 * Fail-closed (D-2): nothing to evaluate is INCONCLUSIVE, never PASS.
 * ⛔ This service owns EXECUTION only — it creates no disposition, no split, no policy.
 */
final readonly class AssessesCompetingCurrentDefinitions
{
    public const POLICY = VocabularyPolicy::STATEMENT;

    public const GOVERNING_RULE = VocabularyPolicy::GOVERNING_RULE;

    /** Two shared distinctive tokens mark "the same disposition" — one common word is noise. */
    private const MIN_SHARED_TRIGGER_TOKENS = 2;

    /** Trigger words too common to carry identity. Branch-boundary words (writer, switch,
     *  case, pre, post, demotion, branch, both) are the split's branch DISCRIMINATORS, not
     *  its subject — a split names "the Phase-7 mismatch disposition", and its branches are
     *  "pre-writer-switch / post-demotion write". */
    private const STOP_WORDS = [
        'before', 'after', 'under', 'over', 'when', 'this', 'that', 'with', 'from', 'into',
        'upon', 'about', 'while', 'never', 'must', 'only', 'then', 'than', 'also', 'still',
        'within', 'between', 'during', 'what', 'case', 'pre', 'post', 'writer', 'switch',
        'demotion', 'branch', 'both',
    ];

    public function validate(DispositionContents $contents, string $subject): Assessment
    {
        $rows = $contents->dispositionRows();
        $splits = $contents->splitDeclarations();

        if (! $contents->policyDeclared()) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Competing-current-definition check of '%s' (§0.4.4 · DP-3): the document does not "
                    .'declare the canonical-document rule (no "## 0.4.4" heading, or no "no two competing '
                    .'current definitions" statement); nothing to evaluate. Absence of evidence is not PASS.',
                    $subject,
                ),
                $subject,
            );
        }

        if ($rows === []) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Competing-current-definition check of '%s': no disposition row found; nothing to "
                    .'evaluate. Absence of evidence is not PASS.',
                    $subject,
                ),
                $subject,
            );
        }

        if ($splits === []) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Competing-current-definition check of '%s': no declared split disposition found; the "
                    .'S5 heuristic detects an unlabelled single-remedy disposition only against a '
                    .'document-declared split of the same trigger; nothing to evaluate. Absence of evidence '
                    .'is not PASS.',
                    $subject,
                ),
                $subject,
            );
        }

        $warnings = [];

        foreach ($splits as $split) {
            $splitTokens = $this->distinctiveTokens($split->text());

            foreach ($rows as $row) {
                if ($row->labelled() || $row->twoBranch()) {
                    continue;
                }

                $shared = array_values(array_intersect(
                    $this->distinctiveTokens($row->trigger()),
                    $splitTokens,
                ));

                if (count($shared) >= self::MIN_SHARED_TRIGGER_TOKENS) {
                    $warnings[] = sprintf(
                        '§%s (line %d) states the disposition for "%s" as a single remedy with no superseded '
                        .'marker, while §%s (line %d) declares it SPLIT (shared trigger tokens: %s)',
                        $row->section(),
                        $row->line(),
                        $row->trigger(),
                        $split->section(),
                        $split->line(),
                        implode(', ', $shared),
                    );
                }
            }
        }

        if ($warnings !== []) {
            return Assessment::of(
                Verdict::WARN,
                sprintf(
                    "Competing current definitions in '%s': %s. §0.4.4 (canonical-document rule · DP-3) "
                    .'forbids two competing current definitions.',
                    $subject,
                    implode('; ', $warnings),
                ),
                $subject,
            );
        }

        return Assessment::of(
            Verdict::PASS,
            sprintf(
                "Canonical-document rule (§0.4.4 · DP-3) in '%s': %d disposition row(s) checked against %d "
                .'split declaration(s); no unlabelled single-remedy disposition shares a trigger with a '
                .'declared split.',
                $subject,
                count($rows),
                count($splits),
            ),
            $subject,
        );
    }

    /**
     * The trigger-identity vocabulary: distinctive words (length ≥ 4, not a stop-word) of a
     * trigger cell or of a split-declaration line. Two constructs are "the same disposition"
     * when they share at least MIN_SHARED_TRIGGER_TOKENS of these words.
     *
     * EXCLUDED from identity, because the corpus shows they carry none:
     *   • amendment-citation tokens (`amd4`, `rc-3`, `cl-9` …) — every row cites the
     *     amendment that introduced it, so they appear in triggers AND split texts and
     *     would make any two Phase-7 constructs "the same disposition";
     *   • branch-boundary words (writer · switch · case · pre · post · demotion · branch)
     *     — the split's BRANCH DISCRIMINATORS, not its subject ("pre-writer-switch" is
     *     the boundary, "Phase-7 mismatch" is the disposition);
     *   • generic process words already in STOP_WORDS.
     *
     * @return list<string>
     */
    private function distinctiveTokens(string $text): array
    {
        $lower = function_exists('mb_strtolower')
            ? mb_strtolower($text, 'UTF-8')
            : strtolower($text);

        $words = preg_split('/[^\p{L}\p{N}]+/u', $lower, -1, PREG_SPLIT_NO_EMPTY);

        if ($words === false) {
            return [];
        }

        $tokens = [];

        foreach ($words as $word) {
            if (preg_match('/^[a-z]{1,3}\d+$/', $word) === 1) {
                continue;
            }

            if (strlen($word) >= 4 && ! in_array($word, self::STOP_WORDS, true)) {
                $tokens[$word] = true;
            }
        }

        return array_keys($tokens);
    }
}
