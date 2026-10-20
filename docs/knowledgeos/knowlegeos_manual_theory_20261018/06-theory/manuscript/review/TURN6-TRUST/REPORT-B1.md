# Turn 6, commission B: review of §9.14 v2 (reviewer B1, Opus)

*2026-10-20. Worked alone. No other turn-6 REPORT was read. Object: `chapter_09/09.14-when-pairs-suffice.md` v2 in full, the pointers in §2.3 (d) and §2.6, CON-0192, EXT-072–076, `tmp/tools/helly_check.py` v2 and its run. Read for context: the turn-5 SETTLEMENT §3 and the turn-5 REPORT-B1/B2 findings tables (earlier review folders, allowed).*

**Access.** Corpus files S1539, S0922 and S0287 were read only after their sha256 matched `CORPUS-ALLOWED.tsv` (all three match). No basis file was opened. SD L103–108 and RM L154–162 (in two R3 placements) are basis files, so those citations are **U**. Bibliographic data comes from public records only (Crossref, OpenAlex, Semantic Scholar, and R. Dechter's UCI abstract pages). Scratch: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/b1_t6.py`, plus a scratch copy of the tool, run with `python3 -I`.

**Tool re-run.** `helly_check.py` (sha256 `b5097ecb…788b`) was re-run in a scratch copy. It took 5 min 23 s, and its output is **byte-identical** to `helly_check-run-20261020-v2.txt`. All assertions pass.

## 1. Verdict table

| Claim | Label (as stated / as found) | Verdict | Findings |
|---|---|---|---|
| §9.14.2 definitions (indexed family, 𝓔(L), h over finite subfamilies, m(ℛ), realisability) | Ded / Ded | sound. A precision point: "largest" fails when h is unbounded | B1-12 |
| R1 (a) bound | Ded / Ded | **sound** (proof table §3.1) | — |
| R1 (b) reduction, regions included | Ded / Ded | **sound** | — |
| R1 (c) converse for the language, under realisability | Ded / Ded | **sound** | — |
| R1 (d) for one base, iff m(ℛ) ≤ 2 | Ded / Ded | sound for "reproduces the set-wise report". **False** for the weaker reading "flags every conflicted point". "Sound" is not defined | B1-06 |
| R1, the UNKNOWN paragraph | Ded / Ded | sound, within its stated scope (fully described context) | — |
| R2 product lemma with its full-product premise | Ded / Ded | **sound** (proof table §3.2). The exactly-one counterexample was re-checked | — |
| R3, single-attribute rows: required value, Boolean, threshold, required + threshold, forbidden value, required or forbidden, allowed set, forbidden range | Ded / Ded | sound for a **total, non-cyclic** order and an **infinite** (not merely "unbounded") domain | B1-05 |
| R3, the new row "threshold with forbidden value" | Ded / Ded | **partly wrong**. The family is correct and minimal on ℕ (any domain with a least element). It is **not empty on ℤ**. "Unbounded on an unbounded discrete domain" is the wrong condition: on ℚ or ℝ, h = 3. The "n on n values" entry is right but inherited from forbidden values alone. "Pairs suffice: no" is wrong for n ≤ 2 | B1-01, B1-02, B1-03 |
| R3, cross-attribute rows: products, coupled X, cardinality, linear, disjunctions | Ded / Ded (+Ext for Doignon) | sound. The tightness witnesses were re-checked | — |
| R3 placements: OAuth2, TLS, design checks, trace links | Int / Int | sound as Int. The SD and RM citations are U (basis files) | — |
| R3 interval proof | Ded / Ded | **sound** (proof table §3.3) | — |
| R3 proof of the new row | Ded / Ded | sound **on ℕ**. Unsound as worded for "unbounded discrete" | B1-01 |
| R4, h ≤ \|X\| and tightness | Ded / Ded | **sound** (proof table §3.4) | — |
| R5 (a) cost | Ded / Ded | sound as a count of tests. The phrase "each test is the ∃∀ question" understates the region computation | B1-12 |
| R5 (b) partitioning (§2.6) | Ded / Ded | sound (follows from R1 (b) and §2.6's set lemma) | — |
| R5 (c) consequences | Ded / Ded | **two sentences false**: "unbounded on an unbounded domain" (false on dense domains), and "a single forbidden-value rule … takes all k complements, or the form admitted for every value" (false once thresholds are admitted) | B1-02, B1-04 |
| R5 (d) list of h = 2 languages | Ded / Ded | **sound under the reading "totally ordered, non-cyclic; infinite"**. Under the literal words ("ordered", "unbounded") there are minimal triples (partial order, cyclic order) | B1-05 |
| §9.14.4 limit of the converse | Ded / Ded | sound | — |
| §9.14.5 rivals | Ext / Ext | the Dechter–van Beek citation is verified, and the description is broadly fair but embellished. Two closer candidates are not named | B1-08 |
| §9.14.6 trust boundary | Int / Int | sound | — |
| §9.14.7 S1539 row | Src + Int / Src + Int | quotes and line numbers correct. The background-logic caveat is applied to MCS-2 only | B1-10 |
| §9.14.7 S0922 row | Src (in meaning) + Int | content correct. The line range misses the boxed sentence it paraphrases | B1-11 |
| §9.14.7 S0287 row | Src (in meaning) | correct (L44, L53 read) | — |
| §2.3 (d) pointer, §2.6 pointer | Ded | consistent with R1 and R5. They inherit B1-05's wording | B1-05 |
| CON-0192 | — | consistent with the text. Inherits B1-02 ("thresholds with forbidden values unbounded") and B1-05 | B1-02, B1-05 |
| EXT-072–076 | Ext | EXT-073, EXT-075 and EXT-076: bibliographic data verified on Crossref. EXT-076's abstract verified on the author's page. EXT-074: the title is misspelt. EXT-072: U for me (no index record checked). EXT-075's verb "characterises": U (abstract elided in every public API I reached) | B1-09 |
| `helly_check.py` v2 and its run | — | reproducible. Check 3 is independent in code. Check 6's "required values with thresholds" is tautological. The "n on n values" entry is not checked | B1-07 |

## 2. Findings

| ID | Finding | Severity | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| **B1-01** | **The new row's family is not empty on ℤ, although ℤ is "an unbounded discrete domain".** On ℤ, {x ≤ m − 1, x ≠ 0, …, x ≠ m − 1} has the common points −1, −2, …. The proof's step "dropping x ≠ j leaves j" is fine. The emptiness step silently uses a least element 0 (true for "violations", a count in ℕ). The claim "h unbounded" is still true on ℤ, but needs a second threshold: {x ≥ 0, x ≤ m − 1, x ≠ 0, …, x ≠ m − 1} is minimal with m + 2 members. Turn-5 B2-01's example had both thresholds, but v2 dropped one. The tool's docstring and run text repeat the over-general wording ("on an unbounded domain"). | normal | scratch `b1_t6.py` (c): on −5..11 the one-threshold family has witness −5. The two-threshold family is minimal with 4 and 5 members for m = 2, 3. The R3 row and proof (L89, L110). Tool L20, L197 | State the row on a domain with a least element (ℕ, counts), or give the two-threshold family for domains unbounded below | the row, its proof and the tool text name the domain on which the family is empty, and ℤ is covered by a correct family |
| **B1-02** | **"Discrete" is not the right condition, and on a dense domain h = 3, not unbounded** (brief Q1). *Derivation (Ded):* let F be intervals I₁…I_p and point complements of a finite set P. Then ⋂F = J ∖ P with J = ⋂Iᵢ an interval. On a dense order, J is empty, a single point, or infinite. If J = ∅, two intervals already have an empty intersection (Helly in dimension 1). If J = {a} with a ∈ P, then J is the intersection of at most two intervals: the one attaining the greatest lower end (closed at a) and the one attaining the least upper end. Those two plus "x ≠ a" form an empty subfamily of at most 3. So h ≤ 3, and {x ≤ a, x ≥ a, x ≠ a} is minimal, so h = 3. (If only strict thresholds are admitted, h = 2.) The exact condition for unbounded h is that **the order has finite intervals of every size**: true for ℕ and ℤ, false for ℚ and ℝ. On n values the answer is n. R5 (c), CON-0192 and §2.3 (d) say "unbounded on an unbounded domain". On ℚ or ℝ that is false. "Pairs suffice: no" survives, because 3 > 2. | normal | the derivation above. R5 (c) L138; CON-0192 ("thresholds with forbidden values unbounded") | Write: "n on n values; unbounded when the order has arbitrarily large finite intervals (ℕ, ℤ); 3 on a dense order with closed thresholds" | the row, R5 (c) and CON-0192 state the condition by the size of finite intervals, with the dense case |
| **B1-03** | **On a finite domain the new row adds nothing, and its "Pairs suffice?" column is wrong for n ≤ 2.** "n on n values" is right: the R4 bound n is reached by the point complements **alone**, so it is the forbidden-value row's k. The union effect (h(union) > max h(parts)) exists **only on infinite domains**, where forbidden values alone have h = 0. The column says "no" without condition. For n = 2 (a Boolean), h = 2 and pairs suffice, as the forbidden-value row itself says ("only if k ≤ 2"). | normal | scratch (a): thresholds with forbidden values give h = n for n = 2..5, equal to forbidden values alone | Note that on n values the threshold adds nothing beyond the forbidden values; write "only if n ≤ 2" in the column | the row's column is conditional, and the text says where the union effect lives |
| **B1-04** | **R5 (c)'s second sentence is false once thresholds are admitted.** It says: "A single forbidden-value rule does not raise the language's h by itself: it takes all k complements, or the form admitted for every value." With thresholds admitted, allowing even **one** interior forbidden value a raises h to 3, through {x ≤ a, x ≥ a, x ≠ a}. Forbidding an endpoint keeps h = 2. Turn-5 B1-11 gave this evidence (its C4b), and the settlement row "admitting the form raises h, while one rule does not" did not carry it. That turn-5 row (B1-03, B1-08, B1-11 / B2-03) is therefore **only partly met**. | normal | scratch (b): intervals plus forbidding one interior value give h = 3 on n = 3, 5, 6. Intervals plus forbidding an endpoint give h = 2 | Restrict the sentence to an attribute with no threshold, and add: "with thresholds, admitting the prohibition of one interior value already gives h = 3" | R5 (c) states the threshold case |
| **B1-05** | **The words of R5 (d), the R3 table, §2.3 (d) and CON-0192 are wider than what was proved** (brief Q2). (i) **"ordered"** must mean a **total, non-cyclic** order. The interval proof assumes one ("finite families in a total order", L109), but the list does not say so. On a partial order (a lattice of classifications, branch-wise versions), the up-sets ↑a, ↑b, ↑c of three incomparable elements with only pairwise upper bounds form a minimal triple. On a cyclic order (time-of-day windows that wrap around), arcs reach h = 5 on 6 points. (ii) **"unbounded"**, for required and forbidden values, should be **"infinite"**. A nominal attribute has no bound. A bounded infinite domain ([0, 1] ∩ ℚ) also gives h = 2. What matters is that no finite set of complements covers the domain. Under the reading "total order; infinite", I found **no minimal family of three or more** inside any listed language. Required values with thresholds are intervals (h = 2). Singletons and cofinite sets give h = 2 (proof: an empty family needs two distinct singletons, or {a} with a set excluding a). Boolean literals on independent Booleans: every subset of {0, 1} is an interval, h = 2. Products on a full product: R2. | normal | scratch (d): the poset triple is minimal. Scratch (e): circular arcs give h = 5. Scratch (f): the Boolean class gives h = 2. R5 (d) L141–144; §2.3 (d) L69; CON-0192 | Write "totally ordered (not cyclic)" for thresholds and "infinite" for forbidden values, in all four places | the four places use the corrected words |
| **B1-06** | **"Sound" in R1 (d) and R5 (d) is not defined, and the "iff" depends on the meaning** (brief Q3). For "pairs-only checking reproduces the set-wise report" (R1 (b)'s sense), the iff with m(ℛ) ≤ 2 holds. For "every conflicted point is flagged", m(ℛ) ≤ 2 is sufficient but not necessary. Countermodel: {p ∨ q, ¬p, ¬q, r, ¬r} at one point has the minimal sets {r, ¬r} and {p ∨ q, ¬p, ¬q}. The point is flagged by a pair, yet m = 3. Apart from this, R1 (d) is stated correctly and used consistently: in R1 (d), R5 (d), §9.14.4, the §2.3 (d) pointer and CON-0192. | normal | scratch (g). R1 (d) L48; R5 (d) L140 | Define: "pairs-only checking is *sound for ℛ* iff it yields the set-wise report (every minimal set with its region)". Optionally state the weaker point-flag property separately | the term is defined where it is first used |
| **B1-07** | **The tool supports less of the new material than the text cites it for** (brief Q4). (i) Check 6's "required values with thresholds h = 2" is tautological: `interval_class(6)` already contains every singleton, so it recomputes the interval check. The text cites it as evidence for an R5 (d) language. (ii) "n on n values" for the new row is computed nowhere. Check 6 shows only that the m + 1 family is minimal on 0..11. (iii) Check 3's analyser **is** independent in code: it calls neither `setwise_report` nor `minimal_empty`. It compares sets of (S, c), so regions are included, and it does test R1 (b) for h ≤ 2. But no class in check 3 contains ∅, so its single-rule branch is never exercised. It has no end-to-end case for products, for the new row, or for R1 (b) at h = 3 (testing triples suffices). None of this falsifies a claim: the proofs carry them. | normal | `helly_check.py` L187, L154–157 | Add h of intervals plus point complements on n values, a class with ∅, and a box class to check 3. Or drop "check 6" as evidence for "required values with thresholds" | the tool computes the cited values, or the text cites the proof alone |
| **B1-08** | **§9.14.5: the rival is verified, but the description is embellished and closer candidates are missing** (brief Q6). Verified (Crossref): Dechter and van Beek, *TCS* 173(1), 283–308, 1997, doi 10.1016/S0304-3975(97)86737-0; CP'95, LNCS, 240–257. The author's abstract page (UCI, R41) says relational consistency is "relation-based, in contrast with … variable-based" (matches), and that it is used "in characterizing relationships between properties of constraint networks and the level of local consistency needed to ensure global consistency". The book's "for several constraint properties, when a given level … guarantees" goes beyond those words. "The closest established notion" is asserted from the abstract alone. *Ext, my knowledge, not verified:* relational m-consistency still concerns extending instantiations of variables across m relations, not the joint satisfiability of m-subsets. Two candidates that may be closer are not named: (a) **Chepoi, Creignou, Hermann and Salzer, "The Helly property and satisfiability of Boolean formulas defined on set families", *European J. Combin.* 31 (2010) 502–516** (bibliographic data verified on Crossref; turn-5 B1-13 suggested it, and the settlement neither took it up nor declined it); (b) **van Beek and Dechter, "On the minimality and global consistency of row-convex constraint networks", *JACM* 42(3) (1995) 543–561** (verified on Crossref and the author's abstract page: path consistency suffices for row-convex constraints). That its proof rests on the one-dimensional Helly property of intervals is my recollection, **not verified**. | normal | Crossref records; UCI pages r41.html and r33.html | Quote or paraphrase the abstract's own words. Either name (a) and (b) as benchmarks (bibliographic data only, content "not read"), or say they were not searched | §9.14.5 matches the abstract, and (a), (b) are named or recorded as not searched |
| **B1-09** | **EXT-074's title is misspelt.** The Crossref record (doi 10.1007/BF01949705) reads "Convexity in **cristallographical** lattices". Turn-5 B2-11 noted the spelling. | normal | Crossref | Use the record's spelling | EXT-074 and §9.14's reference list match the record |
| **B1-10** | **S1539: the background-logic caveat is applied to MCS-2 alone** (brief Q5). The row's reading of MCS-2 is correct: [L116] is quoted exactly, the bridge is at L113–114, and A1/A2/A3 are typed correctly. The triple is minimal with the bridge as logic, and has 4 members with the bridge explicit (re-checked). But in a propositional abstraction, two atoms conflict only through a background axiom too (an exclusion such as "a partial order that is not a chain" against "a chain"). So "the other four are pairs" is equally relative to what the source counts as logic: with every background constraint explicit, they are triples. The Int conclusion, that a pairwise check would miss MCS-2, survives both readings (3 against 2, or 4 against 3). | normal | scratch (h), (i). S1539 L7–8, L108–116, L204 | Add one clause: "every MCS size here is relative to the source's background logic; the comparison 3 against 2 is preserved if all background is made explicit" | the row says so |
| **B1-11** | **S0922: the line range misses the sentence it paraphrases.** "Pairwise consistency ≠ Global consistency" is the box at L53–58. The row cites "§29.1, L21–49". The cardinality examples (L779–800: "ElectionCommitteeMembers ≥ 3", "PrimaryOwner = 1") and the PASS-without-solver reading of §29.56–64 are correct. | normal | S0922 L21, L55 | Cite L21–58 | the range contains the box |
| **B1-12** | **Precision.** (i) §9.14.2 defines h as "the largest size of a minimal finite subfamily". When sizes are unbounded (the new row, allowed sets on infinite domains), there is no largest one. Say "the supremum (possibly ∞)". R1 (b) and R5 (a) then read correctly, with min(h, n) = n. (ii) R5 (a): finding a set's **region** needs, at each point, the conflict of S together with the satisfiability of each maximal proper subset. That is a region computation, not only §2.3 (d)'s yes/no ∃(c, t) ∀x question. The **count** of tests is right. | normal | §9.14.2 L33; R5 (a) L134 | Write "supremum, possibly ∞", and "each test computes S's minimality region" | the two phrases are corrected |

**Count:** 12 findings, all normal. None critical or high: every defect is in the wording, the domain conditions or the support, and no main theorem fails. The verdict "pairs do not suffice" for the new row survives on every domain with at least three values (h = n, 3 or unbounded).

## 3. Proof-step tables for the derivations judged adequate (R4)

### 3.1 R1 (a)–(d): checked as written

| Step | Check | Result |
|---|---|---|
| 1 | equivalence with §2.3 (d)'s pointwise minimality (§2.3 (d) L67 read) | holds. Applicability enters only through ℛ(c, t) |
| 2 | an indexed minimal family has no repeat; size ≤ h by the finite-subfamily definition | holds |
| 4–5 | the subsets of size ≤ h include all proper subsets needed for minimality; the region is per set | holds, so (b) holds |
| 6 | a minimal F with \|F\| ≥ 3 exists by the definition of h, and realisability puts it at one point | holds, so (c) holds |
| 7 | m(ℛ) replaces h | holds for the report sense (B1-06) |

### 3.2 R2

| Step | Check | Result |
|---|---|---|
| 1–2 | ⋂∏ = ∏⋂ on a full product; a product is empty iff a factor is | holds (with empty factors, any empty factor counts) |
| 3–4 | G ⊆ F with \|G\| ≤ h(𝓔ₐ) is empty, so G = F by minimality | holds |
| premise | needed: the exactly-one counterexample (check 6; re-run) | holds |

### 3.3 The interval proof (and the h = 2 rows built on it)

| Step | Check | Result |
|---|---|---|
| symmetric pᵢⱼ = pⱼᵢ ∈ Iᵢ ∩ Iⱼ | needed for p_kᵢ ∈ Iᵢ | stated |
| lᵢ = minⱼ pᵢⱼ ∈ Iᵢ; x = l_k = maxᵢ lᵢ | lᵢ ≤ x ≤ p_kᵢ | holds |
| convexity gives x ∈ Iᵢ | needs a **total** order | holds; see B1-05 |

### 3.4 R4

| Step | Check | Result |
|---|---|---|
| x_A ∈ ⋂(F ∖ {A}), x_A ∉ A | minimality and emptiness | holds |
| A ≠ B ⇒ x_A ≠ x_B | x_A ∈ B, x_B ∉ B | holds |
| tightness | point complements; on {0, 1}ⁿ these are clauses of length n | holds (check 4 re-run) |

### 3.5 The new row, on ℕ (adequate there; B1-01 for ℤ)

| Step | Check | Result |
|---|---|---|
| ⋂ = {0..m−1} ∖ {0..m−1} = ∅ | needs the least element 0 | holds on ℕ |
| without the threshold: m, m+1, … remain | — | holds |
| without x ≠ j: j remains | — | holds; minimal with m + 1 members |

## 4. Turn-5 rows B1-01 … B2-13: are they met?

| Row | Met? | Note |
|---|---|---|
| B1-01 / B2-01 | **in substance yes**; wording no | the row and R5 (d) exist, but B1-01, B1-02, B1-03 and B1-05 apply |
| B1-02 / B2-02, B2-15 | yes | R2's premise, the counterexample and §9.14.6 |
| B1-03, B1-08, B1-11 / B2-03 | **partly** | language and base are separated (yes); B1-11's threshold case is missing from R5 (c) (B1-04) |
| B1-05 / B2-06 | yes | the cardinality row; the S0922 row |
| B1-04, B1-15 / B2-12 | yes | the independent analyser; caps stated (gaps: B1-07) |
| B1-06 | yes | indexed families |
| B1-07 | yes | finite subfamilies (B1-12 (i) is a further point) |
| B1-09 | yes | R2's step table |
| B1-10 / B2-05 | yes | scope; Boolean placement; the linear row marked |
| B1-12 / B2-07 | yes | R5 (a) (B1-12 (ii) is a further point) |
| B1-13 / B2-11 | yes, as settled | rival named and verified; Helly property named; Jeavons verb U for me; Chepoi et al. not addressed (B1-08) |
| B1-14 / B2-09 | yes | h = 0; allowed sets for k ≥ 3; clauses of length n |
| B1-16 / B2-14 | yes | the S1539 row rewritten (B1-10 is a further point) |
| B2-04 | yes | both readings, Int |
| B2-08 | yes | §9.14.9 |
| B2-10 | yes | symmetric choice |
| B2-13 | yes | §9.14.4 retitled |

## 5. Answers to the brief's questions

1. **New row.** The minimality proof is right on ℕ, but wrong as worded on ℤ (B1-01). "n on n values" is right, but inherited from forbidden values (B1-03). "Unbounded" needs arbitrarily large finite intervals, not discreteness. On ℚ or ℝ, h = 3 with closed thresholds, and 2 with strict ones only (B1-02).
2. **R5 (d).** Every listed union is h = 2 under "total order; infinite domain". Under the literal words "ordered" and "unbounded", there are minimal triples (B1-05). Within the corrected reading I found none.
3. **R1 (d).** Stated correctly and used consistently, but "sound" needs a definition (B1-06).
4. **Analyser.** Independent in code, and it tests R1 (b) for h ≤ 2, regions included. It has coverage gaps (B1-07).
5. **S1539 and S0922.** Read correctly. Two caveats: background logic for all MCS (B1-10), and a line range (B1-11).
6. **Dechter and van Beek.** The citation is verified, and the description is broadly fair but beyond the abstract's words. Two possibly closer works are not named (B1-08).

## 6. Coverage

- **Read in full:** §9.14 v2 (L1–255); §2.3 (a) and (d) (02.03 L1–22, L40–76); §2.6 (02.06 L1–13); CON-0192 (register row 193); EXT-072–076 (EXTERNAL-SOURCES L49–53); `helly_check.py` (208 lines) and its v2 run; turn-5 SETTLEMENT §3; the findings tables of turn-5 REPORT-B1 and REPORT-B2.
- **Corpus:**
  - S1539 L1–80, L108–137, L200–206;
  - S0922 L15–62, L741–805, L1566–1600, plus a grep of its headings;
  - S0287 L40–56.
  - All sha256 matched.
- **Re-run:** the whole tool (identical output).
- **Own computations:** `b1_t6.py` (a)–(i), with exhaustive h on small domains.
- **External, public pages only:** Crossref (Dechter and van Beek 1997 and 1995; van Beek and Dechter 1995; Jeavons et al. 1998; Doignon 1973; Danzer et al. 1963; Chepoi et al. 2010); UCI abstract pages r41 and r33. OpenAlex and Semantic Scholar had no abstracts.
- **U:**
  - SD L103–108 and RM L154–162 (basis files, not opened);
  - EXT-072 (Helly 1923; no index checked);
  - the Jeavons et al. abstract verb "characterises" (abstract elided);
  - the content of Chepoi et al. 2010 beyond its title;
  - the Helly argument inside van Beek and Dechter 1995.
- **Not reviewed:** §9.13 and §9.15 (commissions A and C).
