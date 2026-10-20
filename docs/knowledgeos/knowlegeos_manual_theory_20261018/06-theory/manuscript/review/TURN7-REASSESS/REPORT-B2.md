# Turn 7, commission B: report B2 (Sonnet), on §9.14 v3

*Reviewer B2. Object: `chapter_09/09.14-when-pairs-suffice.md` v3 in full, `tmp/tools/helly_check.py` v3 and its run, CON-0192, EXT-081–083, the §2.3 (d) pointer. No scratch script was needed: I read the tool and the run and derived the checks by hand. One public web lookup (Chepoi et al., via the talk abstract only; no book text sent).*

## Verdicts

| Claim | Verdict | Label |
|---|---|---|
| R1 (a), (b), (d) | adequate as stated | Ded |
| R1 (c) converse, under realisability | adequate | Ded |
| "Sound" = reproduces the set-wise report (both directions of R1 (d)) | the right definition; the word is unfortunate (B2-05) | Ded / Int |
| R2 product lemma | adequate | Ded |
| R3 new row, dense order, closed thresholds: h = 3 | proved | Ded |
| R3 new row, dense order, strict only: h = 2 | holds only if MUST_NOT of strict thresholds is not admitted (B2-01) | Ded |
| R3 new row, "unbounded when the order has finite intervals of every size" | true as a sufficient condition; not the exact condition for finite versus unbounded h (B2-01) | Ded |
| R3 n on n values, ℕ (m + 1), ℤ (m + 2) | adequate | Ded |
| R4 | holds for X ≠ ∅; edge case at X = ∅ (B2-04) | Ded |
| R5 (a)–(d) | adequate | Ded |
| Check 7 computes what the text cites | partly: finite stand-ins and a cap, not disclosed in the text; dense case not computed (B2-02) | Src |
| Tautology left in the tool | none in the cited checks; check 6's line is marked in the text, not in the tool (B2-02) | Src |
| Rivals within public abstracts; none contains R1 | fair; Beeri et al. checked only by recall, so U (B2-06) | Ext |
| §9.16 R1: §9.14 "untouched" | holds for records and CE*; "no trust assumption" needs a qualifier (B2-03) | Src |

## Findings

| ID | Finding | Severity | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| B2-01 | The new row's domain conditions are not an exact dichotomy. (i) Orders between "finite intervals of every size" and "dense" exist, with finite h > 3. Example: ℚ × {0,1}, lexicographic. A finite convex intersection J contains at most the two points of one q (two points from different q's span an infinite interval). With x ≥ (q,0), x ≤ (q,1), x ≠ (q,0), x ≠ (q,1), the family is minimal with 4 members, and no minimal family is larger: h = 4. In general h = s + 2 for the largest finite J of size s (fewer at an endpoint). (ii) "2 if only strict thresholds are admitted" is not closed under the admitted MUST_NOT of a one-sided threshold (R5 (d)). MUST_NOT x < a is x ≥ a, which is closed; with MUST_NOT x > a and x ≠ a the family is minimal with 3. | normal | §9.14.3 R3 row; R5 (d) h = 2 list; hand derivation | State the exact condition: h is finite iff the sizes of finite intervals are bounded, and h ≤ s + 2. Keep "3 on dense" as the s = 1 case. Add "strict only" as a property of the admitted forms, with MUST_NOT of a strict threshold excluded. | the row gives h as a function of the largest finite interval size; the strict case names its exclusion |
| B2-02 | Tool and text. (a) Check 7's "h = n" runs `helly(..., cap=n)`; it is a lower bound, and R4 supplies the upper bound. Check 1 discloses caps in the text; check 7 does not. (b) The "ℤ" case runs on −5..11, a finite window. The text cites "check 7" without saying so; only the run says "a stand-in". (c) The dense-order claim, "3 on a dense order (checks 6, 7)", has no computation. Check 7's 0..4 case has one forbidden point and so only supports R5 (c). The proof is in the text only, and is adequate. (d) Check 6's "required values with thresholds h = 2" recomputes the interval class. The text marks it; the docstring and the printed line do not. (e) Check 3 for clauses reached size 3 only, not the h = 4 bound. (f) Check 2 searches products only up to 4 members (disclosed). | normal | `helly_check.py` v3; run v3 | Say in the text "capped at |X| (R4)" and "a finite window stands in for ℤ". Drop "(checks 6, 7)" for the dense claim, or add a dense-order proxy. Mark check 6's line in the tool output. | the text states the cap and the stand-in, the dense claim cites its proof and not a check, and check 6's line is marked in the run |
| B2-03 | §9.16 R1 says §9.14 "reads no record, no CE* and no trust assumption". By grep, §9.14 mentions no record of D, no CE* and no entry of D (hits for "record" and "entries" are runs and proof lists). But §9.14.6 carries its own world assumptions: the outcome space, the formalisation of effects, the domains. §9.15 G5 lists R1 as a guarantee "under its premises". The claim is true for §9.15's K and L assumptions, and false if "trust assumption" is read generally. | normal | §9.14.6; §9.15 G5; §9.16 R1 table row | Reword the row: "reads no record or CE*, and uses none of K1–K7 or L1–L7; its own premises are §9.14.6". | the row is reworded |
| B2-04 | R4 and the table have edge cases. If ∅ ∈ 𝓔, h ≥ 1; and if X = ∅, then h = 1 > |X| = 0. "Required or forbidden, max(2, k)" at k = 1: the complement of {a} is ∅, so h = 1. The proof uses x_A, which needs |F| ≥ 2. | normal (low) | R4 proof; R3 rows | Add "X ≠ ∅, k ≥ 2". | the premises are stated |
| B2-05 | "Sound" here means reproduces the set-wise report, which is soundness and completeness. In logic, soundness means no false positives, and pairs-only checking never gives false positives. Both directions of R1 (d) hold with this definition: m ≤ 2 gives all minimal sets of size at most 2, whose regions are computed the same way; m > 2 gives a reported set the pair test cannot output. The point-flag example {p ∨ q, ¬p, ¬q, r, ¬r} is correct (all pairs among the first three are satisfiable). | low | §9.14.2 "Sound"; R1 (d) | Rename to "adequate" or "reproduces the report". | the term is changed, or the definition notes that it includes completeness |
| B2-06 | Rivals. Chepoi et al.: consistent with the public talk abstract (at most two literals per clause: polynomial iff the family has the Helly property; I did not see the paper's abstract). It uses the Helly property of a set family, not a bound on subset size of a rule base, so it does not contain R1. Beeri et al. and van Beek–Dechter: I confirmed neither against a public page (U); the descriptions are hedged and plausible. R1 is not contained in any of the four. | low | web lookup; §9.14.5 | None required; keep "as summarised by the authors' talk abstract". | no change needed |

## Proof-step tables (derivations judged adequate)

**Dense order, closed thresholds, h = 3.**

| Step | Claim | Justification |
|---|---|---|
| 1 | Let F be minimal empty, J the intersection of its intervals (convex) | definition |
| 2 | J empty: two intervals are already disjoint | Helly in dimension 1 (the proof in the section) |
| 3 | J a single point a: two closed intervals give it; with x ≠ a, 3 members; minimal | closed thresholds only |
| 4 | J with at least 2 points: infinite by density, so finitely many point complements leave a point | density |
| 5 | so h ≤ 3, and {x ≤ a, x ≥ a, x ≠ a} attains 3 | 1–4 |

**Strict thresholds only (conditional on B2-01 (ii)).** J is open, hence empty or infinite; steps 2 and 4 give h = 2.

**ℕ and ℤ.**

| Step | Claim | Justification |
|---|---|---|
| 1 | ℕ: {x ≤ m − 1, x ≠ 0..m − 1} is empty | all values excluded |
| 2 | dropping the threshold leaves m; dropping x ≠ j leaves j | minimal, m + 1 members |
| 3 | ℤ: the same family has −1; with x ≥ 0, empty and minimal with m + 2 members | the same argument |

**R2 product lemma.** Steps 1–5 as in the section: valid. The factor class must contain the full domain (unconstrained attributes), which the text implies; h(𝓔ₐ) = 0 cannot arise for a coordinate that empties the product.

**R1 (a)–(d).** Steps 1–7 valid. For R1 (c), a minimal F with |F| ≥ 3, realised at one point, has every pair satisfiable, so no pair shows the conflict.

## Coverage

- **Read in full:** §9.14 v3, the tool and its run, §9.16 R1 row and its context, §9.15 G5.
- **Checked by hand:** R1 steps, R2, the new row (every case), R4, the check-7 code path, check 3's two analysers (they are independent in code).
- **Not checked:** the outside works' full texts (U). The corpus rows of §9.14.7 and the quote check were not re-done.
- **No critical or high finding.** Every claim in the "resolved when" list is sound or corrected by the entries above, except the new row's two scope statements (B2-01).
