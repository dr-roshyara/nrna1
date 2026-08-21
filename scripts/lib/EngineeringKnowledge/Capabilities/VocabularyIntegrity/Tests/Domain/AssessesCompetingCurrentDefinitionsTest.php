<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\VocabularyIntegrity\Tests\Domain;

use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\AssessesCompetingCurrentDefinitions;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DispositionContents;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\DispositionRow;
use EngineeringKnowledge\Capabilities\VocabularyIntegrity\Domain\SplitDeclaration;
use EngineeringKnowledge\Shared\Domain\Verdict;
use PHPUnit\Framework\TestCase;

/**
 * S5 — the domain service that applies DP-3 to a document's DISPOSITIONS.
 *
 * The canonical-document rule (§0.4.4), under the OQ-2 hypothesis that DI-4 is DP-3:
 *   "no two competing current definitions are left standing; superseded wording remains
 *    ONLY where explicitly labelled."
 *
 * The S5 discriminator is ONE mechanical shape — a disposition stated as a SINGLE remedy
 * with NO superseded marker while the document ITSELF declares the same disposition SPLIT
 * (two-branch) elsewhere on a shared trigger:
 *   an unlabelled single-remedy disposition whose trigger shares ≥2 distinctive tokens
 *     with a declared split          → WARN (DI-4 — AMD5's §8 Phase-7 row vs §4.4's RD-3 split)
 *   the same row LABELLED superseded → quiet (AMD6's repaired §8 Phase-7 row)
 *   the same row carrying the two-branch shape → quiet (it IS the current split)
 *   a split declared for a DIFFERENT trigger  → quiet (AMD4: the split is INV-R1, not the
 *     Phase-7 mismatch disposition)
 *
 * ⛔ WARN only, per the plan. This service executes §0.4.4 / DP-3; it owns no policy
 *    (PA: "Capabilities must execute policies. Capabilities must never own policies.").
 * ⛔ It does NOT detect an unlabelled stale disposition when no split is declared, nor any
 *    semantic reading of what is or is not superseded — the report states that (D-4).
 *
 * Fail-closed (D-2): nothing to evaluate is INCONCLUSIVE, never PASS.
 */
final class AssessesCompetingCurrentDefinitionsTest extends TestCase
{
    private function assessor(): AssessesCompetingCurrentDefinitions
    {
        return new AssessesCompetingCurrentDefinitions();
    }

    private function row(
        string $trigger = 'at Phase 7, on a FINAL re-hash mismatch',
        string $remedy = 'STOP-AND-RECONCILE. Removal is refused until the difference is reconciled and re-verified.',
        int $line = 42,
        string $section = '8',
        bool $labelled = false,
        bool $twoBranch = false,
    ): DispositionRow {
        return DispositionRow::of($trigger, $remedy, $line, $section, $labelled, $twoBranch);
    }

    private function split(
        string $text = 'THE MISMATCH DISPOSITION IS SPLIT — at Phase 7, on a FINAL re-hash mismatch, '
            .'CASE α reconciles under §6 and CASE β is QUARANTINED — never one branch for both.',
        int $line = 30,
        string $section = '4.4',
    ): SplitDeclaration {
        return SplitDeclaration::of($text, $line, $section);
    }

    private function contents(
        array $rows = [],
        array $splits = [],
        bool $policyDeclared = true,
    ): DispositionContents {
        return DispositionContents::of($rows, $splits, $policyDeclared);
    }

    /** ⛔ THE S5 RED BOUNDARY (plan §5, verbatim): a fixture with two current dispositions,
     *  one unlabelled — an unlabelled single-remedy row competes with the declared split. */
    public function test_unlabelled_single_remedy_competing_with_declared_split_is_warn(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(rows: [$this->row()], splits: [$this->split()]),
            'fixture.md',
        );

        self::assertSame(Verdict::WARN, $result->verdict());
        self::assertFalse($result->isClean());
        self::assertStringContainsString('single remedy with no superseded marker', $result->evidence());
        self::assertStringContainsString('declares it SPLIT', $result->evidence());
        self::assertStringContainsString('line 42', $result->evidence());
        self::assertStringContainsString('line 30', $result->evidence());
    }

    /** The AMD6 quiet shape: the same row, but the stale wording is LABELLED superseded. */
    public function test_labelled_row_does_not_compete(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(rows: [$this->row(labelled: true)], splits: [$this->split()]),
            'fixture.md',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertTrue($result->isClean());
    }

    /** A row that already carries the two-branch shape IS the current split — not a competitor. */
    public function test_two_branch_row_does_not_compete(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                rows: [$this->row(
                    remedy: 'PRE-SWITCH: reconcile under §6 then re-verify; POST-DEMOTION: quarantine.',
                    twoBranch: true,
                )],
                splits: [$this->split()],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** The AMD4 quiet shape: a split is declared, but for a DIFFERENT trigger — no shared tokens. */
    public function test_row_whose_trigger_does_not_share_tokens_is_pass(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                rows: [$this->row(trigger: 'before the Phase-5 WRITER switch')],
                splits: [$this->split(
                    text: 'INV-R1 was SPLIT into location-refusal (exit 65) and workflow-state UNRESOLVABLE (report, exit 0).',
                )],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
        self::assertStringContainsString('1 disposition row(s) checked against 1 split declaration(s)', $result->evidence());
    }

    /** D-2 / fail-closed: no declared split → nothing for the heuristic to compete against. */
    public function test_no_split_declaration_is_inconclusive(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(rows: [$this->row()]),
            'fixture.md',
        );

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    /** D-2 / fail-closed: no disposition row → nothing to evaluate. */
    public function test_no_disposition_row_is_inconclusive(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(splits: [$this->split()]),
            'fixture.md',
        );

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    /** The heuristic is anchored to the document's OWN §0.4.4 declaration — refuse without it. */
    public function test_document_without_the_canonical_rule_declaration_is_inconclusive(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(rows: [$this->row()], splits: [$this->split()], policyDeclared: false),
            'fixture.md',
        );

        self::assertSame(Verdict::INCONCLUSIVE, $result->verdict());
        self::assertStringContainsString('not PASS', $result->evidence());
    }

    /** Two candidates yield two named warnings, both surviving into the evidence. */
    public function test_every_candidate_is_named_in_the_evidence(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                rows: [$this->row(line: 42), $this->row(line: 90)],
                splits: [$this->split()],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::WARN, $result->verdict());
        self::assertStringContainsString('line 42', $result->evidence());
        self::assertStringContainsString('line 90', $result->evidence());
    }

    /** DP-5 / AP-8: every outcome states what it checked. */
    public function test_every_verdict_carries_evidence(): void
    {
        $assessor = $this->assessor();

        $outcomes = [
            $assessor->validate($this->contents(rows: [$this->row()], splits: [$this->split()]), 'fixture.md'),
            $assessor->validate($this->contents(rows: [$this->row()]), 'fixture.md'),
            $assessor->validate($this->contents(), 'fixture.md'),
        ];

        foreach ($outcomes as $outcome) {
            self::assertNotSame('', $outcome->evidence(), "no evidence for {$outcome->verdict()->value}");
        }
    }

    /** Observed in the AMD5 corpus: an amendment citation (`amd4`) in both row and split is
     *  not trigger identity — a split that shares ONLY the citation does not compete. */
    public function test_citation_only_overlap_is_not_a_competitor(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                rows: [$this->row()],
                splits: [$this->split(text: 'AMD4 (RC-4) declared the disposition split.')],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
    }

    /** Observed in the AMD5 corpus (§8 line 706 vs §4.4's RD-3 register): branch-boundary words
     *  (writer · switch · case · pre · post · demotion · branch) are the split's branch
     *  DISCRIMINATORS, not its subject. A row sharing only those with a split does not compete —
     *  the subject of the split is "the Phase-7 mismatch", not "the writer switch". */
    public function test_branch_boundary_only_overlap_is_not_a_competitor(): void
    {
        $result = $this->assessor()->validate(
            $this->contents(
                rows: [$this->row(trigger: 'before the Phase-5 WRITER switch')],
                splits: [$this->split(
                    text: 'THE MISMATCH DISPOSITION IS SPLIT — CASE α (pre-writer-switch) reconciles under §6; '
                        .'CASE β (post-demotion write) is QUARANTINED.',
                )],
            ),
            'fixture.md',
        );

        self::assertSame(Verdict::PASS, $result->verdict());
    }
}
