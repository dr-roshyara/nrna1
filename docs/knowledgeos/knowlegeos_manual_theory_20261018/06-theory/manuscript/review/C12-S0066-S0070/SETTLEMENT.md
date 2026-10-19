# Settlement of review C12 (S0066–S0070)

*2026-10-19. One reviewer, one commission and one step-2 message. Report: `REVIEW-REPORT.md`; inventory: `STEP1-INVENTORY.md`. Harness time: step 1 90 s, steps 2–3 211 s, total 300 s.*

**Re-checked:**
- S0066's dates (mtime 2026-08-17 16:17Z; first commit `f24edf316` 2026-08-28);
- S0067 L9 and L93, S0069 L13 and L53–54, S0070 L7.

All hold. **All 12 findings are accepted.**

## Measures

| Measure | Value |
|---|---|
| New concepts | 2 (CON-0139, CON-0140) |
| Findings | 12: 0 critical · 5 high · 7 normal |
| INVALID share | 1 of 2 (CON-0139, as stated) |
| Material omissions | 7 (findings 3, 4, 6, 7, 9, 11, 12) |
| Reviewer time (harness) | 300 s |

## Changes

| Finding | Change |
|---|---|
| 1 | C1 restated, "no second authoritative home", with the admissible copies (§0.7, §1.14.4, §1.16.6); CON-0139 INVALID as stated; "a link" corrected to "a path pointer" |
| 2 | C3: the homonym at S0066's top node ("authority" = canonical home); outline Authority row |
| 3 | §9.6.5 RL row: S0067 L9 and S0070 L7 added; two instances, one line |
| 4 | C8, the direction law (S0066 L99; S0069 L53–54 Observed); the test against §1.14.4 left open |
| 5 | C2 downgraded to an analogy (one worktree, one git identity) |
| 8 | lineage: dates corrected; S0066 and S0070 counted as one line; S0066's rules have no adopting act |
| 9, 11 | C9: harness vs knowledge, blurred in the source; stop conditions are the refusal half |
| C4 | closure as a governance act (S0067 L93); the states are unordered |
| 6, 7, 12, appendix | recorded in the map as "not yet in the book" for the next consolidation (I-0's time clause; CAP-03 not built; "governance" in two senses; the realization vocabulary; claim → evidence → assessment) |

**Not changed:** the source defects (S0067 I-9 gap; S0070 L35 omits I-4; S0070 L25 drift) are recorded in the report.

## Dependency graph

`dependency_graph.py --down CON-0139`: 1 downstream item (§6.6, its host), revised.

## Note for the run

C12 began before the Principal's instruction to rewrite one Ch. 0–2 section per window, so it has none. The rewrites start with §1.14 in C13.
