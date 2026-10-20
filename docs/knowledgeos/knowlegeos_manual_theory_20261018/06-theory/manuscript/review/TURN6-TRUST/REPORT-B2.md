# Turn 6, commission B: report of reviewer B2 (Sonnet)

*Object: §9.14 v2 in full (R1-R5, §9.14.4-9.14.9), the pointers in §2.3 (d) and §2.6, CON-0192, EXT-072-076, `helly_check.py` v2 and its run. Read alone; no other REPORT-*.md read. Scratch: `.../scratchpad/t.py` (python3 -I), which imports `helly_check.py`. Nothing but this file was edited. Corpus read: S1539 and S0922 (sha256 match to CORPUS-ALLOWED). Basis files (RM, SD) not read: claims resting on them are marked U.*

## 1. Verdict table

| Item | Verdict |
|---|---|
| R1 (a) bound, (b) reduction | **sound** (proof steps 1-5 valid) |
| R1 (c) converse for the language | **sound**, but the heading drops the realisability premise (B2-06) |
| R1 (d) one base, m(R) <= 2 | **sound as stated** under "report equality"; "sound" is never defined (B2-06) |
| R2 product lemma with full-product premise | **sound** (steps 1-5 valid; countermodel for the premise valid) |
| R3 single-attribute rows: required value, Boolean, threshold, required+threshold, forbidden value, required-or-forbidden, allowed set, forbidden range | **sound**; each h re-derived |
| R3 new row "threshold with forbidden value" | **unbounded on N: correct; "n on n": true but unproved and not in the run (B2-02); "unbounded on an unbounded domain": needs discreteness and a lower bound; false on dense domains (B2-01)** |
| R3 across-attribute rows (products, coupled, cardinality, linear, disjunction) | **sound**; the S0922 placement is over-read (B2-08) |
| R4 | **sound** (edge: needs X nonempty; trivial) |
| R5 (a), (b) | **sound** |
| R5 (c) | **partly wrong**: unbounded claim without discreteness (B2-01) |
| R5 (d) list of h = 2 languages | **every listed union is h = 2 (verified)**; but the "Not h = 2" list contradicts R3 for unbounded forbidden values (B2-03), and the language definition is silent on MUST_NOT of conjunctions or intervals (B2-04) |
| §9.14.4-§9.14.7 | **sound**, with B2-05, B2-07, B2-08, B2-09 |
| Pointers §2.3 (d), §2.6; CON-0192; EXT-072-076 | consistent with §9.14, with the same repairs as R5; bibliographic data verified (below) |
| helly_check v2 | all assertions hold; check 3 is independent in the stated sense but narrow (B2-10) |
| Turn-5 rows B1-01 ... B2-13 | 16 of 17 met; B1-01/B2-01 met except for the exactness above; B1-14/B2-09 only in R3, not in R5 (B2-03) |

No critical finding. One high, ten normal.

## 2. Findings

| ID | Finding | Sev | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| B2-01 | "Unbounded" for thresholds with forbidden values is stated without discreteness or a lower bound. (i) On a dense domain (Q, R) h = 3: the intersection of intervals is an interval J; finitely many point complements empty J only if J is one point {p}; then the family is two threshold rules that pin p plus x != p. Example x <= p, x >= p, x != p is minimal (checked on a grid, `t.py`). So the claim fails there. (ii) On a domain unbounded below (Z) the printed family {x <= m-1, x != 0..m-1} is **not** empty (x = -1; `t.py`: minimal_empty False). It works on N, or with a lower threshold (m + 2 members). R5 (c) ("makes h unbounded on an unbounded domain"), the status paragraph ("unbounded h"), the check 6 print line and CON-0192 drop the word "discrete". | high | R3 row 7 and its proof; R5 (c) bullet 2; CON-0192 text; run line (6) | Say "an ordered domain in which every interval is finite (e.g. N, or Z with a lower threshold admitted)"; state h = 3 on a dense domain when both one-sided thresholds are admitted (h = 2 if only one direction). Same wording in R5 (c), status, CON-0192. The "pairs do not suffice" conclusion survives (3 > 2). | the four places say "discrete, intervals finite", and the dense case is a row or sentence |
| B2-02 | "**n** on n values" for the new row has no proof in the section: the proof only treats the unbounded case; R4 is not cited; check 6 covers 0..11 with m <= 5, never m = n - 1. True: exhaustive (`t.py`, intervals + point complements, n = 2..5: h = 2, 3, 4, 5; also with singletons). Lower bound: m = n - 1; upper bound: R4. | normal | R3 row 7; proof of the row; run (6) | add "m = n - 1 gives n; h <= n by R4"; add the exhaustive class to check 1 or 6 | proof names m = n - 1 and R4; check prints h for n = 2..5 |
| B2-03 | R5 (d) "Not h = 2: forbidden values or allowed sets on three or more values" and the same sentence in the §2.3 (d) pointer contradict R3: forbidden values on an **unbounded** domain have h = 0 and pairs suffice (an unbounded domain has "three or more" values). The list also mixes "not h = 2" with "pairs do not suffice" (h = 0 and h = 1 are both <= 2). | normal | R3 rows 5-6 (last column) vs R5 (d); §2.3 (d) bullet "It fails for" | write "on a finite attribute with k >= 3 values"; title the list "h > 2" | the two lists agree with R3 |
| B2-04 | The h = 2 language list needs the effect forms to be product-shaped. MUST_NOT applied to a multi-attribute conjunction is a disjunction (complement of a box), and MUST_NOT applied to a bounded interval is a forbidden range (R3 row 9). Both are rules the phrase "required values with thresholds" can be read to include (MUST_NOT is in scope, §9.14.2). R2's premise "every effect set is a product" is stated, but R5 (d) and the §2.3 pointer do not say that MUST_NOT is admitted only on single-attribute atoms (and one-sided thresholds). | normal | §9.14.2 scope (MUST, MUST_NOT); R2 premise; R5 (d) | add to R5 (d): "MUST_NOT only of an atom or a one-sided threshold; MUST_NOT of an interval or of a conjunction leaves the class (forbidden range; disjunction)" | the language definition names the admitted MUST_NOT forms |
| B2-05 | The h = 2 claim for "required and forbidden values on an unbounded attribute" is the analyser's **open-world assumption** about the domain (R3 bounds it as "unbounded"; §9.14.6 lists "domains are what the analyser assumes"). The book's own examples for this form are finite enumerations (authentication method, TLS version, three placement roots). On a finite k >= 3 domain the true conflict {MUST_NOT a, b, c} exists and an open-world analyser (pairs or set-wise) reports none. So the second bullet of R5 (d) licenses pairs-only checking only by also licensing a possibly wrong set-wise report. | normal | R3 row 6; R5 (d) bullet 1; §9.14.6 | state in R5 (d): "unbounded" means the domain is declared open; a closed enumeration is the finite case (h = max(2,k)) | R5 (d) separates declared-open from closed enumerations |
| B2-06 | "Pairs-only checking is sound" is used throughout (R1 (d), R5 (d), §9.14.6) but never defined. R1 (d)'s "iff m <= 2" holds if sound = reproduces the set-wise report (R1 (b)). If sound = flags every point where some conflict exists, only "if" holds: a point can have a minimal triple and also a conflicting pair, so the point is flagged while m = 3. The R1 heading says "exactly when" without the realisability premise that (c) needs. | normal | R1 heading, (c), (d); §9.14.1 | define "sound" as report equality, regions included; put "(converse under realisability)" in the heading | definition in §9.14.2; heading matches (c) |
| B2-07 | §9.14.2 says a coupling is part of X "or, equivalently, a rule of its own in every checked set". Not equivalent for h: the three literals under "exactly one" are a minimal triple when the coupling is in X (h >= 3) and the owner row's four-member set when the coupling is a rule (R3 cardinality row; S1539 MCS-2 is 3 or 4 for the same reason). It is equivalent for pairs-only soundness **only** if the coupling is added to every tested subset (pairs become triples). §9.14.6 says the coupling "counts toward h", which presupposes the second reading. | normal | §9.14.2 bullet 1; R2 counterexample; R3 cardinality; §9.14.6 | write: "in X, or a rule added to every tested subset; then the tested sets are pairs plus the coupling, and h is counted without it" (pick one reading) | one convention, used in R2, the cardinality row and §9.14.6 |
| B2-08 | Two corpus rows are over-read. **S0922:** §29.26 gives `ElectionCommitteeMembers >= 3` and `PrimaryOwner = 1` as constraints on a count; read as written they are a threshold and a required value, h = 2 each. "Counts over members ... linear across attributes: not h = 2" is the doc's own reading (derived from per-member Booleans), not S0922's, and the row states it as the relation. The doc's R3 conditional ("if the count is the only outcome attribute it is a threshold") is correct but the row does not carry it. **S1539:** "The other four are pairs" and the bridge being background logic only for MCS-2: the source's own method (§1-2) is a propositional abstraction of 14 atoms and each MCS also needs an exclusion clause not stated as a commitment (e.g. EntropyNonIncreasing vs EntropyGrows rests on the tower property; OneOwnerPerConcept vs three owners on 3 != 1). By the doc's rule (bridge as commitment) every MCS could count one more. The row's own caveat (no context/outcome split) stands; the 3-or-4 reading of MCS-2 is right (checked: drop any one of {A1, A2, A3, bridge} and the rest is satisfiable). The S1539 quotes L116 and L204 are verbatim; L7-8 is the method line. | normal | S0922 L741-800; S1539 L5-9, L20-66, L103-116, L125-160, L204; whether the exclusion clauses are in the file: **U** (only atoms and results read) | S0922 row: "if per-member (Int), not h = 2; as written, h = 2"; S1539 row: "the other four pairs rest on unstated exclusion clauses (U for exact encoding)" | the two rows carry the conditions |
| B2-09 | The new row's example "violations <= 3 and violations != 1" is an enforcement threshold (the design checks' phase targets), which §9.14.2 puts out of scope; and no book rule of this form is found. The R3 header says "Int for each example's place in the book"; this example has none. A forbidden-value-with-threshold place in the book is not shown. | normal | R3 row 7; §9.14.2 scope bullet 3 | use a neutral example marked "no book rule found", as for the linear row | row says it has no book instance |
| B2-10 | Check 3 is independent as stated: `pairs_report` tests single empty effects and disjoint pairs directly, not through `minimal_empty`, and compares (set, context) pairs, so regions are tested. It tests R1 (b) for only four classes: it does not run the new union rows, required+forbidden, products, or the R3 example classes; no class contains the empty effect set, so the single-rule branch `if not E` never fires; and `assert (misses == 0) == (h <= 2)` is a sampled outcome, not a theorem (it held on 300 sets). Clauses: largest minimal set found was 3 while h = 4, so tightness at 4 rests on check 1 only. Nothing here is wrong; the sentence "tests what R1 (b) claims" holds for 4 classes. | normal | `helly_check.py` L148-190; run lines (3) | add classes (required + forbidden on Boolean, boxes, threshold + required) and a class with the empty set; say "sampled" | the run lists those classes |
| B2-11 | Rival §9.14.5: Dechter and van Beek 1997 described fairly in kind (relation-based local consistency on subsets of constraints; characterises when local consistency suffices). Bibliographic data verified by me (Crossref: TCS 173(1), 1997, pp. 283-308). Abstract wording not retrievable by me (no abstract in Crossref): **U** for the quoted description. Not named: the database result that pairwise consistency implies global consistency exactly for acyclic schemes (Beeri, Fagin, Maier, Yannakakis 1983, JACM; my recollection, **not verified here**), which is a "pairs suffice" result on relations; it belongs in the rival list or in the scan's coverage. | normal | Crossref API; §9.14.5 | add a scan for pairwise-consistency / acyclic-hypergraph results and record the outcome (found or none) | §9.14.5 or the scan note covers it |

## 3. Proof-step tables for derivations judged adequate (R4)

| Derivation | Step | Verdict |
|---|---|---|
| R1 (a)/(b) | 1 conflict at (c,t) = indexed family empty and minimal; 2 no repeats, size <= h; 3 \|S\| <= h; 4 minimal sets = minimal subsets of <= h applying rules, region per set; 5 testing subsets of <= h gives the report | valid; step 4 uses only the definition of "minimal in conflict at (c,t)" |
| R1 (c) | 6 minimal F with \|F\| >= 3, realised at one point; proper subsets, pairs included, satisfiable | valid; singles non-empty because every proper subfamily meets |
| R1 (d) | 7 one base: sets <= m(R); pairs suffice iff m <= 2 | valid under report equality (B2-06) |
| R2 | 1 product intersection factorises; 2 empty product has an empty factor; 3 sub-family G of <= h(E_a) empty in that factor; 4 G empty in the product, so G = F; 5 bound | valid; the premise is used in step 1; countermodel (exactly one of p,q,r) valid: pairwise satisfiable, jointly not (checked by hand and check 6) |
| Interval | choose p_ij = p_ji; l_i = min_j p_ij in I_i; x = max_i l_i = l_k; l_i <= x <= p_ki | valid for any total order; no discreteness needed; no finiteness of the domain needed |
| R4 | x_A for each A in F, x_A not in A, x_A in B for B != A, so x_A != x_B | valid for \|F\| >= 2; \|F\| = 1 needs X nonempty (the empty-set member): trivial edge, not stated |
| Required value, forbidden value, required-or-forbidden, allowed set | singleton disjointness; all-k complements; one singleton + its complement or all k complements; complements are allowed sets | valid (the "supersets of {a}" phrase in the first proof is idle: the class has only singletons) |
| Linear, reals, d = 2 | witnesses (0,0), (0,-1), (-1,0); sum 0 <= -1 | valid (check 5; Fractions) |
| Cardinality | drop an owner prohibition: that member owns; drop the constraint: nobody owns; all four: none | valid; minimal with 4 (check 6) |
| New row, unbounded case (N) | drop threshold: m, m+1, ...; drop x != j: j | valid on N (and domains with min 0); fails on Z (B2-01) |

## 4. Answers to the brief's questions

1. **New row.** Proof of minimality: right for the stated family on N. "n on n values": right (exhaustive n = 2..5, plus R4 for the upper bound) but unproved in the text (B2-02). "Unbounded on an unbounded discrete domain": right, but "discrete" must mean intervals are finite, and a lower end is needed (N, or Z with a lower threshold). Dense domain (Q): h = 3 when both one-sided thresholds are admitted (x <= p, x >= p, x != p); the interval class alone is h = 2. R5 (c), status, CON-0192 need the qualifier (B2-01).
2. **R5 (d) list.** Required values with thresholds: h = 2 (singletons are intervals; interval proof needs only a total order). Required and forbidden on a Boolean (max(2,2)) or unbounded attribute: h = 2 (any empty family contains two different singletons, or {a} with its complement, or cofinite sets alone, which never empty). Products on a full product: <= 2 by R2. I found no minimal family of three or more inside these, subject to B2-04 (MUST_NOT of conjunctions or intervals must be excluded) and B2-05 (open-world assumption).
3. **R1 (d).** Correct and consistent in §9.14.2, R5 (d), §9.14.4, §2.3 pointer and CON-0192, once "sound" is defined (B2-06). The h(E(L)) <= 2 sufficient / not necessary split is right (§9.14.4's example is legitimate).
4. **Check 3.** Independent of the set-wise report in code path; tests equality of (set, context) reports, so regions are included; narrow in classes (B2-10).
5. **S1539 and S0922 rows.** Quotes and reading of MCS-2 correct; MCS-2 size 3 with bridge in logic, 4 with it (re-derived). Over-reads: B2-08.
6. **Dechter and van Beek.** Fair in kind; bibliographic data verified; abstract wording unverified by me; one missing rival named (B2-11).

## 5. Turn-5 rows B1-01 ... B2-13

| Row | Met? |
|---|---|
| B1-01/B2-01 union | yes for the new row and list; exactness gap B2-01, B2-02 |
| B1-02/B2-02, B2-15 coupling | yes; wording B2-07 |
| B1-03, B1-08, B1-11/B2-03 language vs base | yes; "sound" undefined B2-06 |
| B1-05/B2-06 cardinality | yes in R3; S0922 row B2-08 |
| B1-04, B1-15/B2-12 check 3, caps | yes (independent analyser; caps stated as lower bounds) |
| B1-06 indexed families | yes |
| B1-07 finite subfamilies | yes |
| B1-09 R2 minimality step | yes |
| B1-10/B2-05 enforcement thresholds, linear row | yes in scope and linear row; the new row's example slips (B2-09) |
| B1-12/B2-07 cost | yes |
| B1-13/B2-11 rival | yes (Dechter-van Beek, Helly property named); another rival B2-11 |
| B1-14/B2-09 exactness | R3: yes; R5 (d) and pointer: no (B2-03) |
| B1-16/B2-14 S1539 | yes for MCS-2; B2-08 for the other four |
| B2-04 representation | yes |
| B2-08 unknown context | yes (partial, Ded sketch; the §9.14 R1 UNKNOWN paragraph is consistent) |
| B2-10 interval symmetry | yes |
| B2-13 strongest counterexamples | yes |

## 6. Coverage and limits

- Re-derived: R1-R5 proofs, every R3 row and placement, §9.14.4; ran `helly_check.py` v2 assertions (the run file matches the code's prints: 136 and 52 misses, sizes [2..6]); extra exhaustive runs in the scratchpad (intervals + point complements n = 2..5; Z-like domain; grid case x <= 2, x >= 2, x != 2).
- Not checked (U): R3 examples sourced in basis files (RM L154-162, SD L103-108, the TLS and OAuth2 examples in §2.3 (b), as sources); S1539's encoded exclusion clauses; Dechter and van Beek's and Jeavons et al.'s abstract wording; the primary texts of Doignon and Danzer-Grünbaum-Klee (the Doignon bound 2^d for Z^d is standard and the grid run gives 4 for d = 2).
- Bibliographic data EXT-072-076: titles, venues, pages consistent with my knowledge; Dechter-van Beek verified in Crossref; the rest not re-fetched (Jeavons-Cohen-Cooper AI 101(1-2) 251-265; Doignon J. Geom. 3, 71-85; Helly JDMV 32, 175-176).
- §9.14.9 open questions are properly open; the live-store question depends on rule texts not read.
