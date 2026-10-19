# Settlement of the window C9 review (S0046–S0050 + S0091, S0113)

*2026-10-19. Report: `REVIEW-REPORT.md`; blind inventory: `STEP1-INVENTORY.md`. One commission, one step-2 message. Findings 4 and 5 were re-checked in Ch. 0 §0.7, Ch. 1 §1.14.5 and §1.16.6, and hold. All 8 high and 6 normal findings are accepted.*

**Reviewer time.**

| Step | Tool clock (reviewer's `date -u`) | Harness |
|---|---|---|
| Step 1 | 31 s (the two calls did not bracket the reading) | ≈ 105 s |
| Step 2 | 13:36:22 → 13:40:17, 3 min 55 s | ≈ 5 min 27 s (432 s total minus step 1) |

The two still differ. The harness figure includes the hand-back.

**Running measure:** 14 findings · 0 critical · 8 high · 4 material omissions (findings 2, 4, 8 and the coverage items) · 0 defects found in work that had passed review.
- **Mine again:** the new book search was lexical, and its miss ("distribution: 0 hits") became a claim of novelty. This is the error CON-0120 describes. The fix is in the method below.

## Verdicts applied

| CON | Verdict | Valid core / scope |
|---|---|---|
| 0114 | INVALID as stated | a totally derived read rule cannot justify a state boundary; totality presupposes a functional selector |
| 0115 | PROVISIONAL | no invariant owner (detection only) |
| 0116 | INVALID as stated | a declared, not enforced, constraint; home §1.6/RC-37a, P-E |
| 0117 | PROVISIONAL | re-homed under §0.7/§1.16.6; its order clause INVALID; it needs a must-do partner |
| 0118 | INVALID as stated | restricted to two-valued responses; homes CON-0108, CON-0081 |
| 0119 | INVALID as three classes | two axes: novelty and demonstration |
| 0120 | VALIDATED | scope, method and completeness stated |

Each refuted claim is now in its section's **"What does not hold"** (§9.4.4, §9.5.4, §8.4.4), as Rulebook §4 now requires.

## Changes made

| Findings | Change |
|---|---|
| 1, 3 | §9.4 C1 restated (default value; functional selector). C2: premise stated; §1.16.1 cited; §1.16.7 read as "over the write set". |
| 2 | §9.4 C3: no owner for "one current claim" (detection only). |
| 7 | §9.4 C4: "declared closed"; re-homed under §1.6/RC-37a and P-E. |
| coverage | §9.4 C5: the self-establishment tension (fork F20). C6: termination asserted, not shown. |
| 4, 8 | §9.5 C1 re-homed as the failure case of §0.7 via a pinned reference (§1.16.6); fork F18; degenerate without a must-do partner; the "gap in the system" claim marked Hyp; "current" qualified. |
| 6 | §9.5 C4 restricted to two-valued responses; CON-0108, CON-0081 and the third sense of "Missing" cited; zero of five decidable. |
| 5 | §9.5 C5 re-homed under §1.14.5 (INV-A3 not adopted; advisory is correct; the confirmation act); only the residue left open. |
| coverage | §9.5 C2 linked to Ch. 2 §2.8.7. C3: "every" has a counterexample (I-4), and is partly true by definition. |
| CON-0119 | §8.4 C1 as two axes; C2: the convergent side is selected too; C3: the book's own miss recorded, "exhaustive" scoped, the enforcement homonym; C4: the 104 hits were unclassified. |
| forks | F18 pinned vs resolved at use · F19 uniqueness vs aggregation · F20 self-establishment of an evidence-only act. |

## Method change (proposed for the Principal; applied in my own practice now)

Step 3's book search is lexical. From the next window on, I run it with synonyms and the book's own vocabulary for each topic (for example: distribution → assembl · working context · live · pinn · propagat), and I state the terms searched in the step-8 file. A zero-hit result is reported as "no hit for these terms", never as "no home".

## Not done
- Ch. 1 §1.16.7's wording ("global over the write set of the property") is for the next consolidation (finding on an earlier section).
- The mapping of S0113's five conditions onto E1–E10 is not done.
- PM-1's identity across S0046 and Ch. 1 §1.2 is not checked.
