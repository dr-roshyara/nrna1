# Settlement of review C11 = Trial T3 (S0056–S0065 + S0164, S0913, S0067, S0071)

*2026-10-19. Report: `REVIEW-REPORT.md`; blind inventory: `STEP1-INVENTORY.md`. One commission, one step-2 message, no other messages.*

**Re-checked before accepting:**
- the critical finding: Ch. 1 §1.14.2 ARCH-INV-1 (L214) and §1.14.3's ruling "a plane, not a bounded context" (L260) exist, and §6.5 did not cite them;
- the LR counterexample: recomputed, LR₁ = LR₂ = 1.5, joint 50;
- finding 11: S0056 L60 keeps "8 workflow records" by design.

All three hold. **All 17 findings are accepted.**

## Trial T3 measures (METHOD-VERSIONS entry 47)

| Measure | Value |
|---|---|
| Sources | 10 in index order + 4 added under §12a (limit 5) |
| New concepts | 10 (CON-0125 … CON-0134) |
| Findings | 17: 1 critical · 10 high · 6 normal |
| Findings per new concept | 17 for 10 concepts (some findings concern claims without a concept ID) |
| INVALID share | 1 of 10 (CON-0133, as a duplicate); 3 VALIDATED (0126, 0129, 0131), all scoped; 6 PROVISIONAL |
| Material omissions | 7 (the reviewer's count): authority endings never exercised; I-5 breached in practice; REPRODUCIBLE as the corpus's substitute for INDEPENDENT; caveats dropped at acceptance; BC-7's must-do and its central law; scope authorship and process lineage as factors; the F4 link |
| Critical findings | 1: the existing home ARCH-INV-1 and the ARB "plane" ruling were missed by the search; CON-0133 duplicated ARCH-INV-1 |
| Reviewer time (harness) | step 1: 227 s (15:03:58Z → hand-back, 3 min 47 s); steps 2–3: 286 s (15:08:33Z → hand-back, 4 min 46 s); total 513 s (8 min 33 s) |
| Reviewer coverage limits (its own) | S0164 read in parts; 8 of the named dependent sections not read; the book search not run |

*Counts only (entry 46): the windows are not a random sample.*

## Verdicts applied (register and book tags)

| ID | Verdict | Scope |
|---|---|---|
| 0125 | PROVISIONAL | restated: seven factors ("lineage" split; scope authorship added); orthogonality not shown |
| 0126 | **VALIDATED** | for common-mode failures (same wrong output) under the deterministic model |
| 0127 | PROVISIONAL | needs a truthful record as well as an open world |
| 0128 | PROVISIONAL | product rule and copy case valid; "between" INVALID; the lineage rule is a heuristic |
| 0129 | **VALIDATED** | as a sufficient condition |
| 0130 | PROVISIONAL | theorem correct; premise unmet; caveats added; no m estimable |
| 0131 | **VALIDATED** | where the check is the only enforcement point and the caller supplies the scope |
| 0132 | PROVISIONAL | "the reason lives in" is not yet operational; a breach added |
| 0133 | **INVALID** as a new concept | duplicates ARCH-INV-1; merged; plane or context is F22 |
| 0134 | PROVISIONAL | needs a fixed fold function; not new (§1.14.1) |

**Claims without an ID:**
- §8.6 C5: PROVISIONAL;
- §8.6 C6: VALIDATED (an instance of A1);
- §8.7 C4, C5: VALIDATED (instances);
- §6.5 C5: VALIDATED (relocation);
- §5.6 C6's tension: VALIDATED.

**Marking (kernel or application):** the reviewer marks 0125, 0129 and 0130 as assurance practice (application). 0131–0134 bear on the authority primitive, and whether they are kernel depends on F22.

## Changes made

| Finding | Change |
|---|---|
| 1 (critical) | §6.5: new C0 (classified twice; F22); C1 re-tagged as an instance of ARCH-INV-1; C6's kernel Hyp on hold; existing homes extended; F22 in the map and outline |
| 2 | §8.6: common-mode definition; plain-language sentence and C2 scope restricted |
| 3 | §8.6 C3: truthfulness premise, with three corpus counterexamples |
| 4 | §8.6 C4: "between" struck; counterexample added; the lineage rule called a heuristic |
| 5 | §8.6: factors restated as seven (process vs information lineage; scope authorship, S0164 L1789) |
| 6 | §8.6 open question 2: REPRODUCIBLE as the corpus's competing answer |
| 7 | §6.5 C2: authority endings never exercised (S0071 L42, L46, L187, L154) |
| 8 | §6.5 C2: I-5 breached in practice (S0071 L11, L237); the "2 of 5" denominator queried as a possible source defect |
| 9 | §6.5 C4: fixed-fold premise; C-2 cited (S0062 L112) |
| 10 | §6.5 lineage and §5.6 C6: the reviewer's nature UNKNOWN; **question for the Principal** |
| 11 | §8.7 C1: "asserted as current" (S0056 L60) |
| 12 | §6.5 C3: narrowed (lane separation enforced; process-to-lane binding not) |
| 13 | §8.7 C2: "sufficient" |
| 14 | §8.7 C3: three caveats (expected size; correlated findings; a filter can hide defects) |
| 15 | §6.5 C1: the law "refuse malformed, never unauthorized" (S0071 L282, L293) and BC-7's must-do (S0067 L73–77) |
| 16 | §6.5 open questions: F4 link (S0071 L67) |
| omissions | §5.6 C6: caveats dropped at acceptance (S0058 L51 vs S0061 L19; S0064 L5 vs S0065 L16) |

"What does not hold" is filled in §8.6, §8.7 and §6.5.

## Not done here

- **Source defects recorded, not repaired:** I-9 missing in S0067/S0071; S0065 L16 citing the superseded verdict (recorded in §5.6 C6).
- **Terms used without a definition in the book:** "lane", "BC-7". Left for the consolidation review's sequence check, as a test of what this review missed.
- **The question for the Principal:** were the "Principal DDD Architect" reviews in S0064 human or AI?

## Running measure

17 findings · 1 critical · 10 high · 7 material omissions. **The critical finding is mine:** the book search found §1.14.1 and §1.14.5 for "authoriz…" but not §1.14.2, so ARCH-INV-1 was never read. "Nothing found by this search" held for that search; the home existed.
