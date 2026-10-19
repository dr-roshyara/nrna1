# Settlement of theory turn 3 (history: a primitive or derivable?), two reviewers

*2026-10-19. Reviewer A (Opus) and reviewer B (Sonnet), each with a blind step 1 and one step-2 message; neither saw the other's report. Harness time: A step 1 189 s, steps 2–3 604 s, total 793 s; B step 1 86 s, steps 2–3 330 s, total 416 s. First theory turn under §12b's two-reviewer rule (entry 51); the brief listed the C15 settlement changes (step 9).*

**Re-checked in the sources and the book:** S0129 L47 (the two aggregates), L158 (R-CONFLICT NOT SELECTED), L187 (28 grants and 33 transitions outside git); §1.2 ("a claim's history is valid iff … every change is labelled by a recorded act"); §2.11 RM-3; S1609 L405, L415, L595 (read topic-full at the settlement). All hold. **All findings of both reviewers are accepted.**

**Do the critical findings (A 1, 2) block?** No. They refute the turn's headline, and the settlement restates it from the evidence: a conditional decomposition, with the status relative to the other candidates open and one definitional question for the Principal (is verifiability part of "authority"?). Nothing downstream was built on the withdrawn result (dependency graph: §9.8 only).

## Comparison of the two reviews
| Point | A | B | Raised by |
|---|---|---|---|
| Independence from the six fails under the book's definitions (§1.2, §1.16.2, RM-3) | 2 (critical) | T1, T2 | both |
| H3 partly derivable (policy values are acts); split H3a/H3b | 3 | T2 | both |
| No must-do: soundness and completeness or marked absence (H4, H5); the empty record | 4 | T4 | both |
| Result 4 contradicted by S0129 L187 | 6 | T6 | both |
| D's premises hidden: θ index (F27), F26, x, near-definitional | 7 | T8, T9 | both |
| M2 typed over times while H2 fails | 8 | T13 | both |
| M1 under-specified on I-7 | 12 | T3 | both |
| Selection too narrow; S1609 | 10 | T11 | both |
| H1 too strong (retention, recoverable vs removed) | 5 | T12 | both |
| Cut as prefix needs τ monotone; cross-record cut | appendix | T5 | both |
| The record has no independence model (CON-0149) | verdict | T7 | both |
| Inverted logic: models where history fails are independence models *for* history | 1 (critical) | — | A only |
| State history vs act history; necessity rests on CON-0134 | 9 | — | A only |
| R-CONFLICT misread; not adopted | 11 | — | A only |
| Regress of θ(u) | 3 | — | A only |
| "a primitive defined by its queries" a non sequitur | — | T10 | B only |
| Plain language "none follows from the others" unscoped | (1) | T14 | B (A implied) |
| Changed theory: THEORY-MAP stale (three formulations); CON-0144 "COMPLETE"; §9.6 step 6 and option (C) | — (all VALIDATED) | §6 table | B only |

*Count:* 11 points raised by both, 4 by A only, 3 by B only (one shared in substance). The two critical findings came from A only, though B's T1/T2 reach the same refutation of result 2 at major severity.

## Measures
| Measure | Value |
|---|---|
| New concepts | 2 (CON-0148, CON-0149) |
| Findings | A: 12 (2 critical, 8 major, 2 normal); B: 14 (0 critical, 11 major, 3 minor) |
| INVALID share | 1 of 2 new concepts (CON-0148 as stated); results 2, 3, 4 INVALID as stated |
| Material omissions | 5 (H4, H5, act history, S0129 L187, S1609) |
| Changed theory (C15) | A: all checked items VALIDATED; B: 4 minor leftovers, fixed here |
| Reviewer time | A 793 s; B 416 s |

## Changes
- §9.8 rewritten: two kinds of history (state, act); H1′ (every change an entry) replaces H1; H2 with τ monotone and a cut rule; H3 split into H3a and H3b; H4 soundness and H5 completeness or marked absence; Lemma D with all premises, the regress F31 and CON-0134 for necessity; S1609's congruence criterion; M1–M5 checked against the book's definitions; results restated; forks F29–F31; §9.8.10 lists what did not hold.
- Register: CON-0148 INVALID as stated, restated; CON-0149 kept PROVISIONAL (Hyp), restated.
- Map: F29, F30, F31; the TT3 block; the three stale formulations of the C15 settlement corrected (REALIZED anchors, F26(b)/(c), "one authoritative home").
- §5.7 C1: "may close as COMPLETE" (CANCEL remains open to a lane with no output).
- §9.6 option (C): flagged as posed in TT1, "kernel-defined" not shown.
- BOOK-OUTLINE: the History row.

**Settlement self-check:** quote check §9.8 16/0, §5.7 23/0; strength scan of §9.8: remaining "only/never" are conditions of definitions or quotes; "Every Hist query … is of that kind" restated before commit. Grep for the withdrawn phrases ("not a primitive in the §12b sense", "independent of the six", "keeps H1 at the level of git") returns only §9.8.10.
**Dependency graph (§12d):** `--down CON-0148`: CON-0149, §9.8; `--down CON-0149`: §9.8. Nothing else reopened.
**For the Principal (not blocking):** is verifiability part of "authority" in the list of primitives, or a separate property? Should the list carry "the record" (or history under authority and policy)?
**Next review (step 9):** the C17 brief lists this settlement's changes as changed theory.
