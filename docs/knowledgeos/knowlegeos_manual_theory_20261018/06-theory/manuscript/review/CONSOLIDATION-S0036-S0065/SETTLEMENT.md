# Settlement of the consolidation review S0036–S0065

*2026-10-19. Two reviewers in parallel (Rulebook §12, entry 48), each with one commission and one step-2 message:*
- *reviewer A on Opus: `STEP1-INVENTORY-A.md`, `REVIEW-REPORT-A.md`;*
- *reviewer B on Sonnet: `STEP1-INVENTORY-B.md`, `REVIEW-REPORT-B.md`.*

*Settled after the Principal's scoring of the planted-gap test (`PLANTED-GAP-RESULT.md`, the Rule Reviewer's file).*

## Reviewer time (harness)

| Reviewer | Step 1 | Steps 2–3 | Total |
|---|---|---|---|
| A (Opus) | 474 s | 456 s | 930 s |
| B (Sonnet) | 405 s | 387 s | 792 s |

## The planted-gap test: what the settlement does with it

- **The plants were only in the test copies** (`sections/06.04…`, `08.06…`, `09.02…`). The chapter files were checked:
  - none of §6.4, §8.6 or §9.2 changed between the freeze (`adbe16c5c`) and the test;
  - the five omitted points are present (§8.6 C6 "never manufacture authority"; §8.6 C3 "self-contaminating"; §6.4 C6 "better title"; §6.4 C1 "FALSIFIED"; §9.2 C2 the litmus);
  - neither distortion is in the book: §8.6 C1 has no "full model separation"; §9.2 says reuse is shown "by a use, not by inspection".

  **Nothing to repair.**
- **The score is the Principal's.** In short: both distortions were found by both reviewers by reading. The omission scores are not blind, because the chapter originals were reachable (B by `diff`, A through `book_search.py`). O5 was missed by both.
- **The Professor's own plant** ("lane" and "BC-7" used undefined in §5.5 C6 and §6.5) was found by **neither** reviewer as stated. B's C-4 (BC-7's invariants unqualified) is related. Both terms are now defined in §6.5.2.

## Findings, compared (both / one only), excluding the plants

| Finding | A | B | Disposition |
|---|---|---|---|
| No derivation chain primitives → laws; sections add cases to Ch. 1 laws | ✓ | ✓ | accepted; outline and map (architecture view) |
| Req (T9-01) the load-bearing undefined object | ✓ | ✓ (Req post hoc) | accepted; outline §5 row restated |
| Primitives incomplete: **trust** (S0913) | ✓ | ✓ | accepted; **F23**; outline §1 |
| **Conflict over claims** (S0913, R-CONFLICT) vs §9.4 C3 | ✓ | ✓ | accepted; **F24**; outline §1 |
| Assurance-model terms used undefined (INV-ATTR-*, claim, assessment, outcome, C1–C5, Declared) | ✓ | ✓ (critical, C-3) | accepted; **§9.4.1a** glossary |
| BC-7's I-n collide with the book's I-n (§6.5 C2) | ✓ | ✓ (C-4) | accepted; §6.5.2 definition and table label |
| CON-0129 "exactly" stale in register and outline | ✓ | ✓ | accepted; both fixed |
| CON-0102: "needs an operation" (A); "held" homonym (B) | ✓ | ✓ | accepted; register; §6.4 C3 restated (graph reassessment below) |
| Map position block stale | ✓ | ✓ | accepted; updated |
| "Lord" undefined (§5.4) | ✓ | ✓ | accepted; glossed |
| EKS-01, AST-007 undefined (§9.5) | ✓ | ✓ | accepted; glossed |
| "A process cannot attest its own separation" (S0091 L257, L715) | ✓ | ✓ | accepted; §8.6 C3 |
| Conformance layer / neutrality claim (S0164 L1276–1323, L1485) | ✓ | ✓ | accepted as missing; outline §2, map (theory work) |
| S2834 Unknown ≠ Uncertain; E/A/K; q = g ∘ T | ✓ | ✓ | accepted as missing; map |
| "Role" in three senses; communication = distribution + composition (S0091) | ✓ | ✓ | accepted as missing; map |
| "Zero" fourth sense (S0913) | ✓ | — | accepted; §5.4 Z4 |
| "Requirement" names two objects (§5.4 vs §9.1) | ✓ | — | accepted; notes in both; T9-01 |
| CON-0099 meaning ("inconsistent") vs §9.1 ("under-specified") | ✓ | — | accepted; register |
| "five factors" left in §8.6 C5 and open questions | ✓ | — | accepted; "seven" |
| §4.3.3 open question says "admission" | ✓ | — | accepted; reworded |
| §8.2 C2 "often wrong" overgeneralises | ✓ | — | accepted; restricted to the sweep |
| S0071 aggregate test, session-name scope, 33/32/16; S0067 "seq, never wall-time" | ✓ | — | accepted; §6.5 C9 |
| S0058 L18–35, the wrong report cited (referent) | ✓ | — | accepted; §8.5 C1 |
| S0113 L18, HALT decides the category | ✓ | — | accepted; §9.5 |
| Four evidence dimensions (S0164 L2420) | ✓ | — | accepted as missing; map |
| S2377 L982, preservation of previously valid requirements | ✓ | — | accepted; §5.4 C-on-order |
| §8.6 quote "[S0056 L25–27]" off by one | ✓ | — | **rejected:** re-checked; "SAME MODEL" is at L25 and "can share a blind spot" at L27; the anchor holds |
| "A1", "A2" in §9.1 vs Ch. 1 A1 | — | ✓ | accepted; notation note in §9.1 |
| F22 premise "plane ⇒ kernel" unstated | — | ✓ | accepted; §6.5 C0 restated |
| §4.2 C1 vs §4.3 C5b (a decision by an AI) | — | ✓ | already recorded at §4.2 open questions ("meets its first run in §4.3 C5b"); no change |
| Establishing a capability (S0164 D5, E1–E10) | — | ✓ | accepted as missing; outline §5 |
| S0036 stopping rule and uncertainty carriage | — | ✓ | accepted as missing; map |
| Source defects (S0036 L114 vs S0039; S0038 30 vs 32; S0067 "2 of 5"; S2377 slips) | ✓ | ✓ | recorded; nothing in the book relies on them |

**Counts:**
- 15 findings raised by both reviewers;
- 12 by A only (one of them rejected);
- 6 by B only (one already recorded).

## Verdicts

- **Second verdicts:** both reviewers confirm all **12 VALIDATED** concepts (0099, 0102, 0104, 0105, 0107, 0108, 0111, 0120, 0121, 0126, 0129, 0131), with the text corrections above. They confirm all **12 PROVISIONAL** and all **12 INVALID**.
- **Not minimal (A):** 0118 (duplicates CON-0108), 0133 (ARCH-INV-1), 0134 (§1.14.1). Recorded in the register.
- **Kernel / application / method:** the reviewers' marks are kept in their reports (A appendix B; B §4). They agree on the kernel candidates 0099, 0100, 0102, 0105, 0108, 0111, 0131. Where they differ, the marks are recorded, not merged.

## Dependency graph (Rulebook §12d), run on this settlement

`python3 -I tmp/tools/dependency_graph.py --down CON-0102 CON-0129`. The two concepts changed in substance; the CON-0099 text was only aligned.

| Changed | Downstream (explicit references, transitive) | Reassessment on evidence |
|---|---|---|
| CON-0102 ("needs an operation" dropped; "held" two senses) | CON-0117, 0131, 0132; §5.5, §6.4, §6.5, §9.4, §9.5; transitively §4.2, §4.3, §5.6, §8.4, §8.5, §8.6, §8.7 | **§6.4 C3 rested on the dropped clause** ("what makes a statement an invariant is that something guarantees it"): restated. §5.5 C2, §6.5 C2, CON-0131, CON-0132 use stated / held / enforced: unaffected. CON-0117 (distribution) and §9.5: cite CON-0102 for stated ≠ enforced: unaffected. Transitive items cite §5.5 or §6.4 for other claims: unaffected. |
| CON-0129 ("exactly" → sufficient) | §8.7 | §8.7 C2 already says "sufficient": unaffected |

*Limit: the graph sees only explicit references; implicit dependencies remain step 8's and the reviewers' task. It works at section level, so the transitive lists are wide.*

## Trial T3: defects in T3's content that the consolidation found and review C11/T3 missed

T3's content is §8.6, §8.7, §6.5, §5.5 C6 and §5.6 C6. The planted test distortions are excluded. Counts only.

| Class | Count | Items |
|---|---|---|
| Defects in text that review T3 read | **1** | BC-7's I-n unqualified in §6.5 C2 |
| Defects introduced at the T3 settlement (text no reviewer had seen) | **3** | "five factors" left in §8.6; CON-0129 "exactly" left in register and outline; F22's premise unstated in §6.5 C0 |
| Omissions in T3's sources that T3's report did not raise | **5** | S0071 aggregate test, identity scope, 33/32/16 · S0067 "seq, never wall-time" · S0058 wrong referent · S0164 conformance layer · S0164 D5 establishment criteria. *Four of the five were in T3's own step-1 inventory and did not reach its report.* |
| Omissions T3 judged "justifiably excluded" that both consolidation reviewers call missing | **2** | trust (S0913); conflict over claims (S0913, R-CONFLICT) |
| Missed by T3 **and** by both consolidation reviewers | **1** | "lane", "BC-7" undefined (the Professor's plant) |

**Answer to the Principal's question:**
- The consolidation found **6 defects in T3's content that T3's own review missed**: 1 in the text and 5 omissions. 2 more are disputed exclusions.
- 3 further defects were introduced by the T3 settlement itself, after its review.
- One defect was missed by all three reviews.

## Changes made (files)

| File | Change |
|---|---|
| §6.5 | definitions of BC-7, lane, BC-7 I-n; table label; C0 premise; C9 (S0071, S0067) |
| §8.6 | "seven" ×2; C3: a process cannot attest its own separation (S0091) |
| §8.5 C1 | the wrong referent (S0058) |
| §9.4 | §9.4.1a glossary of the assurance model |
| §5.4 | Z4; "Lord" glossed; "requirement" qualified; S2377 L982 |
| §9.1 | A1/A2 notation; "required" qualified |
| §9.5 | EKS-01, AST-007 glossed; S0113 L18 (choosing a response chooses a category) |
| §6.4 C3 | title and first bullet restated (graph reassessment); "held" in two senses |
| §4.3, §8.2 | open question reworded; C2 restricted |
| register | second-verdict history on CON-0099–0134; meaning text of 0099, 0102, 0129; minimal notes |
| THEORY-MAP | F23, F24; consolidation block; position and counts |
| BOOK-OUTLINE | trust, claim conflict, requirement rows; a missing law; architecture view; Req row; forks |

**Not done here: theory work for the coming turns** (§12b):
- F22, the next turn;
- F23, F24;
- the missing laws and topics listed in the map;
- the derivation chain;
- minimality of the primitives.

## Lessons recorded (for the Rule Reviewer, not rules)

- A planted-gap test must keep the originals out of reach. This is the result file's own lesson.
- **Settlement text is unreviewed text.** 3 of the defects above were written at a settlement. A short self-check of settlement edits against the review's own findings (grep for the restated words) would have caught two of them.
- **A reviewer's inventory can hold what its report drops:** 4 of T3's 5 missed omissions were in its own inventory.
