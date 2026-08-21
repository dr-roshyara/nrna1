<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain;

use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;

/**
 * The domain service that applies DP-1 to a document-local register (S1).
 *
 * DP-1 — the catalogue's policy this service executes, stated once, never owned here
 * (PA: "Capabilities must execute policies. Capabilities must never own policies."):
 *
 *   "Every identifier shall be unique within its register(ns), and checked before minting."
 *
 * Here the register is THE DOCUMENT and the identifiers are its numbered section
 * headings. The two defect classes detected are the two halves of the historical
 * DI-1 finding (AMD4, `0a2fa71d`):
 *   1. duplicate section identifiers — `## 4.1` twice, `## 4.2` twice
 *   2. non-monotonic section ordering — `4.1 · 4.3 · 4.4 · 4.2 · 4.0 · 4.1 · 4.2`
 *
 * Monotonic ordering is the document-local EXPRESSION of DP-1, not a new policy:
 * a register entered out of order makes its identifiers ambiguous about which
 * meaning is current, so uniqueness is not meaningful without it.
 *
 * Verdict mapping, each traced:
 *   every identifier unique and in order   → PASS
 *   a duplicate, or an order violation     → FAIL   (the DI-1 defect)
 *   no numbered headings at all            → INCONCLUSIVE (D-2: nothing to evaluate;
 *                                             absence of evidence is never PASS)
 */
final readonly class AssessesDocumentLocalIntegrity
{
    public const POLICY = IdentifierPolicy::STATEMENT;

    public const GOVERNING_RULE = IdentifierPolicy::GOVERNING_RULE;

    public function validate(DocumentSectionSequence $sequence, string $subject): Assessment
    {
        if ($sequence->isEmpty()) {
            return Assessment::of(
                Verdict::INCONCLUSIVE,
                sprintf(
                    "Document-local register '%s': no numbered section headings found; "
                    .'nothing to evaluate. Absence of evidence is not PASS.',
                    $subject,
                ),
                $subject,
            );
        }

        $defects = [
            ...$this->collisions($sequence->identifiers()),
            ...$this->orderViolations($sequence->identifiers()),
        ];

        if ($defects !== []) {
            return Assessment::of(
                Verdict::FAIL,
                sprintf(
                    "Document-local register '%s' violates DP-1: %s.",
                    $subject,
                    implode('; ', $defects),
                ),
                $subject,
            );
        }

        return Assessment::of(
            Verdict::PASS,
            sprintf(
                "Document-local register '%s' (DP-1): %d numbered section headings; "
                .'all unique and in monotonic order.',
                $subject,
                $sequence->count(),
            ),
            $subject,
        );
    }

    /**
     * @param  list<DocumentSectionIdentifier>  $ids
     * @return list<string>
     */
    private function collisions(array $ids): array
    {
        $byNumber = [];

        foreach ($ids as $id) {
            $byNumber[$id->number()][] = $id->line();
        }

        $out = [];

        foreach ($byNumber as $number => $lines) {
            if (count($lines) > 1) {
                $out[] = "duplicate section identifier '{$number}' at lines ".implode(', ', $lines);
            }
        }

        return $out;
    }

    /**
     * @param  list<DocumentSectionIdentifier>  $ids
     * @return list<string>
     */
    private function orderViolations(array $ids): array
    {
        $out = [];
        $count = count($ids);

        for ($i = 1; $i < $count; $i++) {
            $prev = $ids[$i - 1];
            $cur = $ids[$i];

            if ($prev->compareTo($cur) >= 0) {
                $out[] = sprintf(
                    "non-monotonic section order: '%s' (line %d) followed by '%s' (line %d)",
                    $prev->number(),
                    $prev->line(),
                    $cur->number(),
                    $cur->line(),
                );
            }
        }

        return $out;
    }
}
