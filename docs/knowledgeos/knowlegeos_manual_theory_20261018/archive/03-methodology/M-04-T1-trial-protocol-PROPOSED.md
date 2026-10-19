# M-04-T1 — workflow proposal (trial protocol, version 3) — PROPOSED

| Field | Value |
|---|---|
| Status | **PROPOSED.** Not part of the approved method (M-04 v1.0) |
| Trial authorization | the Principal's instruction of 2026-10-18: *"now write and try this methodology"*. Read as **authorization to trial**, not to adopt. Adoption needs a separate Principal decision, recorded in `METHOD-VERSIONS.md` |
| Source | `M-04-efficiency-review-20261018.md` §5–§7 (changes E-1…E-8, stopping rule, checklist) |
| First trial ground | the **batch-3 corrections** (checklist §7, first item). **No new batch is started by this trial** |
| Sequence (Principal, 2026-10-18) | 1 correct batch-3 defects → 2 targeted independent review of the foundational claims → 3 update the workflow proposal with the low-risk improvements → 4 keep the step-7 change separate → 5 resume only when corrections and checks are complete |
| Deviation from approved text | **none in the trial.** E-3 (narrowing approved step 7) is **kept separate, not trialled**, pending the Principal's decision (Principal message of 2026-10-18: "Keep the proposed change to M-04 Step 7 separate until you make the decision") |

## Per file

| Step (M-04) | Approved obligation | Trial addition |
|---|---|---|
| 1 permission | unchanged | — |
| 2 read | read the whole document | **E-5 linear pass:** extract in source order, section by section, before writing into the book |
| 3 compare and label | one label per idea | **E-4:** the register's `disposition` column takes exactly one of step 6a's six values (integrated · already explained · evidence · unresolved · rejected · excluded) |
| 4 verify | logic, code and records, decisions | **E-2:** line anchors, section numbers **and counts** are written only from verified output (grep, read or count in the same step), and re-verified before the file closes (`tools/anchor_check.py` for range; a human spot check for content). **E-9 (new, v2):** every new formula or deduction records its premises and at least one counterexample attempt; a premise taken from outside the book's axioms is named as a premise |
| 5 write | own words; revise earlier text | **E-10 (new, v2): contradiction check against the existing theory.** Before closing, every new or changed paragraph is checked against the book's axioms, invariants and glossary definitions it touches (e.g. §1.6 authority axiom, I-1…I-11, glossary terms). Evidence: batch 3 introduced a validity/well-foundedness conflation that contradicted §1.6 |
| 6 / 6a trace and completeness | three-way check | **E-1:** run `tools/coverage_check.py`. Every non-blank line is either covered by a register row or listed in `06-theory/NONPROP-LINES.tsv` with a reason. Then read every uncovered run. **E-11 (v3):** substance inventory before closing (below). **Coverage is not idea completeness:** the substantive three-way reconciliation still decides whether each meaningful idea has been captured and disposed of |
| 7 look-back A | did I miss anything? | after E-1 reports zero uncovered lines |
| 7 look-back B | does the book still make sense across chapters? | **unchanged (approved scope).** E-3 is not applied in the trial |
| close | — | **E-6:** finish register, log and look-backs before the next file; append the file's timestamps to the trial log |

## Per batch (five files) or per correction round

- *(E-3 batch-level consolidation is not trialled; see the header.)* The approved five-file review continues as is.
- **E-8:** targeted independent review of foundational corrections and new formulas. Background material only; reviewer access rules unchanged. **v3: independence.** The reviewer receives the claims under review and the authorized evidence needed to judge them, **not** the author's dispositions, corrections log or conclusions about earlier findings. Evidence: the round-2 reviewer was given the earlier disposition record and may have anchored on it. **v2: corrections are new claims.** A correction to foundational text goes through E-8 again before it is relied on. Evidence: in the first targeted review, 3 of the 5 corrected items were themselves defective.
- **Decision:** CONTINUE / CORRECT / PAUSE, with the measures below.

## File-closure rule

**COMPLETE** when all of these hold:
1. coverage is complete (E-1);
2. every row has one disposition and a pointer that resolves;
3. new deductions state their premises and a counterexample attempt;
4. anchors are verified;
5. open rows have a reason and a follow-up.

**CORRECT** when:
- any of 1–5 fails;
- a foundational defect is found in text the file depends on;
- a new formula has failed its counterexample attempt and has not yet been rewritten.

**OPEN without blocking** when:
- the question is undecidable on the available evidence or authority;
- both positions are visible in the book;
- a reason and follow-up are recorded;
- no other text from the file depends on it.

## Measures (no thresholds; baseline first)

**Per file or round:**
- non-blank lines;
- rows added;
- uncovered lines before and after the look-back;
- reference repairs;
- rework edits;
- elapsed time from `date -u` stamps in the trial log.

**Later, from review:** omissions, overstatements and foundational defects found afterwards.

## Tools

| Tool | Checks |
|---|---|
| `tools/coverage_check.py` | E-1: uncovered lines per source |
| `tools/register_check.py` | E-4: disposition values; follow-up on open rows; manuscript pointer present |
| `tools/anchor_check.py` | E-2: every line citation "KEY Lx–y" in the chapters points inside its source file |

## Version 2 — what changed and why (2026-10-18)

| Change | Principal's area | Evidence from the trial (round 1 and the targeted review) |
|---|---|---|
| E-2 extended to counts and a pre-closure re-check | source references | the only rework in round 1 was in two unverified counts in my own correction note |
| E-9 premises + counterexample, made explicit | mathematical and logical reasoning | the review found missing premises (P-E; the normative premise) and two counterexamples I had not tried (emergency freeze; office-holder lapse via the static bound) |
| E-10 contradiction check against existing theory | existing theory | §1.17.2 contradicted the §1.6 axiom; §1.14.5 used "effective" in a second meaning |
| E-8 applies to corrections too | — | 3 of 5 corrected items were defective |
| E-1, E-4, E-5, E-6 unchanged | source completeness; register; efficiency | E-1 brought coverage to 0 uncovered lines and found 5 more ideas; E-4 gave 0 register findings on 115 rows |
| E-3 (step-7 change) | efficiency (batch-level terminology and cross-references) | **still separate, not trialled**, awaiting the Principal's decision |

**Unchanged:** the approved M-04 v1.0. This protocol is a proposal and a trial aid; no approved step changes meaning.


## What the checks do not prove (v3)

- **Line coverage is not proof of idea completeness.** E-1 shows only that every non-blank line is linked to some register row or marked non-propositional. A covered line can hold a second idea that was never registered, and an idea spread across covered lines can be registered incompletely. Completeness is judged by the three-way reconciliation (step 6a) and by look-back A, not by the coverage count.
- **A counterexample attempt does not prove a formula or deduction correct.** E-9 records that one attempt failed to refute it. The claim keeps the mark its evidence supports (Ded, Hyp), and it stays open to refutation. Foundational claims still go to targeted independent review (E-8).
- **The register check and the anchor check are format checks.** They show that dispositions, pointers and follow-ups exist, and that line citations point inside their source. They do not show that the disposition is right or that the cited lines say what the book says.

## E-11 Substance inventory before closing a file (v3)

Before a file closes, I list the kinds of content the source contains and confirm each kind is reconciled across source, register and book. The kinds:
- main argument;
- **appendices** and annexes;
- **examples** and worked cases;
- **tables**, including each substantive row;
- **code**, configuration and schemas;
- **assumptions** (stated or evidently relied on);
- **exceptions**;
- **qualifications**: scope, conditions, time, modality.

For each kind present I record the number of items and their dispositions. Implied claims carried by an example or a table are registered as Int.

A file is not closed while any kind is present in the source with **no** disposition. A qualification is never dropped from its claim (A-1 (b), pending, gives the same rule for the unit of extraction).

## Version 3 — what changed and why (2026-10-18)

| Change | Reason |
|---|---|
| "What the checks do not prove" section | Principal, 2026-10-18: line coverage is not idea completeness; counterexample attempts are tests, not proofs |
| E-11 substance inventory (appendices, examples, tables, code, assumptions, exceptions, qualifications; qualifications stay attached) | Principal, 2026-10-18: preserve the complete intellectual substance; reconcile source, register and book before closing |
| E-8 independence rule | Principal, 2026-10-18: the reviewer sees claims and evidence, not the author's conclusions. Evidence: round-2 review limitation |

**Still separate, pending the Principal:** E-3 (the step-7 change), A-1 v2, and permanent adoption of any trial aid. M-04 v1.0 is unchanged.
