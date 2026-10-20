*Report of reviewer B1 (Opus), returned as hand-back text, verbatim (saved by the Professor, 2026-10-20; the reviewer's Write call was refused).*

I could not write REPORT-B1.md: the harness refused the Write with "Subagents should return findings as text, not write report files". The full report follows. It belongs at W/06-theory/manuscript/review/TURN7-REASSESS/REPORT-B1.md.

**Summary**
- R1 (a)–(d), R2 and R4 are adequate as proved.
- The dense-order case is proved: h = 3 with closed thresholds, 2 with strict ones only.
- "Sound" (reproduces the set-wise report) is the definition R1 needs, in both directions.
- §9.16's verdict "untouched" holds for R1–R5.
- No critical or high finding. There are 13 normal ones.
- The main ones:
  - "an order that is partial" is listed as h > 2, which is false as worded (B1-01);
  - the new row's cases are not exhaustive: infinite orders with bounded finite intervals have h = K + 2 (B1-02);
  - R5 (c) needs closed thresholds (B1-03);
  - "adds one member to each minimal set" is false (B1-04);
  - the rival wording has three defects (B1-07 to B1-09).

# Turn 7, commission B: report of reviewer B1 (Opus)

*2026-10-20.*

**Object:**
- §9.14 v3 in full;
- `tmp/tools/helly_check.py` v3 and its run `helly_check-run-20261020-v3.txt`;
- CON-0192 and EXT-081–083;
- the §2.3 (d) and §2.6 pointers;
- §9.16 R1's row for §9.14.

**Access:** I opened no corpus file, no basis file and no other report. Outside works were checked on public pages only, with search terms limited to author names and titles. Scratch check: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/t7b1_check.py`, run with python3 -I.

**Labels:** Fact (read in the file or run) · Ded (my derivation) · Ext (public page) · Hyp · Op (my opinion).

## 1. Verdicts

| Claim | Verdict | Basis |
|---|---|---|
| R1 (a) bound, (b) reduction | holds | the table is adequate (§3 C) |
| R1 (c) converse under realisability | holds | the realised base also fails the weak point-flag property (B1-12) |
| R1 (d) | holds | follows directly from "sound" as defined |
| "Sound" means it reproduces the set-wise report (Q3) | adequate in both directions | §3 D |
| R2 product lemma | holds | R5 (c)'s equality has only ≤ proved (B1-11) |
| New row, n on n values | holds | R4 plus the point complements; check 7 |
| New row, ℕ (m + 1) and ℤ (m + 2) | holds | proofs at lines 114–115 |
| New row, dense order: 3 with closed thresholds, 2 with strict (Q1) | proved | §3 A. The check citation is wrong (B1-06) |
| "Unbounded when the order has finite intervals of every size" (Q2) | true and in fact exact (iff), but the list of cases is not exhaustive | §3 B; B1-02 |
| R4 | holds | the proof is adequate |
| R5 (c), one interior forbidden value gives 3 | holds only with closed thresholds | B1-03 |
| R5 (d), the h = 2 list | holds | — |
| R5 (d), the h > 2 list: "an order that is partial" | false as worded | B1-01 |
| Convention "the coupling is part of X" | justification overstated; boundary unclear | B1-04, B1-05 |
| Check 7 (Q4) | computes what is cited, with two citation overreaches; no tautology left in check 7 | B1-06, B1-10 |
| Rivals (Q5) | fair in substance; three wording defects. None contains R1 as stated, but R1 (a)/(b) are the definition of h, as the book itself says | B1-07, B1-08, B1-09 |
| §9.16 R1, "untouched" | holds for R1–R5; "reads no record" is imprecise | B1-13 |

## 2. Findings

Every finding is normal. None is critical or high.

**B1-01 (normal). "An order that is partial" in the h > 2 list is false as worded.**
- **Finding:** R5 (d)'s h > 2 list (line 164), and §2.3 (d)'s pointer, include "an order that is partial … (up-sets…)". Not every partial order gives h > 2.
  - The book's own boxes are the order intervals of the product partial order, and the book gives them h = 2.
  - Up-sets and down-sets in a rooted tree also have h = 2.
  - Check 7 shows h ≥ 3 for one poset only.
  - The cyclic case likewise depends on admitting arcs of every length.
  - The error is on the safe side, but it misleads the choice for hierarchies such as placement paths, which are trees.
- **Evidence:** Ded, plus scratch (c): product order 3×3, intervals with up- and down-sets, h = 2; tree poset, h = 2.
- **Correction:** "some partial orders (e.g. check 7's poset); not product orders or trees". For cyclic orders: "when arcs of every length are admitted". The same change goes in §2.3 (d).
- **Resolved when:** both lists name the class by an existence condition.

**B1-02 (normal). The new row's three cases are not exhaustive.**
- **Finding:** The row gives n on n values, unbounded on ℕ or ℤ, and 3 on a dense order. R5 (c), the Status line and CON-0192 read this as a classification. It is not one.
  - Infinite total orders whose finite intervals have at most K ≥ 2 points have finite h = K + 2.
  - Example: ℚ×{0,1}, ordered lexicographically, has h = 4, through {x ≥ (q,0), x ≤ (q,1), x ≠ (q,0), x ≠ (q,1)}.
  - The unboundedness condition is exact (iff), but the text only says "when".
- **Evidence:** §3 B; scratch (a), a block model, gives h = 4.
- **Correction:** state the formula h = max(2, sup over finite convex J of |J| + t(J)), where t = 0 if J = X, 1 for a proper initial or final segment, and 2 otherwise.
- **Resolved when:** the row, R5 (c) and CON-0192 give the formula, or say "for example".

**B1-03 (normal). R5 (c)'s interior-value claim needs closed thresholds.**
- **Finding:** R5 (c) (line 152) says "with thresholds admitted, forbidding one interior value raises h to 3". The new row itself says that on a dense order with strict thresholds only, h = 2.
- **Evidence:** §3 A, step 3.
- **Correction:** add "with closed thresholds, or on a discrete order".
- **Resolved when:** R5 (c) and the row agree.

**B1-04 (normal). "Adds one member to each minimal set" is false.**
- **Finding:** Line 30 says that making the coupling a rule "adds one member to each minimal set".
- **Countermodel:** on X = exactly one of p, q, r, {MUST p, MUST_NOT p} is minimal with 2 members. With the coupling C as a rule, {C, p, ¬p} is not minimal, and {p, ¬p} stays minimal.
- **Exact statement:** F ∪ {C} is minimal iff F is satisfiable without the coupling. So h rises by at most 1.
- **Evidence:** Ded; scratch (b).
- **Correction:** state it as above.
- **Resolved when:** the word "each" is corrected.

**B1-05 (normal). The convention's boundary is unclear.**
- **Finding:** Three sites treat "exactly one owner" differently:
  - §9.14.2 fixes the coupling as part of X;
  - §9.14.6 still says "as a rule, or part of X";
  - R3's cardinality row counts "exactly one owner" as a rule, minimal with 4. Under the convention it would be in X, giving 3.
  - Both values are above 2, so no conclusion changes, but nothing defines coupling against obligation.
- **Evidence:** lines 30, 99, 199.
- **Correction:** define the distinction, e.g. a coupling is a schema constraint that no rule states. Align §9.14.6 with it, and note in the cardinality row that the owner constraint is a rule.
- **Resolved when:** one rule governs all three sites.

**B1-06 (normal). Two citations claim more than the checks compute.**
- **Finding:**
  1. The dense-order sentence cites "(checks 6, 7)". No check computes on a dense order or with strict thresholds.
  2. "On ℤ … minimal with 5" is computed on the window −5..11 with m = 3. The window is a valid stand-in, but the text does not say so.
- **Evidence:** line 91, line 131; tool lines 221–223.
- **Correction:** cite the proof for the dense case, and name the window for the ℤ case.
- **Resolved when:** the citations match what the checks compute.

**B1-07 (normal). The §9.14.7 table is out of step with §9.14.5.**
- **Finding:**
  - The Ext table has no rows for EXT-081–083.
  - It calls Dechter–van Beek 1997 "the closest rival". §9.14.5 instead calls Beeri et al. "of the closest kind" and Chepoi et al. "closer".
  - The gloss "local consistency on subsets of constraints" goes beyond the abstract, against line 186. EXT-076 uses the same gloss.
- **Evidence:** lines 183–186 vs 232; EXTERNAL-SOURCES line 53; Ext: tcs97_abs page (relation-based definition of local consistency).
- **Correction:** add the three rows, align "closest", and use the abstract's words.
- **Resolved when:** the table, §9.14.5 and EXT-076 agree.

**B1-08 (normal). Chepoi et al.: "exactly when" needs P ≠ NP.**
- **Finding:** The text says satisfiability is polynomial "exactly when" the family has the Helly property. The public talk abstract, as reported by search, says polynomial if Helly and NP-complete otherwise. "Exactly when" follows only under P ≠ NP.
- **Evidence:** Ext: Hermann's talk page, clementj01.users.greyc.fr/semalgo/2011/02/01/talk.html.
- **Correction:** state the dichotomy instead.
- **Resolved when:** the wording is fixed.

**B1-09 (normal). van Beek–Dechter is stated more strongly than the abstract.**
- **Finding:** The text says "path consistency ensures minimality and global consistency" for row-convex constraints. The abstract says only that row convexity is useful "in deciding when" path consistency is sufficient.
- **Hyp (from my own knowledge, not checked on a public page):** the theorem is about path-consistent networks that are row convex, and enforcing path consistency may destroy row convexity.
- **Evidence:** Ext: cs.uwaterloo.ca/~vanbeek/Publications/jacm95_abs.html.
- **Correction:** keep to the abstract's words.
- **Resolved when:** the sentence stays within the abstract.

**B1-10 (normal). Stale wording at the pointers and in the tool.**
- **Finding:**
  1. CON-0192 and §2.3 (d) still say "Boolean or unbounded attribute"; v3 says "infinite".
  2. The tool docstring is out of date:
     - it still says "unbounded on an unbounded domain";
     - it says "Families are multisets", but `helly` removes duplicates;
     - it lists check 7 before check 6.
  3. Check 6's tautological line is marked in the book but still printed and asserted unmarked in the tool.
- **Evidence:** register line 193; §2.3 (d) line 69; tool lines 7, 19–25, 195, 203.
- **Correction:** fix all three.
- **Resolved when:** grep finds no "unbounded attribute", and the tool marks the line.

**B1-11 (normal). R5 (c) states an equality where only ≤ is proved.**
- **Finding:** R5 (c) says "h belongs to the language, attribute by attribute" and that a form "raises that attribute's h to k". R2 proves only h ≤ maxₐ h(𝓔ₐ). The lower bound needs cylinders: rules that constrain one attribute and leave the others free.
- **Evidence:** Ded.
- **Correction:** state the premise.
- **Resolved when:** the equality is stated with its premise.

**B1-12 (normal). Line 36 understates R1 (c).**
- **Finding:** Line 36 says the weak point-flag property "is not what R1 is about". But step 6's realised base has a point that no pair and no single rule flags. So for languages under realisability the weak property also characterises h ≤ 2; the two notions differ only for one base.
- **Op:** "exact" or "complete" would avoid the clash with the logical meaning of "sound".
- **Evidence:** Ded, step 6.
- **Correction:** say where the two notions coincide.
- **Resolved when:** line 36 does.

**B1-13 (normal). §9.16's "reads no record" is imprecise.**
- **Finding:** §9.16 says "§9.14 reads no record". The verdict "untouched" is right, because R1–R5 hold for any applicability. But §9.14 consumes A′ (§2.3 (b)), which is built from "recorded, authorized, current" exceptions. So tombstoned or unsigned exception entries can change regions, though never h. A tombstoned rule is in no form (E6).
- **Evidence:** §9.16 line 66; §9.14 line 39.
- **Correction:** "R1–R5 hold for any A′; records enter only through A′ and through which rules form the base".
- **Resolved when:** the row states this input dependence.

## 3. Proof steps for the derivations I judge adequate

**A. Dense order (adequate).**
1. Let J be the intersection of the family's intervals.
2. If J = ∅, two intervals already have an empty intersection (Helly in dimension 1).
3. If J = {a}, the interval with the largest left end and the one with the smallest right end are both closed at a. Together with x ≠ a they make at most 3 members.
4. If J has 2 or more points, density makes it infinite, so finitely many complements leave a point.
5. So h = 3, attained by {x ≤ a, x ≥ a, x ≠ a}. With strict thresholds only, step 3 is impossible, so h = 2.

**B. Exact condition (mine, for B1-02).**
1. In a minimal empty family F that contains complements, J ≠ ∅, and the forbidden points are exactly J. So J is finite.
2. At most two intervals are needed: the one with the largest left end and the one with the smallest right end.
3. So |F| ≤ |J| + t(J), with equality reached by the closed interval J.
4. So h is unbounded iff finite intervals of unbounded size exist.
5. On ℚ×{0,1}, h = 4.

**C. R1, R2 and R4.** The book's tables are adequate. I found no invalid step and no countermodel. In R2 step 3, repeated factors are handled by choosing one index per distinct factor.

**D. "Sound", both directions.**
1. The pairs-only output is the minimal sets of size ≤ 2 with their regions.
2. If m ≤ 2, the output equals the set-wise report (R1 step 4).
3. If m > 2, a minimal set of size 3 or more is missing, so the output is not equal.

## 4. Coverage

**Read in full:**
- §9.14 v3 (279 lines);
- the tool and its v3 run;
- CON-0192;
- EXT-072–076 and EXT-081–083;
- §2.3 (d) and (e);
- the §2.6 lines that cite §9.14;
- the §9.16 R1 table.

**Checked:**
- every R3 proof, including the degenerate cases k = 1 and n = 2 (no finding);
- every check 1–7 assertion against the text;
- the independence of check 3's pairs analyser;
- the §2.6 pointer, which is consistent with R5 (b).

**Public pages:**
- the van Beek abstract pages (the 1995 and 1997 papers);
- the Chepoi et al. result, through the talk abstract as reported by search.

Beeri et al. was not re-checked; I take its statement to be standard (Op). It is linked to the Helly property through Berge's conformal/Helly duality (Hyp, not checked).

**Not done:**
- The S1539, S0922 and S0287 rows were not re-checked, because they are not on the changed list.
- No finite model can test a dense order, so B1-02 rests on derivation B and a finite stand-in.

**Sources:**
- [van Beek–Dechter 1995 abstract](https://cs.uwaterloo.ca/~vanbeek/Publications/jacm95_abs.html)
- [Dechter–van Beek 1997 abstract](https://cs.uwaterloo.ca/~vanbeek/Publications/tcs97_abs.html)
- [Hermann talk abstract](https://clementj01.users.greyc.fr/semalgo/2011/02/01/talk.html)
- [HAL record, Chepoi et al.](https://hal.archives-ouvertes.fr/hal-01199922)
