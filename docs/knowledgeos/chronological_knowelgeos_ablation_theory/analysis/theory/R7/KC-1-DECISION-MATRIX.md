# R7 · KC-1 integrated decision matrix (T3, M, K / K-T, C / C-Σ, U)

**Analysis only; decides nothing; draft r1.1, 2026-10-13. r0 reviewed (PASS_WITH_LIMITATIONS; 8 material, 7 minor); r1 re-reviewed (PASS_WITH_LIMITATIONS; 2 material, 7 medium/minor); r1.1 fixes not re-reviewed.** This document integrates the reviewed analyses for the human's KC-1 decision (F-LOG-0302). It ratifies no convention. It does not resolve C-γ, D12 or the spanning rule (F-LOG-0271). It authorizes neither E4 nor E5.

**Standing qualifier:** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable". Λ7 applies: every encoding, analysis and review is Claude-family.

**Sources (integrated, not re-derived; no new computation):**
- `KC-CONVENTIONS-PROPOSAL.md` r1.1 (KC). Reviewed twice, PWL.
- `KC-MKC-REASSESSMENT.md` r1.2 (MKC). Three review rounds, all PWL; r1.2 fixes not re-reviewed (wording only; outputs reproduced byte-identically).
- `AUDIT-Q6-TEST2-CLAUSE-COORDINATE-TABLE.md` (T2C) r1.1. r0 reviewed: FAIL (one stale-copy finding, plus substantive findings fixed in r1); the r0 reviewer re-ran the enumeration and confirmed the formal core (Thm 1–3). r1 re-reviewed: PWL, with the new proofs (Lem 3, Lem 5, W3 at N = 0) confirmed and the §7 item 6 quotes verified verbatim; r1.1 wording fixes not re-reviewed. Used only in §5's last paragraph.
- `RESEARCH-AUDIT-1.md` r1.1. Two different items are both called "C6" there: **§5 C6** (frozen encoding or downgrade the lists) and **Appendix C item C6** (the Lemma 2 check). This document writes them as C6 (§5) and C6 (App. C).

MKC and T2C share sources and an analyst. **They are not cited as corroborating each other.**

---

## 1. Options

| option | one-line definition | status |
|---|---|---|
| **T3** | rules 1–5 as written. A1 (denials) and A3 (typing) **would be** ratified as stipulations | **minimal-amendment baseline** (F-LOG-0302): a reference point, not a default |
| **M** | mention: a row reads every concept it names, at the level it is worded | KC option |
| **K** | contribution: a row reads what its role contributes; guard role from T2's label | KC option |
| **C** | content: a row reads only what it asserts; guard rows read nothing. Its class-level clause is undefined, so a parse must be chosen (K1c / SH / R15ref / R15ref-R14) | KC option |
| **K-T** | K with the label replaced by T2's tier. **Encoding identical to K's** (MKC J1); differs in definition only | MKC repair, post-hoc [E]; offered "to locate failures, not as options for adoption" (MKC §6.2) |
| **C-Σ** | C with a fixed part classification. **Its verdicts equal C-R15ref's** (encodings differ only at R11); it selects that parse by premise A-ii | as K-T |
| **U** | K's level with M's polarity | as K-T |

**Act-text note.** KC's KC-1 template (KC §5) has option boxes for T3, K, C, M and P only, and **no box for the guard-row purpose**. Choosing K-T, C-Σ or U would need new act text or option P.

---

## 2. Decision matrix

Confidence: **H** proof or computation · **M** depends on the A-i classification · **L** depends on a contested premise or purpose. Requirement status: [U] follows from the uses; [E-s] a source-supported stipulation; [E] the analyst's stipulation.

| criterion | T3 | M | K | K-T [E] | C | C-Σ [E] | U [E] |
|---|---|---|---|---|---|---|---|
| **A. Formal correctness** | | | | | | | |
| Rq1 determinacy [E] | ✗ A1 moves E3-REL (H) | ✓ (M) | ✗ label moves E3-REL (H) | ~ (H; rests on excluding the "R21 without c" reading [E]) | ✗ parse moves E3-REL (H) | ✗ exceptive moves E3-REL (H) | ~ (M) |
| Rq2a definitional invariance [E] | ✗ (H) | ✗ (H) | ✓ | ✓ | ✓ | ✓ | ✓ |
| Rq2b meta-level invariance [E]; targets C by construction | ~ | ✓ | ~ | ~ | ✗ | ✓ | ✓ |
| Rq3 polarity coherence [E] | c(ab) | ✓ | c(ab) | c(ab) | ✗ (M) | ✓ (M) | ✓ |
| Rq4 compositionality [E-s] | ✓ | ✓ | ✓ | ✓ | ✗ a KC encoding slip, repaired in C-R15ref (H) | ✓ (L) | ✓ |
| Rq5 T-B slot functionality [E-s]; T3's P3 promoted selectively (P6, P7 not promoted alike; MKC §2.2) | ✓ | ✗ (H) | ✓ | ✓ | ✓ | ✓ | ✗ (H) |
| Rq6 separability [U] | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Rq7h hosting relevance [E-s] | ✓ (L) | ✓ (L) | ✓ (L) | ✓ (L) | ✗ (L) | ✗ (L) | ✓ (L) |
| Rq7c constraint coupling [E-s], guard rows only | ✗ (L) | ✗ (L) | ✗ (L) | ✗ (L) | ✓ (L) | ✓ (L) | ✗ (L) |
| Rq8 family neutrality [U] | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| **B. Source fidelity** (KC §2.2, §4; MKC §4) | | | | | | | |
| T3's own rule text, conventions agreed (of 12); favours T3 by construction | 12, by definition | **10, the most of M, K, C** (U, K-T, C-Σ not counted); departs at K5 (P3, P4: absent / negated clauses read) and K7 (relational clause) | 7; against P6, P7 | as K | 6; against P2, P6, P7 | as C, plus a fixed parse | against P3, P4, P6, P7 |
| T2's tier label "non-constraint; guard form" (P8, P11) | against the "non-constraint" half | as T3 | uses the "guard form" half; against "non-constraint" | as K | **for** | **for** | as T3 |
| T2's guard / T-C pairing (P10), = Rq9 [E], restates Rq7h on guard rows | ✓ 7 / 6 / 5 | ✓ | ✓ | ✓ | ✗ 1 / 0 / 1 | ✗ 2 / 1 / 1 | ✓ |
| P9 "prevents it and asserts nothing about it" | conflicting within one sentence: "prevents" points to K, "asserts nothing" points to C | | | | | | |
| **C. Consistency across the 83 rows** (MKC §5.1, §5.4) | | | | | | | |
| T-A / T-B host cells departing from T3, S; S′ | 0; 0 | 2; 2 (R79, R80) | 2; 0 (R27, R28) | as K | 7; 5 | 6; 4 | 4; 2 |
| T-C / T-D rows departing from T3 | — | R45, R52 → CB1 (S, S′) | R56, R60, R61 → X (S only); R62 → X; R71 → X (S′); R75 → X | as K | as K | as K | as K |
| guard-row purpose it implies | hosting relevance (7h) | 7h | 7h | 7h | coupling (7c) | 7c | 7h |
| P-c rank, S′ / S″ (crossings / strictly fewer / equal) | 17 / 0 / 1 | 19 / 0 / 2 (tied) | 17 / 0 / 1 | as K | 13 / 0 / 1 (K1c only) | 14 / 0 / 1 | 19 / 0 / 1 |
| P-c rank, S | 24 / 10 / 78 | 26 / 147 / 417 | 26 / 64 / 304 | as K | 22 / 46 / 234 (K1c only) | 23 / 34 / 155 | 28 / 469 / 1246 |
| **D. E3-REL consequences** (accepted 13 / 8, F-LOG-0297) | | | | | | | |
| E3-REL, S / S′ (other D12 reading) | 13 / 8 (14 / 9) | 13 / 8 (14 / 9) | 13 / 8 (14 / 9) | as K | 9 / 4 to 11 / 6 by parse | 10 / 5 (11 / 6) | 13 / 8 (14 / 9) |
| F-LOG-0297 acceptance | stands | stands | stands | stands | **needs a new act** | **needs a new act** | stands |
| E3 sites that change | none | §6 R79 / R80; asymmetry rows; totals 46 / 36 (different rows) | §6 R27 / R28 under S; §2.5 slot rows; totals 53 / 39 | as K | §2.2, §4.2, §6, totals; E3-EV3 fails | E3-EV3 fails; a "none" placement (R14, R16, R17); totals 50 / 36 | as K plus R79 / R80; totals 55 / 41 |
| asymmetry records (U4; MKC §5.5); row level only: the coordinate-level record-clause asymmetry under R-i / R-i′ holds under every convention (T2C §7), not as corroboration | R-i / R-i′ asymmetric; S-only H-D asymmetry at R25 | R-i / R-i′ **symmetric** (row level); S-only H-D at R25 | R-i / R-i′ asymmetric; S-only widened to D-st | as K | as K | as K | R-i / R-i′ **symmetric** (row level); S-only widened to D-st |
| **E. Unresolved ambiguity and open boxes** | | | | | | | |
| left to stipulation | A1, A3 | level of wording | T2's label; R80 role (VC8); R79 contrast | the tier (a T2 label); R80; R79 contrast; the R21 exclusion | the parse; R80 (VC8) | the A-ii premise at R15; R80 (VC8) | R80 |
| KC-1 boxes still open (KC §5) | none: T3 fixes R80 = empty clause and K9 = A.0 | K9 | VC8 sub-variant; K9 | as K, plus new act text | parse; VC8 sub-variant; K9 | VC8 sub-variant; K9; new act text | new act text; K9; R80 ("acts exist" reading, U's moving choice point) |
| C-γ interaction (decided separately) | guard rows keep crossing groupings | as T3 | as T3 | as T3 | empty under K1c / SH; R15 (and R14) under the R15ref parses | **not emptied**: R15 still crosses | as T3 |
| amendment acts required | KC-4 (recording only) | KC-2, KC-3, KC-4, KC-5 | KC-2 (S row), KC-3, KC-4, KC-5 (widened under S) | as K | KC-2, KC-3 with a new E3-REL acceptance, KC-4, KC-5; R17's host | as C | as K, plus M's R79 / R80 restatements |

**Notes on the matrix.**
- No column passes every row. Rq7h and Rq7c conflict on R14, R16 and R21, so no principle passes Rq7 as a whole (MKC §6.2).
- Block A's requirements are stipulations of uneven status, and its failures are not independent: Rq2b targets C by construction, Rq9 restates Rq7h, and Rq7h / Rq7c depend on the purpose (L). Only Rq6 and Rq8 follow from the uses, and every option passes both. **Counting failures in block A does not rank the options.**
- Restating RESEARCH-AUDIT-1 F2 and F-LOG-0292 under M or U is an amendment burden of the same kind as output preservation. Per KC §4 it is **not** counted against M or U.
- All ranks and E3-REL figures are computed with the inventory conventions K8, K9 and K13 at (a) and without K18 (§3 item 3).

---

## 3. What the evidence establishes for KC-1

1. **Among the options compared, the guard-row purpose is the option-level choice that moves E3-REL** [E for the grouping; the per-option figures are H]. Options that read guard rows as hosting-relevant (T3, M, K, K-T, U) give 13 / 8 and keep the F-LOG-0297 acceptance. Options that read them as non-constraints (C, C-Σ) change E3-REL and need a new acceptance.
   - **Other movers of E3-REL:** the D12 reading (+1 / +1 under every option); C's parse (9 / 4 to 11 / 6); and K18, the source of ρ (6 / 3 with T2's "rests on" column; weak, 7 rows lack it; KC §1.1).
   - **E3-REL is invariant within the hosting group only.** Within that group the choice still moves S-reading host cells, R79 / R80, T-C / T-D rows, totals and asymmetry records.
2. **The sources do not settle the purpose question** (MKC §4.2). T3 rule 1, T2's pairing column and the hosting uses point to hosting relevance. T2's tier column, T2 §6 and E3's own text point to coupling. P9 contains both. The question is entangled with C-γ.
3. **P-c's standing.** For the evaluated encodings (K8, K9, K13 at (a); C at K1c only), P-c is minimal under S′ / S″ for every option (tied under M) and not minimal under S for any option. **This is not invariant to the open boxes:** K8 (b) or K9 (b) gives 17 / 1 / 4 under S′ (one cut strictly fewer), and K13 (b) makes the S′ minimum tied (KC §1). **These box effects were computed against T3's encoding only, not under M, K or C.** K9 is still an open KC-1 box under M, K and C.
4. **"Single principle" does not select a unique option.** M, K-T, U and C-Σ are each single principles; they give up different requirements (MKC §6.3). The criterion stays provisional.
5. **C as written has a defined defect, stated without ranking.** Its parse range comes from an undefined class-level clause, and KC's encoding has one slip (Rq4) that C-R15ref repairs (H). If the coupling purpose is chosen, a parse must be fixed. C-Σ fixes it by premise A-ii; it has the same P-c verdicts as C-R15ref, but its encoding differs at R11 and its S rank differs (23 / 34 / 155 vs 23 / 53 / 283); MKC §6.4 finds that it has "no formal advantage".
6. **T3** is the only option with zero host and E3 changes. That is output preservation, which the human directed must not be privileged. (Every option's failures in block A are failures of stipulated requirements; this is not special to T3.)
7. **Choice (b), whether Rq3 spans row types, separates T3, K and K-T from M and U** within the hosting group. It is a stipulation, not a finding.

**Not established:** that any principle is correct for this model; that any requirement should be given up; which option within a group is better; anything about H-D or H-B; that the encodings are right (Λ7).

---

## 4. Analyst recommendation [E] (not a decision)

**Recommended form of KC-1: decide the guard-row purpose, not a criterion label.** The evidence locates the option-level decision there: it splits the options into two groups with different E3-REL consequences, the sources conflict on it, and no formal requirement settles it. Deciding "single principle" first does not narrow the options (§3 item 4).

**C-γ stays separate, unless the human expressly includes it.** As recommended, a purpose ruling made in KC-1 would apply to K-c only and leave C-γ returned (F-LOG-0302); the act text would say so expressly, because the purpose question is entangled with C-γ (KC §2.4, MKC §4.2). The KC §5 scope box also lets the human include C-γ expressly; that widens F-LOG-0300's scope. Under C with K1c or SH, C-γ would become empty **only if** the ratified "reads" also governs T3 §1b (KC §4), and that consequence would need its own act.

**Within each group, the evidence gives trade-offs, not a ranking.**
- **Hosting relevance (7h): T3, M, K (K-T), U.** E3-REL 13 / 8 and F-LOG-0297 stand under all four.
  Each option is given the same fields (block A marks; changed outputs).
  - **T3:** passes Rq4, Rq5; ~ Rq2b; Rq3 conditional on (a), (b); fails Rq1 (at A1), Rq2a. Outputs: none change. Fixes R80 and K9, so no box stays open.
  - **M:** passes Rq1 (the only ✓ of any option), Rq2b, Rq3, Rq4; fails Rq2a, Rq5. Closest of M, K, C to T3's own text (10 / 12). Outputs: R79 / R80 → X; R45 / R52 → CB1; R-i / R-i′ records symmetric at row level; totals 46 / 36.
  - **K:** passes Rq2a, Rq4, Rq5; ~ Rq2b; Rq3 conditional; fails Rq1 (label). Stated in K-T's tier wording, Rq1 becomes ~, resting on the R21 exclusion [E]; K-T is post-hoc with K's encoding. Outputs: R27 / R28 → X under S; R56, R60, R61 (S), R62, R71 (S′), R75 → X; totals 53 / 39; asymmetry widened to D-st under S.
  - **U:** passes Rq2a, Rq2b, Rq3, Rq4; ~ Rq1; fails Rq5. Post-hoc. Outputs: R27 / R28 → X under S; R79 / R80 → X; the T-C / T-D changes as K; R-i / R-i′ records symmetric at row level; totals 55 / 41.
  - The deciding questions in this group are all stipulations: Rq1 (whether determinacy without row labels should bind; it splits T3 ✗, K ✗, K-T ~, U ~, M ✓); Rq2a (reading through R4's definitions, against T3's P6); Rq5 (T3's P3, promoted selectively); and choice (b).
- **Coupling (7c): C with a parse, or C-Σ.** E3-REL changes (10 / 5 for C-R15ref and C-Σ; 9 / 4 or 11 / 6 for the other parses) and needs a new acceptance. The parse is the deciding question; C-Σ is one way of fixing it, not a better principle. C-γ is not emptied under the R15ref parses.

**Confidence:** low to medium. The per-option figures are computed (H) under the stated box settings. The grouping, the trade-offs and every requirement are [E] or [E-s]; the encodings are Claude-family (Λ7); K-T, C-Σ and U are post-hoc.

---

## 5. Exact consequences per choice (for the act)

| choice | would ratify | E3-REL / F-LOG-0297 | follow-on acts (each separate) | boxes to fill within KC-1 | still open after KC-1 |
|---|---|---|---|---|---|
| T3 | rules 1–5 as written; A1, A3 as stipulations; R80 = empty clause; K9 = A.0 | 13 / 8, stands | KC-4 recording; E2b §A2.2 note and §A1.10 bullet 1 updated by recording | none | C-γ, D12, spanning rule, C6 (§5) frozen encoding, K15–K19 |
| M | mention principle; R80 read | 13 / 8, stands | KC-4 → KC-2 (R79, R80) → KC-3 and KC-5, including RESEARCH-AUDIT-1 F2 and F-LOG-0292 | K9 | as T3 |
| K | contribution principle; guard label; R80 = empty clause | 13 / 8, stands | KC-4 → KC-2 (§A1.4 S row) → KC-3 (§6, §2.5, totals) and KC-5 (widened under S) | K9; VC8 sub-variant | as T3 |
| K-T / U | needs new act text (or option P) | 13 / 8, stands | as K (U: plus M's KC-5 restatements) | K-T: as K; U: K9, R80 | as T3 |
| C (parse chosen) | content principle; parse; R80 = empty clause | 9 / 4, 10 / 5 or 11 / 6 by parse; new acceptance needed | KC-4 → KC-2 (a "none" category) → KC-3 (new E3-REL acceptance) and KC-5; R17's host | parse; K9; VC8 sub-variant | as T3; C-γ concerns R15 (and R14) under the R15ref parses |
| C-Σ | needs new act text (or option P); same P-c verdicts as C-R15ref (encoding differs at R11; S rank differs), fixed by premise A-ii | 10 / 5; new acceptance needed | as C | K9; VC8 sub-variant | as C |

Under every choice: no spanning rule is selected (F-LOG-0271); D12 stays open; C-γ stays returned unless the human expressly includes it; the asymmetry is not accepted; C6 (§5) stays open, because KC-1 ratifies conventions, not a frozen encoding; E4 and E5 are not authorized; the standing qualifier applies.

**T2C bearing on the records.** T2C §7 item 6 (reviewed in r1) finds that, under its assumption A-1, Lemma 2 holds unless the actor is counted on the record side. That **conflicts with the literal wording** "only if … (S′)" in RESEARCH-AUDIT-1 C6 (App. C) and F-LOG-0292. On an alternative reading of that wording ("do not count actor-valued coordinates" meaning α / 𝒜 on the record side, not k), only the literal wording is affected. Either way the point holds under **every** option, not only M or U, and it would be handled by its own recording. It changes no KC-1 output.

---

## 6. Limitations

- Λ7: every encoding, principle, review and this integration are Claude-family.
- K-T, C-Σ and U, and most requirements, were written after the KC results were known (post-hoc).
- The integration adds no computation. Every number is from MKC r1.2 or KC r1.1.
- C's ranks are for K1c only; the R15ref parses were not ranked.
- r0 and r1 of this document were each independently reviewed (both PWL); the r1.1 fixes are not re-reviewed.
- Standing qualifier (verbatim): "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable".

---

## 7. Changes: r1 (review of r0: PASS_WITH_LIMITATIONS)

| finding | change |
|---|---|
| 1 E3-REL movers | §3 item 1: "option-level choice among those compared"; D12, C's parse and K18 listed; invariance limited to the hosting group; grouping graded [E] |
| 2 P-c standing | §3 item 3 limited to the evaluated encodings; K8 / K9 (b) give 17 / 1 / 4 under S′; K13 ties; C ranks K1c only |
| 3 "C weakest" | deleted; only the H-graded facts kept (§3 item 5) |
| 4 neutrality | §4 rewritten as trade-offs within each group; the C-Σ preference dropped; C-Σ = C-R15ref plus A-ii; M's fit with T3's text added; the M / U restatement burden not counted against them |
| 5 C-γ | §4: a purpose ruling applies to K-c only; C-γ stays returned; how the act text carries it |
| 6 T2C use | header and §5 cite T2C and its review status (updated after T2C's r1 re-review: "conflicts with the literal wording", with the alternative reading); "only under M or U" removed; the two C6s distinguished |
| 7 T-C / T-D rows | row added in block C |
| 8 act boxes | open boxes per option (block E, §5); no template box for K-T, U, C-Σ or the purpose (§1) |
| 9 minor | T3 "would be ratified"; M's K7 departure; K's use of the "guard form" half; M's R25 asymmetry; C's Rq3 grade; Rq7h / Rq7c rows; M totals 46 / 36 |

### 7.1 Changes: r1.1 (re-review of r1: PASS_WITH_LIMITATIONS)

| finding | change |
|---|---|
| 1 §4 asymmetric to M, U | §4: same fields for every hosting option (marks; changed outputs); M's Rq1 ✓, Rq2b, Rq3 and U's marks and outputs added; Rq1 added to the deciding questions |
| 2 selective promotion | Rq5 row marked "P3 promoted selectively"; block B row 1 marked "favours T3 by construction" |
| 3 U's open boxes; column mix | R80 added for U; §5 split into "boxes to fill within KC-1" and "still open after KC-1" |
| 4 C-γ wording | §4: "unless the human expressly includes it"; the KC §5 scope box named; C-γ empties under K1c / SH only if "reads" also governs T3 §1b |
| 5 C-Σ = C-R15ref | "same P-c verdicts; encoding differs at R11; S rank differs" (§3, §4, §5) |
| 6 K8 / K9 / K13 | computed against T3's encoding only; "K13 (b)" |
| 7 T3 framing | §3 item 6: the stipulated-requirement point stated for every option |
| 8 row-level symmetry | block D: row level only; the coordinate-level asymmetry holds under every convention (T2C), not corroboration |
| 9 "most of any principle" | "of M, K, C" |
