# After the C10 planted-gap test: the test reviewer's omissions, checked against the book

*2026-10-19. Principal's instruction: "Check each with the book search: write in what has no home, and cite what already has one. Also write back the planted points that are now missing from the test copies… only check that nothing went astray."*

*Input:* `review/TEST-C10-planted-gaps/REVIEW-REPORT.md` (findings 3–13 and the coverage table), written by the Rule Reviewer's test run. That folder is not mine and is not edited here.

## 1. The planted points: nothing went astray

The test copies were made from commit 5cfd2abea.
- **§8.5 is unchanged since that commit.**
- **§5.6** differs only by C6, added in Trial T3 (committed).

A grep of the live files finds all five omissions (O1 shape, O2 supersession, O3 false unknowns and disguised declarations, O4 "as at", O5 separate verbs and producer ≠ disposer) and the true text of both distortions ("NOT YET CONFIRMED", "No defect changes the direction"). The §5.6 C4 "Limit" bullet (supersession accounting) is present. **Nothing needed writing back.**

## 2. The test reviewer's omissions

Book search, lexical and by meaning (output in the session).

| Test finding / coverage row | Home in the book? | Action |
|---|---|---|
| 3 · independence only Declared | **yes:** §8.6 C3 (T3), §5.5 C2 (S0129 "not attestable") | cited, with S0051 L30–31, S0054 L89, S0055 L95–96, as a scope bullet in §8.5 C2 |
| 4 · the approved object is a composite stack; V-1 standing | partly: §1.16.6 (pinning), §5.6 C4 Limit, §8.5 C8 | **written in:** §5.6 C7, first bullet (restricts C3) |
| 5 · landing before independent verification | nothing found by this search | **written in:** §5.6 C7, second bullet |
| 6 · the Declared→Observed upgrade | the probes are in §5.5 C2; the grade change had no home | **written in:** §8.5 C9 |
| 6 · evidence hierarchy recorded, not settled | the map lists it as "not yet in the book" | **written in:** §8.5 C9 |
| 6 · boundaries not from names | §6.5 C3 (S0062 L104) | cited in §8.5 C9 |
| 6 · wrong measurement vs disguised declaration; false unknowns | §8.5 C3 (planted O3; present) | none |
| 7 · temporal change vs defect | §8.5 C6 (planted O4; present) | none |
| 8 · count errors recur; erratum, never rewrite | nothing found by this search | **written in:** §8.5 C9 (six cases, count only) |
| 9 · C7's scope; "not re-litigated" (S0051 L39) | — | **written in:** a scope bullet in §8.5 C7 |
| 10 · confounded sufficiency case | — | **written in:** §8.5 C2 |
| 11 · D-3, declared vs registry (composition root) | §3.9 and §1.12 hits are other subjects | **written in:** §8.5 C9 |
| 12 · accept / approve / establish homonyms | nothing found by this search | **written in:** §5.6 C7, third bullet |
| 13 · producer ≠ disposer | §5.6 C5 (planted O5; present) | none |
| coverage · negative scope (INV-ATTR-4/5/6 not adopted) | §5.6 C4 lists the levels, not the non-adoptions | **written in:** §5.6 C7, fourth bullet |
| coverage · the Stage-2 gate across work items | §5.6 C6 (S0061 L51) in T3 | cited in §5.6 C7 |
| coverage · assessor standing and its regress | §8.6 C3 (one-sided independence) | cited only; no new text |
| coverage · V-D, a method-dependent count | — | **written in:** §8.5 C9 |
| coverage · V-B, V-3, "do not accept v1.0 unamended" | not written | left: minor, or already covered by §8.5 C7 and C3 |
| findings 1–2 (the distortions) | the true text is in the book | none |

**All written-in text is marked provisional and not yet reviewed.** It enters the consolidation review's scope (S0036–S0065 includes S0051–S0055).

## 3. Count

- 11 test findings (3–13) and 13 coverage rows checked.
- Written in: 11 points (§8.5 C2 ×2, C7 ×1, C9 ×4 bullets; §5.6 C7 ×4 bullets).
- Already at home, cited: 6.
- Left out, with a reason: 3 minor rows.
