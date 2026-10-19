# V-2 report — independent review on the RQ-002 background files

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| What was tested | **extraction fidelity** on 7 background files that Professor had read before. Not discovery, and not the KnowledgeOS corpus |
| Criteria | fixed in M-03 §5 before the run: **SUCCESS** = all disagreements adjudicated, no Professor LABEL-ERROR, no adjudicated Professor MISREADING of a definition or invariant · **FAILURE** = any of those · **INCONCLUSIVE** = reviewer access breach, or the matching rule cannot be applied |
| Evidence | `V2-RUN-RECORD.md` · `v2-professor/` · `v2-reviewer/` · `v2-adjudication/` (worksheets, ADJ-01…07, revealed label key, freeze manifests) · `V2-DISAGREEMENTS.tsv` (366 non-MATCH rows, un-blinded) |

## Result: **FAILURE (narrow)**

**Reason.** The adjudicators found **no** Professor MISREADING and **no** Professor LABEL-ERROR. They did find **two Professor items classed SPURIOUS**:
- F2 Q60: *"later eleven in the Constitutional Model"*
- F5 Q36: *"five in the Meta-Model"*

In both, I put a cross-document comparison into the meaning line of an item about a *different* source, without marking it [I].

SPURIOUS was **not** in the M-03 vocabulary. I introduced it in the adjudicator prompt. Mapped back to the predeclared classes, an inference recorded as if the source said it is exactly M-03's **LABEL-ERROR** ("interpretation recorded as evidence"). Applying the criterion as declared, the result is **FAILURE**. Treating the new class as outside the criterion would redefine success after seeing the outcome.

**Severity.** Both statements are *true* facts about other documents, recorded in the wrong place. The defect is a **breach of the R/T separation**, not a fabrication. It is narrow (2 of 635 items), systematic (the same habit twice), and has a precise fix (below).

## 1. Mechanical validity

| Check | Professor | Reviewer |
|---|---|---|
| File hashes, sources unchanged | PASS | PASS |
| Quote anchors | 538 / 538 PASS | 854 / 854 PASS |
| Line accounting | complete (7/7) | 6/7 complete; **one blank line (E-04 L40) unaccounted** |
| Freeze before comparison | 13:49 UTC, manifest `90d7263e…` | manifest `359cb168…` |
| Access audit | — | **PASS:** read exactly the 7 sources, wrote only its own outputs |
| Adjudicators (7 fresh sessions, blinded X/Y) | — | **PASS for reads:** each read only its source and its worksheet. One (file 06) wrote its output via a shell heredoc, contrary to the "no shell" instruction. That is procedural; it read nothing else |

## 2. Semantic agreement (849 source propositions identified by the adjudicators)

| | Professor | Reviewer |
|---|---|---|
| Propositions captured | 635 (**74.8%**, 95% CI 71.8–77.6%) | 842 (**99.2%**, 98.3–99.6%) |
| Foundational captured (169) | 160 (**94.7%**, 90.2–97.2%) | all except the 1 missed by both |
| MISREADING | 0 | 2 |
| LABEL-ERROR | 0 | 7 (1 foundational) |
| SPURIOUS | 2 | 1 |
| OMISSION charged | 205 (9 foundational) | 2 |

Also: 141 SCOPE (granularity or type choice, both faithful) · 1 BOTH-DEFENSIBLE (whether "( × AUTHORITY-SCOPE )" is a full fourth factor or optional) · **5 NEITHER** (missed by both; 1 foundational: the Synthesis binds *every future implementation* to the nine invariants, F6 L106).

**Reading.** The two readers fail in opposite directions:
- **I am precise but incomplete.** A quarter of propositions are missed, mostly sub-findings inside dense lines (104 of 205 omissions in F7, which I had flagged as summarised). There are 9 foundational misses: e.g. the Decision canonicity predicate in F5 L57, the product formula in F1 L31 (which I wrongly marked as a repetition), and "retirement is a status transition" in F7 L59.
- **The reviewer is complete but less disciplined.** It has 9 misreadings or label errors, e.g. stating its own definition of "second-population" as fact.

**Controls.** No control flagged my omissions before adjudication. Line accounting showed the lines as covered, because the misses were *within* lines I extracted from, or in lines I marked REPETITION. Only the independent second reading caught them. This is the residual failure mode RF-2 predicted in M-03.

## 3. Capture–recapture: **not estimable as a measure of misses**

Chapman's estimator gives N̂ ≈ 844.7 (±1.8), i.e. about 0.7 propositions missed by both. **This is not credible:**
- the adjudicators built the proposition set *from the two extractions*, so the population is not closed independently of capture;
- the two readers are the same model family (positive dependence, so N̂ is biased low);
- the adjudicators independently found **5** propositions missed by both, which already exceeds the estimate.

The defensible statement is a direct lower bound: **at least 5 of about 849 propositions (≥0.6%) were missed by both readers, 1 of them foundational.**

## 4. What V-2 does and does not establish

- **Establishes:**
  - quotations and line anchoring are mechanically reliable;
  - my interpretations were faithful wherever I captured something (0 misreadings);
  - the R/T separation leaked twice;
  - single-reader extraction at my granularity misses about a quarter of propositions and about 5% of foundational ones;
  - a second independent reader catches most of that.
- **Does not establish:**
  - discovery ability (I knew these files);
  - performance on the corpus;
  - independence beyond one model family.

## 5. Corrections (CORRECT before the first corpus batch; no new framework)

1. **R/T separation in extraction.** Meaning lines describe only the source at hand. Cross-document observations go to *Uncertainties* or the theory map, marked [I]. This addresses the cause of the FAILURE.
2. **Dense lines.** Every numbered or bulleted finding inside a line is its own item. No "summarised, not itemised".
3. **REPETITION-OF** is allowed only when the repeated item is word-for-word or a strict subset. Otherwise extract the item.
4. **Double reading is worth its cost.** It caught 205 omissions and 12 reviewer errors. The per-batch review sample (proposed in my last report) should keep a real independent re-extraction, not only an audit of my records.

These four corrections apply to extraction practice only. Templates and criteria are unchanged.
