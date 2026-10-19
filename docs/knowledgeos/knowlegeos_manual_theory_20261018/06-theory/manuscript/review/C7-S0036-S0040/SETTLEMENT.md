# Settlement of the group C7 review (S0036–S0040, §9.1–9.2, §5.4 C7–C8)

*2026-10-19. Report: `REVIEW-REPORT.md`; blind inventory: `STEP1-INVENTORY.md`. One commission, one step-2 message. Reviewer time: about 2 min (step 1) + 9 min (step 2) by the tool clock. All 16 findings accepted; findings 1 and 2 re-checked by running the model.*

**Running measure:** 16 findings · 1 critical · 4 material omissions · 1 defect found in work that had passed a check: §9.1's non-uniqueness had passed my own model run and the Principal's settle step, and was found here.

## Verdicts applied

| CON | Verdict | Scope |
|---|---|---|
| 0099 | VALIDATED | closed reading; A1 ∧ A2 fragment |
| 0100 | PROVISIONAL | minimality relative to the pool |
| 0101 | PROVISIONAL | — |
| 0102 | VALIDATED | as a distinction |
| 0103 | PROVISIONAL | — |
| 0104 | VALIDATED | restated (criterion of change, held by ARB/DA) |
| 0105 | VALIDATED | as a non-implication |
| 0106 | **INVALID** | replaced by standing × recorded supersession |
| 0107 | VALIDATED | as a source description |
| 0108 | VALIDATED | restated; blocking enforcement |

## Changes made

| Finding | Change |
|---|---|
| **1 (critical)** | `kernel_model.py` rewritten. grant2 (the same function as grant) removed and recgrant (a coarser, different mechanism) added. The script asserts that the pool is functionally distinct. New result: the minimal kernel is unique for the fine pool ({record, grant}); recgrant adds a second, {recgrant}. Non-uniqueness depends on the pool. Restated in §9.1 C4, 09.00, RESEARCH-PURPOSE deliverable 5, T9-04 and the map. |
| 2 | §9.1 C3, new: existence iff the union of individually preserving mechanisms meets Req, with proof, checked in the model. C5 and T9-03: minimality relative to the pool. Fork F16. |
| 3 | §9.1 C1: two readings (closed, guard), with a trivial case for each. "Inconsistent" → "under-specified"; liveness → reachability. Fork F17. |
| 4, 5 | §6.4 C3: V-2's breach is not established (CON-0059); I-3 is held, not enforced (a homonym of "enforced"). |
| 6 | §6.4 C1: "determinate given the scores"; the source overrides its own bar; there is one rater. |
| 7 | §5.4 C8 item 3: two cases; fork F15 (what Sat refers to). |
| 8 | §8.2 C3 and CON-0106: two variables. |
| 9, 10 | §9.2 C1 as a non-implication by counterexample; C2's identification with the first boundary question removed. |
| 11, 12 | §5.4 C8: proof-status table; S0721 lineage; S0896 "absent" cells marked unverified. Appendix: applicability via the validity field; Sārathi and A1. |
| 13 | §6.4 C8: one line of support. |
| 14 | CON-0108 restated; the placement check's own "unruled" value is recorded as unverified. |
| 15 | CON-0103 stays PROVISIONAL. |
| 16 | §4.2 C1 (projections only), C2 scoped, C5 weakened. |
| Book errors | "eight mechanisms" → seven; "S0001 later" corrected (S0001 is earlier, and the phrase is cited from S0001 L29). |
| Coverage | the stopping rule, the owner field, AR-7 and S2377's backbone order go into the map as topics not yet in the book. |

## Not done in this settlement
- The proof that P is closed under union is general; the reviewer's computation only illustrates it.
- The guard reading of §9.1 C1 is not modelled.
- S0896 is still not read in full, so the C8 table's S0896 column stays partly unverified.
- The stopping rule and uncertainty carriage (S0036) are not yet written into Ch. 5; they are a topic in the map.
